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

    $zone_lat = "40.3"; $zone_lng = "-1.0"; $zone_radius = "10";
    $zones = RequestUtil::get("/zones/3/$zone_lat/$zone_lng/$zone_radius");

    function makeZoneObject($zone) {
        $str = "";
        foreach($zone as $key => $value) { $str .= "$key : $value<br>"; } $str .= "<br>";
        return $str;
    }
?>

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
</div> <!-- ./work -->