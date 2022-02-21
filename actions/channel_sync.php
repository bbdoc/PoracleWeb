<?php

   include_once "../config.php";
   include_once "../include/db_connect.php";

if (isset($_POST['sync'])) {

    foreach ($_POST as $key => $value) {  
      if (substr($key, 0, 7) == "target_") {
	      $target = ltrim($key, 'target_');
	      $target_fields = explode("|", $target);
	      $target_db=$target_fields[0];
	      $target_id=$target_fields[1];
	      $target_id=str_replace("_com", ".com", $target_id);

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
	      
	      $stmt = $conn->prepare("INSERT INTO ".$target_db.".monsters 
		      (id, ping, clean, pokemon_id, distance,
                      min_iv, max_iv,
                      min_cp, max_cp,
                      min_level, max_level,
                      atk, def, sta,
                      template, min_weight, max_weight, form, max_atk,
                      max_def, max_sta, gender,
                      pvp_ranking_worst, pvp_ranking_best,pvp_ranking_min_cp, pvp_ranking_league,
                      profile_no, min_time, rarity, max_rarity
                      )
                      SELECT REPLACE(id, ?, ? ),
                      ping, clean, pokemon_id, distance,
                      min_iv, max_iv,
                      min_cp, max_cp,
                      min_level, max_level,
                      atk, def, sta,
                      template, min_weight, max_weight, form, max_atk,
                      max_def, max_sta, gender,
                      pvp_ranking_worst, pvp_ranking_best,pvp_ranking_min_cp, pvp_ranking_league,
                      profile_no, min_time, rarity, max_rarity
		      FROM monsters
                      WHERE id = ?
		      ");

	      $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
	      $rs = $stmt->execute();
              $stmt->close();

	      // Insert New Eggs Tracking

	      $stmt = $conn->prepare("INSERT INTO ".$target_db.".egg 
		      (id,ping, clean, exclusive, template, distance, team, level, profile_no)
                      SELECT REPLACE(id, ?, ? ),
                      ping, clean, exclusive, template, distance, team, level, profile_no
                      FROM egg
                      WHERE id = ?
                      ");

              $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
              $rs = $stmt->execute();
              $stmt->close();

	      // Insert New Raids Tracking

	      $stmt = $conn->prepare("INSERT INTO ".$target_db.".raid 
		      (id, ping, clean, pokemon_id, exclusive, template, distance, team, level, form, profile_no)
                      SELECT REPLACE(id, ?, ? ),
                      ping, clean, pokemon_id, exclusive, template, distance, team, level, form, profile_no
                      FROM raid
                      WHERE id = ?
                      ");

              $rs = $stmt->bind_param("sss", $_SESSION['id'], $target_id, $_SESSION['id']);
              $rs = $stmt->execute();
              $stmt->close();

              // Insert New Quests Tracking

	      $stmt = $conn->prepare("INSERT INTO ".$target_db.".quest 
		      (id, ping, clean, reward, template, shiny, reward_type, distance, profile_no)
                      SELECT REPLACE(id, ?, ? ),
                      ping, clean, reward, template, shiny, reward_type, distance, profile_no
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

