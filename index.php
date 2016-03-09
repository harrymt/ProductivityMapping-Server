<?php

    require_once '../Environment_variable.class.php';


    /**
     * Perform a GET request to the API.
     *
     * @param $url /v1/status/time
     * @param bool $data array("param" => "value") ==> index.php?param=value
     * @return mixed JSON output.
     */
    function requestGETFromAPI($url, $data = false)
    {
        $curl = curl_init();

        $api_key = "?apikey=" . Environment_variable::$API_KEY;
        $api_url = Environment_variable::$LOCAL_SERVER_URL;

        $url = $api_url . $url . $api_key;

        if ($data) {
            $url = sprintf("%s&%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result)->{"response"};
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>API front end</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <?php
    // STATUS
        $server_status = requestGETFromAPI('/status/');
        $server_date = requestGETFromAPI('/status/date');
        $server_time = requestGETFromAPI('/status/time'); date_default_timezone_set('GMT'); $st = new DateTime("@$server_time");
        $server_time_formatted = $st->format('H:i:s');

        $keywords = requestGETFromAPI('/keywords/3');
        $keywords_str = ""; foreach($keywords as $key => $value) { $keywords_str .= "$key : $value<br>"; }

        $apps = requestGETFromAPI('/apps/3');
        $apps_str = ""; foreach($apps as $key => $value) { $apps_str .= "$key : $value<br>"; }

        $zone_lat = "51.2"; $zone_lng = "-1.19"; $zone_radius = "10";
        $zones = requestGETFromAPI("/zones/3/$zone_lat/$zone_lng/$zone_radius");
        foreach ($zones as $zone) {
            foreach($zone as $key => $value) { $zones_str .= "$key : $value<br>"; } $zones_str .= "<br>";
        }

    ?>

    <h1>Study Mapping</h1>
    <p><?= $server_status; ?> at <?= $server_date . ' ' . $server_time_formatted; ?></p>

    <h2>Keywords</h2>
    <p>Top 3 most used Keywords</p>
    <code><?= $keywords_str ?></code>

    <h2>Apps</h2>
    <p>Top 3 most popular blocked Apps</p>
    <code><?= $apps_str ?></code>

    <h2>Zones</h2>
    <p>3 nearest Zones to <?= $zone_lat ?>,<?= $zone_lng ?> in radius of <?= $zone_radius ?> meters</p>
    <code><?= $zones_str ?></code>

</body>
</html>