<?php
/**
 * Created by PhpStorm.
 * User: harrymt
 * Date: 12/03/2016
 * Time: 16:55
 */
    require_once 'RequestUtil.class.php';
?>

<?php
    $apps = RequestUtil::get('/apps/3');
    $apps_str = ""; foreach($apps as $key => $value) { $apps_str .= "$key being blocked in $value zones<br>"; }

    $app_pairs = array();
    // Print out the words as a json object { text: "", size: "" }
    foreach($apps as $key => $value) {
        array_push($app_pairs, array(
            "text" => $key,
            "size" => $value * 10
        ));
    }
?>

<script>var apps = <?= json_encode($app_pairs); ?>;</script>

<div class="section" id="apps">
    <h3>Apps</h3>
    <p class="description">Top 3 most popular blocked Apps</p>
    <code><?= $apps_str ?></code>
    <div class="js-app-cloud"></div>
</div><!-- /.section -->