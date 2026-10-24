<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/functions.php";

  // UPDATE AREAS

  if (isset($_POST['action']) && $_POST['action'] == 'areas') {

    $areas = array();
    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "area_") {
        $area = substr($key, 5);
        $area = strtolower($area);
        $area = str_replace('%20', ' ', $area);
        $areas[] = $area;
      }
    }

    try {
      $body = array('areas' => $areas);
      if (isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin'])) {
        // Admins are shown areas that are not user selectable
        $body['trusted'] = true;
      }
      $res = poracle_api()->post(poracle_human_path($_SESSION['id']) . '/areas', $body, array('profile' => (int) $_SESSION['profile']));
    } catch (PoracleApiException $e) {
      poracle_fail('area', $e);
    }

    if (!empty($res['rejected'])) {
      $_SESSION['api_error'] = i8ln("Some areas were not accepted") . ': ' . implode(', ', $res['rejected']);
      header("Location: $redirect_url?type=display&page=area&return=api_error");
      exit();
    }

    header("Location: $redirect_url?type=display&page=area&return=success_update_areas");
    exit();
  }

include "./action_error.php";
