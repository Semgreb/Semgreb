<?php

function filter_api_reponse($relation, $type)
{
    if ($relation == '') {
        return [];
    }

    $infoReturn = [];

    if ($type == 'sello' || $type == 'sellos') {
        if (is_array($relation)) {
            $certificationkey = $relation['certificationkey'];
            $title     = $relation['title'];
            $requirements   = $relation['requirements'];
            $description  = $relation['description'];
            $logo_active = $relation['logo_active'];
            $id = $relation['id'];
        } else {
            $certificationkey = $relation->certificationkey;
            $title     = $relation->title;
            $requirements   = $relation->requirements;
            $description  = $relation->description;
            $logo_active = $relation->logo_active;
            $id = $relation->id;
        }

        $pathComplete = FCPATH . 'uploads/seals/' . $id . '/' . $logo_active;

        $infoReturn = [
            'nui' =>  $certificationkey,
            'title' =>  $title,
            //'requirements' => $requirements,
            'description' => $description,
            'image_url' => file_exists($pathComplete) ? _convert_img_base64($pathComplete) : null
        ];
        // $subtext = get_company_name($userid);
        //$link    = admin_url('clients/client/' . $userid . '?contactid=' . $id);
    }

    return  $infoReturn;
}


if (!function_exists('client_exist')) {
    function client_exist($id_client)
    {
        $CI = &get_instance();
        $CI->db->select('userid');
        $CI->db->from(db_prefix() . 'clients');
        $CI->db->where('userid', $id_client);
        $CI->db->where('active', 1);

        if ($CI->db->get()->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function _api_get_country($id)
    {
        $CI = &get_instance();

        $CI->db->where('country_id', $id);
        $country = $CI->db->get(db_prefix() . 'countries')->row();

        return $country;
    }


    function _get_client_($limit, $page, $where = [], $numRow = false)
    {
        $ci = &get_instance();
        $ci->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')));

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $ci->db->where($where);
        }

        // $ci->db->join(db_prefix() . 'customer_groups', db_prefix() . 'customer_groups.customer_id=' . db_prefix() . 'clients.userid', 'left');
        // $ci->db->join(db_prefix() . 'customers_groups', db_prefix() . 'customers_groups.id=' . db_prefix() . 'customer_groups.groupid', 'left');


        $ci->db->order_by('company', 'asc');


        if (!$numRow) {
            return $ci->db->get(db_prefix() . 'clients', $limit, $page)->result_array();
        } else {
            return $ci->db->get(db_prefix() . 'clients')->num_rows();
        }
    }

    function _get_client_customer($clienId)
    {
        $ci = &get_instance();
        $ci->db->select('customers_groups.*');

        $ci->db->where(['customer_groups.customer_id' => $clienId]);

        $ci->db->join(db_prefix() . 'customers_groups', db_prefix() . 'customers_groups.id=' . db_prefix() . 'customer_groups.groupid', 'left');
        $result = $ci->db->get(db_prefix() . 'customer_groups')->result_array();

        return $result;
    }



    /****. ", GROUP_CONCAT(" . db_prefix() . 'customers_groups.name */

    function _get_articles_($limit, $page, $where = [],  $numRow = false, $busqueda = "", $slug = "", $for_group = null, $slug_group = null)
    {
        $ci = &get_instance();
        $ci->db->select('knowledge_base.*, knowledge_base_groups.name as group_name');
        //$ci->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')));

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $ci->db->where($where);
        }

        if ($for_group != null) {
            $ci->db->where(db_prefix() . 'knowledge_base.articlegroup IN(' . $for_group . ')');
        }

        if (!empty($busqueda)) {
            $ci->db->like('knowledge_base.subject', $busqueda);
            $ci->db->or_like('knowledge_base.description', $busqueda);
        }

        if ($slug != null) {
            $ci->db->where(["knowledge_base.slug" => $slug]);
        }

        if ($slug_group != null) {
            $ci->db->where(["knowledge_base_groups.group_slug" => $slug_group]);
        }

        $ci->db->join(db_prefix() . 'knowledge_base_groups', db_prefix() . 'knowledge_base_groups.groupid=' . db_prefix() . 'knowledge_base.articlegroup', 'left');

        $ci->db->order_by('datecreated', 'asc');

        if (!$numRow) {
            return  $ci->db->get(db_prefix() . 'knowledge_base', $limit, $page)->result_array();
        } else {
            return $ci->db->get(db_prefix() . 'knowledge_base')->num_rows();
        }
    }

    function find_get_articles_($where = [], $article_slug = "")
    {
        $ci = &get_instance();
        $ci->db->select('knowledge_base.*, knowledge_base_groups.name as group_name');
        //$ci->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')));

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $ci->db->where($where);
        }

        // $ci->db->where(["knowledge_base.articleid" => $articleid]);

        $ci->db->where(["knowledge_base.slug" => $article_slug]);


        $ci->db->join(db_prefix() . 'knowledge_base_groups', db_prefix() . 'knowledge_base_groups.groupid=' . db_prefix() . 'knowledge_base.articlegroup', 'left');

        $ci->db->order_by('datecreated', 'asc');

        return  $ci->db->get(db_prefix() . 'knowledge_base')->result_array();
    }

    function _get_group_articles_($limit, $page, $where = [],  $numRow = false)
    {
        $ci = &get_instance();
        //$ci->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')));

        if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
            $ci->db->where($where);
        }

        if (!$numRow) {
            return $ci->db->get(db_prefix() . 'knowledge_base_groups', $limit, $page)->result_array();
        } else {
            return $ci->db->get(db_prefix() . 'knowledge_base_groups')->num_rows();
        }
    }


    function _get_client_type($exclude_commerce, $search, $grupo, $limit, $page, $where = [], $numRow = false)
    {
        $ci = &get_instance();


        $ci->db->select(db_prefix() . 'clients.*');


        if ($search !=  null) {
            //  $ci->db->where(db_prefix() . 'clients.company', $search);
            $ci->db->like(db_prefix() . 'clients.company', $search);

            // $ci->db->like('knowledge_base.subject', $busqueda);
            // $ci->db->or_like('knowledge_base.description', $busqueda);
        }

        $ci->db->where(db_prefix() . 'clients.active = 1');

        if ($exclude_commerce != null) {
            $ci->db->where(db_prefix() . 'clients.userid NOT IN(' . $exclude_commerce . ')');
        }

        if ($grupo != null) {

            $ci->db->join(db_prefix() . 'customer_groups', db_prefix() . 'customer_groups.customer_id=' . db_prefix() . 'clients.userid');
            $ci->db->join(db_prefix() . 'customers_groups', db_prefix() . 'customers_groups.id=' . db_prefix() . 'customer_groups.groupid');
            $ci->db->where(db_prefix() . 'customers_groups.id', $grupo);
        }

        if (!$numRow) {
            return $ci->db->get(db_prefix() . 'clients', $limit, $page)->result_array();
        } else {
            return $ci->db->get(db_prefix() . 'clients')->num_rows();
        }

        // $ci->db->select(implode(',', prefixed_table_fields_array(db_prefix() . 'clients')));

        // if ((is_array($where) && count($where) > 0) || (is_string($where) && $where != '')) {
        //     $ci->db->where($where);
        // }

        // $ci->db->order_by('company', 'asc');
        // if (!$numRow) {
        //     return $ci->db->get(db_prefix() . 'clients', $limit, $page)->result_array();
        // } else {
        //     return $ci->db->get(db_prefix() . 'clients')->num_rows();
        // }
    }

    function _convert_img_base64($pathComplete)
    {
        if (file_exists($pathComplete)) {
            $type = strtolower(pathinfo($pathComplete, PATHINFO_EXTENSION));
            if (in_array($type, ['pdf', 'png', 'jpg', 'jpeg', 'gif'])) {
                $data = file_get_contents($pathComplete);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                return "";
            }
        } else {
            return "";
        }

        return $base64;
    }


    function get_extra_data_customer_global($customerid)
    {
        $CI = &get_instance();
        return  $CI->db
            ->where('userid', $customerid)
            ->get(db_prefix() . 'extra_fields_clients')
            ->row();
    }

    function fields_extra_clients($id_client)
    {
        $result = get_extra_data_customer_global($id_client);

        $list_fields = [
            'logo' => '', 'client_email' => '', 'client_description' => '' //, 'client_razon_social' => ''
        ];

        if ($result != null) {
            $client_description = $result->descriptions;
            $client_email = $result->email;
            $client_logo = $result->logo;
            $slug = $result->slug;

            $pathLogoComplete = FCPATH . 'uploads/seals/logo_cliente_base/' . $id_client . '/' . $client_logo;

            $list_fields = [
                'client_email' =>  $client_email, 'client_description' =>  $client_description,
                'slug' => $slug,
                'logo' => file_exists($pathLogoComplete) ? _convert_img_base64($pathLogoComplete) : null
            ];
        }

        return $list_fields;
    }

    function get_extra_data_customer_global_slug($slug)
    {
        $CI = &get_instance();
        return  $CI->db
            ->where('slug', $slug)
            ->get(db_prefix() . 'extra_fields_clients')
            ->row();
    }

    function fields_extra_clients_slug($slug)
    {
        $result = get_extra_data_customer_global_slug($slug);

        $list_fields = [
            'logo' => '', 'client_email' => '', 'client_description' => '' //, 'client_razon_social' => ''
        ];

        if ($result != null) {
            $client_description = $result->descriptions;
            $client_email = $result->email;
            $client_logo = $result->logo;
            $slug = $result->slug;

            $pathLogoComplete = FCPATH . 'uploads/seals/logo_cliente_base/' . $result->userid . '/' . $client_logo;

            $list_fields = [
                'client_email' =>  $client_email, 'client_description' =>  $client_description,
                'userid' => $result->userid,
                'slug' => $slug,
                'logo' => file_exists($pathLogoComplete) ? _convert_img_base64($pathLogoComplete) : null
            ];
        }

        return $list_fields;
    }

    function _filter_logo_status($id_customer)
    {
        $result = get_logo_seal_client($id_customer);
        $infoReturn = [];

        foreach ($result  as $value) {

            if ($value['status'] == 1) {
                $pathComplete = FCPATH . 'uploads/seals/' . $value['id'] . '/' . $value['logo_active'];
                if (file_exists($pathComplete)) {
                    $value['logo'] =  _convert_img_base64($pathComplete);
                } else {
                    $value['logo'] = null;
                }
            } else {
                $pathComplete = FCPATH . 'uploads/seals/' . $value['id'] . '/' . $value['logo_inactive'];
                if (file_exists($pathComplete)) {
                    $value['logo'] =  _convert_img_base64($pathComplete);
                } else {
                    $value['logo'] = null;
                }
            }

            $infoReturn[] = [
                'description' => $value['description'],
                'title' => $value['title'],
                'date_expiration' => $value['date_expiration'],
                'logo' => $value['logo'],
                'nui' => $value['certificationkey']
            ];
        }

        return $infoReturn;
    }
    /***foreach ($CI->db->get()->result_array() as $value) {
        if (
            $value['date_expiration'] < date('Y-m-d')
            &&  $value['status'] != 2
        ) {
            $CI->db->where('id', $value['id']);
            $CI->db->update(db_prefix() . 'certifications', ['status' => 2]);
            if ($CI->db->affected_rows() > 0) {
                log_activity('Certification Updated [ID:' . $value['id'] . ']');
            }
        }
    } */

    function get_logo_seal_client($id_customer)
    {
        $ci = &get_instance();
        $id_customer = (int) $id_customer;

        $sql = "SELECT se.id, se.status, ct.certificationkey, se.title, se.description, se.logo_active, se.logo_inactive, ct.date_expiration
        FROM " . db_prefix() . "certifications ct
        JOIN " . db_prefix() . "seals se ON se.id = ct.id_seal
        WHERE  ct.id_customer = $id_customer;";

        $result = $ci->db->query($sql)->result_array();
        return $result;
    }

    function get_type_client()
    {
        $ci = &get_instance();
        $sql = "SELECT * FROM `" . db_prefix() . "customers_groups` ORDER BY `name` ASC";
        $result = $ci->db->query($sql)->result_array();
        return $result;
    }

    function get_type_client_count()
    {
        $ci = &get_instance();
        $sql = "SELECT count(name) AS total FROM `" . db_prefix() . "customers_groups`;";
        $result = $ci->db->query($sql)->result_array();
        return $result;
    }

    function get_clients_certificates_count()
    {
        $ci = &get_instance();

        $sql = "SELECT COUNT(ct.id_seal) as total FROM " . db_prefix() . "certifications as ct
JOIN " . db_prefix() . "clients cl ON (cl.userid = ct.id_customer)
WHERE ct.date_expiration > NOW() AND cl.active = 1";


        $result = $ci->db->query($sql)->result_array();
        return $result;
    }
}
