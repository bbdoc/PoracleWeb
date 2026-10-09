<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];

  // UPDATE QUESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'quests') {
    try {
      $changes = form_common($_POST);
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, 'quest', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quest', $e);
    }
    header("Location: $redirect_url?type=display&page=quest&return=success_update_quest");
    exit();
  }

  // DELETE QUESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'quests') {
    try {
      poracle_track_delete($api, $human, 'quest', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quest', $e);
    }
    header("Location: $redirect_url?type=display&page=quest&return=success_delete_quest");
    exit();
  }

  // ADD QUEST

  if (isset($_POST['add_quest'])) {
    $rules = form_quest_rules($_POST);
    if (empty($rules)) {
      header("Location: $redirect_url?type=display&page=quest&return=success_added_quest");
      exit();
    }
    try {
      $res = poracle_track_add($api, $human, 'quest', $rules, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quest', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=quest&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=quest&return=success_added_quest");
    exit();
  }

  // DELETE ALL QUESTS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_quests') {
    try {
      poracle_track_delete_uids($api, $human, 'quest', array_column(poracle_rules('quest'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quest', $e);
    }
    header("Location: $redirect_url?type=display&page=quest&return=success_delete_quest");
    exit();
  }

  // UPDATE ALL QUESTS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_quests_distance') {
    try {
      poracle_track_set_field($api, $human, 'quest', poracle_rules('quest'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quest', $e);
    }
    header("Location: $redirect_url?type=display&page=quest&return=success_update_quests_distance");
    exit();
  }

  include "./action_error.php";
