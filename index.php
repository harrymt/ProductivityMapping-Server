<?php

    require_once '../Environment_variable.class.php';

    $api_key = "?apikey=" . Environment_variable::$API_KEY;
    $api_url = Environment_variable::$SERVER_URL;

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

        if ($data) {
            $url = sprintf("%s&%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
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

        .status {
            font-weight: bold;
        }

        .status-good {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Study Mapping</h1>
    <p>The status of the API is <span class="status status-good">good</span></p>
    <p><?php
        echo file_get_contents($api_url . '/status/date' . $api_key);
        // echo requestGETFromAPI($api_url . '/status/date' . $api_key);
        //echo json_decode($response)[0];
    ?></p>

</body>
</html>