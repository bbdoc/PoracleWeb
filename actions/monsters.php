<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/defaults.php";
   include_once "../include/functions.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];
  $gen = $_POST['gen'] ?? $_GET['gen'] ?? 'all';
  $pvpMinCp = array(500 => $_SESSION['pvpFilterLittleMinCP'], 1500 => $_SESSION['pvpFilterGreatMinCP'], 2500 => $_SESSION['pvpFilterUltraMinCP']);

  // UPDATE POKEMON

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {
    try {
      $changes = array_merge(form_common($_POST), form_pokemon_filters($_POST, $pvpMinCp, (int) $_SESSION['defaultPvpCap']));
      // :user destinations post an empty hidden ping field; keep any ping set elsewhere
      if (substr($_SESSION['type'], -5) === ':user') {
        unset($changes['ping']);
      }
      poracle_track_update($api, $human, 'pokemon', (int) $_POST['uid'], $changes, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('pokemon', $e);
    }
    header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_update_mons");
    exit();
  }

  // DELETE MONSTERS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {
    try {
      poracle_track_delete($api, $human, 'pokemon', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('pokemon', $e);
    }
    header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_delete_mons");
    exit();
  }

  // ADD MONSTER

  if (isset($_POST['add_mon'])) {
    $rules = form_pokemon_rules($_POST, $pvpMinCp, (int) $_SESSION['defaultPvpCap']);
    if (empty($rules)) {
      header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_added_mons");
      exit();
    }
    try {
      $res = poracle_track_add($api, $human, 'pokemon', $rules, $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('pokemon', $e);
    }
    if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
      header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=duplicate");
      exit();
    }
    header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_added_mons");
    exit();
  }

  // DELETE ALL MONSTERS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_mons') {
    try {
      poracle_track_delete_uids($api, $human, 'pokemon', array_column(poracle_rules('pokemon'), 'uid'), $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('pokemon', $e);
    }
    header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_delete_mons");
    exit();
  }

  // UPDATE ALL MONSTERS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_mons_distance') {
    try {
      $refused = poracle_track_set_field($api, $human, 'pokemon', poracle_rules('pokemon'), 'distance', form_common($_POST)['distance'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('pokemon', $e);
    }
    poracle_report_refused('pokemon', $refused);
    header("Location: $redirect_url?type=display&page=pokemon&gen=$gen&return=success_update_mons_distance");
    exit();
  }

include "./action_error.php";
