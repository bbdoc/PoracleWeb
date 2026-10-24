<?php

  include_once "../config.php";
  include_once "../include/db_connect.php";
  include_once "../include/functions.php";

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  $api = poracle_api();
  $human = $_SESSION['id'];

  if (isset($_POST['profile'])) {
    try {
      if (!in_array((int) $_POST['profile'], array_map('intval', array_column(poracle_snapshot()['profiles'], 'profile_no')), true)) {
        poracle_fail('profiles', new Exception(i8ln("Unknown profile")));
      }
    } catch (PoracleApiException $e) {
      poracle_fail('profiles', $e);
    }
    $_SESSION['profile'] = (int) $_POST['profile'];
  }

  if ( isset($_POST['activate']) ) {

    // The server copies the profile's area and location to the human
    try {
      $api->post(poracle_human_path($human) . '/profile', array('profile_no' => (int) $_POST['profile']));
    } catch (PoracleApiException $e) {
      poracle_fail('profiles', $e);
    }
    header("Location: $redirect_url?type=display&page=profiles&return=success_switch_profile_activate");

  } else if ( isset($_POST['view']) ) {

    header("Location: $redirect_url?type=display&page=profiles&return=success_switch_profile_view");

  }

  if ( isset($_POST['create']) ) {

    try {
      $snapshot = poracle_snapshot();
      $first = (count($snapshot['profiles']) <= 1);
      $source = poracle_profile_view($snapshot, (int) $_SESSION['profile']);

      $res = $api->post(poracle_human_path($human) . '/profiles', array('name' => $_POST['profile_name']));
      $_SESSION['profile'] = (int) $res['profile_no'];

      // The first extra profile starts with the area and location in use
      if ($first) {
        if (!empty($source['area'])) {
          $api->post(poracle_human_path($human) . '/areas', array('areas' => $source['area']), array('profile' => (int) $res['profile_no']));
        }
        if ((float) $source['latitude'] != 0 || (float) $source['longitude'] != 0) {
          $api->post(poracle_human_path($human) . '/location', poracle_location_body($source['latitude'], $source['longitude']), array('profile' => (int) $res['profile_no']));
        }
      }
    } catch (PoracleApiException $e) {
      poracle_fail('profiles', $e);
    }
    header("Location: $redirect_url?type=display&page=profiles&return=success_create_profile");

  }

  if ( isset($_POST['rename']) ) {

    try {
      $api->patch(poracle_human_path($human) . '/profiles/' . (int) $_SESSION['profile'], array('name' => $_POST['profile_name']));
    } catch (PoracleApiException $e) {
      poracle_fail('profiles', $e);
    }
    header("Location: $redirect_url?type=display&page=profiles&return=success_rename_profile");

  }

  if ( isset($_GET['action']) && $_GET['action'] == "delete" ) {

    // DELETE PROFILE (the server deletes its tracking and reassigns the active profile)

    try {
      $api->delete(poracle_human_path($human) . '/profiles/' . (int) $_SESSION['profile']);
      poracle_snapshot_invalidate();
      $_SESSION['profile'] = (int) poracle_snapshot()['human']['current_profile_no'];
    } catch (PoracleApiException $e) {
      poracle_fail('profiles', $e);
    }
    header("Location: $redirect_url?type=display&page=profiles&return=success_delete_profile");

  }

  include "./action_error.php";


?>
