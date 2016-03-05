<?php

require_once '../Environment_variable.class.php';
require_once 'ZoneTableSchema.class.php';

class DatabaseAdapater
{

    public function __construct() {
    }

    // Utility function to create dummy data
    private function makeArr($number, $str) {
        $i = 0; $stack = array();
        while ($i < $number) {
            array_push($stack, $str);
            $i++;
        };

        return $stack;
    }

    // Utility function to create dummy data
    private function makeZoneObj() {
        return array(
            "name" => "my zone",
            "radius" => 47.1,
            "lat" => 52.123123,
            "lng" => 52.123123
        );
    }

    /**
     * Gets the top $number of popular keywords.
     *
     * @param  int $number Number of keywords to get.
     * @return String array Keywords.
     */
    public function getKeywords($number) {
        return $this->makeArr($number, "a_keyword");
    }

    /**
     * Gets the top $number of popular keywords near a location.
     *
     * @param  int $number  Number of keywords to get.
     * @param  float $lat    Latitude of location
     * @param  float $lng    Longitude of location
     * @param  float $radius Radius from location.
     * @return String array Keywords.
     */
    public function getKeywordsNearLocation($number, $lat, $lng, $radius) {
        return $this->makeArr($number, "keyword_near_(" . $lat . "," . $lng . ")_with_radius_" . $radius);
    }


    /**
     * Gets the top $number of popular keywords.
     *
     * @param  int $number Number of keywords to get.
     * @return String array Keywords.
     */
    public function getApps($number) {
        return $this->makeArr($number, "a_keyword");
    }

    /**
     * Gets the top $number of popular apps near a location.
     *
     * @param  int $number  Number of apps to get.
     * @param  float $lat    Latitude of location
     * @param  float $lng    Longitude of location
     * @param  float $radius Radius from location.
     * @return String array Apps.
     */
    public function getAppsNearLocation($number, $lat, $lng, $radius) {
        return $this->makeArr($number, "keyword_near_(" . $lat . "," . $lng . ")_with_radius_" . $radius);
    }

    /**
     * Gets the top $number of zones near a location.
     *
     * @param  int $number  Number of zones to get.
     * @param  float $lat    Latitude of location
     * @param  float $lng    Longitude of location
     * @param  float $radius Radius from location.
     * @return String array Zone.
     */
    public function getZonesNearLocation($number, $lat, $lng, $radius) {
        return array(
            $this->makeZoneObj(),
            $this->makeZoneObj(),
            $this->makeZoneObj()
        );
    }

    /**
     * Writes the zone + the user id to database.
     *
     * @param $zone_object taken from payload
     * @return null on succcess, error string on failure.
     */
    public function writeZone($zone_object)
    {
        $error_string = null;

        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );

        if ($mysqli->connect_errno) {
            $error_string = "cFailed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "bFailed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }

        $blocking_apps_safe_string = ""; // serialize($zone_object->{ZoneTableSchema::blockingApps})
        $keywords_safe_string = ""; // serialize($zone_object->{ZoneTableSchema::keywords})
        foreach ($zone_object->{ZoneTableSchema::blockingApps} as $app) { $blocking_apps_safe_string .= $app . ","; }
        foreach ($zone_object->{ZoneTableSchema::keywords} as $word) { $keywords_safe_string .= $word . ","; }

        $sql = "INSERT INTO
                `" . ZoneTableSchema::table_name . "`
                (`" . ZoneTableSchema::user_id . "`,
                `" . ZoneTableSchema::id . "`,
                `" . ZoneTableSchema::name . "`,
                `" . ZoneTableSchema::lat . "`,
                `" . ZoneTableSchema::lng . "`,
                `" . ZoneTableSchema::radius . "`,
                `" . ZoneTableSchema::blockingApps . "`,
                `" . ZoneTableSchema::keywords . "`)
                VALUES (" . $zone_object->{ZoneTableSchema::user_id} . ",
                " . $zone_object->{ZoneTableSchema::id} . ",
                '" . $zone_object->{ZoneTableSchema::name} . "',
                " . $zone_object->{ZoneTableSchema::lat} . ",
                " . $zone_object->{ZoneTableSchema::lng} . ",
                " . $zone_object->{ZoneTableSchema::radius} . ",
                '" . $blocking_apps_safe_string . "',
                '" . $keywords_safe_string . "')";

        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "aFailed to write to database: " . $sql . $result;
        }
        $mysqli->close();

        // failure
        if($error_string != null) {
            return $error_string;
        }
        // success
        return null;
    }
}

?>