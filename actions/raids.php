<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE RAIDS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'raids') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : 1;

    $stmt = $conn->prepare("
      UPDATE raid
      SET distance = ?, clean = ?, template = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UR1&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UR2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UR3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=raid&return=success_update_raid#pills-raids");
    exit();
  }

  // UPDATE EGGS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'eggs') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : 1;

    $stmt = $conn->prepare("
      UPDATE egg 
      SET distance = ?, clean = ?, template = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UE1&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UE2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UE3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=raid&return=success_update_egg#pills-raids");
    exit();
  }


  // DELETE RAIDS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'raids') {

    $stmt = $conn->prepare("
      DELETE FROM raid
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DR1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DR2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DR3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=raid&return=success_delete_raid#pills-raids");
    exit();
  }

  // DELETE EGGS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'eggs') {

    $stmt = $conn->prepare("
      DELETE FROM egg
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DE1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DE2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DE3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=raid&return=success_delete_egg#pills-raids");
    exit();
  }

  // ADD RAID

  if (isset($_POST['add_raid'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : 1;

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "egg_") {
        $level = ltrim($key, 'egg_');

        $stmt = $conn->prepare("INSERT INTO egg ( id, ping, clean, template, distance, team, level, profile_no)
	                       VALUES ( ?, '', ? , ?, ?, 4, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AE1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("sisiii", $_SESSION['id'], $clean, $template, $_POST['distance'], $level, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AE2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=raid&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AE3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "raid_") {
        $level = ltrim($key, 'raid_');

        $stmt = $conn->prepare("INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form, profile_no)
                               VALUES ( ?, '', ? , ?, 9000, ?, 4, ?, 0, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AR1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("sisiii", $_SESSION['id'], $clean, $template, $_POST['distance'], $level, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AR2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=raid&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=AR3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $arr = explode("_", $key);
        $boss_id = $arr[1];
        $boss_form = $arr[2];
        if (isset($arr[3])) { $boss_mega = $arr[3];} 

        $stmt = $conn->prepare("INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form, profile_no)
                               VALUES ( ?, '', ? , ?, ? , ?, 4, 9000, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=ARM1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("sisiiii", $_SESSION['id'], $clean, $template, $boss_id, $_POST['distance'], $boss_form, $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=ARM2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=raid&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=ARM3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?type=display&page=raid&return=success_added_raids#pills-raids");
    exit();
  }

  // DELETE ALL RAIDS AND EGGS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_raids') {

    $stmt = $conn->prepare("DELETE FROM raid WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAR1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAR2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAR3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM egg WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAE1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAE2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=DAE3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=raid&return=success_delete_raids#pills-raids");
    exit();
  }


  // UPDATE ALL RAIDS & EGGS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_raids_distance') {

    $stmt = $conn->prepare("UPDATE raid set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=URD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=URD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=URD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("UPDATE egg set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UED1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UED2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=raid&return=sql_error&phase=UED3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?type=display&page=raid&return=success_update_raids_distance#pills-raids");
    exit();

  }

  include "./action_error.php";

