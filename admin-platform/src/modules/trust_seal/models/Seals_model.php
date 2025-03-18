<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Seals_model extends App_Model
{

    public function getFileSeals($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'seal_files');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }
    /**
     * Add new employee role
     * @param mixed $data
     */

    public function get($id = '', $where = [])
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'seals');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function getJoin($id = '', $where = [])
    {
        $this->db->select(db_prefix() . 'seals.*, ' . db_prefix() . 'certifications.certificationkey');
        $this->db->from(db_prefix() . 'seals');
        $this->db->join(db_prefix() . 'certifications', db_prefix() . 'certifications.id_seal=' . db_prefix() . 'seals.id', 'left');

        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'seals.id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_all_seals()
    {
        $this->db->order_by('id', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'seals')->result_array();

        return array_values($trust_seal);
    }

    public function get_exams($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'exams');
        $this->db->where('id_seal', $id);
        return $this->db->get()->result_array();
    }

    public function add($data)
    {
        $data['title']      = $data['title'];
        $data['exams']      = $data['exams'];
        $data['requirements']      = $data['requirements'];
        $data['description']      = $data['description'];
        $data['date_start']      = $data['date_start'];

        $this->db->insert(db_prefix() . 'seals', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal Seal [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function update_seal($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'seals', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Seal Updated [ID:' . $id . ']');
            return $id;
        }
        return false;
    }

    public function delete($id)
    {

        if (is_reference_in_table('id_seal', db_prefix() . 'audits', $id)) {
            return [
                'referenced' => true,
            ];
        }

        if (is_reference_in_table('id_seal', db_prefix() . 'certifications', $id)) {
            return [
                'referenced' => true,
            ];
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'seals');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Section Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function get_all_exams_for_select()
    {
        $this->db->select('id, name');
        $this->db->order_by('date', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'exams')->result_array();
        return array_values($trust_seal);
    }


    public function status($id = '')
    {
        foreach (get_status_seals() as $audit) {
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

    public function visibility($id = '')
    {
        $status_array = [
            ['id' => '1', 'name' => _l('seal_public')],
            ['id' => '2', 'name' => _l('seal_private')]
        ];
        if ($id == '') {
            return $status_array;
        }
        foreach ($status_array as $key => $value) {
            if ($key == $id) {
                return $value;
            }
        }
    }

    public function add_file($data)
    {
        $this->db->insert(db_prefix() . 'seal_files', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Trust Seal - New file of seal [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function delete_file($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'seal_files');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - File of seal deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function getDocumentsSealClient($id_seal)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'seal_files');
        $this->db->where('id_seal', $id_seal);
        return $this->db->get()->result_array();
    }

    public function add_seal_exams($data)
    {
        $data['id_seal']      = $data['id_seal'];
        $data['id_exams']      = $data['id_exams'];
        $data['status']      = $data['status'];

        $this->db->insert(db_prefix() . 'seal_exams', $data);
        $insert_id = $this->db->insert_id();
        if ($this->db->affected_rows() > 0) {
            log_activity('New Trust Seal Seal [ID:' . $insert_id . ']');
            return true;
        }

        return false;
    }

    public function delete_seal_exams($id_seal, $id_exams)
    {
        $this->db->where([
            'id_seal' => $id_seal
            ,'id_exams' => $id_exams
        ]);
        
        $this->db->delete(db_prefix() . 'seal_exams');

        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Exam of seal deleted [ID_SEAL:' . $id_seal .' ID_EXAM:'. $id_exams.']');

            return true;
        }

        return false;
    }

    public function get_seal_exams_groups($id)
    {
        $this->db->where('id_seal', $id);

        return $this->db->get(db_prefix().'seal_exams')->result_array();
    }

    public function clear_seal_exams($id_seal)
    {
        $this->db->where([
            'id_seal' => $id_seal
        ]);
        
        $this->db->delete(db_prefix() . 'seal_exams');

        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - All seal exams deleted [ID:' . $id_seal . ']');

            return true;
        }

        return false;
    }
}
