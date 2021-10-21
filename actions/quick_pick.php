<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/defaults.php";

   // Use Default Values if Empty POST or no set
   if (!isset($_POST['min_iv']) || $_POST['min_iv'] == "") { $_POST['min_iv'] = $monster_defaults['min_iv']; }
   if (!isset($_POST['max_iv']) || $_POST['max_iv'] == "") { $_POST['max_iv'] = $monster_defaults['max_iv']; }
   if (!isset($_POST['min_cp']) || $_POST['min_cp'] == "") { $_POST['min_cp'] = $monster_defaults['min_cp']; }
   if (!isset($_POST['max_cp']) || $_POST['max_cp'] == "") { $_POST['max_cp'] = $monster_defaults['max_cp']; }
   if (!isset($_POST['min_level']) || $_POST['min_level'] == "") { $_POST['min_level'] = $monster_defaults['min_level']; }
   if (!isset($_POST['max_level']) || $_POST['max_level'] == "") { $_POST['max_level'] = $monster_defaults['max_level']; }
   if (!isset($_POST['min_weight']) || $_POST['min_weight'] == "") { $_POST['min_weight'] = $monster_defaults['min_weight']; }
   if (!isset($_POST['max_weight']) || $_POST['max_weight'] == "") { $_POST['max_weight'] = $monster_defaults['max_weight']; }
   if (!isset($_POST['atk']) || $_POST['atk'] == "") { $_POST['atk'] = $monster_defaults['atk']; }
   if (!isset($_POST['def']) || $_POST['def'] == "") { $_POST['def'] = $monster_defaults['def']; }
   if (!isset($_POST['sta']) || $_POST['sta'] == "") { $_POST['sta'] = $monster_defaults['sta']; }
   if (!isset($_POST['max_atk']) || $_POST['max_atk'] == "") { $_POST['max_atk'] = $monster_defaults['max_atk']; }
   if (!isset($_POST['max_def']) || $_POST['max_def'] == "") { $_POST['max_def'] = $monster_defaults['max_def']; }
   if (!isset($_POST['max_sta']) || $_POST['max_sta'] == "") { $_POST['max_sta'] = $monster_defaults['max_sta']; }
   if (!isset($_POST['gender']) || $_POST['gender'] == "") { $_POST['gender'] = $monster_defaults['gender']; }
   if (!isset($_POST['content']) || $_POST['content'] == "") { $_POST['content'] = ''; }
   if (!isset($_POST['league']) || $_POST['league'] == "") { $_POST['league'] = 0; }
   if (!isset($_POST['pvp_ranking_best']) || $_POST['pvp_ranking_best'] == "") { $_POST['pvp_ranking_best'] = 1; }

   // Replace Default Values if Set
   if (@$_POST['pvp_ranking_worst'] == "" ) { $_POST['pvp_ranking_worst'] = 4096; }

   if (@$_POST['pvp_ranking_min_cp'] == "" ) 
   { 
	   if ( @$_POST['league'] == 500 ) { $_POST['pvp_ranking_min_cp']  = $_SESSION['pvpFilterLittleMinCP']; } 
	   else if ( @$_POST['league'] == 1500 ) { $_POST['pvp_ranking_min_cp'] = $_SESSION['pvpFilterGreatMinCP']; }
	   else if ( @$_POST['league'] == 2500 ) { $_POST['pvp_ranking_min_cp'] = $_SESSION['pvpFilterUltraMinCP']; } 
	   else { $_POST['pvp_ranking_min_cp'] = 0; }
   }

   // Handle NO IV Pokemon

   if (isset($_POST['noiv']) && $_POST['noiv'] == "on" ) { $_POST['min_iv'] = "-1"; }


  // ADD MONSTER

  if (isset($_POST['action']) && $_POST['action'] == 'add') {

    $template = !empty($_POST['template']) ? $_POST['template'] : $_SESSION['defaultTemplateName'];

        $stmt = $conn->prepare("
          INSERT INTO monsters (
             id, ping, pokemon_id, distance, 
             min_iv, max_iv,
             min_cp, max_cp,
             min_level, max_level,
             atk, def, sta, template, clean,
             min_weight, max_weight, form,
             max_atk, max_def, max_sta, gender,
             pvp_ranking_worst, pvp_ranking_best, pvp_ranking_min_cp, pvp_ranking_league,
             profile_no
           )
	   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ?, ? )");

        if (false === $stmt) {
          exit();
        }

        $rs = $stmt->bind_param(
          "sssiiiiiiiiiisiiiiiiiiiiii",
          $_SESSION['id'],
          $_POST['content'],
          $_POST['pokemon_id'],
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
	  $template,
	  $_POST['clean'],
          $_POST['min_weight'],
          $_POST['max_weight'],
          $_POST['max_atk'],
          $_POST['max_def'],
          $_POST['max_sta'],
          $_POST['gender'],
          $_POST['pvp_ranking_worst'],
          $_POST['pvp_ranking_best'],
          $_POST['pvp_ranking_min_cp'],
	  $_POST['league'],
	  $_SESSION['profile']
        );

        if (false === $rs) {
          header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=sql_error&phase=AM2&sql=$stmt->error");
          exit();
        }

        $rs = $stmt->execute();

	if (false === $rs) { 
          if ( stristr($stmt->error, "Duplicate") ) { 
            header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=duplicate");
            exit();
          }
          header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=sql_error&phase=AM3&sql=$stmt->error");
          exit();
        }

        $stmt->close();

    header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=success_added_quick_pick");
    exit();

  }

  // ADD MONSTER

  if (isset($_POST['action']) && $_POST['action'] == 'delete') {

    $stmt = $conn->prepare("
      DELETE FROM monsters
      WHERE uid = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=sql_error&phase=DM1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "i",
      $_POST['uid']
    );

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=sql_error&phase=DM2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=sql_error&phase=DM3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?type=display&page=quick_pick&pick=".$_POST['pick']."&return=success_delete_quick_pick");
    exit();

  }

include "./action_error.php"; 

