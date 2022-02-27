<?php

include_once "./config.php";
include_once "include/defaults.php";
include_once "include/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

// Check Telegram Bot Auth
$auth_data = $_GET;
$check_hash = $auth_data['hash'];
unset($auth_data['hash']);

$data_check_arr = [];
foreach ($auth_data as $key => $value) {
   $data_check_arr[] = $key . '=' . $value;
}
sort($data_check_arr);

$data_check_string = implode("\n", $data_check_arr);
$secret_key = hash('sha256', $telegram_bot_token, true);
$hash = hash_hmac('sha256', $data_check_string, $secret_key);

if (strcmp($hash, $check_hash) !== 0) {
   header("Location: $redirect_url/?return=invalid_auth");
   exit();
}

if ((time() - $auth_data['auth_date']) > 86400) {
   header("Location: $redirect_url/?return=expired");
   exit();
}

$_SESSION['type'] = "telegram:user";
$_SESSION['id'] = $_GET['id'];
$_SESSION['avatar'] = $_GET['photo_url'];
if (isset($_SESSION['username'])) {
   $_SESSION['username'] = $_GET['username'];
} else {
   $_SESSION['username'] = @$_GET['first_name'] . " " . @$_GET['last_name'];
}

include_once "./session.php";

if (isset($no_api) && $no_api == "True") {
   header("Location: $redirect_url?type=display&page=server_settings");
} else if (version_compare($_SESSION['poracleVersion'], $min_poracle_version) < 0) {
   header("Location: $redirect_url?type=display&page=server_settings");
} else {
   header("Location: $redirect_url");
}
