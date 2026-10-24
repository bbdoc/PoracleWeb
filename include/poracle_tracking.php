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

// A snapshot's rules as create requests: one step per type and profile, in
// profile order, with response-only keys removed. A null $onlyProfile means
// every profile.
function poracle_copy_plan(array $snapshot, $onlyProfile)
{
   $plan = array();
   foreach ($snapshot['tracking'] as $type => $rules) {
      $byProfile = array();
      foreach ($rules as $rule) {
         $p = (int) $rule['profile_no'];
         if ($onlyProfile === null || $p === (int) $onlyProfile) {
            $byProfile[$p][] = poracle_rule_body($rule);
         }
      }
      ksort($byProfile);
      foreach ($byProfile as $p => $bodies) {
         $plan[] = array('type' => $type, 'profile_no' => $p, 'rules' => $bodies);
      }
   }
   return $plan;
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
   if (empty($current['rules'][0]) || !is_array($current['rules'][0])) {
      throw new PoracleApiException(404, 'Rule not found');
   }
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

// What clearing each bulk-settable field sends: PATCH reads null as "leave
// as stored", so "off" has to be spelled out (distance 0 = match by area).
const PORACLE_SET_CLEARED = array('distance' => 0, 'clean' => false, 'edit' => false, 'summary' => false, 'template' => '', 'ping' => '');

// Set one delivery setting on many rules in one PATCH; each rule keeps its uid
// and every other field. null switches the setting off. Returns the rules
// PoracleNG left alone, e.g. because the change would duplicate another rule.
function poracle_track_set_field(PoracleApi $api, $human, $type, array $rules, $field, $value, $profile)
{
   if (!$rules) {
      return array();
   }
   if ($value === null) {
      $value = PORACLE_SET_CLEARED[$field];
   }
   $body = array('uids' => array_map('intval', array_column($rules, 'uid')), 'set' => array($field => $value));
   $res = $api->patch(poracle_tracking_path($human, $type), $body, array('profile' => (int) $profile));
   return (isset($res['refused']) && is_array($res['refused'])) ? $res['refused'] : array();
}

// The rules a bulk change left alone, for the user. Plain English like the
// API's own details: actions need not load i8ln().
function poracle_refused_message(array $refused)
{
   $details = array_unique(array_map(function ($r) { return isset($r['detail']) ? $r['detail'] : $r['reason']; }, $refused));
   return count($refused) . ' alarm(s) were not changed: ' . implode('; ', $details);
}

function poracle_location_body($latitude, $longitude)
{
   return array('latitude' => (float) $latitude, 'longitude' => (float) $longitude);
}

// Every profile number the human has: those with a profiles entry, the
// active one (a legacy human may have no entry for it) and any holding rules.
function poracle_profile_numbers(array $snapshot)
{
   $numbers = array_map('intval', array_column($snapshot['profiles'], 'profile_no'));
   $numbers[] = (int) $snapshot['human']['current_profile_no'];
   foreach ($snapshot['tracking'] as $rules) {
      $numbers = array_merge($numbers, array_map('intval', array_column($rules, 'profile_no')));
   }
   $numbers = array_values(array_unique($numbers));
   sort($numbers);
   return $numbers;
}

// The profiles to recreate on another human, in profile order. An active
// profile with no profiles entry (common after a PoracleJS migration) is
// recreated from the human's own area and location; rules left on a deleted
// profile never match, so they are not carried over.
function poracle_migration_profiles(array $snapshot)
{
   $profiles = $snapshot['profiles'];
   $human = $snapshot['human'];
   $current = (int) $human['current_profile_no'];
   if (!in_array($current, array_map('intval', array_column($profiles, 'profile_no')), true)) {
      $profiles[] = array('profile_no' => $current, 'name' => '', 'area' => $human['area'],
         'latitude' => $human['latitude'], 'longitude' => $human['longitude'], 'active_hours' => array());
   }
   usort($profiles, function ($a, $b) { return (int) $a['profile_no'] - (int) $b['profile_no']; });
   return $profiles;
}

function poracle_type_for_table($table)
{
   $map = array('monsters' => array('pokemon'), 'raid' => array('raid'), 'egg' => array('egg'), 'quest' => array('quest'),
      'invasion' => array('invasion', 'incident'), 'lures' => array('lure'), 'nests' => array('nest'), 'gym' => array('gym'));
   return isset($map[$table]) ? $map[$table] : array();
}

function poracle_profile_view(array $snapshot, $profile)
{
   $human = $snapshot['human'];
   $view = array(
      'name' => null, 'area' => $human['area'],
      'latitude' => $human['latitude'], 'longitude' => $human['longitude'],
      'profile_count' => count($snapshot['profiles']), 'current_profile_no' => (int) $human['current_profile_no'],
      'enabled' => (int) $human['enabled'], 'admin_disable' => (int) $human['admin_disable'],
      'disabled_date' => $human['disabled_date'], 'language' => $human['language'],
   );
   foreach ($snapshot['profiles'] as $p) {
      if ((int) $p['profile_no'] === (int) $profile) {
         $view['name'] = $p['name'];
         $view['area'] = $p['area'];
         $view['latitude'] = $p['latitude'];
         $view['longitude'] = $p['longitude'];
      }
   }
   return $view;
}

function poracle_pokemon_gen($pokemonId)
{
   if ($pokemonId === null || (int) $pokemonId === 0) {
      return 'all';
   }
   $ends = array(151, 251, 386, 493, 649, 721, 809, 905);
   foreach ($ends as $i => $end) {
      if ($pokemonId <= $end) {
         return (string) ($i + 1);
      }
   }
   return '9';
}

function poracle_filter_by_ids(array $rules, $ids)
{
   if ($ids === null) {
      return $rules;
   }
   return array_values(array_filter($rules, function ($r) use ($ids) {
      return in_array((int) ($r['pokemon_id'] ?? 0), $ids, true);
   }));
}

function poracle_quick_pick_match(array $rule, $preset)
{
   $get = function ($key) use ($rule) { return array_key_exists($key, $rule) ? $rule[$key] : null; };
   if ($get('pokemon_id') !== null) {
      return false;
   }
   switch ($preset) {
      case 'iv100': return $get('min_iv') === 100;
      case 'iv0': return $get('min_iv') === 0 && $get('max_iv') === 0;
      case 'pvp500': case 'pvp1500': case 'pvp2500':
         return $get('pvp_ranking_league') === (int) substr($preset, 3) && $get('pvp_ranking_worst') === 1;
      case 'xxs': return $get('size') === 1 && $get('max_size') === 1;
      case 'xxl': return $get('size') === 5 && $get('max_size') === null;
   }
   return false;
}
