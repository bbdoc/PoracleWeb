<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE QUESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE quest
      SET distance = ?, clean = ?
      WHERE reward = ? AND reward_type = ? AND distance = ? 
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UQ1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiis",
      $_POST['distance'],
      $clean,
      $_POST['cur_reward'],
      $_POST['cur_reward_type'],
      $_POST['cur_distance'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_quest#pills-quests");
    exit();
  }

  // DELETE QUESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    $stmt = $conn->prepare("
      DELETE FROM quest
      WHERE reward = ? AND reward_type = ? AND distance = ? 
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DQ1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiis",
      $_POST['cur_reward'],
      $_POST['cur_reward_type'],
      $_POST['cur_distance'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_quest#pills-quests");
    exit();
  }

  // ADD QUESTS

  if (isset($_POST['add_quest'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $mon_id = ltrim($key, 'mon_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance)
                               VALUES ( ?, '', ? , ?, 1, 0, 7, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AQM1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $mon_id, $_POST['distance']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQM2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQM3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "item_") {
        $item = ltrim($key, 'item_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance)
                               VALUES ( ?, '', ? , ?, 1, 0, 2, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AQI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $item, $_POST['distance']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 7) === "energy_") {
        $item = ltrim($key, 'energy_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance)
                               VALUES ( ?, '', ? , ?, 1, 0, 12, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AQI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $item, $_POST['distance']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?return=success_added_quest#pills-quests");
    exit();
  }

  // DELETE ALL QUESTS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_quests') {

    $stmt = $conn->prepare("DELETE FROM quest WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_delete_quest#pills-quests");
    exit();
  }


    // UPDATE ALL QUEST DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_quests_distance') {

    $stmt = $conn->prepare("UPDATE quest set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UQD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_update_quests_distance#pills-quests");
    exit();
  }

  include "./action_error.php";

