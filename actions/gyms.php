<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE GYMS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'gyms') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    $stmt = $conn->prepare("
      UPDATE gym
      SET distance = ?, clean = ?, template = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iisi",
      $_POST['distance'],
      $clean,
      $template,
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=gym&return=success_update_gym");
    exit();
  }


  // DELETE GYMS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'gyms') {

    $stmt = $conn->prepare("
      DELETE FROM gym
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=gym&return=success_delete_gym");
    exit();
  }


  // ADD GYM

  if (isset($_POST['add_gyms'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "gym_") {
        $team = substr($key, 4); 
        $slot_changes = 0;

        $stmt = $conn->prepare("INSERT INTO gym ( id, ping, clean, distance, template, team, slot_changes, profile_no)
	                       VALUES ( ?, '', ? , ?, ?, ?, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AL1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisiii", $_SESSION['id'], $clean, $_POST['distance'], $template, $team, $slot_changes, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AL2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=gym&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AL3&sql=$stmt->error");
          exit();
        }
        $stmt->close(); 
      }
    }

    header("Location: $redirect_url?type=display&page=gym&return=success_added_gyms");
    exit();
  }

  // DELETE ALL GYMS 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_gyms') {

    $stmt = $conn->prepare("DELETE FROM gym WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAL1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAL2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAL3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=gym&return=success_delete_gyms");
    exit();
  }


  // UPDATE ALL GYMS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_gyms_distance') {

    $stmt = $conn->prepare("UPDATE gym set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=ULD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=ULD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=ULD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=gym&return=success_update_gyms_distance");
    exit();

  }

  include "./action_error.php";

