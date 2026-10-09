<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE NESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'nests') {
    try {
      $changes = array_merge(form_common($_POST), array('min_spawn_avg' => form_int($_POST, 'min_spawns') ?: null));
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, 'nest', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('nest', $e);
    }
    header("Location: $redirect_url?type=display&page=nest&return=success_update_nest");
    exit();
  }

  // DELETE NESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'nests') {
    try {
      poracle_track_delete($api, $human, 'nest', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('nest', $e);
    }
    header("Location: $redirect_url?type=display&page=nest&return=success_delete_nest");
    exit();
  }

  // ADD NEST

  if (isset($_POST['add_nests'])) {
    $rules = form_nest_rules($_POST);
    if (empty($rules)) {
      header("Location: $redirect_url?type=display&page=nest&return=success_added_nests");
      exit();
    }
    try {
      $res = poracle_track_add($api, $human, 'nest', $rules, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('nest', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=nest&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=nest&return=success_added_nests");
    exit();
  }

  // DELETE ALL NESTS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_nests') {
    try {
      poracle_track_delete_uids($api, $human, 'nest', array_column(poracle_rules('nest'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('nest', $e);
    }
    header("Location: $redirect_url?type=display&page=nest&return=success_delete_nests");
    exit();
  }

  // UPDATE ALL NESTS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_nests_distance') {
    try {
      poracle_track_set_field($api, $human, 'nest', poracle_rules('nest'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('nest', $e);
    }
    header("Location: $redirect_url?type=display&page=nest&return=success_update_nests_distance");
    exit();
  }

  include "./action_error.php";
