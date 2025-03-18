<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trust_seal_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '', $exclude_notified = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'sections')->row();
        }

        if ($exclude_notified == true) {
            $this->db->where('notified', 0);
        }

        return $this->db->get(db_prefix() . 'sections')->result_array();
    }

    public function get_all_goals($exclude_notified = true)
    {
        if ($exclude_notified) {
            $this->db->where('notified', 0);
        }

        $this->db->order_by('end_date', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'sections')->result_array();

        return array_values($trust_seal);
    }

    public function add($data)
    {
        $data['notify_when_fail']    = isset($data['notify_when_fail']) ? 1 : 0;
        $data['notify_when_achieve'] = isset($data['notify_when_achieve']) ? 1 : 0;

        $data['name']      = to_sql_date($data['name']);
        $this->db->insert(db_prefix() . 'sections', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal [ID:' . $insert_id . ']');

            return $insert_id;
        }

        return false;
    }

    public function update($data, $id)
    {
        $data['notify_when_fail']    = isset($data['notify_when_fail']) ? 1 : 0;
        $data['notify_when_achieve'] = isset($data['notify_when_achieve']) ? 1 : 0;

        $data['name'] = $data['name'] == '' ? 0 : $data['name'];
        $trust_seal = $this->get($id);

        if ($trust_seal->notified == 1 && date('Y-m-d') < $data['end_date']) {
            $data['notified'] = 0;
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'sections', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal Updated [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sections');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function getOnlySealPublicCustomer($id_customer)
    {
        $sql = "SELECT se.* 
        FROM `" . db_prefix() . "seals` se
        JOIN  " . db_prefix() . "certifications ct ON (ct.id_seal = se.id AND ct.id_customer = " . $this->db->escape_str($id_customer) . " )
        WHERE ct.status = 1 AND se.status = 1";

        $query = $this->db->query($sql);

        return $query->result_array();
    }
}
