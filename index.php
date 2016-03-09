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

    ?>

    <h1>Study Mapping</h1>
    <p><?= $server_status; ?> at <?= $server_date . ' ' . $server_time_formatted; ?></p>

    <h2>Keywords</h2>
    <code><?= var_dump(requestGETFromAPI('/keywords/3')); ?></code>

    <h2>Apps</h2>
    <code><?= var_dump(requestGETFromAPI('/apps/3')); ?></code>

    <h2>Zones</h2>
    <code><?= var_dump(requestGETFromAPI('/zones/3/1/1/100000000')); ?></code>

</body>
</html>