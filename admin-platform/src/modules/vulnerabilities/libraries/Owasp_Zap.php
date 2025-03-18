<?php
class Owasp_Zap
{
    // const BASE_URL = 'http://40.117.102.146';

    // const API_KEY = 123456789;
    private static $baseURL = null;
    private static $apiKey = null;
    private static $ports = null;

    public function __construct()
    {
        self::$baseURL = get_option("url_analisis");
        self::$apiKey = get_option("key_analisys");
    }
    public static function setPort($port)
    {
        self::$ports = $port;
    }

    private static function sendRequest($method, $uri,  $data = [])
    {
        if (self::$ports != null) {

            try {

                $url = sprintf("%s:%s/%s",  self::$baseURL, self::$ports, $uri);
                $method = strtoupper($method);
                $ch = curl_init();
                if ($method == 'POST') {
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));
                } else if ($method == 'DELETE') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                } else if ($method == 'PUT') {
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($ch, CURLOPT_POSTFIELDS,  json_encode($data));
                } else {
                    if ($data) {
                        $url = sprintf('%s?%s', $url, http_build_query($data));
                    }
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Deshabilitar la verificación del certificado SSL
                // Se debe quitar esta parte al enviar a prod
                // curl_setopt($ch, CURLOPT_CAINFO, '/etc/ssl/certs/cert.pem');
                // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $response = curl_exec($ch);

                if ($response === false) {
                    $error = curl_error($ch);
                    throw new Exception($error, 1);
                }
                curl_close($ch);

                return json_decode($response);
            } catch (Exception $e) {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function create_spider_scan($url)
    {
        // $data->target_urls = ["http://malla.agency/"];
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->url  = $url;
        $data->recurse =  (bool)false;
        $data = self::sendRequest('get', 'JSON/spider/action/scan/', $data);
        return $data;
    }

    public static function create_scan($url)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->url  = $url;
        $data->recurse = (bool)false;
        $data = self::sendRequest('get', 'JSON/ascan/action/scan/', $data);
        return $data;
    }

    public static function scan_list($data)
    {
        $data = self::sendRequest('get', '', $data);
        return $data;
    }

    public static function scan_status($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/view/status/",  $data);
        return $data;
    }

    public static function scan_progress_status($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/view/scanProgress/",  $data);
        return $data;
    }

    public static function scan_alert_by_url($baseurl, $start = 1, $count = -1)
    {
        $data = new stdClass();
        $data->apikey   =  self::$apiKey;
        $data->baseurl  = $baseurl;
        $data->start    = $start;
        $data->count    = $count;

        $data = self::sendRequest('get', "JSON/alert/view/alerts/",  $data);
        return $data;
    }

    public static function scan_alert($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/view/alertsIds/",  $data);
        return $data;
    }

    public static function scan_resume($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/action/resume/",  $data);
        return $data;
    }

    public static function scan_stop($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/action/stop/",  $data);
        return $data;
    }

    public static function scan_spider_stop($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/spider/action/stop/",  $data);
        return $data;
    }

    public static function scan_spider_view_status($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/spider/view/status/",  $data);
        return $data;
    }

    public static function scan_spider_result($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/spider/view/results/",  $data);
        return $data;
    }

    public static function scan_remove($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/ascan/action/removeScan/",  $data);
        return $data;
    }

    public static function scan_spider_remove($scanId)
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data->scanId  = $scanId;
        $data = self::sendRequest('get', "JSON/spider/action/removeScan/",  $data);
        return $data;
    }


    public static function scan_stop_all()
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data = self::sendRequest('get', "JSON/ascan/action/stopAllScans/",  $data);
        return $data;
    }

    public static function scan_spider_stop_all()
    {
        $data = new stdClass();
        $data->apikey =  self::$apiKey;
        $data = self::sendRequest('get', "JSON/spider/action/stop/",  $data);
        return $data;
    }


    /***************************************** */

    public static function clrear_all_scan_result($id)
    {
        $data = self::sendRequest('delete', "scans/$id");
        return $data;
    }

    public static function knowledge_base($id)
    {
        $data = self::sendRequest('get', "scans/$id/kb/");
        return $data;
    }

    public static function request_is_error($data)
    {
        if ($data->code && $data->code < 200 || $data->code >= 300) {
            return true;
        }

        return false;
    }
}
