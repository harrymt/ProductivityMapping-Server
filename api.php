<?php

  require_once 'MyAPI.class.php';

  // Requests from the same server don't have a HTTP_ORIGIN header
  if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
      $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
  }

  $request = $_SERVER['PATH_INFO'];

  try {
      $API = new MyAPI($request, $_SERVER['HTTP_ORIGIN']);
      echo $API->processAPI();
  } catch (Exception $e) {
      echo json_encode(Array('error' => $e->getMessage()));
  }

?>