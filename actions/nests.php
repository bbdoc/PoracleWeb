<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";

  // UPDATE NESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'nests') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    $stmt = $conn->prepare("
      UPDATE nests
      SET distance = ?, clean = ?, min_spawn_avg = ?, template = ?, ping = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UN1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiissi",
      $_POST['distance'],
      $clean,
      $_POST['min_spawns'],
      $template,
      $_POST['content'],
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UN2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UN3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=nest&return=success_update_nest");
    exit();
  }


  // DELETE NESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'nests') {

    $stmt = $conn->prepare("
      DELETE FROM nests
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DN1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DN2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DN3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=nest&return=success_delete_nest");
    exit();
  }


  // ADD NEST

  if (isset($_POST['add_nests'])) { 

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "nest_") {
        $nest = substr($key, 5); 

        $stmt = $conn->prepare("INSERT INTO nests ( id, ping, clean, distance, template, pokemon_id, min_spawn_avg, form, profile_no)
	                       VALUES ( ?, ?, ? , ?, ?, ?, ?, 0, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=AN1&sql=$stmt->error");
          exit();
        } 
        $rs = $stmt->bind_param("ssiisiii", $_SESSION['id'], $_POST['content'], $clean, $_POST['distance'], $template, $nest, $_POST['min_spawns'], $_SESSION['profile']); 
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=AN2&sql=$stmt->error");
          exit();
        } 
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=nest&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=AN3&sql=$stmt->error");
          exit();
        }
        $stmt->close(); 
      }
    }

    header("Location: $redirect_url?type=display&page=nest&return=success_added_nests");
    exit();
  }

  // DELETE ALL NESTS 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_nests') {

    $stmt = $conn->prepare("DELETE FROM nests WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DAN1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DAN2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=DAN3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=nest&return=success_delete_nests");
    exit();
  }


  // UPDATE ALL NESTS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_nests_distance') {

    $stmt = $conn->prepare("UPDATE nests set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UND1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UND2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=nest&return=sql_error&phase=UND3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=nest&return=success_update_nests_distance");
    exit();

  }

  include "./action_error.php";

