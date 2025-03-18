<?php
class Download_complaints extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('download');
    }


    public function file($folder_indicator, $attachmentid = '')
    {
        $this->load->model('complaints_model');
        if ($folder_indicator == 'complaint') {


            //if (is_logged_in()) {
            $this->db->where('id', $attachmentid);
            $attachment = $this->db->get(db_prefix() . 'complaints_attachments')->row();
            if (!$attachment) {
                show_404();
            }


            $ticket = $this->complaints_model->get_complaint_by_id($attachment->complaintid);
            $complaintid = $attachment->complaintid;
            if ($ticket->userid == get_client_user_id() || is_staff_logged_in()) {
                if ($attachment->id != $attachmentid) {
                    show_404();
                }
                $path = COMPLAINTS_ATTACHMENTS_FOLDER . $complaintid . '/' . $attachment->file_name;
            }
            // }
        }

        $path = hooks()->apply_filters('download_file_path', $path, [
            'folder'       => $folder_indicator,
            'attachmentid' => $attachmentid,
        ]);

        force_download($path, null);
    }
}
