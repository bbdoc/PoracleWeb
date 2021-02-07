<?php

   include "../config.php";
   include "../db_connect.php";

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

include "./action_error.php";

