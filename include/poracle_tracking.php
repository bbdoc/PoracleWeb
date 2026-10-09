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
   $firstError = null;
   foreach ($apis as $instance => $api) {
      try {
         $api->get(poracle_human_path($id));
         return $instance;
      } catch (PoracleApiException $e) {
         if ($e->status !== 404 && $firstError === null) {
            $firstError = $e;
         }
      }
   }
   if ($firstError !== null) {
      throw $firstError;
   }
   return null;
}

function poracle_compact(array $rule)
{
   return array_filter($rule, function ($value) { return $value !== null; });
}

// A rule read from the API, made sendable: response-only keys removed, and
// null ("any") fields left out.
function poracle_rule_body(array $rule)
{
   unset($rule['uid'], $rule['description'], $rule['profile_no']);
   return poracle_compact($rule);
}

function poracle_rules_in(array $snapshot, $type, $profile)
{
   $rules = isset($snapshot['tracking'][$type]) ? $snapshot['tracking'][$type] : array();
   if ($profile === null) {
      return $rules;
   }
   $out = array();
   foreach ($rules as $rule) {
      if ((int) $rule['profile_no'] === (int) $profile) {
         $out[] = $rule;
      }
   }
   return $out;
}

// '1' when every rule cleans, '0' when one does not, null when there are no
// rules (the values the old SELECT min(clean) gave the cleaning toggles).
function poracle_all_clean(array $rules)
{
   if (!$rules) {
      return null;
   }
   foreach ($rules as $rule) {
      if (empty($rule['clean'])) {
         return '0';
      }
   }
   return '1';
}

function poracle_common_distance(array $rules)
{
   $counts = array();
   foreach ($rules as $rule) {
      $d = (int) (isset($rule['distance']) ? $rule['distance'] : 0);
      $counts[$d] = (isset($counts[$d]) ? $counts[$d] : 0) + 1;
   }
   if (!$counts) {
      return 0;
   }
   arsort($counts);
   return (int) key($counts);
}

function poracle_shared_distance(array $rules)
{
   $distances = array();
   foreach ($rules as $rule) {
      $distances[(int) (isset($rule['distance']) ? $rule['distance'] : 0)] = true;
   }
   return count($distances) === 1 ? (int) key($distances) : 0;
}

function poracle_tracking_path($human, $type)
{
   return poracle_human_path($human) . '/tracking/' . $type;
}

function poracle_track_add(PoracleApi $api, $human, $type, array $rules, $profile)
{
   return $api->post(poracle_tracking_path($human, $type), array_map('poracle_compact', $rules), array('profile' => (int) $profile));
}

// PUT replaces the whole rule, so start from the stored rule and overwrite
// only what the form changed; fields the form does not show are kept.
function poracle_track_update(PoracleApi $api, $human, $type, $uid, array $changes, $profile)
{
   $path = poracle_tracking_path($human, $type) . '/' . (int) $uid;
   $query = array('profile' => (int) $profile);
   $current = $api->get($path, $query);
   $body = poracle_rule_body(array_merge($current['rules'][0], $changes));
   return $api->put($path, $body, $query);
}

function poracle_track_delete(PoracleApi $api, $human, $type, $uid, $profile)
{
   return $api->delete(poracle_tracking_path($human, $type) . '/' . (int) $uid, array('profile' => (int) $profile));
}

function poracle_track_delete_uids(PoracleApi $api, $human, $type, array $uids, $profile)
{
   if (!$uids) {
      return null;
   }
   return $api->delete(poracle_tracking_path($human, $type), array('uid' => implode(',', array_map('intval', $uids)), 'profile' => (int) $profile));
}

// Set one field on many rules in one request: POST upserts, and a rule that
// differs from its stored self in a single updatable field is updated in place.
function poracle_track_set_field(PoracleApi $api, $human, $type, array $rules, $field, $value, $profile)
{
   if (!$rules) {
      return null;
   }
   $bodies = array();
   foreach ($rules as $rule) {
      $rule[$field] = $value;
      $bodies[] = poracle_rule_body($rule);
   }
   return $api->post(poracle_tracking_path($human, $type), $bodies, array('profile' => (int) $profile));
}

function poracle_type_for_table($table)
{
   $map = array('monsters' => array('pokemon'), 'raid' => array('raid'), 'egg' => array('egg'), 'quest' => array('quest'),
      'invasion' => array('invasion', 'incident'), 'lures' => array('lure'), 'nests' => array('nest'), 'gym' => array('gym'));
   return isset($map[$table]) ? $map[$table] : array();
}

function poracle_decode_areas($json)
{
   $areas = is_string($json) ? json_decode($json, true) : $json;
   return is_array($areas) ? $areas : array();
}

function poracle_profile_view(array $snapshot, $profile)
{
   $human = $snapshot['human'];
   $view = array(
      'name' => null, 'area' => poracle_decode_areas($human['area']),
      'latitude' => $human['latitude'], 'longitude' => $human['longitude'],
      'profile_count' => count($snapshot['profiles']), 'current_profile_no' => (int) $human['current_profile_no'],
      'enabled' => (int) $human['enabled'], 'admin_disable' => (int) $human['admin_disable'],
      'disabled_date' => $human['disabled_date'], 'language' => $human['language'],
   );
   foreach ($snapshot['profiles'] as $p) {
      if ((int) $p['profile_no'] === (int) $profile) {
         $view['name'] = $p['name'];
         $view['area'] = poracle_decode_areas($p['area']);
         $view['latitude'] = $p['latitude'];
         $view['longitude'] = $p['longitude'];
      }
   }
   return $view;
}
