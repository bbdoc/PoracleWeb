<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE LURES

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'lures') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE lures
      SET distance = ?, clean = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iii",
      $_POST['distance'],
      $clean,
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_lure#pills-lures");
    exit();
  }


  // DELETE LURES

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'lures') {

    $stmt = $conn->prepare("
      DELETE FROM lures
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DL1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DL2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DL3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_lure#pills-lures");
    exit();
  }


  // ADD LURE

  if (isset($_POST['add_lures'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "lure_") {
        $lure = substr($key, 5); 

        $stmt = $conn->prepare("INSERT INTO lures ( id, ping, clean, distance, template, lure_id, profile_no)
	                       VALUES ( ?, '', ? , ?, 1, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AL1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siiii", $_SESSION['id'], $clean, $_POST['distance'], $lure, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AL2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?return=duplicate");
            exit();
          }
          header("Location: $redirect_url?return=sql_error&phase=AL3&sql=$stmt->error");
          exit();
        }
        $stmt->close(); 
      }
    }

    header("Location: $redirect_url?return=success_added_lures#pills-lures");
    exit();
  }

  // DELETE ALL LURES 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_lures') {

    $stmt = $conn->prepare("DELETE FROM lures WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAL1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAL2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAL3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_delete_lures#pills-lures");
    exit();
  }


  // UPDATE ALL LURES DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_lures_distance') {

    $stmt = $conn->prepare("UPDATE lures set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=ULD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=ULD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=ULD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_update_lures_distance#pills-lures");
    exit();

  }

  include "./action_error.php";

