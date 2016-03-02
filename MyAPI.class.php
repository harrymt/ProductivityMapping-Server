<?php

require_once 'API.class.php';
class MyAPI extends API
{
    protected $User;

    public function __construct($query_string, $api_endpoint) {
        parent::__construct($query_string, $api_endpoint);
    }

    /**
     * Example of an Endpoint
     */
     public function getInfo() {
        if ($this->method == 'GET') {
            return "OK The current time is " . $_SERVER['REQUEST_TIME']; //. $this->User->name;
        } else {
            return "Only accepts GET requests";
        }
     }
 }

 ?>