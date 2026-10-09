<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE LURES

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'lures') {
    try {
      $changes = form_common($_POST);
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, 'lure', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_update_lure");
    exit();
  }

  // DELETE LURES

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'lures') {
    try {
      poracle_track_delete($api, $human, 'lure', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lure");
    exit();
  }

  // ADD LURE

  if (isset($_POST['add_lures'])) {
    $rules = form_lure_rules($_POST);
    if (empty($rules)) {
      header("Location: $redirect_url?type=display&page=lure&return=success_added_lures");
      exit();
    }
    try {
      $res = poracle_track_add($api, $human, 'lure', $rules, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=lure&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_added_lures");
    exit();
  }

  // DELETE ALL LURES

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_lures') {
    try {
      poracle_track_delete_uids($api, $human, 'lure', array_column(poracle_rules('lure'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lures");
    exit();
  }

  // UPDATE ALL LURES DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_lures_distance') {
    try {
      poracle_track_set_field($api, $human, 'lure', poracle_rules('lure'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('lure', $e);
    }
    header("Location: $redirect_url?type=display&page=lure&return=success_update_lures_distance");
    exit();
  }

  include "./action_error.php";
