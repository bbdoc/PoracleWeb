<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // Invasions and incidents share one table but are separate v2 types
  $type = (isset($_POST['rule_type']) && $_POST['rule_type'] === 'incident') ? 'incident' : 'invasion';

  // UPDATE INVASIONS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {
    try {
      $changes = form_common($_POST);
      if ($type === 'invasion') {
        $changes = array_merge($changes, array('gender' => poracle_gender_enum((int) form_radio($_POST, 'gender', 'gender_'))));
      }
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, $type, (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('invasion', $e);
    }
    header("Location: $redirect_url?type=display&page=invasion&return=success_update_invasion");
    exit();
  }

  // DELETE INVASIONS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {
    try {
      poracle_track_delete($api, $human, $type, (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('invasion', $e);
    }
    header("Location: $redirect_url?type=display&page=invasion&return=success_delete_invasion");
    exit();
  }

  // ADD INVASION

  if (isset($_POST['add_invasions'])) {
    $built = form_invasion_rules($_POST);
    $calls = 0;
    $changed = false;
    try {
      foreach (array('invasion', 'incident') as $ruleType) {
        if (empty($built[$ruleType])) {
          continue;
        }
        $res = poracle_track_add($api, $human, $ruleType, $built[$ruleType], $profile);
        $calls++;
        if (!empty($res['created']) || !empty($res['updated']) || empty($res['unchanged'])) {
          $changed = true;
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('invasion', $e);
    }
    if ($calls > 0 && !$changed) {
      header("Location: $redirect_url?type=display&page=invasion&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=invasion&return=success_added_invasions");
    exit();
  }

  // DELETE ALL INVASIONS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_invasions') {
    try {
      foreach (array('invasion', 'incident') as $ruleType) {
        poracle_track_delete_uids($api, $human, $ruleType, array_column(poracle_rules($ruleType), 'uid'), $profile);
      }
    } catch (PoracleApiException $e) {
      poracle_fail('invasion', $e);
    }
    header("Location: $redirect_url?type=display&page=invasion&return=success_delete_invasions");
    exit();
  }

  // UPDATE ALL INVASIONS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_invasions_distance') {
    try {
      foreach (array('invasion', 'incident') as $ruleType) {
        poracle_track_set_field($api, $human, $ruleType, poracle_rules($ruleType), 'distance', form_common($_POST)['distance'], $profile);
      }
    } catch (PoracleApiException $e) {
      poracle_fail('invasion', $e);
    }
    header("Location: $redirect_url?type=display&page=invasion&return=success_update_invasions_distance");
    exit();
  }

  include "./action_error.php";
