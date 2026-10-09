<?php

include_once "../config.php";
include_once "../include/db_connect.php";

if (!isset($_GET['id']) || $_GET['id'] === '' || !isset($_GET['type']) || !in_array($_GET['type'], array('add', 'remove'), true)) {
  poracle_fail('manage_roles', new Exception(i8ln("Invalid role request")));
}

$path = poracle_human_path($_SESSION['id']) . '/roles/' . rawurlencode($_GET['id']);

try {
  if ($_GET['type'] === 'add') {
    poracle_api()->post($path);
  } else if ($_GET['type'] == 'remove') {
    poracle_api()->delete($path);
  }
} catch (PoracleApiException $e) {
  poracle_fail('manage_roles', $e);
}

header("Location: $redirect_url?type=display&page=manage_roles");
