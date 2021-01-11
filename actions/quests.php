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

  // UPDATE QUESTS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE quest
      SET distance = ?, clean = ?
      WHERE reward = ? AND reward_type = ? AND distance = ? 
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UQ1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiis",
      $_POST['distance'],
      $clean,
      $_POST['cur_reward'],
      $_POST['cur_reward_type'],
      $_POST['cur_distance'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_quest#pills-quests");
    exit();
  }

  // DELETE QUESTS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'quests') {

    $stmt = $conn->prepare("
      DELETE FROM quest
      WHERE reward = ? AND reward_type = ? AND distance = ? 
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DQ1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiis",
      $_POST['cur_reward'],
      $_POST['cur_reward_type'],
      $_POST['cur_distance'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DQ2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DQ3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_quest#pills-quests");
    exit();
  }

  // ADD QUESTS

  if (isset($_POST['add_quest'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $mon_id = ltrim($key, 'mon_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance)
                               VALUES ( ?, '', ? , ?, 1, 0, 7, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AQM1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $mon_id, $_POST['distance']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQM2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQM3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 5) === "item_") {
        $item = ltrim($key, 'item_');

        $stmt = $conn->prepare("INSERT INTO quest ( id, ping, clean, reward, template, shiny, reward_type, distance)
                               VALUES ( ?, '', ? , ?, 1, 0, 2, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AQI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siii", $_SESSION['id'], $clean, $item, $_POST['distance']);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AQI3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?return=success_added_quest#pills-quests");
    exit();
  }

  // DELETE ALL QUESTS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_quests') {

    $stmt = $conn->prepare("DELETE FROM quest WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAQ3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_delete_quest#pills-quests");
    exit();
  }


    // UPDATE ALL QUEST DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_quests_distance') {

    $stmt = $conn->prepare("UPDATE quest set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UQD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UQD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_update_quests_distance#pills-quests");
    exit();
  }

