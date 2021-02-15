<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE RAIDS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'raids') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE raid
      SET distance = ?, clean = ?
      WHERE pokemon_id = ? AND level = ? AND form = ? AND distance = ? AND team = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UR1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiiiis",
      $_POST['distance'],
      $clean,
      $_POST['pokemon_id'],
      $_POST['level'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_team'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UR2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UR3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_raid#pills-raids");
    exit();
  }

  // UPDATE EGGS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'eggs') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE egg 
      SET distance = ?, clean = ?
      WHERE level = ? AND distance = ? AND team = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UE1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiis",
      $_POST['distance'],
      $clean,
      $_POST['level'],
      $_POST['cur_distance'],
      $_POST['cur_team'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UE2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UE3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_egg#pills-raids");
    exit();
  }


  // DELETE RAIDS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'raids') {

    $stmt = $conn->prepare("
      DELETE FROM raid
      WHERE pokemon_id = ? AND level = ? AND form = ? AND distance = ? AND team = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DR1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiis",
      $_POST['pokemon_id'],
      $_POST['level'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_team'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DR2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DR3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_raid#pills-raids");
    exit();
  }

  // DELETE EGGS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'eggs') {

    $stmt = $conn->prepare("
      DELETE FROM egg
      WHERE level = ? AND distance = ? AND team = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DE1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiis",
      $_POST['level'],
      $_POST['cur_distance'],
      $_POST['cur_team'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DE2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DE3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_egg#pills-raids");
    exit();
  }

  // ADD RAID

  if (isset($_POST['add_raid'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "egg_") {
        $level = ltrim($key, 'egg_');

        $stmt = $conn->prepare("INSERT INTO egg ( id, ping, clean, template, distance, team, level)
	                       VALUES ( ?, '', ? , 1, ?, 4, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AE1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $_POST['distance'], $level);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AE2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AE3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "raid_") {
        $level = ltrim($key, 'raid_');

        $stmt = $conn->prepare("INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                               VALUES ( ?, '', ? , 1, 9000, ?, 4, ?, 0)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AR1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $_POST['distance'], $level);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AR2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AR3&sql=$stmt->error");
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

        $stmt = $conn->prepare("INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                               VALUES ( ?, '', ? , 1, ? , ?, 4, 9000, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=ARM1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siiii", $_SESSION['id'], $clean, $boss_id, $_POST['distance'], $boss_form);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=ARM2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=ARM3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?return=success_added_raids#pills-raids");
    exit();
  }

  // DELETE ALL RAIDS AND EGGS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_raids') {

    $stmt = $conn->prepare("DELETE FROM raid WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAR1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAR2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAR3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM egg WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAE1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAE2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAE3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_delete_raids#pills-raids");
    exit();
  }


  // UPDATE ALL RAIDS & EGGS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_raids_distance') {

    $stmt = $conn->prepare("UPDATE raid set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=URD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=URD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=URD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("UPDATE egg set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UED1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UED2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UED3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_update_raids_distance#pills-raids");
    exit();

  }

  include "./action_error.php";

