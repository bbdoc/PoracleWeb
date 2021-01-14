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

  // UPDATE INVASIONS

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
    }

    $stmt = $conn->prepare("
      UPDATE invasion
      SET distance = ?, clean = ?, gender = ?
      WHERE grunt_type= ? AND clean = ? AND gender = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UI1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiisiis",
      $_POST['distance'],
      $clean,
      $gender,
      $_POST['grunt_type'],
      $_POST['cur_clean'],
      $_POST['cur_gender'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UI2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UI3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_invasion#pills-invasions");
    exit();
  }


  // DELETE INVASIONS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'invasions') {

    $stmt = $conn->prepare("
      DELETE FROM invasion
      WHERE grunt_type = ? AND gender = ? 
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DI1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "sis",
      $_POST['grunt_type'],
      $_POST['cur_gender'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DI2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DI3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_invasion#pills-invasions");
    exit();
  }


  // ADD INVASION

  if (isset($_POST['add_invasions'])) {

    foreach ($_POST as $key => $value) {
      if (substr($value, 0, 6) === "clean_") {
        $clean = ltrim($value, 'clean_');
      }
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 6) === "grunt_") {
        $grunt = ltrim($key, 'grunt_');

        $stmt = $conn->prepare("INSERT INTO invasion ( id, ping, clean, distance, template, gender, grunt_type)
	                       VALUES ( ?, '', ? , ?, 1, ?, ?)");
        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AI1&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->bind_param("siiis", $_SESSION['id'], $clean, $_POST['distance'], $gender, $grunt);
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AI2&sql=$stmt->error");
          exit();
        }
        $rs = $stmt->execute();
        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AI3&sql=$stmt->error");
          exit();
        }
        $stmt->close();
      }
    }

    header("Location: $redirect_url?return=success_added_invasions#pills-invasions");
    exit();
  }

  // DELETE ALL INVASIONS 

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_invasions') {

    $stmt = $conn->prepare("DELETE FROM invasion WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAI1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAI2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAI3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_delete_invasions#pills-invasions");
    exit();
  }


  // UPDATE ALL INVASIONS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_invasions_distance') {

    $stmt = $conn->prepare("UPDATE invasion set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UID1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UID2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UID3&sql=$stmt->error");
      exit();
    }
    $stmt->close();

    header("Location: $redirect_url?return=success_update_invasions_distance#pills-invasions");
    exit();

  }

