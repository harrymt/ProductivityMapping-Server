<?php
/**
 * Created by PhpStorm.
 * User: harrymt
 * Date: 12/03/2016
 * Time: 16:49
 */
require_once 'RequestUtil.class.php';
?>

<?php

// See if url has lat lng and radius query strings in
    $query = $_SERVER["QUERY_STRING"];
    if($query != NULL) {
        $pieces = explode("&", $_SERVER["QUERY_STRING"]);
        $location = array();
        foreach($pieces as $piece) {
            $parts = explode("=", $piece);
            $location[$parts[0]] = $parts[1];
        }
    } else {
        // Fallback, if no query strings are provided
        // Jubilee campus 52.953221 | -1.187199 | 10
        $location['lat'] = 52.953221;
        $location['lng'] = -1.187199;
        $location['radius'] = 10;
    }

    $zone_lat = $location['lat']; $zone_lng = $location['lng']; $zone_radius = $location['radius'];
    $zones = RequestUtil::get("/zones/3/$zone_lat/$zone_lng/$zone_radius");

    $zone_pairs = array();
    foreach($zones as $zone) {
        array_push($zone_pairs, array(
            "center" => array(
                "lat" => (float) $zone->lat,
                "lng" => (float) $zone->lng
            ),
            "radius" => (float) $zone->radius
        ));
    }

    function makeZoneObject($zone) {
        $str = "";
        foreach($zone as $key => $value) { $str .= "$key : $value<br>"; } $str .= "<br>";
        return $str;
    }
?>

<script>var phpZones = <?= json_encode($zone_pairs); ?>;</script>


<div class="section" id="maps">
    <h3>Maps</h3>
    <p class="description">3 nearest Zones to <?= $zone_lat ?>,<?= $zone_lng ?> in radius of <?= $zone_radius ?> meters</p>
    <div> <!-- needed div -->
        <ul class="zones">

            <?php foreach ($zones as $zone) { ?>

            <li>
                <span class="details">
                    <?= makeZoneObject($zone); ?>
                </span>
                <i class="fa fa-heart"></i>

            </li>

            <?php } ?>

        </ul>
    </div> <!-- ./needed div -->

    <div style="height: 400px;" id="map"></div>

</div> <!-- ./work -->