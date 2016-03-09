<?php

    require_once 'MyAPI.class.php';
    require_once '../Environment_variable.class.php';

    $api_key = "?apikey=" . Environment_variable::$API_KEY;
    $api_url = Environment_variable::$SERVER_URL;

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
        $response = file_get_contents($api_url . '/status/date' . $api_key);
        echo json_decode($response)[0];
    ?></p>

</body>
</html>