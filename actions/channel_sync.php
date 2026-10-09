<?php
  include_once "../config.php";
  include_once "../include/db_connect.php";

  if (empty($_SESSION['admin_id'])) { header("Location: $redirect_url?return=invalid_auth"); exit(); }

  // Copy the viewed profile's rules (every tracking type) into each selected
  // target's active profile, replacing what that profile tracked before.
  if (isset($_POST['sync'])) {
    try {
      $source = poracle_snapshot();
      $plan = poracle_copy_plan($source, (int) $_SESSION['profile']);
      $types = array_keys($source['tracking']);
      foreach ($_POST as $key => $value) {
        if (strpos($key, 'target_') !== 0) { continue; }
        // The checkbox value carries "instance|id" intact; the name is the
        // fallback, where PHP has turned dots into underscores.
        if ($value !== 'on' && strpos($value, '|') !== false) {
          list($target_instance, $target_id) = explode('|', $value, 2);
        } else {
          list($target_instance, $target_id) = array_pad(explode('|', substr($key, 7), 2), 2, '');
          $target_id = str_replace('_com', '.com', $target_id);
        }
        if ($target_id === '' || !in_array($target_instance, poracle_instance_names(), true)) { continue; }
        $target_api = poracle_api_for($target_instance);
        $target_profile = (int) $target_api->get(poracle_human_path($target_id))['human']['current_profile_no'];
        $existing = $target_api->get(poracle_human_path($target_id) . '/tracking', array('profile' => $target_profile));
        foreach ($types as $type) {
          $rules = isset($existing['tracking'][$type]) ? $existing['tracking'][$type] : array();
          $uids = array_column($rules, 'uid');
          if ($uids) {
            $target_api->delete(poracle_tracking_path($target_id, $type), array('uid' => implode(',', array_map('intval', $uids)), 'profile' => $target_profile, 'silent' => true));
          }
        }
        foreach ($plan as $step) {
          $target_api->post(poracle_tracking_path($target_id, $step['type']), $step['rules'], array('profile' => $target_profile, 'silent' => true));
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('', $e);
    }
    header("Location: $redirect_url?return=success_channel_sync");
    exit();
  }

  include "./action_error.php";
