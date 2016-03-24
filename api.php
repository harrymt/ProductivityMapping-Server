<?php

    require_once 'MyAPI.class.php';

    $request = $_SERVER['PATH_INFO'];

    try {
      $API = new MyAPI($request, $_SERVER["QUERY_STRING"]);
      echo $API->processAPI();
    } catch (Exception $e) {
      echo json_encode(Array("error" => $e->getMessage()));
    }

?>