<?php

abstract class API
{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    /**
     * Property: endpoint
     * The Model requested in the URI. eg: /files
     */
    protected $endpoint = '';
    /**
     * Property: args
     * Any additional URI components after the endpoint and verb have been removed, in our
     * case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
     * or /<endpoint>/<arg0>
     */
    protected $args = Array();
    /**
     * Property: file
     * Stores the input of the PUT request
     */
    protected $file = Null;
     /**
     * Property: api_version
     * States what version of the API we are using
     */
    protected $api_version = '';

    /**
     * Constructor: __construct
     * Allow for CORS, assemble and pre-process the data
     */
    public function __construct($request) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        // $request: "/v1/status/time"
        $this->args = explode('/', trim($request, '/')); // atm set to ["v1", "status", "time"]
        $this->api_version = array_shift($this->args); // "v1"
        $this->endpoint = array_shift($this->args); // "status"
        // Now $this->args is set to ["time"]

        // See if its a POST (DELETE) or PUT, otherwise its a GET
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) {
            case 'DELETE': // Same as POST
            case 'POST':
                $this->request = $this->_cleanInputs($_POST);
                $this->file = file_get_contents("php://input");
                break;
            case 'GET':
                $this->request = $this->_cleanInputs($_GET);
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        }
    }

    public function processAPI() {
        // Checks to see if api endpoint exists
        if (method_exists($this, $this->endpoint)) {
            $function_name = $this->endpoint;
            $function_args = $this->args;
            $endpoint_return = $this->{$function_name}($function_args);
            return $this->_response($endpoint_return->response, $endpoint_return->code);
        }

        return $this->_response("No Endpoint: $this->endpoint" , 404);
    }

    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        if($status == 200) {
            return json_encode($data);
        } else {
            return json_encode(Array("error" => $data));
        }
    }

    private function _cleanInputs($data) {
        $clean_input = Array();
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
        return $clean_input;
    }

    private function _requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code])?$status[$code]:$status[500];
    }

    /**
     * Converts a string to a numeric value.
     *
     * @param  String $str The String value to convert.
     * @return int or float Output value, can be int or float.
     */
    public function get_numeric($str) {
      if (is_numeric($str)) {
        return $str + 0;
      }
      return 0;
    }

}


class Response_Wrapper
{
    public $code = 0;
    public $response = "";

    public function __construct($r, $c = 200) {
        $this->response = $r;
        $this->code = $c;
    }
}
?>