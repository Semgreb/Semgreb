<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Consumers_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get single goal
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {

            $this->db->where('consumerid', $id);

            return $this->db->get(db_prefix() . 'consumers')->row();
        }

        // if ($exclude_notified == true) {
        //     $this->db->where('notified', 0);
        // }

        return $this->db->get(db_prefix() . 'consumers')->result_array();
    }

    public function getConsumerDocument($document)
    {
        $this->db->where('document', $document);
        $this->db->order_by("consumerid", "desc");
        return $this->db->get(db_prefix() . 'consumers')->row();
    }

    public function searchCustomer($where)
    {
        if (!empty($where)) {
            $this->db->where($where);
            $res = $this->db->get(db_prefix() . 'consumers')->result_array();
            return $res;
        }

        return "";
    }

    public function searchClient($where)
    {
        if (!empty($where)) {
            $this->db->where($where);
            $res = $this->db->get(db_prefix() . 'clients')->result_array();
            return $res;
        }

        return "";
    }

    public function getClients($id = '')
    {
        if (is_numeric($id)) {

            $this->db->where('userid', $id);

            return $this->db->get(db_prefix() . 'clients')->row();
        }

        return $this->db->get(db_prefix() . 'clients')->result_array();
    }

    public function get_all_consumers()
    {
        $this->db->order_by('datecreated', 'asc');
        $complaints = $this->db->get(db_prefix() . 'consumers')->result_array();
        return array_values($complaints);
    }


    /**
     * Add new ticket to database
     * @param mixed $data  ticket $_POST data
     * @param mixed $admin If admin adding the ticket passed staff id
     */
    public function add($data)
    {
        $data['datecreated'] = date('Y-m-d H:i:s');

        if ($this->db->insert(db_prefix() . 'consumers', $data)) {

            $consumerid = $this->db->insert_id();
            return $consumerid;
        }

        return false;
    }

    public function update_consumer($data, $id)
    {
        $data['dateupdate'] = date('Y-m-d H:i:s');

        $this->db->where('consumerid', $id);
        $this->db->update(db_prefix() . 'consumers', $data);
        if ($this->db->affected_rows() > 0) {
            // log_activity('Complaint Service Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete($id)
    {
        if (is_reference_in_table('contactid', db_prefix() . 'complaints', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('consumerid', $id);
        $this->db->delete(db_prefix() . 'consumers');
        if ($this->db->affected_rows() > 0) {
            log_activity('Consumers  Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }
}
