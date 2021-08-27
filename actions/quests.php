<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE QUESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    $stmt = $conn->prepare("
      UPDATE quest
      SET distance = ?, clean = ?, template = ?
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQ1&sql=$stmt->error");
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
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=quest&return=success_update_quest");
    exit();
  }

  // DELETE QUESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    $stmt = $conn->prepare("
      DELETE FROM quest
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DQ1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=quest&return=success_delete_quest");
    exit();
  }

  // ADD QUESTS

  if (isset($_POST['add_quest'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }
    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $mon_id = ltrim($key, 'mon_');

        $arr = explode("_", $mon_id);
        $mon_id = $arr[0];
	$form_id = $arr[1];
	if ( !isset($form_id) || $form_id = "" ) { $form_id = 0;}

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance, profile_no, form)
                               VALUES ( ?, '', ? , ?, ?, 0, 7, ?, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQM1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisiii", $_SESSION['id'], $clean, $mon_id, $template, $_POST['distance'], $_SESSION['profile'], $form_id);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQM2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
	if (false === $rs) {
          if ( stristr($stmt->error, "Duplicate") ) {
            header("Location: $redirect_url?type=display&page=quest&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQM3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "item_") {
        $item = ltrim($key, 'item_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance, profile_no)
                               VALUES ( ?, '', ? , ?, ?, 0, 2, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisii", $_SESSION['id'], $clean, $item, $template, $_POST['distance'], $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQI3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 7) === "energy_") {
        $item = ltrim($key, 'energy_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance, profile_no)
                               VALUES ( ?, '', ? , ?, ?, 0, 12, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQE1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisii", $_SESSION['id'], $clean, $item, $template, $_POST['distance'], $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQE2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQE3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 6) === "candy_") {
        $item = ltrim($key, 'candy_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance, profile_no)
                               VALUES ( ?, '', ? , ?, ?, 0, 4, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQC1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siisii", $_SESSION['id'], $clean, $item, $template, $_POST['distance'], $_SESSION['profile']);
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQC2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=AQC3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?type=display&page=quest&return=success_added_quest");
    exit();
  }

  // DELETE ALL QUESTS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_quests') {

    $stmt = $conn->prepare("DELETE FROM quest WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DAQ1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DAQ2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=DAQ3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?type=display&page=quest&return=success_delete_quest");
    exit();
  }


    // UPDATE ALL QUEST DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_quests_distance') {

    $stmt = $conn->prepare("UPDATE quest set distance = ? WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("isi", $_POST['distance'], $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quest&return=sql_error&phase=UQD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?type=display&page=quest&return=success_update_quests_distance");
    exit();
  }

  include "./action_error.php";

