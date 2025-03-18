<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Audits_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'audits');

        $this->db->where($where);

        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_all_audits()
    {
        $this->db->order_by('id', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'audits')->result_array();

        return array_values($trust_seal);
    }

    public function get_certification($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'audits');
        $this->db->where('id_seal', $id);
        return $this->db->get()->result_array();
    }

    public function add($data)
    {
        $data['id_customer']      = $data['id_customer'];
        $data['id_seal']      = $data['id_seal'];
        $data['description']      = $data['description'];
        $data['auto_asignar']  = $data['auto_asignar'];

        $this->db->insert(db_prefix() . 'audits', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal certification [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }


    public function update($data, $id)
    {

        $data['id_customer']      = $data['id_customer'];
        $data['id_seal']      = $data['id_seal'];
        $data['description']      = $data['description'];
        $data['status']      = $data['status'];
        $data['auto_asignar']  = $data['auto_asignar'];
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'audits', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Audit Updated [ID:' . $id . ']');
            if ($data['qualification'] == 2 && $data['status'] == 2) {

                if ($data['notification'] == 1) {

                    $template = 'new_audit_completed';
                    $contactsClients = get_contacts_notification_clients_audits($data['id_customer']);

                    $this->load->model('announcements_model');
                    $announcement = [];
                    $announcement['name'] = _l('trust_audit_completed_subject');
                    $announcement['showname'] = 1;
                    $announcement['showtousers'] = 1;
                    $announcement['showtostaff'] = 1;
                    $announcement['message'] = html_purify(_l('trust_seal_audit_message_alert', $id));
                    //$this->announcements_model->add($announcement);

                    foreach ($contactsClients as $value) {
                        send_mail_template($template, "trust_seal", $value['email'], get_staff_user_id(), $id, $data['id_customer'], get_contact_user_id(), $value['id']);
                    }
                }
            }

            return $id;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'audits');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Certification Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_all_customers_for_select()
    {
        $this->db->select('userid, company');
        $trust_seal = $this->db->get(db_prefix() . 'clients')->result_array();
        return array_values($trust_seal);
    }

    public function get_all_seals_for_select()
    {
        $this->db->select('id, title');
        $trust_seal = $this->db->get(db_prefix() . 'seals')->result_array();
        return array_values($trust_seal);
    }

    public function get_customer($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients');
        $this->db->where('userid', $id);
        return $this->db->get()->result_array();
    }


    public function get_seal($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'seals');
        $this->db->where('id', $id);
        return $this->db->get()->result_array();
    }

    public function status($id = '')
    {
        // $status_array = [
        //     ['id' => '1', 'name' => _l('audit_progress')],
        //     ['id' => '2', 'name' => _l('audit_complete')]
        // ];

        foreach (get_status_audits() as $audit) {
            $status_array[] = ['id' => $audit['status'], 'name' => $audit['translate_name']];
        }


        if ($id == '') {
            return $status_array;
        }
        foreach ($status_array as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
    }

    public function qualifications($id = '')
    {
        // $status_array = [
        //     ['id' => '1', 'name' => _l('audit_progress')],
        //     ['id' => '2', 'name' => _l('audit_complete')]
        // ];

        foreach (get_qualification_audits() as $audit) {
            $status_array[] = ['id' => $audit['qualification'], 'name' => $audit['translate_name']];
        }


        if ($id == '') {
            return $status_array;
        }
        foreach ($status_array as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
    }

    public function add_audit_exam($data)
    {
        $data['id_audit']      = $data['id_audit'];
        $data['id_customer']      = $data['id_customer'];
        $data['id_question']      = $data['id_question'];
        $data['approved']      = $data['approved'];

        $this->db->insert(db_prefix() . 'audits_exams', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New record audit of exam [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function validate_audit_exam($id_audit, $id_customer, $id_question)
    {
        $this->db->select('approved');
        $this->db->from(db_prefix() . 'audits_exams');
        $this->db->where('id_audit', $id_audit);
        $this->db->where('id_customer', $id_customer);
        $this->db->where('id_question', $id_question);
        $query = $this->db->order_by('id', "desc")
            ->limit(1)
            ->get();

        return $query->num_rows() > 0 ? $query->result_array()[0] : [];
    }

    public function add_comment($data)
    {
        $data['id_audit']      = $data['id_audit'];
        $data['id_question']      = $data['id_question'];
        $data['comment']      = $data['comment'];
        $data['contactid']    =  get_staff_user_id();


        $this->db->insert(db_prefix() . 'audit_comments', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New record audit comment of exam [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function get_comment($id_audit, $id_question)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'audit_comments');
        $this->db->where('id_audit', $id_audit);
        $this->db->where('id_question', $id_question);
        return $this->db->get()->result_array();
    }

    public function if_exist_certifications_with_this_seal_and_client($seal, $id_customer)
    {
        return $this->db->query("SELECT au.id_customer 
        FROM " . db_prefix() . "audits au
        JOIN " . db_prefix() . "certifications ct ON (ct.id_customer = au.id_customer AND ct.id_seal = au.id_seal)
        WHERE ct.id_seal = $seal AND ct.id_customer = $id_customer AND au.status = 2 AND au.qualification = 2")->num_rows() > 0 ? true : false;
    }
}
