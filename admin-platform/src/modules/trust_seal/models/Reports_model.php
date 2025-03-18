<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model
{

    public function indice_certificaciones_por_periodos($extraWhere)
    {
        $sql = "SELECT  DATE_FORMAT(date, '%m-%Y') AS production_month,  COUNT(id) AS count
            FROM " . db_prefix() . "certifications
            WHERE status = 1 $extraWhere
            GROUP BY production_month, MONTH(date),  YEAR(date);";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function indice_certificaciones_por_sellos($extraWhere)
    {
        $sql = "SELECT  DATE_FORMAT(ct.date, '%m-%Y') AS production_month,  COUNT(ct.id) AS count, se.title
        FROM " . db_prefix() . "certifications ct
        JOIN " . db_prefix() . "seals se ON se.id = ct.id_seal
        WHERE ct.status = 1 $extraWhere
        GROUP BY se.title ,production_month, ct.id_seal, YEAR(date);";

        $result = $this->db->query($sql)->result_array();

        // echo $this->db->last_query();
        // die();
        return $result;
    }

    public function indice_certificaciones_por_estado($extraWhere)
    {

        $sql = "SELECT DATE_FORMAT(ct.date, '%m-%Y') AS production_month, COUNT(ct.id) AS count, ct.status
        FROM " . db_prefix() . "certifications ct 
        WHERE $extraWhere
        GROUP BY production_month, ct.status, YEAR(date);";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function indice_auditorias_por_calificacion($extraWhere)
    {

        $sql = "SELECT  COUNT(au.id) AS count, au.qualification
        FROM " . db_prefix() . "audits au 
        WHERE $extraWhere
        GROUP BY au.qualification";

        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    public function indice_auditorias_por_estado($extraWhere)
    {

        $sql = "SELECT DATE_FORMAT(au.date, '%m-%Y') AS production_month, COUNT(au.id) AS count, au.status
        FROM " . db_prefix() . "audits au 
        WHERE $extraWhere
        GROUP BY production_month, au.status";


        // echo $sql;
        // die();

        $result = $this->db->query($sql)->result_array();

        // echo  "<pre>";
        // print_r($result);
        // die();
        return $result;
    }

    public function indice_auditoria_completadas($extraWhere)
    {
        $sql = "SELECT  DATE_FORMAT(date, '%m-%Y') AS production_month,  COUNT(au.id) AS count, au.status
            FROM " . db_prefix() . "audits au
            WHERE au.status = 2 $extraWhere
            GROUP BY production_month, au.status ,MONTH(au.date),  YEAR(au.date);";

        $result = $this->db->query($sql)->result_array();

        return $result;
    }

    public function get_distinct_certifications_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'certifications')->result_array();
    }

    public function get_distinct_audits_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'audits')->result_array();
    }
}
