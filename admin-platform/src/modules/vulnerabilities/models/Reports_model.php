<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model
{
    public function get_distinct_vulnerabilities_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'vulnerabilities')->result_array();
    }



    public function tasas_riesgo_vulnerabilidad($extraWhere)
    {
        $sql = "SELECT COUNT(risk) as cantidad, risk FROM `" . db_prefix() . "vulnerabilities` 
        WHERE state = 3  $extraWhere 
        GROUP BY risk";

        // $sql = "SELECT  DATE_FORMAT(ct.date, '%m-%Y') AS production_month,  COUNT(ct.id) AS count, se.title
        // FROM " . db_prefix() . "certifications ct
        // JOIN " . db_prefix() . "seals se ON se.id = ct.id_seal
        // WHERE ct.status = 1 $extraWhere
        // GROUP BY ct.id_seal, YEAR(date);";

        $result = $this->db->query($sql)->result_array();

        // echo $this->db->last_query();
        // die();
        return $result;
    }

    public function total_tasas_riesgo_vulnerabilidad($extraWhere)
    {
        $alertDb = GetCustomConect();
        $sql = "SELECT COUNT(vl.risk) as cantidad, vl.risk 
        FROM " . db_prefix() . "list_alert_vulnerabilities vl
        JOIN " . db_prefix() . "vulnerabilities v ON v.id = vl.id_analyzes
        WHERE v.state = 3  $extraWhere
        GROUP BY vl.risk";

        // $sql = "SELECT  DATE_FORMAT(ct.date, '%m-%Y') AS production_month,  COUNT(ct.id) AS count, se.title
        // FROM " . db_prefix() . "certifications ct
        // JOIN " . db_prefix() . "seals se ON se.id = ct.id_seal
        // WHERE ct.status = 1 $extraWhere
        // GROUP BY ct.id_seal, YEAR(date);";

        $result = $alertDb->query($sql)->result_array();

        // echo $this->db->last_query();
        // die();
        return $result;
    }
}
