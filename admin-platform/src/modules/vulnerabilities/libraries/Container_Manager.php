<?php
class Container_Manager
{
    //const BASE_URL = 'http://40.117.102.146:5000/';
    private static $baseURL = null;
    private static $apiKey = null;

    public function __construct()
    {
        self::$baseURL = get_option("url_container");
        self::$apiKey = get_option("key_analisys");
    }

    private static function sendRequest($method, $uri,  $data = [])
    {
        $url = self::$baseURL . $uri;
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

        $key_header = get_option("key_container");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'X-API-KEY:' . $key_header]);
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
    }

    public static function count_container()
    {
        $data = new stdClass();
        $data->api_key = (string)self::$apiKey;
        $data = self::sendRequest('get', "status-containers-count", []);

        if ($data != null) {
            return $data->count;
        } else {
            return 1000000;
        }
    }

    /**
     * Response:
     * {
          container: {
                api_key: 123456789,
                container_name: TestingContainer4,
                host_port": 8004
            },
          message: Zap container 'TestingContainer4' started
       }
     */
    public static function create_container($port)
    {
        $data = new stdClass();
        $data->api_key = (string)self::$apiKey;
        $data->host_port  = (string)$port;
        $data->name = (string)time() . "_$port";
        $data = self::sendRequest('post', "start", $data);
        return $data;
    }

    /**
     * Response:
     * {
         "message": "Container with port '8004' deleted"
       }
     */
    public static function delete_container($portsDynamic)
    {
        $data = self::sendRequest('delete', "delete_by_port/$portsDynamic");
        return $data;
    }

    /**
     * {
        "status": "running"
      }
     */
    public static function status_container($portsDynamic)
    {
        $data = self::sendRequest('get', "status/$portsDynamic");
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
