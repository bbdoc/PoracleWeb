<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";

  // UPDATE LURES

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'lures') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    $stmt = $conn->prepare("
      UPDATE lures
      SET distance = ?, clean = ?, template = ?, ping = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=UL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iissi",
      $_POST['distance'],
      $clean,
      $template,
      $_POST['content'],
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=UL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=UL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=lure&return=success_update_lure");
    exit();
  }


  // DELETE LURES

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'lures') {

    $stmt = $conn->prepare("
      DELETE FROM lures
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lure");
    exit();
  }


  // ADD LURE

  if (isset($_POST['add_lures'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "lure_") {
        $lure = substr($key, 5); 

        $stmt = $conn->prepare("INSERT INTO lures ( id, ping, clean, distance, template, lure_id, profile_no)
	                       VALUES ( ?, ?, ? , ?, ?, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=AL1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("ssiisii", $_SESSION['id'], $_POST['content'], $clean, $_POST['distance'], $template, $lure, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=AL2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=lure&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=AL3&sql=$stmt->error");
          exit();
        }
        $stmt->close(); 
      }
    }

    header("Location: $redirect_url?type=display&page=lure&return=success_added_lures");
    exit();
  }

  // DELETE ALL LURES 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_lures') {

    $stmt = $conn->prepare("DELETE FROM lures WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DAL1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DAL2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=DAL3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=lure&return=success_delete_lures");
    exit();
  }


  // UPDATE ALL LURES DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_lures_distance') {

    $stmt = $conn->prepare("UPDATE lures set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=ULD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=ULD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=lure&return=sql_error&phase=ULD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=lure&return=success_update_lures_distance");
    exit();

  }

  include "./action_error.php";

