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

    $zone_lat = "51.2"; $zone_lng = "-1.19"; $zone_radius = "10";
    $zones = RequestUtil::get("/zones/3/$zone_lat/$zone_lng/$zone_radius");
    foreach ($zones as $zone) {
        foreach($zone as $key => $value) { $zones_str .= "$key : $value<br>"; } $zones_str .= "<br>";
    }

?>

<h1>Map</h1>
<p>3 nearest Zones to <?= $zone_lat ?>,<?= $zone_lng ?> in radius of <?= $zone_radius ?> meters</p>
<code><?= $zones_str ?></code>