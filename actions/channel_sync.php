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
      <img src='../img/homer.png' width=250><br><br>
      Looks like Something went wrong, you shouldn't be here.<br><br>
      Please get back to homepage:<br><br>
      <a href="<?php echo $redirect_url; ?>">
        <font size=5>PoracleWeb</font>
      </a>
    </div>
  </center>

  <?php


if (isset($_POST['sync'])) {

    foreach ($_POST as $key => $value) {  
      if (substr($key, 0, 7) == "target_") {
	      $target = ltrim($key, 'target_');
	      $target_fields = explode("|", $target);
	      $target_db=$target_fields[0];
	      $target_id=$target_fields[1];

              // Delete All Previous Trackings
	      $stmt = $conn->prepare("DELETE FROM ".$target_db.".monsters WHERE id = ?");
	      $rs = $stmt->bind_param("s", $target_id);
              $rs = $stmt->execute();
              $stmt->close();

              $stmt = $conn->prepare("DELETE FROM ".$target_db.".egg WHERE id = ?");
              $rs = $stmt->bind_param("s", $target_id);
              $rs = $stmt->execute();
              $stmt->close();

              $stmt = $conn->prepare("DELETE FROM ".$target_db.".raid WHERE id = ?");
              $rs = $stmt->bind_param("s", $target_id);
              $rs = $stmt->execute();
              $stmt->close();

              $stmt = $conn->prepare("DELETE FROM ".$target_db.".quest WHERE id = ?");
              $rs = $stmt->bind_param("s", $target_id);
              $rs = $stmt->execute();
              $stmt->close();

	      // Insert New Monsters Tracking
	      
	      $stmt = $conn->prepare("INSERT INTO ".$target_db.".monsters SELECT
                      REPLACE(id, ?, ? ),
                      ping, clean, pokemon_id, distance,
                      min_iv, max_iv,
                      min_cp, max_cp,
                      min_level, max_level,
                      atk, def, sta,
                      template, min_weight, max_weight, form, max_atk,
                      max_def, max_sta, gender,
                      great_league_ranking, great_league_ranking_min_cp,
                      ultra_league_ranking, ultra_league_ranking_min_cp
		      FROM monsters
                      WHERE id = ?
		      ");

	      $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
	      $rs = $stmt->execute();
              $stmt->close();

	      // Insert New Eggs Tracking

              $stmt = $conn->prepare("INSERT INTO ".$target_db.".egg SELECT
                      REPLACE(id, ?, ? ),
                      ping, clean, exclusive, template, distance, team, level
                      FROM egg
                      WHERE id = ?
                      ");

              $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
              $rs = $stmt->execute();
              $stmt->close();

	      // Insert New Raids Tracking

              $stmt = $conn->prepare("INSERT INTO ".$target_db.".raid SELECT
                      REPLACE(id, ?, ? ),
                      ping, clean, pokemon_id, exclusive, template, distance, team, level, form
                      FROM raid
                      WHERE id = ?
                      ");

              $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
              $rs = $stmt->execute();
              $stmt->close();

              // Insert New Quests Tracking

              $stmt = $conn->prepare("INSERT INTO ".$target_db.".quest SELECT
                      REPLACE(id, ?, ? ),
                      ping, clean, reward, template, shiny, reward_type, distance
                      FROM quest
                      WHERE id = ?
                      ");

              $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
              $rs = $stmt->execute();
              $stmt->close();

      }
    }

  header("Location: $redirect_url?return=success_channel_sync");
  exit();

}

?>
