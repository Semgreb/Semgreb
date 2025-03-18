<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Vulnerabilities_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_Analysis_finalized()
    {
        $this->db->where('state', 'finalizada');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function get_Analysis_in_process()
    {
        $this->db->where('state', 'proceso');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function get_Analysis_canceled()
    {
        $this->db->where('state', 'cancelada');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function save_WebSites($datos)
    {
        $this->db->trans_start(); // Inicia la transacción
        $this->db->insert(db_prefix() . 'clients_web_sites', $datos);
        $this->db->trans_complete(); // Finaliza la transacción

        if ($this->db->trans_status() === FALSE) {
            // Si ocurrió un error en la transacción, se deshacen las consultas
            $this->db->trans_rollback();
            return false;
        } else {
            // Si la transacción se completó correctamente, se confirman las consultas
            $this->db->trans_commit();
            return true;
        }
    }

    public function save_Analysis($datos)
    {
        $this->db->trans_start(); // Inicia la transacción

        $this->db->insert(db_prefix() . 'vulnerabilities', $datos);
        $insert_id = $this->db->insert_id();
        // $datos['detail_vulnerability']['id_vulnerability'] = $insert_id;
        // $this->db->insert(db_prefix() . 'detalles_vulnerabilities', $datos['detail_vulnerability']);

        $this->db->trans_complete(); // Finaliza la transacción

        if ($this->db->trans_status() === FALSE) {
            // Si ocurrió un error en la transacción, se deshacen las consultas
            $this->db->trans_rollback();
            return false;
        } else {
            // Si la transacción se completó correctamente, se confirman las consultas
            $this->db->trans_commit();

            // save_details_analisis($insert_id, $datos['analisis_id']);
            //send_notifications_and_emails_scan($datos['id_client'], $datos['analisis_id'], $insert_id);

            return $insert_id;
        }
    }

    public function get_no_vulnerability()
    {
        $query = $this->db->select_max('no_vulnerability')->get(db_prefix() . 'vulnerabilities');
        $resultado = $query->row();
        $maximo = ($resultado->no_vulnerability !== null) ? $resultado->no_vulnerability + 1 : 1;
        return $maximo;
    }

    public function get_analisys($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $this->db->where('id', $id);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            $row = null;
            return $row;
        }
    }

    public function get_analisys_conditions($where = "")
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'vulnerabilities');

        if ($where != "")
            $this->db->where($where);

        return $this->db->get()->result_array();
    }

    public function get_analisys_idscan($analisis_id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $this->db->where('analisis_id', $analisis_id);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            $row = null;
            return $row;
        }
    }

    public function getWebSites($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients_web_sites');
        $this->db->where('id_client', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            $result = null;
            return $result;
        }
    }

    public function verify_url_exist($id, $url)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients_web_sites');
        $this->db->where('id_client', $id);
        $this->db->where('web_site', $url);

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            $row = null;
            return $row;
        }
    }


    public function getWebSiteId($id, $url)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients_web_sites');
        $this->db->where('id_client', $id);
        $this->db->where('web_site', $url);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->id;
        } else {
            $row = 0;
            return $row;
        }
    }

    public function get_informative_detail_count($id)
    {
        $this->db->where('tipo', 'Informativo');
        $this->db->where('id_vulnerability', $id);
        $this->db->from(db_prefix() . 'detalles_vulnerabilities');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getClientWebSites($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients_web_sites');
        $this->db->where('id_client', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            $result = null;
            return $result;
        }
    }

    public function delete_websites($idClient)
    {
        $this->db->where('id_client', $idClient);
        $this->db->delete(db_prefix() . 'clients_web_sites');
        if ($this->db->affected_rows() > 0) {
            // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

            return true;
        }

        return false;
    }

    public function update_last_scan($id_client, $web_site, $data)
    {

        $this->db->where(['id_client' => $id_client, 'web_site' => $web_site]);
        $this->db->update(db_prefix() . 'vulnerabilities', $data);
        if ($this->db->affected_rows() > 0) {
            // log_activity('Trust Seal Updated [ID:' . $id . ']');
            return true;
        }

        return false;
    }

    public function update_scan($data, $where = [])
    {

        $this->db->where($where);
        $this->db->update(db_prefix() . 'vulnerabilities', $data);
        if ($this->db->affected_rows() > 0) {
            // log_activity('Trust Seal Updated [ID:' . $id . ']');
            return true;
        }

        return false;
    }

    public function delete_scan($where = [])
    {
        $this->db->where($where);
        $this->db->delete(db_prefix() . 'vulnerabilities');
        if ($this->db->affected_rows() > 0) {
            // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

            return true;
        }

        return false;
    }

    public function getClientValues($id)
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'clients');
        $this->db->where('userid', $id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result();
            return $result;
        } else {
            $result = null;
            return $result;
        }
    }

    public function verify_scan_exist($id, $url, $extraWhere = "")
    {
        $this->db->select('id_client, id');
        $this->db->from(db_prefix() . 'vulnerabilities');
        $this->db->where('id_client', $id);
        $this->db->where('web_site', $url);

        if ($extraWhere != "")
            $this->db->where($extraWhere);

        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row;
        } else {
            $row = null;
            return $row;
        }
    }
}
