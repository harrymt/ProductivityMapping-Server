<?php

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
 }

?>