<?php

require_once 'API.class.php';
require_once 'DatabaseAdapater.class.php';
require_once 'ZoneTableSchema.class.php';

/**
 *
 * GET /status/
 * GET /status/date/
 * GET /status/time/
 *
 * GET /keywords/number/
 * GET /keywords/number/lat/lng/radius/
 *
 * GET /apps/number/
 * GET /apps/number/lat/lng/radius/
 *
 * GET /zones/number/lat/lng/radius/
 *
 * POST /zone/
 *
*/
class MyAPI extends API
{
    public function __construct($request, $query_string) {
        parent::__construct($request, $query_string);
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

            if($arguments >= 1) {

                //
                // /status/date/
                //
                if($arguments[0] == "date") {
                    date_default_timezone_set('Europe/London'); // set default time zone
                    return new Response_Wrapper(date("Y-m-d"));
                }

                //
                // /status/time/
                //
                if($arguments[0] == "time") {
                    date_default_timezone_set('GMT');
                    $st = new DateTime();
                    return new Response_Wrapper($st->format('H:i:s'));
                }
            }

            //
            // /status/
            //
            return new Response_Wrapper("Server Status: OK");
        } else {
            return new Response_Wrapper("Only accepts GET requests", 405);
        }
    }

    /**
     * Get popular keywords.
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

    /**
     * Get popular apps.
     *
     * GET /apps/number/
     * GET /apps/number/lat/lng/radius/
     *
     */
    public function apps($arguments) {
        if ($this->method == 'GET') {

            //
            // /apps/number/lat/lng/radius/
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
                return new Response_Wrapper($adapter->getAppsNearLocation($number, $lat, $lng, $radius));
            }

            //
            // /apps/number/
            //
            if($arguments >= 1 && is_numeric($arguments[0])) {
                $adapter = new DatabaseAdapater();
                $number = $this->get_numeric($arguments[0]);
                return new Response_Wrapper($adapter->getApps($number));
            }

            //
            // /apps/
            //
            return new Response_Wrapper("Please provide a number /apps/number/", 405);
        } else {
            return new Response_Wrapper("Only accepts GET requests", 405);
        }
    }

    /**
     * Get zones near a location.
     *
     * GET /zones/number/lat/lng/radius/
     *
     */
    public function zones($arguments) {
        if ($this->method == 'GET') {

            //
            // /zones/number/lat/lng/radius/
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

                $zones = $adapter->getZonesNearLocation($number, $lat, $lng, $radius);
                return new Response_Wrapper($zones);
            }


            //
            // /zones/
            //
            return new Response_Wrapper("Please provide a number and location /zones/number/lat/lng/radius/", 405);
        } else {
            return new Response_Wrapper("Only accepts GET requests", 405);
        }
    }

    /**
     * Saves a zone to the zone database
     *
     * POST /zone/
     * Payload:
     * {
     *  user_id: 123,
     *  id: 123,
     *  zone: name,
     *  radius: 123.0,
     *  lat: 123.0,
     *  lng: 123.0,
     *  blockingApps: [
     *    "facebook.com",
     *    "google.com"
     *  ],
     *  keywords: [
     *    "harry"
     *  ]
     * }
     */
    public function zone($arguments) {
        if ($this->method == 'POST') {

            // Get the zone from the data passed in
            $zone_object = $this->deSerializeZone($this->file);

            if(gettype($zone_object) == "string") { // error
                return new Response_Wrapper("Error reading payload. " . $zone_object, 405);
            }

            // Write to database
            $adapter = new DatabaseAdapater();
            $success_message = "Sent zone '" . $zone_object->{ZoneTableSchema::name} . "' successfully.";
            $database_message = $adapter->writeZone($zone_object);
            if($database_message == null) {
                return new Response_Wrapper($success_message); // Force it to be JSON
            } else {
                return new Response_Wrapper("Failed to write zone to database: " . $database_message, 405);
            }
        }
        return new Response_Wrapper("Only accepts POST requests", 405);
    }

    /**
     * Validate the payload zone data.
     *
     * @param $payload json passed with POST request.
     * @return mixed|null|string zone object
     */
    private function deSerializeZone($payload) {
        $zone_object = json_decode($payload);

        $error_string = null;

        // Validate zone object (least important first)
        // Note we accept empty blocking apps and keywords, but everything else must have data.
        if($zone_object->{ZoneTableSchema::name} == null) { $error_string = "zone name cannot be null"; }
        if($zone_object->{ZoneTableSchema::radius} == null) { $error_string = "zone radius cannot be null"; }
        if($zone_object->{ZoneTableSchema::lng} == null) { $error_string = "zone lng cannot be null"; }
        if($zone_object->{ZoneTableSchema::lat} == null) { $error_string = "zone lat cannot be null"; }
        if($zone_object->{ZoneTableSchema::id} == null) { $error_string = "zone id cannot be null"; }
        if($zone_object->{ZoneTableSchema::user_id} == null) { $error_string = "user id cannot be null"; }

        // Convert Blocking Apps and Keywords to database safe strings
        $blocking_apps_safe_string = "";
        foreach ($zone_object->{ZoneTableSchema::blockingApps} as $app) { $blocking_apps_safe_string .= $app . ","; }
        $zone_object->{ZoneTableSchema::blockingApps} = $blocking_apps_safe_string;

        $keywords_safe_string = "";
        foreach ($zone_object->{ZoneTableSchema::keywords} as $word) { $keywords_safe_string .= $word . ","; }
        $zone_object->{ZoneTableSchema::keywords} = $keywords_safe_string;

        if($error_string != null) {
            return $error_string;
        }

        return $zone_object;
    }

}

?>