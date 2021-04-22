<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE INVASIONS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : 1;

    $stmt = $conn->prepare("
      UPDATE invasion
      SET distance = ?, clean = ?, template = ?, gender = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UI1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iisii",
      $_POST['distance'],
      $clean,
      $template,
      $gender,
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UI2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UI3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=invasion&return=success_update_invasion#pills-invasions");
    exit();
  }


  // DELETE INVASIONS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {

    $stmt = $conn->prepare("
      DELETE FROM invasion
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DI1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DI2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DI3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=invasion&return=success_delete_invasion#pills-invasions");
    exit();
  }


  // ADD INVASION

  if (isset($_POST['add_invasions'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : 1;

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 6) === "grunt_") {
        $grunt = substr($key, 6); 

        $stmt = $conn->prepare("INSERT INTO invasion ( id, ping, clean, distance, template, gender, grunt_type, profile_no)
	                       VALUES ( ?, '', ? , ?, ?, ?, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=AI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisisi", $_SESSION['id'], $clean, $_POST['distance'], $template, $gender, $grunt, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=AI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=invasion&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=AI3&sql=$stmt->error");
          exit();
        }
        $stmt->close(); 
      }
    }

    header("Location: $redirect_url?type=display&page=invasion&return=success_added_invasions#pills-invasions");
    exit();
  }

  // DELETE ALL INVASIONS 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_invasions') {

    $stmt = $conn->prepare("DELETE FROM invasion WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DAI1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DAI2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=DAI3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=invasion&return=success_delete_invasions#pills-invasions");
    exit();
  }


  // UPDATE ALL INVASIONS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_invasions_distance') {

    $stmt = $conn->prepare("UPDATE invasion set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UID1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UID2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=invasion&return=sql_error&phase=UID3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=invasion&return=success_update_invasions_distance#pills-invasions");
    exit();

  }

  include "./action_error.php";

