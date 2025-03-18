<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Certifications_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function get($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'certifications');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function getJoin($id = '', $where = [])
    {
        $this->db->select('cf.*, sl.logo_inactive, sl.logo_active');
        $this->db->from(db_prefix() . 'certifications cf');
        $this->db->join(db_prefix() . 'seals sl', 'sl.id = cf.id_seal', 'left');


        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('certifications.id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_all_certifications()
    {
        $this->db->order_by('id', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'certifications')->result_array();

        return array_values($trust_seal);
    }

    public function get_certification($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'certifications');
        $this->db->where('id_seal', $id);
        return $this->db->get()->result_array();
    }

    public function add($data)
    {
        $row['certificationkey']      = $data['certificationkey'];
        $row['id_customer']      = (int)$data['id_customer'];
        $row['id_seal']      = (int)$data['id_seal'];
        $row['date_expiration']  = $data['date_expiration'];
        $row['notification'] = isset($data['notification']) ? 1 : 0;
        $row['reminder'] = isset($data['reminder']) ? 1 : 0;
        $row['status']  = 4;

        $this->db->insert(db_prefix() . 'certifications', $row);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal certification [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }


    public function update($data, $id)
    {

        //$data['name']      = $data['name'];
        $data['id_customer']      = $data['id_customer'];
        $data['id_seal']      = $data['id_seal'];
        $data['date_expiration']      = $data['date_expiration'];
        $data['status']      = $data['status'];
        $data['notification'] = isset($data['notification']) ? 1 : 0;
        $data['reminder'] = isset($data['reminder']) ? 1 : 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'certifications', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Certification Updated [ID:' . $id . ']');


            if ($data['status'] == 1 && $data['date_expiration'] >= date('Y-m-d')) {


                if ($data['notification'] == 1) {
                    $template = 'certification_assigned_to_client';
                    $contactsClients = get_contacts_notification_clients_certifications($data['id_customer']);

                    $this->load->model('announcements_model');
                    $announcement = [];
                    $announcement['name'] = _l('trust_seal_certification_subject');
                    $announcement['showname'] = 1;
                    $announcement['showtousers'] = 1;
                    $announcement['showtostaff'] = 1;
                    $announcement['message'] = html_purify(_l('trust_seal_certification_message_alert', $id));
                    //$this->announcements_model->add($announcement);

                    // echo "<pre>";
                    // print_r($contactsClients);
                    // die();

                    foreach ($contactsClients as $value) {
                        send_mail_template($template, "trust_seal", $value['email'], get_staff_user_id(), $id, $data['id_customer'], $data['id']);
                    }
                }

                if ($data['reminder'] == 1) {
                    $dateTimeNotificaction = "";
                    load_client_language($data['id_customer']);
                    foreach (get_date_reminder($data['date_expiration']) as $content) {
                        $dateTimeNotificaction = $content['fecha'];

                        $reminderanounce = [];
                        $reminderanounce['description'] = $content['description'];
                        $reminderanounce['notify_by_email'] = 1;
                        $reminderanounce['rel_type'] = 'certifications';
                        $reminderanounce['rel_id'] = $data['id_customer'];
                        $reminderanounce['date'] = $dateTimeNotificaction;
                        $reminderanounce['staff'] = 0;
                        $idReminder = $this->custom_reminder($reminderanounce, $data['id_customer']);
                        if ($idReminder > 0) {
                            $this->add_relations_certifications_reminder(['id_certifications' => $id, 'id_reminders' => $idReminder]);
                        }
                    }
                }
            } else {
                $this->remove_complete_delete($id);
            }

            return $id;
        }
        return false;
    }

    public function custom_reminder($data, $id)
    {
        if (isset($data['notify_by_email'])) {
            $data['notify_by_email'] = 1;
        } //isset($data['notify_by_email'])
        else {
            $data['notify_by_email'] = 0;
        }
        $data['date']        = to_sql_date($data['date'], true);
        $data['description'] = nl2br($data['description']);
        $data['creator']     = get_staff_user_id();
        $this->db->insert(db_prefix() . 'reminders', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Reminder Added [' . ucfirst($data['rel_type']) . 'ID: ' . $data['rel_id'] . ' Description: ' . $data['description'] . ']');
            return $insert_id;
        } //$insert_id
        return false;
    }

    public function remove_complete_delete($idCertifications)
    {
        $this->load->model('misc_model');
        $success = false;
        $this->db->select('*');
        $this->db->from(db_prefix() . 'relations_reminder_certifications');
        $this->db->where('id_certifications', $idCertifications);
        $result = $this->db->get()->result_array();
        foreach ($result as $value) {
            $success = $this->misc_model->delete_reminder($value['id_reminders']);
        }
        if ($success) {
            $this->delete_reminder($idCertifications);
        }
    }
    public function delete_reminder($idCertifications)
    {
        $this->db->where('id_certifications', $idCertifications);
        $this->db->delete(db_prefix() . 'relations_reminder_certifications');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Certification delete_reminder Deleted [ID:' . $idCertifications . ']');

            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'certifications');
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
        foreach (get_status_certifications() as $audit) {
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

    public function add_relations_certifications_reminder($data)
    {
        $data['id_certifications']      = $data['id_certifications'];
        $data['id_reminders']      = $data['id_reminders'];

        $this->db->insert(db_prefix() . 'relations_reminder_certifications', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New add_relations_certifications_reminder [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }
}
