<?php

include_once __DIR__ . '/poracle_tracking.php';

// An instance is one Poracle database named in $dbname; its API address and
// secret live in that database's pweb_settings.

function poracle_instance_names()
{
   global $dbname;
   return array_values(array_filter(array_map('trim', explode(',', $dbname))));
}

function poracle_instance_settings($instance)
{
   global $dbhost, $dbport, $dbuser, $dbpass;
   static $cache = array();
   if (!isset($cache[$instance])) {
      $settings = array('api_address' => '', 'api_secret' => '');
      $db = new mysqli($dbhost . ":" . $dbport, $dbuser, $dbpass, $instance);
      if (!$db->connect_errno) {
         $result = $db->query("SELECT setting, value FROM pweb_settings WHERE setting IN ('api_address','api_secret')");
         while ($result && $row = $result->fetch_assoc()) {
            $settings[$row['setting']] = $row['value'];
         }
         $db->close();
      }
      $cache[$instance] = $settings;
   }
   return $cache[$instance];
}

function poracle_api_for($instance)
{
   static $apis = array();
   if (!isset($apis[$instance])) {
      $s = poracle_instance_settings($instance);
      $apis[$instance] = new PoracleApi($s['api_address'], $s['api_secret']);
   }
   return $apis[$instance];
}

function poracle_api()
{
   return poracle_api_for($_SESSION['dbname']);
}

// Every instance's client, keyed by instance name.
function poracle_all_apis()
{
   $apis = array();
   foreach (poracle_instance_names() as $instance) {
      $apis[$instance] = poracle_api_for($instance);
   }
   return $apis;
}

// Report an API failure to the user and stop.
function poracle_fail($page, Exception $e)
{
   global $redirect_url;
   $_SESSION['api_error'] = ($e instanceof PoracleApiException) ? $e->userMessage() : $e->getMessage();
   $target = ($page === '') ? "$redirect_url?return=api_error" : "$redirect_url?type=display&page=$page&return=api_error";
   header("Location: $target");
   exit();
}
