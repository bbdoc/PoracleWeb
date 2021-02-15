<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE POKEMON

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 5) === "form_") {
        $form = ltrim($value, 'form_');
      }
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE monsters
      SET distance = ?, min_iv = ?, max_iv = ?, min_cp = ?, max_cp = ?, 
          min_level = ?, max_level = ?, min_weight = ?, max_weight = ?,
	  atk = ?, def = ?, sta = ?, max_atk = ?, max_def = ?, max_sta = ?,
          great_league_ranking = ?, great_league_ranking_min_cp = ?, ultra_league_ranking = ?, ultra_league_ranking_min_cp = ?,
          form = ?, gender = ?, clean = ? 
      WHERE pokemon_id = ? AND form = ?  AND distance = ? AND gender = ?  
      AND min_iv = ? AND max_iv = ?  AND min_cp = ? AND max_cp = ?
      AND min_level = ? AND max_level = ? AND min_weight = ? AND max_weight = ?  
      AND atk = ? AND def = ? AND sta = ? AND max_atk = ? AND max_def = ? AND max_sta = ?
      AND great_league_ranking = ? AND great_league_ranking_min_cp = ?  AND ultra_league_ranking = ? AND ultra_league_ranking_min_cp = ?  
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UM1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis",
      $_POST['distance'],
      $_POST['min_iv'],
      $_POST['max_iv'],
      $_POST['min_cp'],
      $_POST['max_cp'],
      $_POST['min_level'],
      $_POST['max_level'],
      $_POST['min_weight'],
      $_POST['max_weight'],
      $_POST['atk'],
      $_POST['def'],
      $_POST['sta'],
      $_POST['max_atk'],
      $_POST['max_def'],
      $_POST['max_sta'],
      $_POST['great_league_ranking'],
      $_POST['great_league_ranking_min_cp'],
      $_POST['ultra_league_ranking'],
      $_POST['ultra_league_ranking_min_cp'],
      $form,
      $gender,
      $clean,
      $_POST['pokemon_id'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_gender'],
      $_POST['cur_min_iv'],
      $_POST['cur_max_iv'],
      $_POST['cur_min_cp'],
      $_POST['cur_max_cp'],
      $_POST['cur_min_level'],
      $_POST['cur_max_level'],
      $_POST['cur_min_weight'],
      $_POST['cur_max_weight'],
      $_POST['cur_atk'],
      $_POST['cur_def'],
      $_POST['cur_sta'],
      $_POST['cur_max_atk'],
      $_POST['cur_max_def'],
      $_POST['cur_max_sta'],
      $_POST['cur_great_league_ranking'],
      $_POST['cur_great_league_ranking_min_cp'],
      $_POST['cur_ultra_league_ranking'],
      $_POST['cur_ultra_league_ranking_min_cp'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UM2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UM3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_mons");
    exit();
  }

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
    header("Location: $redirect_url?return=success_update_raid");
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
    header("Location: $redirect_url?return=success_update_egg");
    exit();
  }

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
    header("Location: $redirect_url?return=success_update_quest");
    exit();
  }


  // DELETE MONSTERS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {

    $stmt = $conn->prepare("
      DELETE FROM monsters
      WHERE pokemon_id = ? AND form = ?  AND distance = ? AND gender = ?
      AND min_iv = ? AND max_iv = ?  AND min_cp = ? AND max_cp = ?
      AND min_level = ? AND max_level = ? AND min_weight = ? AND max_weight = ?
      AND atk = ? AND def = ? AND sta = ? AND max_atk = ? AND max_def = ? AND max_sta = ?
      AND great_league_ranking = ? AND great_league_ranking_min_cp = ?  AND ultra_league_ranking = ? AND ultra_league_ranking_min_cp = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DM1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiiiiiiiiiiiiiiiiiiis",
      $_POST['pokemon_id'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_gender'],
      $_POST['cur_min_iv'],
      $_POST['cur_max_iv'],
      $_POST['cur_min_cp'],
      $_POST['cur_max_cp'],
      $_POST['cur_min_level'],
      $_POST['cur_max_level'],
      $_POST['cur_min_weight'],
      $_POST['cur_max_weight'],
      $_POST['cur_atk'],
      $_POST['cur_def'],
      $_POST['cur_sta'],
      $_POST['cur_max_atk'],
      $_POST['cur_max_def'],
      $_POST['cur_max_sta'],
      $_POST['cur_great_league_ranking'],
      $_POST['cur_great_league_ranking_min_cp'],
      $_POST['cur_ultra_league_ranking'],
      $_POST['cur_ultra_league_ranking_min_cp'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DM2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DM3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_mons");
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
    header("Location: $redirect_url?return=success_delete_raid");
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
    header("Location: $redirect_url?return=success_delete_egg");
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
    header("Location: $redirect_url?return=success_delete_quest");
    exit();
  }


  // ADD MONSTER

  if (isset($_POST['add_mon'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $pokemon_id = ltrim($key, 'mon_');

        $stmt = $conn->prepare("
          INSERT INTO monsters (
             id, ping, pokemon_id, distance, 
             min_iv, max_iv,
             min_cp, max_cp,
             min_level, max_level,
             atk, def, sta, template, clean,
             min_weight, max_weight, form,
             max_atk, max_def, max_sta, gender,
             great_league_ranking, great_league_ranking_min_cp,
             ultra_league_ranking, ultra_league_ranking_min_cp
           )
	   VALUES (?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ? )");

        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AM1&sql=$stmt->error");
          exit();
        }

        $rs = $stmt->bind_param(
          "ssiiiiiiiiiiiiiiiiiiiii",
          $_SESSION['id'],
          $pokemon_id,
          $_POST['distance'],
          $_POST['min_iv'],
          $_POST['max_iv'],
          $_POST['min_cp'],
          $_POST['max_cp'],
          $_POST['min_level'],
          $_POST['max_level'],
          $_POST['atk'],
          $_POST['def'],
          $_POST['sta'],
          $clean,
          $_POST['min_weight'],
          $_POST['max_weight'],
          $_POST['max_atk'],
          $_POST['max_def'],
          $_POST['max_sta'],
          $gender,
          $_POST['great_league_ranking'],
          $_POST['great_league_ranking_min_cp'],
          $_POST['ultra_league_ranking'],
          $_POST['ultra_league_ranking_min_cp']
        );

        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AM2&sql=$stmt->error");
          exit();
        }

        $rs = $stmt->execute();

        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AM3&sql=$stmt->error");
          exit();
        }

        $stmt->close();
        if ($pokemon_id == 0) {
          break;
        }
      }
    }
    header("Location: $redirect_url?return=success_added_mons");
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
        $boss_mega = $arr[3];

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

    header("Location: $redirect_url?return=success_added_raids");
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

    header("Location: $redirect_url?return=success_added_quest");
    exit();
  }


  // DELETE ALL MONSTERS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_mons') {

    $stmt = $conn->prepare("DELETE FROM monsters WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAM1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAM2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAM3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_delete_mons");
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

    header("Location: $redirect_url?return=success_delete_raids");
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

    header("Location: $redirect_url?return=success_delete_quest");
    exit();
  }


  // UPDATE AREAS

  if (isset($_POST['action']) && $_POST['action'] == 'areas') {

    $area_list = array();
    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "area_") {
        $area = substr($key, 5);
        $area = strtolower($area);
        array_push($area_list, "\"$area\"");
        echo $area . "<br>";
      }
    }
    $area_list = implode(',', $area_list);
    $area_list = "[" . $area_list . "]";

    $stmt = $conn->prepare("UPDATE humans set area = ?  WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UA1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("ss", $area_list, $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UA2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UA3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_update_areas");
    exit();
  }


  // PROFILE SETTINGS (ALARMS&CLEANING) FORM
  if (isset($_POST['action']) && $_POST['action'] == 'profile_settings') {

    // ENABLE AND DISABLE ALERTS GLOBALLY
    if (isset($_POST['alerts_toggle']) && $_POST['alerts_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE humans set enabled = 1  WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=E1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=E2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=E3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    } else if (!isset($_POST['alerts_toggle'])) {

      $stmt = $conn->prepare("UPDATE humans set enabled = 0  WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=D1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=D2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=D3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    }

    // ENABLE AND DISABLE MONS CLEAN GLOBALLY
    if (isset($_POST['pokes_clean_toggle']) && $_POST['pokes_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE monsters set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=EC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    } else if (!isset($_POST['pokes_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE monsters set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    }

    // ENABLE AND DISABLE RAID CLEAN GLOBALLY
    if (isset($_POST['re_clean_toggle']) && $_POST['re_clean_toggle'] == 'on') {
      $stmt = $conn->prepare("UPDATE raid set clean = 1 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=ERC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=ERC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=ERC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();

      $stmt = $conn->prepare("UPDATE egg set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=EEC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EEC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EEC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    } else if (!isset($_POST['re_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE raid set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DRC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DRC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DRC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();

      $stmt = $conn->prepare("UPDATE egg set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DEC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DEC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DEC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    }

    // ENABLE AND DISABLE QUESTS GLOBALLY
    if (isset($_POST['quests_clean_toggle']) && $_POST['quests_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE quest set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=EC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['quests_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE quest set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }

    header("Location: $redirect_url?return=success_update_settings");
    exit();

  }


  // UPDATE ALL MONSTERS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_mons_distance') {

    $stmt = $conn->prepare("UPDATE monsters set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UMD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UMD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UMD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_update_mons_distance");
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
    header("Location: $redirect_url?return=success_update_raids_distance");
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
    header("Location: $redirect_url?return=success_update_quests_distance");
    exit();
  }

  include "./action_error.php";

