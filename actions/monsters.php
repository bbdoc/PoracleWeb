<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE DEFAULT PVP VALUES

   $config = file_get_contents("$poracle_dir/config/local.json");
   $json = json_decode($config, true);
   $MaxRank = 4096;
   $GreatMinCP = 0;
   $UltraMinCP = 0;
   foreach ($json as $key => $value) {
      if ($key == "pvp") {
        if (@$value['pvpFilterMaxRank'] == $_POST['great_league_ranking'] ) { $_POST['great_league_ranking'] = 4096; }
        if (@$value['pvpFilterMaxRank'] == $_POST['ultra_league_ranking'] ) { $_POST['ultra_league_ranking'] = 4096; } 
        if (@$value['pvpFilterGreatMinCP'] == $_POST['great_league_ranking_min_cp']) { $_POST['great_league_ranking_min_cp'] = 0; }
        if (@$value['pvpFilterUltraMinCP'] == $_POST['ultra_league_ranking_min_cp']) { $_POST['ultra_league_ranking_min_cp'] = 0; }
      }
   }


  // UPDATE POKEMON

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {

    foreach ($_POST as $key => $value) { 
      if (substr($value, 0, 5) == "form_") {
        $form = ltrim($value, 'form_');
      }
      if (substr($value, 0, 7) == "gender_") {
        $gender = ltrim($value, 'gender_');
      }
      if (substr($value, 0, 6) == "clean_") {
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


  // ADD MONSTER

  if (isset($_POST['add_mon'])) {

    foreach ($_POST as $key => $value) { 
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
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
	   VALUES (?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ? )");

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

include "./action_error.php"; 

