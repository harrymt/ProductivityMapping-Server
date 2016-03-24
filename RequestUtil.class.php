<?php

require_once '../Environment_variable.class.php';

/**
 * Class ZoneTableSchema.
 *
 * Utility Class to help with API requests.
 */
class RequestUtil
{
    /**
     * Perform a GET request to the API.
     *
     * @param $url String url call e.g. /v1/status/time
     * @param bool $params array("param" => "value") ==> index.php?param=value
     * @return mixed JSON output.
     */
    static function get($url, $params = false) {
        $curl = curl_init();

        $api_key = "?apikey=" . Environment_variable::$API_KEY;
        $api_url = Environment_variable::$SERVER_URL;

        $url = $api_url . $url . $api_key;

        if ($params) {
            $url = sprintf("%s&%s", $url, http_build_query($params));
        }

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return json_decode($result)->{"response"};
    }
}