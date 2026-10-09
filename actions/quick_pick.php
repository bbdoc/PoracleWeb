<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";
   include_once "../include/poracle_forms.php";

  $api = poracle_api();
  $human = $_SESSION['id'];
  $profile = (int) $_SESSION['profile'];
  $pick = $_POST['pick'] ?? '';
  $pvpMinCp = array(500 => $_SESSION['pvpFilterLittleMinCP'], 1500 => $_SESSION['pvpFilterGreatMinCP'], 2500 => $_SESSION['pvpFilterUltraMinCP']);

  // ADD MONSTER

  if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $rules = form_pokemon_rules($_POST, $pvpMinCp, (int) $_SESSION['defaultPvpCap']);
    if (!empty($rules)) {
      try {
        $res = poracle_track_add($api, $human, 'pokemon', $rules, $profile);
      } catch (PoracleApiException $e) {
        poracle_fail('quick_pick', $e);
      }
      if (empty($res['created']) && empty($res['updated']) && !empty($res['unchanged'])) {
        header("Location: $redirect_url?type=display&page=quick_pick&pick=".urlencode($pick)."&return=duplicate");
        exit();
      }
    }
    header("Location: $redirect_url?type=display&page=quick_pick&pick=".urlencode($pick)."&return=success_added_quick_pick");
    exit();
  }

  // DELETE MONSTER

  if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    try {
      poracle_track_delete($api, $human, 'pokemon', (int) $_POST['uid'], $profile);
    } catch (PoracleApiException $e) {
      poracle_fail('quick_pick', $e);
    }
    header("Location: $redirect_url?type=display&page=quick_pick&pick=".urlencode($pick)."&return=success_delete_quick_pick");
    exit();
  }

include "./action_error.php";
