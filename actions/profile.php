<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";

  $api = poracle_api();
  $human = $_SESSION['id'];

  // ALARM SETTINGS

  if (isset($_POST['action']) && $_POST['action'] == 'alarms_settings') {
    try {
      // ENABLE AND DISABLE ALERTS GLOBALLY
      $api->post(poracle_human_path($human) . '/' . (isset($_POST['alerts_toggle']) ? 'enable' : 'disable'));
    } catch (PoracleApiException $e) {
      poracle_fail('', $e);
    }

    header("Location: $redirect_url?return=success_update_settings");
    exit();
  }

  // CLEANING SETTINGS

  if (isset($_POST['action']) && $_POST['action'] == 'profile_settings') {
    $toggles = array(
      'pokes_clean_toggle' => 'monsters',
      're_clean_toggle' => 'raid',
      'quests_clean_toggle' => 'quest',
      'invasions_clean_toggle' => 'invasion',
      'leures_clean_toggle' => 'lures',
      'nids_clean_toggle' => 'nests',
      'gyms_clean_toggle' => 'gym',
    );
    try {
      foreach ($toggles as $checkbox => $table) {
        $tables = ($table == 'raid') ? array('raid', 'egg') : array($table);
        foreach ($tables as $t) {
          foreach (poracle_type_for_table($t) as $type) {
            foreach (poracle_snapshot()['profiles'] as $p) {
              poracle_track_set_field($api, $human, $type, poracle_rules($type, (int) $p['profile_no']), 'clean', isset($_POST[$checkbox]) ? true : null, (int) $p['profile_no']);
            }
          }
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('cleaning', $e);
    }

    header("Location: $redirect_url?type=display&page=cleaning&return=success_update_settings");
    exit();
  }

include "./action_error.php";
