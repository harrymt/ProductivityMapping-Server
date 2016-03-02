<?php

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
        $i = 0; $stack = array();
        while ($i < $number) {
            array_push($stack, "a_keyword");
            $i++;
        };

        return $stack;
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
        $i = 0; $stack = array();
        while ($i < $number) {
            array_push($stack, "keyword_near_(" . $lat . "," . $lng . ")_with_radius_" . $radius);
            $i++;
        };

        return $stack;
    }

 }

?>