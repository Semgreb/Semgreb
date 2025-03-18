<?php

namespace modules\complaints\services;

class MergeComplaints
{
    /**
     * @var int
     */
    protected $primaryComplaintId;

    /**
     * @var array
     */
    protected $ids;

    /**
     * @var int|null
     */
    protected $status;

    /**
     * CI Instance
     */
    protected $ci;

    /**
     * Initiate new MergeTickets class
     *
     * @param int $primaryComplaintId
     * @param array $ids
     */
    public function __construct($primaryComplaintId, $ids)
    {
        $this->primaryComplaintId = $primaryComplaintId;
        $this->ids             = $ids;
        $this->ci              = &get_instance();
    }

    /**
     * Merge the tickets into the primary ticket
     *
     * @return bool
     */
    public function merge()
    {
        $replies = $this->convertToMergeReplies(
            $this->getTicketsToMerge()
        );

        $merged = 0;
        $this->ci->db->trans_begin();

        try {
            foreach ($replies as $reply) {
                if ($this->mergeInPrimaryTicket($reply)) {
                    if ($reply['merge_type'] === 'complaint') {
                        $this->markTicketAsMerged($reply);
                    }

                    $merged++;
                }
            }



            if ($this->status && $merged > 0) {
                $this->ci->db->set('status', $this->status)
                    ->where('complaintid', $this->primaryComplaintId)
                    ->update('complaints');
            }

            $this->ci->db->trans_commit();
        } catch (Exception $e) {
            $this->ci->db->trans_rollback();
        }

        return $merged > 0;
    }

    /**
     * After merge, change the primary ticket status to the given status
     *
     * @param  int $status
     *
     * @return $this
     */
    public function markPrimaryTicketAs($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Merge the given reply into the primary ticket
     *
     * @param  array $reply
     *
     * @return bool
     */
    protected function mergeInPrimaryTicket($reply)
    {
        $result = $this->ci->db->insert('complaints_replies', [
            'complaintid'  => $this->primaryComplaintId,
            'userid'    => $reply['userid'],
            'contactid' => $reply['contactid'],
            'name'      => $reply['name'],
            'email'     => $reply['email'],
            'date'      => $reply['date'],
            'message'   => $reply['message'],
            'admin'     => $reply['admin'],
        ]);

        $replyId = $this->ci->db->insert_id();

        if (count($reply['attachments']) > 0) {
            $this->moveAttachments($reply['attachments'], $replyId);
        }

        return $result;
    }

    /**
     * Get the tickets to be merged into the primary ticket
     *
     * @return array
     */
    protected function getTicketsToMerge()
    {
        $complaints = $this->ci->db->where_in('complaintid', $this->ids)
            ->order_by('complaintid', 'ASC')
            ->get('complaints')
            ->result_array();

        return array_map(function ($complaint) {
            return array_merge($complaint, [
                'merge_type'  => 'complaint',
                'attachments' => $this->getAttachments($complaint['complaintid']),
                'replies'     => $this->getReplies($complaint['complaintid']),
            ]);
        }, $this->removeAlreadyMergedTickets($complaints));
    }

    /**
     * Get attachments for the merge
     *
     * @param  int $id
     * @param  int|null $replyId
     *
     * @return array
     */
    protected function getAttachments($id, $replyId = null)
    {
        return $this->ci->complaints_model->get_complaint_attachments($id, $replyId);
    }

    /**
     * Remove the already merged tickets from the given tickets list
     *
     * @param  array $tickets
     *
     * @return array
     */
    protected function removeAlreadyMergedTickets($complaints)
    {
        return array_values(
            array_filter($complaints, function ($complaint) {
                return $complaint['merged_complaint_id'] === null;
            })
        );
    }

    /**
     * Mark the ticket as merged
     *
     * @param  array $ticket
     *
     * @return void
     */
    protected function markTicketAsMerged($complaint)
    {
        $subject = strpos($complaint['subject'], '[MERGED]') !== false ?
            $complaint['subject'] :
            $complaint['subject'] . ' [MERGED]';

        $this->ci->db->set('merged_complaint_id', $this->primaryComplaintId)
            ->set('subject', $subject)
            ->set('status', 5)
            ->where('complaintid', $complaint['complaintid'])
            ->update('complaints');
    }

    /**
     * Get the replies for merging for the given ticket
     *
     * @param  int $id
     *
     * @return array
     */
    protected function getReplies($id)
    {
        $this->ci->db->where('complaintid', $id);
        $replies = $this->ci->db->get('complaints_replies')->result_array();

        return array_map(function ($reply) use ($id) {
            return array_merge($reply, [
                'merge_type'  => 'reply',
                'attachments' => $this->getAttachments($id, $reply['id']),
            ]);

            return $reply;
        }, $replies);
    }

    /**
     * Convert the given tickets with replies to replies for ready for merging
     *
     * @param  array $tickets
     *
     * @return array
     */
    protected function convertToMergeReplies($complaints)
    {
        $replies = [];

        foreach ($complaints as $complaint) {
            $complaintReplies = $complaint['replies'];
            unset($complaint['replies']);
            $replies = array_merge($replies, [$complaint], $complaintReplies);
        }

        return $replies;
    }

    /**
     * Move the given attachment from merged ticket/reply to the new reply
     *
     * @param  array $attachment
     * @param  int $replyId
     *
     * @return void
     */
    protected function moveAttachments($attachments, $replyId)
    {
        $complaintsUploadPath = COMPLAINTS_ATTACHMENTS_FOLDER;
        $primaryTicketPath = $complaintsUploadPath . $this->primaryComplaintId . DIRECTORY_SEPARATOR;
        _maybe_create_upload_path($primaryTicketPath);

        foreach ($attachments as $attachment) {
            $filePath = $complaintsUploadPath . $attachment['complaintid'] . DIRECTORY_SEPARATOR . $attachment['file_name'];

            $newFilename = unique_filename($primaryTicketPath, $attachment['file_name']);
            $newPath     = $primaryTicketPath . $newFilename;

            if (xcopy($filePath, $newPath)) {
                $this->ci->db->insert('complaints_attachments', [
                    'complaintid'  => $this->primaryComplaintId,
                    'replyid'   => $replyId,
                    'file_name' => $newFilename,
                    'filetype'  => $attachment['filetype'],
                    'dateadded' => $attachment['dateadded'],
                ]);

                $this->ci->complaints_model->delete_complaint_attachment($attachment['id']);
            }
        }
    }
}
