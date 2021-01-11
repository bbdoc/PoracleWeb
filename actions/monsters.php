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

  echo "<table>";
      foreach ($_POST as $key => $value) {
          echo "<tr>";
          echo "<td>";
          echo $key;
          echo "</td>";
          echo "<td>";
          echo $value;
          echo "</td>";
          echo "</tr>";
      }
  echo "</table>";

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

  // UPDATE POKEMON

  if (isset($_POST['update']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {

    foreach ($_POST as $key => $value) { 
      if (substr($value, 0, 5) == "form_") {
        $form = ltrim($value, 'form_');
      }
      if (substr($value, 0, 7) == "gender_") {
        $gender = ltrim($value, 'gender_');
      }
      if (substr($value, 0, 6) == "clean_") {
        $clean = ltrim($value, 'clean_'); 
      }
    }

    $stmt = $conn->prepare("
      UPDATE monsters
      SET distance = ?, min_iv = ?, max_iv = ?, min_cp = ?, max_cp = ?, 
          min_level = ?, max_level = ?, min_weight = ?, max_weight = ?,
	  atk = ?, def = ?, sta = ?, max_atk = ?, max_def = ?, max_sta = ?,
          great_league_ranking = ?, great_league_ranking_min_cp = ?, ultra_league_ranking = ?, ultra_league_ranking_min_cp = ?,
          form = ?, gender = ?, clean = ? 
      WHERE pokemon_id = ? AND form = ?  AND distance = ? AND gender = ?  
      AND min_iv = ? AND max_iv = ?  AND min_cp = ? AND max_cp = ?
      AND min_level = ? AND max_level = ? AND min_weight = ? AND max_weight = ?  
      AND atk = ? AND def = ? AND sta = ? AND max_atk = ? AND max_def = ? AND max_sta = ?
      AND great_league_ranking = ? AND great_league_ranking_min_cp = ?  AND ultra_league_ranking = ? AND ultra_league_ranking_min_cp = ?  
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UM1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis",
      $_POST['distance'],
      $_POST['min_iv'],
      $_POST['max_iv'],
      $_POST['min_cp'],
      $_POST['max_cp'],
      $_POST['min_level'],
      $_POST['max_level'],
      $_POST['min_weight'],
      $_POST['max_weight'],
      $_POST['atk'],
      $_POST['def'],
      $_POST['sta'],
      $_POST['max_atk'],
      $_POST['max_def'],
      $_POST['max_sta'],
      $_POST['great_league_ranking'],
      $_POST['great_league_ranking_min_cp'],
      $_POST['ultra_league_ranking'],
      $_POST['ultra_league_ranking_min_cp'],
      $form,
      $gender,
      $clean,
      $_POST['pokemon_id'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_gender'],
      $_POST['cur_min_iv'],
      $_POST['cur_max_iv'],
      $_POST['cur_min_cp'],
      $_POST['cur_max_cp'],
      $_POST['cur_min_level'],
      $_POST['cur_max_level'],
      $_POST['cur_min_weight'],
      $_POST['cur_max_weight'],
      $_POST['cur_atk'],
      $_POST['cur_def'],
      $_POST['cur_sta'],
      $_POST['cur_max_atk'],
      $_POST['cur_max_def'],
      $_POST['cur_max_sta'],
      $_POST['cur_great_league_ranking'],
      $_POST['cur_great_league_ranking_min_cp'],
      $_POST['cur_ultra_league_ranking'],
      $_POST['cur_ultra_league_ranking_min_cp'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UM2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UM3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_update_mons");
    exit();
  }

  // DELETE MONSTERS

  if (isset($_POST['delete']) && isset($_POST['type']) && $_POST['type'] == 'monsters') {

    $stmt = $conn->prepare("
      DELETE FROM monsters
      WHERE pokemon_id = ? AND form = ?  AND distance = ? AND gender = ?
      AND min_iv = ? AND max_iv = ?  AND min_cp = ? AND max_cp = ?
      AND min_level = ? AND max_level = ? AND min_weight = ? AND max_weight = ?
      AND atk = ? AND def = ? AND sta = ? AND max_atk = ? AND max_def = ? AND max_sta = ?
      AND great_league_ranking = ? AND great_league_ranking_min_cp = ?  AND ultra_league_ranking = ? AND ultra_league_ranking_min_cp = ?
      AND id = ?");

    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DM1&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->bind_param(
      "iiiiiiiiiiiiiiiiiiiiiis",
      $_POST['pokemon_id'],
      $_POST['cur_form'],
      $_POST['cur_distance'],
      $_POST['cur_gender'],
      $_POST['cur_min_iv'],
      $_POST['cur_max_iv'],
      $_POST['cur_min_cp'],
      $_POST['cur_max_cp'],
      $_POST['cur_min_level'],
      $_POST['cur_max_level'],
      $_POST['cur_min_weight'],
      $_POST['cur_max_weight'],
      $_POST['cur_atk'],
      $_POST['cur_def'],
      $_POST['cur_sta'],
      $_POST['cur_max_atk'],
      $_POST['cur_max_def'],
      $_POST['cur_max_sta'],
      $_POST['cur_great_league_ranking'],
      $_POST['cur_great_league_ranking_min_cp'],
      $_POST['cur_ultra_league_ranking'],
      $_POST['cur_ultra_league_ranking_min_cp'],
      $_SESSION['id']
    );

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DM2&sql=$stmt->error");
      exit();
    }

    $rs = $stmt->execute();

    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DM3&sql=$stmt->error");
      exit();
    }

    $stmt->close();
    header("Location: $redirect_url?return=success_delete_mons");
    exit();
  }


  // ADD MONSTER

  if (isset($_POST['add_mon'])) {

    foreach ($_POST as $key => $value) { 
      if (substr($value, 0, 7) === "gender_") {
        $gender = ltrim($value, 'gender_');
      }
      if (substr($value, 0, 6) === "clean_") { 
        $clean = ltrim($value, 'clean_'); 
      }
    }

    foreach ($_POST as $key => $value) {
      if (substr($key, 0, 4) === "mon_") {
        $pokemon_id = ltrim($key, 'mon_');

        $stmt = $conn->prepare("
          INSERT INTO monsters (
             id, ping, pokemon_id, distance, 
             min_iv, max_iv,
             min_cp, max_cp,
             min_level, max_level,
             atk, def, sta, template, clean,
             min_weight, max_weight, form,
             max_atk, max_def, max_sta, gender,
             great_league_ranking, great_league_ranking_min_cp,
             ultra_league_ranking, ultra_league_ranking_min_cp
           )
	   VALUES (?, '', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, ? )");

        if (false === $stmt) {
          header("Location: $redirect_url?return=sql_error&phase=AM1&sql=$stmt->error");
          exit();
        }

        $rs = $stmt->bind_param(
          "ssiiiiiiiiiiiiiiiiiiiii",
          $_SESSION['id'],
          $pokemon_id,
          $_POST['distance'],
          $_POST['min_iv'],
          $_POST['max_iv'],
          $_POST['min_cp'],
          $_POST['max_cp'],
          $_POST['min_level'],
          $_POST['max_level'],
          $_POST['atk'],
          $_POST['def'],
          $_POST['sta'],
          $clean,
          $_POST['min_weight'],
          $_POST['max_weight'],
          $_POST['max_atk'],
          $_POST['max_def'],
          $_POST['max_sta'],
          $gender,
          $_POST['great_league_ranking'],
          $_POST['great_league_ranking_min_cp'],
          $_POST['ultra_league_ranking'],
          $_POST['ultra_league_ranking_min_cp']
        );

        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AM2&sql=$stmt->error");
          exit();
        }

        $rs = $stmt->execute();

        if (false === $rs) {
          header("Location: $redirect_url?return=sql_error&phase=AM3&sql=$stmt->error");
          exit();
        }

        $stmt->close();
        if ($pokemon_id == 0) {
          break;
        }
      }
    }
    header("Location: $redirect_url?return=success_added_mons");
    exit();
  }


  // DELETE ALL MONSTERS

  if (isset($_GET['action']) && $_GET['action'] == 'delete_all_mons') {

    $stmt = $conn->prepare("DELETE FROM monsters WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=DAM1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("s", $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAM2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=DAM3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_delete_mons");
    exit();
  }


  // UPDATE ALL MONSTERS DISTANCE

  if (isset($_GET['action']) && $_GET['action'] == 'update_mons_distance') {

    $stmt = $conn->prepare("UPDATE monsters set distance = ? WHERE id = ?");
    if (false === $stmt) {
      header("Location: $redirect_url?return=sql_error&phase=UMD1&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->bind_param("is", $_POST['distance'], $_SESSION['id']);
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UMD2&sql=$stmt->error");
      exit();
    }
    $rs = $stmt->execute();
    if (false === $rs) {
      header("Location: $redirect_url?return=sql_error&phase=UMD3&sql=$stmt->error");
      exit();
    }
    $stmt->close();
    header("Location: $redirect_url?return=success_update_mons_distance");
    exit();
  }
