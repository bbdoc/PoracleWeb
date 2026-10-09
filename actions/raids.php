<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE RAIDS AND EGGS

  if (isset($_POST['update']) && isset($_POST['type']) && ($_POST['type'] == 'raids' || $_POST['type'] == 'eggs')) {
    $isEgg = $_POST['type'] == 'eggs';
    try {
      $changes = form_common($_POST);
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, $isEgg ? 'egg' : 'raid', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('raid', $e);
    }
    header("Location: $redirect_url?type=display&page=raid&return=" . ($isEgg ? 'success_update_egg' : 'success_update_raid'));
    exit();
  }

  // DELETE RAIDS AND EGGS

  if (isset($_POST['delete']) && isset($_POST['type']) && ($_POST['type'] == 'raids' || $_POST['type'] == 'eggs')) {
    $isEgg = $_POST['type'] == 'eggs';
    try {
      poracle_track_delete($api, $human, $isEgg ? 'egg' : 'raid', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('raid', $e);
    }
    header("Location: $redirect_url?type=display&page=raid&return=" . ($isEgg ? 'success_delete_egg' : 'success_delete_raid'));
    exit();
  }

  // ADD RAID

  if (isset($_POST['add_raid'])) {
    $built = form_raid_rules($_POST);
    $created = false;
    $duplicate = false;
    try {
      foreach (array('egg', 'raid') as $type) {
        if (empty($built[$type])) {
          continue;
        }
        $res = poracle_track_add($api, $human, $type, $built[$type], $profile);
        if (!empty($res['created']) || !empty($res['updated'])) {
          $created = true;
        } elseif (!empty($res['unchanged'])) {
          $duplicate = true;
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('raid', $e);
    }
    if ($duplicate && !$created) {
      header("Location: $redirect_url?type=display&page=raid&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=raid&return=success_added_raids");
    exit();
  }

  // DELETE ALL RAIDS AND EGGS (viewed profile only)

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_raids') {
    try {
      poracle_track_delete_uids($api, $human, 'raid', array_column(poracle_rules('raid'), 'uid'), $profile);
      poracle_track_delete_uids($api, $human, 'egg', array_column(poracle_rules('egg'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('raid', $e);
    }
    header("Location: $redirect_url?type=display&page=raid&return=success_delete_raids");
    exit();
  }

  // UPDATE ALL RAIDS & EGGS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_raids_distance') {
    try {
      $distance = form_common($_POST)['distance'];
      poracle_track_set_field($api, $human, 'raid', poracle_rules('raid'), 'distance', $distance, $profile);
      poracle_track_set_field($api, $human, 'egg', poracle_rules('egg'), 'distance', $distance, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('raid', $e);
    }
    header("Location: $redirect_url?type=display&page=raid&return=success_update_raids_distance");
    exit();
  }

  include "./action_error.php";
