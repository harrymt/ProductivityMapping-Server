<?php

require_once '../Environment_variable.class.php';
require_once 'ZoneTableSchema.class.php';

class DatabaseAdapater
{

    public function __construct() {
    }

    /**
     * Gets the top $number of popular keywords.
     *
     * @param  int $number Number of keywords to get.
     * @return String array Keywords.
     */
    public function getKeywords($number) {
        $error_string = null;
        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );
        if ($mysqli->connect_errno) {
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }


        $sql = "SELECT " . ZoneTableSchema::keywords . " FROM " . ZoneTableSchema::table_name . ";";
        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
        }
        $mysqli->close();
        // failure
        if($error_string != null) {
            return $error_string;
        }

        // Make into php object array
        $result->data_seek(0);
        $keywords_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($keywords_array, str_getcsv(rtrim($row[ZoneTableSchema::keywords], ",")));
        }

        // Find the top n most common words
        $top_words = $this->findTopNStringsFromStringArray($number, $keywords_array);

        // return
        return $top_words;
    }

    private function findTopNStringsFromStringArray($n, $string_array) {
        if($n == 0) { return null; } // stop the extra processing

        $assoc_array = null;
        foreach($string_array as $array) {
            foreach($array as $word) {
                $assoc_array[$word]++;
            }
        }

        // Get the top $n popular strings
        $output_words = null;
        $i = 0;
        foreach ($assoc_array as $key => $value) {
            $i++;
            $output_words[$key] = $value;

            if($i >= $n) {
                break;
            }
        }

        arsort($output_words);

        return $output_words;
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
        // SQL query to return number near location, using The Haversine formula!
        // http://en.wikipedia.org/wiki/Haversine_formula

        $miles_number = 3959;
        $kilometers_number = 6371;

        $sql = "SELECT " . ZoneTableSchema::keywords . ",
                ( ". $miles_number . " * acos( cos( radians(" . $lat . ") ) * cos( radians( " . ZoneTableSchema::lat . " ) )
                * cos( radians( " . ZoneTableSchema::lng . " ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") )
                * sin( radians( " . ZoneTableSchema::lat . " ) ) ) )
                AS distance FROM " . ZoneTableSchema::table_name . " HAVING distance < " . $radius . " ORDER BY distance LIMIT 0 , " . $number . ";";

        $error_string = null;
        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );
        if ($mysqli->connect_errno) {
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }

        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
        }
        $mysqli->close();
        // failure
        if($error_string != null) {
            return $error_string;
        }

        // Make into php object array
        $result->data_seek(0);
        $apps_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($apps_array, array(
                ZoneTableSchema::keywords => str_getcsv(rtrim($row[ZoneTableSchema::keywords], ","))
            ));
        }

        // return
        return $apps_array;
    }


    /**
     * Gets the top $number of popular apps.
     *
     * @param  int $number Number of apps to get.
     * @return String array Apps.
     */
    public function getApps($number) {
        $error_string = null;
        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );
        if ($mysqli->connect_errno) {
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }

        $sql = "SELECT " . ZoneTableSchema::blockingApps . " FROM " . ZoneTableSchema::table_name . ";";
        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
        }
        $mysqli->close();
        // failure
        if($error_string != null) {
            return $error_string;
        }

        // Make into php object array
        $result->data_seek(0);
        $apps_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($apps_array, str_getcsv(rtrim($row[ZoneTableSchema::blockingApps], ",")));
        }

        // Find the top n most common words
        $top_words = $this->findTopNStringsFromStringArray($number, $apps_array);

        // return
        return $top_words;
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
        // SQL query to return number near location, using The Haversine formula!
        // http://en.wikipedia.org/wiki/Haversine_formula

        $miles_number = 3959;
        $kilometers_number = 6371;

        $sql = "SELECT " . ZoneTableSchema::blockingApps . ",
                ( ". $miles_number . " * acos( cos( radians(" . $lat . ") ) * cos( radians( " . ZoneTableSchema::lat . " ) )
                * cos( radians( " . ZoneTableSchema::lng . " ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") )
                * sin( radians( " . ZoneTableSchema::lat . " ) ) ) )
                AS distance FROM " . ZoneTableSchema::table_name . " HAVING distance < " . $radius . " ORDER BY distance LIMIT 0 , " . $number . ";";

        $error_string = null;
        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );
        if ($mysqli->connect_errno) {
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }

        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
        }
        $mysqli->close();
        // failure
        if($error_string != null) {
            return $error_string;
        }

        // Make into php object array
        $result->data_seek(0);
        $apps_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($apps_array, array(
                ZoneTableSchema::blockingApps => str_getcsv(rtrim($row[ZoneTableSchema::blockingApps], ","))
            ));
        }

        // return
        return $apps_array;
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

        // SQL query to return number near location, using The Haversine formula!
        // http://en.wikipedia.org/wiki/Haversine_formula

        $miles_number = 3959;
        $kilometers_number = 6371;

        $sql = "SELECT " . ZoneTableSchema::name . ", ". ZoneTableSchema::lat . ", " . ZoneTableSchema::lng . ", " . ZoneTableSchema::radius . ", " . ZoneTableSchema::blockingApps . ", " . ZoneTableSchema::keywords . ",
                ( ". $miles_number . " * acos( cos( radians(" . $lat . ") ) * cos( radians( " . ZoneTableSchema::lat . " ) )
                * cos( radians( " . ZoneTableSchema::lng . " ) - radians(" . $lng . ") ) + sin( radians(" . $lat . ") )
                * sin( radians( " . ZoneTableSchema::lat . " ) ) ) )
                AS distance FROM " . ZoneTableSchema::table_name . " HAVING distance < " . $radius . " ORDER BY distance LIMIT 0 , " . $number . ";";


        $error_string = null;
        // Connect to the database
        $mysqli = mysqli_connect(
            Environment_variable::$MYSQL_HOST,
            Environment_variable::$MYSQL_USERNAME,
            Environment_variable::$MYSQL_PASSWORD,
            Environment_variable::$MYSQL_DATABASE
        );
        if ($mysqli->connect_errno) {
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }


        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
        }
        $mysqli->close();
        // failure
        if($error_string != null) {
            return $error_string;
        }

        // Make into php object array
        $result->data_seek(0);
        $zones_array = array();
        while ($row = $result->fetch_assoc()) {
            array_push($zones_array, array(
                ZoneTableSchema::name => $row[ZoneTableSchema::name],
                ZoneTableSchema::lat => $row[ZoneTableSchema::lat],
                ZoneTableSchema::lng => $row[ZoneTableSchema::lng],
                ZoneTableSchema::radius => $row[ZoneTableSchema::radius],
                ZoneTableSchema::blockingApps => $row[ZoneTableSchema::blockingApps],
                ZoneTableSchema::keywords => $row[ZoneTableSchema::keywords]
            ));
        }

        // return
        return $zones_array;
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
            $error_string = "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }

        // Make sure the table has been created
        $result = $mysqli->query(ZoneTableSchema::make_sql_table_string());
        if(!$result) {
            $error_string = "Failed to create zone table: " . ZoneTableSchema::make_sql_table_string() . $result;
            $mysqli->close();
            return $error_string;
        }

        $sql = "INSERT INTO
                `" . ZoneTableSchema::table_name . "`
                (`" . ZoneTableSchema::user_id . "`,
                `" . ZoneTableSchema::id . "`,
                `" . ZoneTableSchema::name . "`,
                `" . ZoneTableSchema::lat . "`,
                `" . ZoneTableSchema::lng . "`,
                `" . ZoneTableSchema::radius . "`,
                `" . ZoneTableSchema::hasSynced . "`,
                `" . ZoneTableSchema::blockingApps . "`,
                `" . ZoneTableSchema::keywords . "`)
                VALUES ('" . $zone_object->{ZoneTableSchema::user_id} . "',
                " . $zone_object->{ZoneTableSchema::id} . ",
                '" . $zone_object->{ZoneTableSchema::name} . "',
                " . $zone_object->{ZoneTableSchema::lat} . ",
                " . $zone_object->{ZoneTableSchema::lng} . ",
                " . $zone_object->{ZoneTableSchema::radius} . ",
                1,
                '" . $zone_object->{ZoneTableSchema::blockingApps} . "',
                '" . $zone_object->{ZoneTableSchema::keywords} . "')";

        $result = $mysqli->query($sql);
        if(!$result) {
            $error_string = "Failed to write to database: " . $sql . $result;
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