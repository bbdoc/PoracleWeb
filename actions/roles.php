<?php

include_once "../config.php";

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "X-Poracle-Secret: $api_secret\r\n"
  )
);

$context = stream_context_create($opts);

$result = @file_get_contents("$api_address/api/humans/".$_SESSION['id']."/roles/".$_GET['type']."/".$_GET['id'], false, $context);

header("Location: $redirect_url?type=display&page=manage_roles");

