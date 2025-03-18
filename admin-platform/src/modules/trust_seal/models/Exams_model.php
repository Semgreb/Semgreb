<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Exams_model extends App_Model
{
    /**
     * Add new employee role
     * @param mixed $data
     */
    public function get($id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'exams');

        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get()->row();
        }

        return $this->db->get()->result_array();
    }

    public function get_sections($exam_id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sections');
        $this->db->where('exam_id', $exam_id);
        return $this->db->get()->result_array();
    }

    public function get_quizs($section_id = '')
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'quiz');
        $this->db->where('section_id', $section_id);


        return $this->db->get()->result_array();
    }

    public function get_sections_from_exam($exam_id = '')
    {
        $sections = count($this->get_sections($exam_id));
        return $sections;
    }

    public function get_quizs_from_exam($exam_id = '')
    {
        $count = 0;
        $sections = $this->get_sections($exam_id);
        foreach ($sections as $key) {
            $count += count($this->get_quizs($key['id']));
        }
        return $count;
    }

    public function get_all_exams()
    {
        $this->db->order_by('date', 'asc');
        $trust_seal = $this->db->get(db_prefix() . 'exams')->result_array();

        return array_values($trust_seal);
    }

    public function add($data)
    {

        $data['name']        = $data['name'];
        $data['description'] = $data['description'];
        $data['active']      = 1;
        $data['date']        = date("Y-m-d H:i:s");

        $this->db->insert(db_prefix() . 'exams', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal Exam [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function update_exam($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'exams', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Exam Updated [ID:' . $id . ']');
            return $id;
        }
        return false;
    }

    public function delete_exam($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'exams');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Section Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function add_section($data)
    {
        $this->db->insert(db_prefix() . 'sections', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Section Trust Seal [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function update_section($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'sections', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Section Updated [ID:' . $id . ']');
            return $id;
        }
        return false;
    }

    public function delete_section($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sections');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Section Deleted [ID:' . $id . ']');
            $this->db->where('section_id', $id);
            $this->db->delete(db_prefix() . 'quiz');
            return true;
        }

        return false;
    }

    public function add_quiz($data)
    {
        $this->db->insert(db_prefix() . 'quiz', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Trust Seal Quiz [ID:' . $insert_id . ']');
            return $insert_id;
        }

        return false;
    }

    public function update_quiz($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'quiz', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Quiz Updated [ID:' . $id . ']');
            return $id;
        }
        return false;
    }

    public function delete_quiz($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'quiz');
        if ($this->db->affected_rows() > 0) {
            log_activity('Trust Seal - Quiz Deleted [ID:' . $id . ']');

            return true;
        }

        return false;
    }

    public function status($id = '')
    {

        foreach (get_status_exams() as $audit) {
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

    public function get_exams_details_groups($id_audit, $id_customer, $id_exams, $id_seal)
    {
        $sql = " SELECT count(au.approved) AS TOTAL
                ,SUM(CASE WHEN au.approved > 0 THEN 1 ELSE 0 END) AS COUNT_APPROBED
                ,SUM(CASE WHEN au.approved <= 0 THEN 1 ELSE 0 END) AS COUNT_FAILURE
                 FROM ".db_prefix()."audits_exams au
				 WHERE  au.id_audit = $id_audit AND au.id_customer = $id_customer AND au.id_question IN (
                     SELECT qz.id
					FROM  ".db_prefix()."exams ex
					JOIN  ".db_prefix()."seal_exams sm ON ( sm.id_exams = ex.id )
					JOIN  ".db_prefix()."sections sc ON (sc.exam_id = sm.id_exams)
					JOIN  ".db_prefix()."quiz qz ON (qz.section_id = sc.id)
					WHERE sm.id_seal = $id_seal AND sm.id_exams = $id_exams
                 )
                ";


        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function get_count_quiz($id_seal, $id_exams){

        $sql = "SELECT count(qz.id) as TOTAL
        FROM  ".db_prefix()."exams ex
        JOIN  ".db_prefix()."seal_exams sm ON ( sm.id_exams = ex.id )
        JOIN  ".db_prefix()."sections sc ON (sc.exam_id = sm.id_exams)
        JOIN  ".db_prefix()."quiz qz ON (qz.section_id = sc.id)
        WHERE sm.id_seal = $id_seal AND sm.id_exams = $id_exams";

        $result = $this->db->query($sql)->result_array();
        return $result[0]['TOTAL'];

    }

    public function get_exams_group($id_seal, $id_audit, $where = [])
    {
        $this->db->select('ex.*, aud.id_customer, sm.id_seal');
        $this->db->from(db_prefix() . 'exams ex');
        $this->db->join(db_prefix() . 'seal_exams sm','sm.id_exams=ex.id');
        $this->db->join(db_prefix() . 'audits aud', 'aud.id='.$id_audit);
        $this->db->where($where);
        $this->db->where('sm.id_seal', $id_seal);
        return $this->db->get()->result_array();
    }

}
