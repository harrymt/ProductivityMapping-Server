<?php

require_once 'API.class.php';
class MyAPI extends API
{
    protected $User;

    public function __construct($query_string, $api_endpoint) {
        parent::__construct($query_string, $api_endpoint);
    }

    /**
     * Gets the status of the API, returns OK or BAD.
     * Can be used as a test call to see if API is working.
     *
     * /status/date/
     * /status/time/
     * /status/time/
     */
     public function status($arguments) {
        if ($this->method == 'GET') {

            // status/date
            if($arguments >= 1 && $arguments[0] == "date") {
                date_default_timezone_set('Europe/London'); // set default time zone
                return "OK The current date is " . date("Y-m-d");
            }

            // status/time
            if($arguments >= 1 && $arguments[0] == "time") {
                return "OK The current time is " . time();
            }

            // status/
            return "OK The general status is fine.";
        } else {
            return "BAD Only accepts GET requests";
        }
     }
 }

 ?>