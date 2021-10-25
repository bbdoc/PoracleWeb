<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";

  // ALARM SETTINGS

   if (isset($_POST['action']) && $_POST['action'] == 'alarms_settings') {

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

    header("Location: $redirect_url?return=success_update_settings");
    exit();

  }

  // CLEANING SETTINGS

  if (isset($_POST['action']) && $_POST['action'] == 'profile_settings') {

    // ENABLE AND DISABLE MONS CLEAN GLOBALLY
    if (isset($_POST['pokes_clean_toggle']) && $_POST['pokes_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE monsters set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    } else if (!isset($_POST['pokes_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE monsters set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    }

    // ENABLE AND DISABLE RAID CLEAN GLOBALLY
    if (isset($_POST['re_clean_toggle']) && $_POST['re_clean_toggle'] == 'on') {
      $stmt = $conn->prepare("UPDATE raid set clean = 1 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ERC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ERC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ERC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();

      $stmt = $conn->prepare("UPDATE egg set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EEC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EEC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EEC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    } else if (!isset($_POST['re_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE raid set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DRC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DRC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DRC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();

      $stmt = $conn->prepare("UPDATE egg set clean = 0 WHERE id = ?");

      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DEC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DEC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DEC3&sql=$stmt->error");
        exit();
      }

      $stmt->close();
    }

    // ENABLE AND DISABLE QUESTS GLOBALLY
    if (isset($_POST['quests_clean_toggle']) && $_POST['quests_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE quest set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EQC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EQC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EQC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['quests_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE quest set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DQC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DQC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DQC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }


    // ENABLE AND DISABLE INVASIONS GLOBALLY
    if (isset($_POST['invasions_clean_toggle']) && $_POST['invasions_clean_toggle'] == 'on') { 

      $stmt = $conn->prepare("UPDATE invasion set clean = 1 WHERE id = ?"); 
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EIC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EIC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=EIC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['invasions_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE invasion set clean = 0 WHERE id = ?"); 
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DIC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DIC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DIC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }


    // ENABLE AND DISABLE LURES GLOBALLY
    if (isset($_POST['leures_clean_toggle']) && $_POST['leures_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE lures set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ELC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ELC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ELC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['leures_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE lures set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DLC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DLC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DLC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }


    // ENABLE AND DISABLE NESTS GLOBALLY
    if (isset($_POST['nids_clean_toggle']) && $_POST['nids_clean_toggle'] == 'on') { 

      $stmt = $conn->prepare("UPDATE nests set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['nids_clean_toggle'])) { 

      $stmt = $conn->prepare("UPDATE nests set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }

    // ENABLE AND DISABLE GYMS GLOBALLY
    if (isset($_POST['nids_clean_toggle']) && $_POST['nids_clean_toggle'] == 'on') {

      $stmt = $conn->prepare("UPDATE gym set clean = 1 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=ENC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['nids_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE gym set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=sql_error&phase=DNC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }


    header("Location: $redirect_url?type=display&page=cleaning&type=display&page=cleaning&return=success_update_settings");
    exit();


  }

include "./action_error.php";

