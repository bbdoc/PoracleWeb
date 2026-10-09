<?php
  include_once "../config.php";
  include_once "../include/db_connect.php";

  // Discord → Telegram: create the Telegram human with the Discord human's
  // settings, profiles, saved locations and rules, then delete the Discord
  // human. The Telegram identity comes from the login verified on the migrate
  // page, never from the form.
  if (isset($_POST['migrate']) && $_POST['migration_type'] == 'discord2telegram' && isset($_SESSION['migrate_telegram'])
    && ($_SESSION['type'] ?? '') == 'discord:user' && @$enable_telegram == "True") {
    $api = poracle_api();
    $source = $_SESSION['id'];
    $target = $_SESSION['migrate_telegram'];
    $target_path = poracle_human_path($target['id']);
    $created = false;
    try {
      $snap = $api->get(poracle_human_path($source) . '/tracking', array('all_profiles' => true));
      $h = $snap['human'];
      $profiles = $snap['profiles'];
      usort($profiles, function ($a, $b) { return $a['profile_no'] - $b['profile_no']; });
      // The snapshot carries active_hours as the stored JSON string; the
      // profiles endpoint gives the typed entries the create body takes.
      $hours = array();
      foreach ($api->get(poracle_human_path($source) . '/profiles')['profiles'] as $p) {
        $hours[(int) $p['profile_no']] = isset($p['active_hours']) ? $p['active_hours'] : array();
      }

      $api->post('/api/v2/humans', array_filter(array(
        'id' => (string) $target['id'], 'name' => $target['username'], 'type' => 'telegram:user',
        'language' => $h['language'], 'area' => $h['area'], 'latitude' => $h['latitude'], 'longitude' => $h['longitude'],
        'community' => poracle_decode_areas($h['community_membership']), 'notes' => $h['notes'],
        'profile_name' => $profiles ? $profiles[0]['name'] : null,
      ), function ($v) { return $v !== null && $v !== '' && $v !== array(); }));
      $created = true;

      // Profile numbers on the new human: the first is created with the human;
      // the rest get the lowest free number, so map old → new.
      $map = array();
      foreach ($profiles as $i => $p) {
        $old = (int) $p['profile_no'];
        if ($i === 0) {
          $map[$old] = 1;
        } else {
          $body = array('name' => $p['name'] !== '' ? $p['name'] : 'Profile ' . $old);
          if (!empty($hours[$old])) {
            $body['active_hours'] = $hours[$old];
          }
          $res = $api->post($target_path . '/profiles', $body);
          $map[$old] = (int) $res['profile_no'];
        }
        $n = $map[$old];
        $api->post($target_path . '/areas', array('areas' => poracle_decode_areas($p['area']), 'trusted' => true), array('profile' => $n));
        if ((float) $p['latitude'] != 0 || (float) $p['longitude'] != 0) {
          $api->post($target_path . '/location', array('lat' => (float) $p['latitude'], 'lon' => (float) $p['longitude']), array('profile' => $n));
        }
      }
      // The first profile's active hours cannot be given on create.
      if ($profiles && !empty($hours[(int) $profiles[0]['profile_no']])) {
        $api->patch($target_path . '/profiles/1', array('active_hours' => $hours[(int) $profiles[0]['profile_no']]));
      }
      $named = isset($snap['locations']['named']) ? $snap['locations']['named'] : array();
      foreach ($named as $loc) {
        $api->post($target_path . '/locations', array('label' => $loc['label'], 'lat' => (float) $loc['latitude'], 'lon' => (float) $loc['longitude']));
      }
      foreach (poracle_copy_plan($snap, null) as $step) {
        if (!isset($map[$step['profile_no']])) {
          throw new PoracleApiException(0, 'Rules found for missing profile ' . $step['profile_no']);
        }
        $api->post(poracle_tracking_path($target['id'], $step['type']), $step['rules'], array('profile' => $map[$step['profile_no']], 'silent' => true));
      }
      $api->post($target_path . '/profile', array('profile_no' => $map[(int) $h['current_profile_no']] ?? 1));
      $api->delete(poracle_human_path($source));
    } catch (PoracleApiException $e) {
      if ($created) {
        try { $api->delete($target_path); } catch (PoracleApiException $ignored) {}
      }
      poracle_fail('', $e);
    }

    # CONNECT NEW USER
    $_SESSION['id'] = $target['id'];
    $_SESSION['username'] = $target['username'];
    $_SESSION['avatar'] = $target['avatar'];
    $_SESSION['type'] = 'telegram:user';
    unset($_SESSION['profile'], $_SESSION['migrate_telegram']);

    # UPDATE ADMIN ID
    unset($_SESSION['admin_id']);
    $admins = isset($admin_id) ? explode(",", $admin_id) : array();
    foreach ($admins as $admin) {
      if ($_SESSION['id'] == $admin) {
        $_SESSION['admin_id'] = $_SESSION['id'];
        $_SESSION['admin_username'] = $_SESSION['username'];
        $_SESSION['admin_dbname'] = $_SESSION['dbname'];
        $_SESSION['admin_type'] = $_SESSION['type'];
      }
    }

    header("Location: $redirect_url?return=success_migrate&mig_source=Discord&mig_target=Telegram");
    exit();
  }

  include "./action_error.php";
