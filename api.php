<?php

  require_once 'MyAPI.class.php';

  // Requests from the same server don't have a HTTP_ORIGIN header
  if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
      $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
  }

  // var_dump($_SERVER);

var_dump($_SERVER['REQUEST_METHOD']);
var_dump($_SERVER['REQUEST_URI']);
var_dump($_SERVER['PATH_INFO']);

if (($stream = fopen('php://input', "r")) !== FALSE)
    var_dump(stream_get_contents($stream));

//
//  $request = $_SERVER['PATH_INFO']; // "/v1/status/time"
//
//  try {
//      $API = new MyAPI($request, $_SERVER['HTTP_ORIGIN']);
//      echo $API->processAPI();
//  } catch (Exception $e) {
//      echo json_encode(Array('error' => $e->getMessage()));
//  }

?>