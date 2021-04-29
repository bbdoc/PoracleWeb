<?php

   include "../config.php";
   include "../include/db_connect.php";

  // UPDATE AREAS

  if (isset($_POST['action']) && $_POST['action'] == 'areas') {

    $area_list = array();
    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "area_") {
        $area = substr($key, 5);
	$area = strtolower($area);
	$area = str_replace('%20', ' ', $area);
        array_push($area_list, "\"$area\"");
      }
    }
    $area_list = implode(',', $area_list);
    $area_list = "[" . $area_list . "]";

    // Update Humans Table if current Profile is active
    $stmt = $conn->prepare("UPDATE humans set area = ?  WHERE id = ? AND current_profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAH1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("ssi", $area_list, $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAH2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAH3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    // Update Profile if exist
    $stmt = $conn->prepare("UPDATE profiles set area = ?  WHERE id = ? AND profile_no = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAP1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("ssi", $area_list, $_SESSION['id'], $_SESSION['profile']);
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAP2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?type=display&page=area&return=sql_error&phase=UAP3&sql=$stmt->error");
      exit();
    }
    $stmt->close();


    header("Location: $redirect_url?type=display&page=area&return=success_update_areas");
    exit();
  }

include "./action_error.php";

