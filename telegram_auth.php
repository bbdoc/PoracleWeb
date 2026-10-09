<?php

include_once "./config.php";
include_once "include/defaults.php";
include_once "include/db_connect.php";

if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

// Check Telegram Bot Auth: the signature first (judged as if just issued),
// then the age, so a bad hash and an old login get different messages.
$auth_date = isset($_GET['auth_date']) ? (int) $_GET['auth_date'] : 0;
if (!telegram_auth_valid($_GET, $telegram_bot_token ?? '', $auth_date)) {
   header("Location: $redirect_url/?return=invalid_auth");
   exit();
}

if (!telegram_auth_valid($_GET, $telegram_bot_token ?? '', time())) {
   header("Location: $redirect_url/?return=expired");
   exit();
}

$_SESSION['type'] = "telegram:user";
$_SESSION['id'] = $_GET['id'];
$_SESSION['avatar'] = @$_GET['photo_url'];
if (isset($_SESSION['username'])) {
   $_SESSION['username'] = $_GET['username'];
} else {
   $_SESSION['username'] = @$_GET['first_name'] . " " . @$_GET['last_name'];
}

include_once "./session.php";

if (isset($no_api) && $no_api == "True") {
   header("Location: $redirect_url?type=display&page=server_settings");
} else { 
   header("Location: $redirect_url");
}
