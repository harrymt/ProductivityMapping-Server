<?php

require_once 'API.class.php';
require_once 'DatabaseAdapater.class.php';

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
     * GET /status/
     * GET /status/date/
     * GET /status/time/
     */
    public function status($arguments) {
        if ($this->method == 'GET') {

            //
            // /status/date/
            //
            if($arguments >= 1 && $arguments[0] == "date") {
                date_default_timezone_set('Europe/London'); // set default time zone
                return new Response_Wrapper("The current date is " . date("Y-m-d"));
            }

            //
            // /status/time/
            //
            if($arguments >= 1 && $arguments[0] == "time") {
                return new Response_Wrapper("The current time is " . time());
            }

            //
            // /status/
            //
            return new Response_Wrapper("The general status is fine");
        } else {
            return new Response_Wrapper("Only accepts GET requests", 405);
        }
    }



    /**
     * Get popular keywords
     *
     * GET /keywords/number/
     * GET /keywords/number/lat/lng/radius/
     *
     */
    public function keywords($arguments) {
        if ($this->method == 'GET') {

            //
            // /keywords/number/lat/lng/radius/
            //
            if($arguments >= 4 && is_numeric($arguments[0])
                && is_numeric($arguments[1]) // lat
                && is_numeric($arguments[2]) // lng
                && is_numeric($arguments[3]) // radius
                ) {

                $adapter = new DatabaseAdapater();
                $number = $this->get_numeric($arguments[0]);
                $lat = $this->get_numeric($arguments[1]);
                $lng = $this->get_numeric($arguments[2]);
                $radius = $this->get_numeric($arguments[3]);
                return new Response_Wrapper($adapter->getKeywordsNearLocation($number, $lat, $lng, $radius));
            }

            //
            // /keywords/number/
            //
            if($arguments >= 1 && is_numeric($arguments[0])) {
                $adapter = new DatabaseAdapater();
                $number = $this->get_numeric($arguments[0]);
                return new Response_Wrapper($adapter->getKeywords($number));
            }

            //
            // /keywords/
            //
            return new Response_Wrapper("Please provide a number /keywords/number/", 405);
        } else {
            return new Response_Wrapper("Only accepts GET requests", 405);
        }
    }
 }

?>