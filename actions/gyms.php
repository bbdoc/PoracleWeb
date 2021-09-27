<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE GYMS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'gyms') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
      if (substr($value, 0, 5) === "slot_") {
        $slots = ltrim($value, 'slot_');
      }

    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    $stmt = $conn->prepare("
      UPDATE gym
      SET distance = ?, clean = ?, slot_changes = ?, template = ?, ping = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UG1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiissi",
      $_POST['distance'],
      $clean,
      $slots,
      $template,
      $_POST['content'],
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UG2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UG3&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DG1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DG2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DG3&sql=$stmt->error");
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
      if (substr($value, 0, 5) === "slot_") {
        $slots = ltrim($value, 'slot_'); 
      }

    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "gym_") {
        $team = substr($key, 4); 

        $stmt = $conn->prepare("INSERT INTO gym ( id, ping, clean, distance, template, team, slot_changes, profile_no)
	                       VALUES ( ?, ?, ? , ?, ?, ?, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AG1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("ssiisiii", $_SESSION['id'], $_POST['content'], $clean, $_POST['distance'], $template, $team, $slots, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AG2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=gym&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=AG3&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAG1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAG2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=DAG3&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UGD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UGD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=gym&return=sql_error&phase=UGD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=gym&return=success_update_gyms_distance");
    exit();

  }

  include "./action_error.php";

