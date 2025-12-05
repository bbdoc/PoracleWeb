<?php

  include_once "../config.php";
  include_once "../include/db_connect.php";

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

  if (isset($_POST['profile'])) {
	  $_SESSION['profile'] = $_POST['profile'];
  }

  if ( isset($_POST['activate']) ) {

	  $stmt = $conn->prepare("SELECT area, latitude, longitude from profiles WHERE id = ? AND profile_no = ?");
	  $stmt->bind_param("si", $_SESSION['id'], $_POST['profile']);
	  $stmt->execute();
	  $result = $stmt->get_result();
	  while ($row = $result->fetch_assoc()) {
		  $area = $row['area'];
		  $latitude = $row['latitude'];
		  $longitude = $row['longitude'];
	  }
	  $stmt->close();

	  $stmt = $conn->prepare("UPDATE humans SET area = ?, latitude = ?, longitude = ?, current_profile_no = ? WHERE id = ?");
	  $stmt->bind_param("ssdis", $area, $latitude, $longitude, $_POST['profile'], $_SESSION['id']);
	  $stmt->execute();
	  $stmt->close();
	  header("Location: $redirect_url?type=display&page=profiles&return=success_switch_profile_activate");


  } else if ( isset($_POST['view']) ) {

	  header("Location: $redirect_url?type=display&page=profiles&return=success_switch_profile_view");
	  
  }

  if ( isset($_POST['create']) ) {

	  // Get Next Profile Number
          #$sql = "SELECT IFNULL(max(profile_no),0)+1 next_profile from profiles WHERE id = '" . $_SESSION['id'] . "'";
	  $stmt = $conn->prepare("SELECT MIN(t1.profile_no + 1) AS nextID
                  FROM (select profile_no from profiles  WHERE id = ? UNION select 0 profile_no) t1
                  LEFT JOIN (select profile_no from profiles  WHERE id = ? UNION select 0 profile_no) t2
                  ON t1.profile_no + 1 = t2.profile_no
                  WHERE t2.profile_no IS NULL");
	  $stmt->bind_param("ss", $_SESSION['id'], $_SESSION['id']);
	  $stmt->execute();
	  $result = $stmt->get_result();
	  while ($row = $result->fetch_assoc()) {
		  $next_profile = $row['nextID'];
	  }
	  $stmt->close();

	  if ( $next_profile == 1 ) {
             // Get Info on currently active Profile
             $stmt = $conn->prepare("SELECT area, latitude, longitude from humans WHERE id = ?");
	     $stmt->bind_param("s", $_SESSION['id']);
	     $stmt->execute();
             $result = $stmt->get_result();
             while ($row = $result->fetch_assoc()) {
                  $area = $row['area'];
                  $latitude = $row['latitude'];
		  $longitude = $row['longitude'];
		  $_SESSION['profile_name'] = $_POST['profile_name'];
             }
	     $stmt->close();
	  } else {
		  $area = "[]";
		  $latitude = "0.0000000000";
		  $longitude = "0.0000000000";
	  }

          // Create New Profile

          $stmt = $conn->prepare("INSERT INTO profiles ( id, profile_no, name, area, latitude, longitude)
                               VALUES ( ?, ?, ?, ?, ?, ?)");
          if (false === $stmt) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=CP1&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->bind_param("sissdd", $_SESSION['id'], $next_profile, $_POST['profile_name'], $area, $latitude, $longitude);
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=CP2&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->execute();
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=CP3&sql=$stmt->error");
            exit();
          }
	  $stmt->close();

	  $_SESSION['profile'] = $next_profile;
          header("Location: $redirect_url?type=display&page=profiles&return=success_create_profile");

  }

  if ( isset($_POST['rename']) ) {

          $stmt = $conn->prepare("UPDATE profiles set name = ? where id = ? AND profile_no = ?");
          if (false === $stmt) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=RP1&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->bind_param("ssi", $_POST['profile_name'], $_SESSION['id'], $_SESSION['profile']);
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=RP2&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->execute();
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=RP3&sql=$stmt->error");
            exit();
          }
          $stmt->close();

          header("Location: $redirect_url?type=display&page=profiles&return=success_rename_profile");

  }

  if ( isset($_GET['action']) && $_GET['action'] == "delete" ) {

	  // DELETE PROFILE
	 
          $stmt = $conn->prepare("DELETE from profiles where id = ? AND profile_no = ?");
          if (false === $stmt) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=DP1&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=DP2&sql=$stmt->error");
            exit();
          }
          $rs = $stmt->execute();
          if (false === $rs) {
            header("Location: $redirect_url?type=display&page=profiles&return=sql_error&phase=DP3&sql=$stmt->error");
            exit();
          }
          $stmt->close();

	  // DELETE MONSTERS, EGG, RAID, QUEST, INVASION, LURES

	  if ( $_SESSION['profile'] <> 1) {

	     $tables = "monsters,egg,raid,quest,invasion,lures";
             $tables_items = explode(',', $tables);
	     foreach ($tables_items as &$table) {
		  $stmt = $conn->prepare("DELETE from ".$table." where id = ? AND profile_no = ?");
		  $rs = $stmt->bind_param("si", $_SESSION['id'], $_SESSION['profile']);
		  $rs = $stmt->execute();
	     }

	  }

          // Change Active Profile if Deleting Active one

          $stmt = $conn->prepare("SELECT current_profile_no FROM humans WHERE id = ?");
          $stmt->bind_param("s", $_SESSION['id']);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
             $current_profile = $row['current_profile_no'];
          }
          $stmt->close();

          if ( $current_profile == $_SESSION['profile'])  {
                  $stmt = $conn->prepare("UPDATE humans set current_profile_no = (select IFNULL(min(profile_no),1) from profiles where id = ?) WHERE id = ?");
                  $stmt->bind_param("ss", $_SESSION['id'], $_SESSION['id']);
                  $stmt->execute();
                  $stmt->close();
          }

	  // Check for smaller Profiles and redirect

          $stmt = $conn->prepare("SELECT IFNULL(min(profile_no),1) min from profiles WHERE id = ?");
          $stmt->bind_param("s", $_SESSION['id']);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
             $_SESSION['profile'] = $row['min'];
          }
          $stmt->close();

          header("Location: $redirect_url?type=display&page=profiles&return=success_delete_profile");

  }

  include "./action_error.php";


?>
