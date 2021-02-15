<?php

   include "../config.php";
   include "../db_connect.php";

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
        header("Location: $redirect_url?return=sql_error&phase=EQC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EQC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EQC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['quests_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE quest set clean = 0 WHERE id = ?");
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DQC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DQC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DQC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }


    // ENABLE AND DISABLE INVASIONS GLOBALLY
    if (isset($_POST['invasions_clean_toggle']) && $_POST['invasions_clean_toggle'] == 'on') { 

      $stmt = $conn->prepare("UPDATE invasion set clean = 1 WHERE id = ?"); 
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=EIC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EIC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=EIC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    } else if (!isset($_POST['invasions_clean_toggle'])) {

      $stmt = $conn->prepare("UPDATE invasion set clean = 0 WHERE id = ?"); 
      if (false === $stmt) {
        header("Location: $redirect_url?return=sql_error&phase=DIC1&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->bind_param("s", $_SESSION['id']);
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DIC2&sql=$stmt->error");
        exit();
      }
      $rs = $stmt->execute();
      if (false === $rs) {
        header("Location: $redirect_url?return=sql_error&phase=DIC3&sql=$stmt->error");
        exit();
      }
      $stmt->close();
    }

    header("Location: $redirect_url?return=success_update_settings");
    exit();


  }

include "./action_error.php";

