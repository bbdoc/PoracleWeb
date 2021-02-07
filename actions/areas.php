<?php

   include "../config.php";
   include "../db_connect.php";

  // UPDATE AREAS

  if (isset($_POST['action']) && $_POST['action'] == 'areas') {

    $area_list = array();
    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "area_") {
        $area = substr($key, 5);
	$area = strtolower($area);
	$area = str_replace('%20', ' ', $area);
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

include "./action_error.php";

