<?php

include_once __DIR__ . '/poracle_api.php';

// PoracleNG reports capabilities on /health; PoracleJS never did, so their
// presence is the v2 check.
function poracle_supports_v2($health)
{
   return is_array($health) && isset($health['capabilities']) && is_array($health['capabilities']);
}

function poracle_human_path($id)
{
   return '/api/v2/humans/' . rawurlencode($id);
}

// The instance (Poracle database name) the human is registered on, or null.
function poracle_find_human_instance(array $apis, $id)
{
   foreach ($apis as $instance => $api) {
      try {
         $api->get(poracle_human_path($id));
         return $instance;
      } catch (PoracleApiException $e) {
         if ($e->status !== 404) {
            throw $e;
         }
      }
   }
   return null;
}
