<?php
defined('BASEPATH') or exit('No direct script access allowed');
// Include Rest Controller library 
require APP_MODULES_PATH . 'trust_seal/libraries/REST_Controller.php';

class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load user model 
        $this->load->model('Seals_model');
        $this->load->helper('api');
    }

    /***
     * Obtiene detalle de sello por id
     */
    public function seal_get()
    {
        try {
            $id = $this->input->get("id") ?  $this->input->get("id") : 0;
            // Returns all rows if the id parameter doesn't exist, 
            //otherwise single row will be returned 
            $seals = $this->Seals_model->getJoin($id);

            //check if the user data exists 
            if (!empty($seals)) {

                $seals->niu = $seals->certificationkey;
                unset($seals->certificationkey);
                // Set the response and exit 
                //OK (200) being the HTTP response code 
                $this->response($seals, REST_Controller::HTTP_OK);
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([
                    'status' =>  REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No seals were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Muestra listado de sellos disponibles
     */
    public function seals_get()
    {
        try {
            // Returns all rows if the id parameter doesn't exist, 
            //otherwise single row will be returned 
            $seals = $this->Seals_model->getJoin('', [db_prefix() . 'seals.status' => 1]);

            //check if the user data exists 
            if (!empty($seals)) {
                $type  = "sellos";
                $_data = [];

                foreach ($seals as $relation) {
                    $relation_values = filter_api_reponse($relation, $type);
                    $_data[] = $relation_values;
                }
                // Set the response and exit 
                //OK (200) being the HTTP response code 
                $this->response($_data, REST_Controller::HTTP_OK);
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([], REST_Controller::HTTP_OK);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Codifica el id del cliente y lo retorna
     */
    public function hash_client_get()
    {
        try {

            $client_id = $this->input->get("id") ?  $this->input->get("id") : 0;
            //check if the user data exists 
            if (!empty($client_id) && $client_id > 0) {
                if (client_exist($client_id)) {
                    $client_id = urlencode(base64_encode(base64_encode(base64_encode($client_id))));
                    $this->response($client_id, REST_Controller::HTTP_OK);
                } else {

                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => 'No customers were found.'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'Something went wrong, please try again.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Obtiene el perfil de un cliente
     */
    public function client_profile_get()
    {
        try {
            $client_id = $this->input->get("id") ?  $this->input->get("id") : 0;

            if (!empty($client_id) && $client_id > 0) {
                if (client_exist($client_id)) {
                    $custom_response = [];
                    $response =  _get_client_(0, 0, ['userid' => $client_id], false, true);
                    $extraResult  =  fields_extra_clients($client_id);
                    $response[0]['client_id_encrypt'] = urlencode(base64_encode(base64_encode(base64_encode($client_id))));
                    $response[0]['country'] = _api_get_country($response[0]['country']);
                    $custom_response =  array_merge($response[0], $extraResult);
                    $custom_response['type'] = _get_client_customer($client_id);
                    $custom_response['seals'] = _filter_logo_status($client_id);
                    $this->response($custom_response, REST_Controller::HTTP_OK);
                } else {

                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => 'No customers were found.'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'Something went wrong, please try again.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Obtiene listado de clientes
     */
    public function clients_get()
    {
        try {
            $search = $this->input->get("busqueda") ?  $this->input->get("busqueda") : null;
            $exclude_commerce = $this->input->get("exclude_commerce") ?  $this->input->get("exclude_commerce") : null;
            $group = $this->input->get("group") ?  $this->input->get("group") : null;
            $limit = $this->input->get("limit") ? $this->input->get("limit") : 12;
            $page = $this->input->get("page") ? $this->input->get("page") : 0;

            $tm_page = $page;
            $page  = $page <= 0 ? $page : $page -= 1;
            $limitPage = ($page *  $limit);

            // $total  =  _get_client_($limit, $limitPage, [], TRUE);
            // $result =  _get_client_($limit, $limitPage);
            $total  =  _get_client_type($exclude_commerce, $search, $group, $limit, $limitPage, [], TRUE);
            $result =  _get_client_type($exclude_commerce, $search, $group, $limit, $limitPage);

            // if (!empty($result)) {

            $rs = [];
            foreach ($result as $value) {
                $custom_response = [];
                $extraResult  =  fields_extra_clients($value['userid']);
                // $value['client_id_encrypt'] = urlencode(base64_encode(base64_encode(base64_encode($value['userid']))));
                // $value['country'] = _api_get_country($value['country']);
                // $value['type'] = _get_client_customer($value['userid']);
                //$custom_response['country'] = _api_get_country($value['country']);


                $custom_response['userid'] = $value['userid'];
                $custom_response['website'] = $value['website'];
                $custom_response['company'] = $value['company'];

                $custom_response['type'] = _get_client_customer($value['userid']);
                $custom_response['logo'] = $extraResult['logo'];
                $custom_response['slug'] = $extraResult['slug'];

                //logo, type, website, company, userid

                // $custom_response =  array_merge($value, $extraResult);
                // $value['userid'] = 
                $rs[] = $custom_response;
            }

            $response         = new stdClass();
            $response->page   = $tm_page;
            $response->limit  = $limit;
            $response->total  = $total;
            $response->data   = $rs;

            $this->response($response, REST_Controller::HTTP_OK);
            // } else {
            //     // Set the response and exit 
            //     //NOT_FOUND (404) being the HTTP response code 
            //     $this->response([
            //         'status' => REST_Controller::HTTP_NOT_FOUND,
            //         'message' => 'No customers were found.'
            //     ], REST_Controller::HTTP_NOT_FOUND);
            // }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 
     */
    public function articles_get()
    {

        try {

            $busqueda = $this->input->get("busqueda") ?  $this->input->get("busqueda") : null;
            $slug = $this->input->get("slug") ?  $this->input->get("slug") : null;
            $limit = $this->input->get("limit") ? $this->input->get("limit") : 12;
            $page = $this->input->get("page") ? $this->input->get("page") : 0;

            $tm_page = $page;
            $page  = $page <= 0 ? $page : $page -= 1;
            $limitPage = ($page *  $limit);

            $total  =  _get_articles_($limit, $limitPage, ['knowledge_base.staff_article' => 0, 'knowledge_base.active' => 1], TRUE, $busqueda, $slug);
            $result =  _get_articles_($limit, $limitPage, ['knowledge_base.staff_article' => 0, 'knowledge_base.active' => 1], FALSE, $busqueda, $slug);


            // if (!empty($result)) {

            $rs = [];
            foreach ($result as $value) {

                $value['short_description']  = substr($value['description'], 0, 200);
                $rs[] = $value;
            }

            $response         = new stdClass();
            $response->page   = $tm_page;
            $response->limit  = $limit;
            $response->total  = $total;
            $response->data   = $rs;

            $this->response($response, REST_Controller::HTTP_OK);
            // } else {

            //     $this->response([
            //         'status' => REST_Controller::HTTP_NOT_FOUND,
            //         'message' => 'No article were found.'
            //     ], REST_Controller::HTTP_NOT_FOUND);
            // }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 
     */
    public function article_group_list_get()
    {
        try {
            // $slug = $this->input->get("slug") ?  $this->input->get("slug") : null;
            $limit = $this->input->get("limit") ? $this->input->get("limit") : 12;
            $page = $this->input->get("page") ? $this->input->get("page") : 0;

            $tm_page = $page;
            $page  = $page <= 0 ? $page : $page -= 1;
            $limitPage = ($page *  $limit);

            $total  =  _get_group_articles_($limit, $limitPage, [], TRUE);
            $result =  _get_group_articles_($limit, $limitPage, []);


            // if (!empty($result)) {

            $response         = new stdClass();
            $response->page   = $tm_page;
            $response->limit  = $limit;
            $response->total  = $total;
            $response->data   = $result;

            $this->response($response, REST_Controller::HTTP_OK);
            // } else {

            //     $this->response([
            //         'status' => REST_Controller::HTTP_NOT_FOUND,
            //         'message' => 'No article group were found.'
            //     ], REST_Controller::HTTP_NOT_FOUND);
            // }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function article_by_group_get()
    {
        try {

            $slug_group = $this->input->get("slug") ?  $this->input->get("slug") : null;
            $limit = $this->input->get("limit") ? $this->input->get("limit") : 12;
            $page = $this->input->get("page") ? $this->input->get("page") : 0;

            $tm_page = $page;
            $page  = $page <= 0 ? $page : $page -= 1;
            $limitPage = ($page *  $limit);

            $total  =  _get_articles_($limit, $limitPage, ['knowledge_base.staff_article' => 0, 'knowledge_base.active' => 1], TRUE, null, null, null, $slug_group);
            $result =  _get_articles_($limit, $limitPage, ['knowledge_base.staff_article' => 0, 'knowledge_base.active' => 1], FALSE, null, null, null, $slug_group);

            // if (!empty($result)) {

            $rs = [];
            foreach ($result as $value) {

                $value['short_description']  = substr($value['description'], 0, 200);
                $rs[] = $value;
            }

            $response         = new stdClass();
            $response->page   = $tm_page;
            $response->limit  = $limit;
            $response->total  = $total;
            $response->data   = $rs;

            $this->response($response, REST_Controller::HTTP_OK);
            // } else {

            //     $this->response([
            //         'status' => REST_Controller::HTTP_NOT_FOUND,
            //         'message' => 'No article groups were found.'
            //     ], REST_Controller::HTTP_NOT_FOUND);
            // }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function find_article_get()
    {
        try {
            $article_slug = $this->input->get("slug") ?  $this->input->get("slug") : null;

            $result =  find_get_articles_(['knowledge_base.staff_article' => 0, 'knowledge_base.active' => 1], $article_slug);

            $rs = [];
            foreach ($result as $value) {

                $value['short_description']  = substr($value['description'], 0, 200);
                $rs[] = $value;
            }

            $response = new stdClass();

            if (!empty($rs)) {
                $this->response($rs[0], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No article were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function clients_type_get()
    {
        try {
            $group = $this->input->get("group") ?  $this->input->get("group") : 0;
            $limit = $this->input->get("limit") ? $this->input->get("limit") : 12;
            $page = $this->input->get("page") ? $this->input->get("page") : 0;

            $tm_page = $page;
            $page  = $page <= 0 ? $page : $page -= 1;
            $limitPage = ($page *  $limit);

            $total  =  _get_client_type(null, null, $group, $limit, $limitPage, [], TRUE);
            $result =  _get_client_type(null, null, $group, $limit, $limitPage);


            $complete_response = [];

            // if (!empty($result)) {

            foreach ($result as $value) {

                $custom_response = [];
                $extraResult  =  fields_extra_clients($value['userid']);
                $custom_response =  array_merge($value, $extraResult);
                $custom_response['type'] = _get_client_customer($value['userid']);

                $complete_response[] = $custom_response;
            }

            $response         = new stdClass();
            $response->page   = $tm_page;
            $response->limit  = $limit;
            $response->total  = $total;
            $response->data   = $complete_response;

            $this->response($response, REST_Controller::HTTP_OK);
            // } else {

            //     $this->response([
            //         'status' => REST_Controller::HTTP_NOT_FOUND,
            //         'message' => 'No clients type were found.'
            //     ], REST_Controller::HTTP_NOT_FOUND);
            // }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function client_seal_get()
    {
        try {
            $client_id = $this->input->get("id") ?  $this->input->get("id") : 0;

            if (!empty($client_id) && $client_id > 0) {
                if (client_exist($client_id)) {

                    $response = _filter_logo_status($client_id);

                    if (empty($response)) {

                        $this->response([
                            'status' => REST_Controller::HTTP_NOT_FOUND,
                            'message' => 'No seals were found.'
                        ], REST_Controller::HTTP_NOT_FOUND);
                    } else {
                        $this->response($response, REST_Controller::HTTP_OK);
                    }
                } else {

                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => 'No customers were found.'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'Something went wrong, please try again.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function client_profile_slug_get()
    {

        try {
            $client_slug = $this->input->get("slug") ?  $this->input->get("slug") : null;

            if (!empty($client_slug) && strlen($client_slug) > 0) {

                $extraResult  =  fields_extra_clients_slug($client_slug);
                if (isset($extraResult['userid'])) {

                    $client_id = $extraResult['userid'];
                    unset($extraResult['userid']);

                    if (client_exist($client_id)) {
                        $custom_response = [];
                        $response =  _get_client_(0, 0, ['userid' => $client_id, 'active' => 1], false, true);
                        $response[0]['client_id_encrypt'] = urlencode(base64_encode(base64_encode(base64_encode($client_id))));
                        $response[0]['country'] = _api_get_country($response[0]['country']);
                        $custom_response =  array_merge($response[0], $extraResult);
                        $custom_response['type'] = _get_client_customer($client_id);
                        $custom_response['seals'] = _filter_logo_status($client_id);
                        $this->response($custom_response, REST_Controller::HTTP_OK);
                    } else {

                        $this->response([
                            'status' => REST_Controller::HTTP_NOT_FOUND,
                            'message' => 'No customers were found.'
                        ], REST_Controller::HTTP_NOT_FOUND);
                    }
                } else {
                    $this->response([
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => 'No customers were found.'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            } else {
                // Set the response and exit 
                //NOT_FOUND (404) being the HTTP response code 
                $this->response([
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'Something went wrong, please try again.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function list_type_get()
    {
        try {
            $response = get_type_client();

            if (!empty($response)) {
                $this->response($response, REST_Controller::HTTP_OK);
            } else {

                $this->response([
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No clients type were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Detalle de un grupo
     */
    public function group_details_get()
    {
        try {
            $slug = $this->input->get("slug") ?  $this->input->get("slug") : null;
            $result =  _get_group_articles_(null, null, ['group_slug' => $slug]);

            if (!empty($result)) {

                $this->response($result[0], REST_Controller::HTTP_OK);
            } else {

                $this->response([
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No article group were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function count_type_customers_get()
    {
        try {
            $response = get_type_client_count();

            if (!empty($response)) {
                $this->response($response[0], REST_Controller::HTTP_OK);
            } else {

                $this->response([
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No clients type were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function count_certificates_customers_get()
    {
        try {
            $response = get_clients_certificates_count();
            $response_type = get_type_client_count();

            if (!empty($response) || !empty($response_type)) {
                $this->response(['total_certificate' => (int) $response[0]['total'], 'total_type_clients' => (int) $response_type[0]['total']], REST_Controller::HTTP_OK);
            } else {

                $this->response([
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'No clients type were found.'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
            $this->response([
                'status' => REST_Controller::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'Something went wrong, please try again.'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
