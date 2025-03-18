<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Vulnerabilities_merge_fields extends App_merge_fields
{
    public function build()
    {

        return [

            //vulnerabilities-assigned-to-client
            [
                'name'      => 'Scan Assgine Subject',
                'key'       => '{vulnerabilities_scan_subject}',
                'available' => [
                    'vulnerabilities',
                ],
            ],
            [
                'name'      => 'Scan Id',
                'key'       => '{vulnerabilities_scan}',
                'available' => [
                    'vulnerabilities',
                ],
            ],
            [
                'name'      => 'Scan State',
                'key'       => '{vulnerabilities_scan_state}',
                'available' => [
                    'vulnerabilities',
                ],
            ],
            [
                'name'      => 'Scan Description',
                'key'       => '{vulnerabilities_scan_message}',
                'available' => [
                    'vulnerabilities',
                ],
            ],

            //vulnerabilities-scan-to-client
            [
                'name'      => 'Scan Complete Subject',
                'key'       => '{vulnerabilities_scan_subject}',
                'available' => [
                    'vulnerabilities',
                ],
            ],

            [
                'name'      => 'Scan Id',
                'key'       => '{vulnerabilities_scan}',
                'available' => [
                    'vulnerabilities',
                ],
            ],
            [
                'name'      => 'Scan State',
                'key'       => '{vulnerabilities_scan_state}',
                'available' => [
                    'vulnerabilities',
                ],
            ],

            [
                'name'      => 'Vulnerabilities Scan Details',
                'key'       => '{vulnerabilities_scan_message}',
                'available' => [
                    'vulnerabilities',
                ],
            ],
        ];
    }

    /**
     * Merge fields for tickets
     * @param  string $template  template name, used to identify url
     * @param  mixed $ticket_id ticket id
     * @param  mixed $reply_id  reply id
     * @return array
     */
    public function format($template, $scan_id, $reply_id = '', $contactid = 0)
    {
        $fields = [];


        $this->ci->load->model('vulnerabilities_model');
        $trust_seal = $this->ci->vulnerabilities_model->get_analisys($scan_id);


        if (!$trust_seal) {
            return $fields;
        }

        // $languageChanged = false;
        // if (
        //     !is_client_logged_in()
        //     && !empty($trust_seal->id_client)
        //     && isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])
        //     && !$GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id() // email to client
        // ) {
        //     load_client_language($trust_seal->id_client);
        //     $languageChanged = true;
        // } else {
        //     if (isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])) {
        //         $sending_to_staff_id = $GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id();
        //         if ($sending_to_staff_id) {
        //             load_admin_language($sending_to_staff_id);
        //             $languageChanged = true;
        //         }
        //     }
        // }

        // if (!is_client_logged_in() && $languageChanged) {
        //     load_admin_language();
        // } elseif (is_client_logged_in() && $languageChanged) {
        //     load_client_language();
        // }

        // $client = $this->ci->clients_model->get($trust_seal->id_client);

        if ($template == 'vulnerabilities-assigned-to-client') {

            // $fields['{trust_seal_audit_id}'] = $trust_seal_id;
            $fields['{vulnerabilities_scan_subject}'] = _l('email_subject_vulnerabilities_init');

            $status = '';

            foreach (get_status_scan() as $qualification) {
                if ($qualification['status'] == $trust_seal->state) {
                    $status = get_status_audits_format($qualification);
                    break;
                }
            }

            $fields['{vulnerabilities_scan_state}'] = $status;

            // $status = "";
            // foreach (get_status_audits() as $qualification) {
            //     if ($qualification['status'] == $trust_seal->status) {
            //         $status =  get_status_audits_format($qualification);
            //         break;
            //     }
            // }

            $fields['{vulnerabilities_scan_message}'] = _l('email_subject_vulnerabilities_init_message') . " " . $trust_seal->id;

            // $qualificationName = "";
            // foreach (get_qualification_audits() as $qualification) {
            //     if ($qualification['qualification'] == $trust_seal->qualification) {
            //         $qualificationName = get_qualification_format($qualification);
            //         break;
            //     }
            // }

            // $fields['{trust_seal_audit_qualification}'] =  $qualificationName;
            // $fields['{trust_seal_audit_description}'] =  $trust_seal->description;


            // $this->ci->load->model('seals_model');
            // $this->ci->load->model('exams_model');

            // //audit
            // $data['status'] = $this->ci->audits_model->status();
            // $data['qualification'] = $this->ci->audits_model->qualifications();
            // $audit               = $this->ci->audits_model->get($trust_seal_id);
            // $data['audit']       = $audit;
            // //seal
            // $id_exam = $this->ci->seals_model->get($audit->id_seal)->exams;

            // //exams
            // $data['exam'] = $this->ci->exams_model->get($id_exam);
            // $sections = $this->ci->exams_model->get_sections($id_exam);
            // foreach ($sections as $section) {

            //     $quizs = $this->ci->exams_model->get_quizs($section['id']);

            //     $new_array_quizs = array();

            //     foreach ($quizs as $quiz) {
            //         $approved = $this->ci->audits_model->validate_audit_exam($audit->id, $audit->id_customer, $quiz['id']);
            //         $quiz["approved"] = $approved;
            //         array_push($new_array_quizs, $quiz);
            //     }

            //     array_push($section, ["quizs" => $new_array_quizs]);
            //     $data['sections'][] = $section;
            // }

            // $fields['{trust_seal_audit_message}']  = $this->ci->load->view('audits/email/audit_email', $data, true);
        } else {

            // $certificationName = $this->ci->certifications_model->get_seal($trust_seal->id_seal)[0]['title'];
            // $fields['{trust_seal_certification_id}'] = $scan_id;
            // $fields['{companyname}'] = $client->company;
            $fields['{vulnerabilities_scan_subject}'] = _l('email_subject_vulnerabilities_completed');


            $status = '';

            foreach (get_status_scan() as $qualification) {
                if ($qualification['status'] == $trust_seal->state) {
                    $status = get_status_audits_format($qualification);
                    break;
                }
            }


            $fields['{vulnerabilities_scan_state}'] = $status;
            // $fields['{trust_seal_certification}'] =  $certificationName;

            // $status = '';

            // foreach (get_status_certifications() as $qualification) {
            //     if ($qualification['status'] == $trust_seal->status) {
            //         $status = get_status_audits_format($qualification);
            //         break;
            //     }
            // }

            $fields['{vulnerabilities_scan_message}'] = _l('email_subject_vulnerabilities_completed_message') . " " . $trust_seal->id;
            // $fields['{trust_seal_certification_message}'] = _l('trust_seal_certification_message', $certificationName);
        }

        $reply = false;


        return hooks()->apply_filters('vulnerabilities_merge_fields', $fields, [
            'id'       => $scan_id,
            'reply_id' => $reply_id,
            'template' => $template,
            'trust_seal'   => $trust_seal,
            'reply'    => $reply,
        ]);
    }
}
