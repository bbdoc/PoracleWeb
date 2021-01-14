<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PoracleWeb</title>
  <link rel="icon" type="image/x-icon" href="favicon.png" />
  <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

</head>

<body style="background-color:#FFFFFF; color: grey;">
  <br>

  <?php

#  echo "<table>";
#      foreach ($_POST as $key => $value) {
#          echo "<tr>";
#          echo "<td>";
#          echo $key;
#          echo "</td>";
#          echo "<td>";
#          echo $value;
#          echo "</td>";
#          echo "</tr>";
#      }
#  echo "</table>";

  include "../config.php";
  include "../db_connect.php";

  ?>

  <center>
    <div style="max-width:90%">
      <font size=6 color="darkred">Oops...</font><br><br>
      <img src='img/homer.png' width=250><br><br>
      Looks like Something went wrong, you shouldn't be here.<br><br>
      Please get back to homepage:<br><br>
      <a href="<?php echo $redirect_url; ?>">
        <font size=5>PoracleWeb</font>
      </a>
    </div>
  </center>

  <?php

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


