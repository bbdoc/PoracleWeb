<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE GYMS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'gyms') {
    try {
      $changes = array_merge(form_common($_POST), array(
        'slot_changes' => form_radio($_POST, 'slots', 'slot_') === '1' ? true : null,
        'battle_changes' => form_radio($_POST, 'battles', 'battle_') === '1' ? true : null,
      ));
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, 'gym', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('gym', $e);
    }
    header("Location: $redirect_url?type=display&page=gym&return=success_update_gym");
    exit();
  }

  // DELETE GYMS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'gyms') {
    try {
      poracle_track_delete($api, $human, 'gym', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('gym', $e);
    }
    header("Location: $redirect_url?type=display&page=gym&return=success_delete_gym");
    exit();
  }

  // ADD GYM

  if (isset($_POST['add_gyms'])) {
    $rules = form_gym_rules($_POST);
    if (empty($rules)) {
      header("Location: $redirect_url?type=display&page=gym&return=success_added_gyms");
      exit();
    }
    try {
      $res = poracle_track_add($api, $human, 'gym', $rules, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('gym', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=gym&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=gym&return=success_added_gyms");
    exit();
  }

  // DELETE ALL GYMS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_gyms') {
    try {
      poracle_track_delete_uids($api, $human, 'gym', array_column(poracle_rules('gym'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('gym', $e);
    }
    header("Location: $redirect_url?type=display&page=gym&return=success_delete_gyms");
    exit();
  }

  // UPDATE ALL GYMS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_gyms_distance') {
    try {
      $refused = poracle_track_set_field($api, $human, 'gym', poracle_rules('gym'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('gym', $e);
    }
    poracle_report_refused('gym', $refused);
    header("Location: $redirect_url?type=display&page=gym&return=success_update_gyms_distance");
    exit();
  }

  include "./action_error.php";
