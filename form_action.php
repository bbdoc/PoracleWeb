<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PoracleWeb</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

</head>

<body style="background-color:#FFFFFF; color: grey;">
<br>

<?php 

#echo "<table>";
#    foreach ($_POST as $key => $value) {
#        echo "<tr>";
#        echo "<td>";
#        echo $key;
#        echo "</td>";
#        echo "<td>";
#        echo $value;
#        echo "</td>";
#        echo "</tr>";
#    }
#echo "</table>";

include "./config.php";
include "./db_connect.php";

?>

<center>
<div style="max-width:90%">
<font size=6 color="darkred">Oops...</font><br><br>
<img src='homer.png' width=250><br><br>
Looks like Something went wrong, you shouldn't be here.<br><br>
Please get back to homepage:<br><br>
<a href="<?php echo $redirect_url; ?>"><font size=5>PoracleWeb</font></a>
</div>
</center>

<?php


if ( isset($_POST['update']) && $_POST['update'] == 'Update' && isset($_POST['type']) && $_POST['type'] == 'monsters' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 5 ) === "form_" ) {
      $form = ltrim($value,'form_');
    }
    if ( substr( $value, 0, 7 ) === "gender_" ) {
      $gender = ltrim($value,'gender_');
    }
    if ( substr( $value, 0, 6 ) === "clean_" ) {
      $clean = ltrim($value,'clean_');
    }
  }


  $sql = "UPDATE monsters  
      SET distance = ".$_POST['distance'].",
          min_iv = ".$_POST['min_iv'].", max_iv = ".$_POST['max_iv'].",
          min_cp = ".$_POST['min_cp'].", max_cp = ".$_POST['max_cp'].",
          min_level = ".$_POST['min_level'].", max_level = ".$_POST['max_level'].",
          min_weight = ".$_POST['min_weight'].", max_weight = ".$_POST['max_weight'].",
          atk = ".$_POST['atk'].", def = ".$_POST['def'].", sta = ".$_POST['sta'].",
          great_league_ranking = ".$_POST['great_league_ranking'].", great_league_ranking_min_cp = ".$_POST['great_league_ranking_min_cp'].",
	  ultra_league_ranking = ".$_POST['ultra_league_ranking'].", ultra_league_ranking_min_cp = ".$_POST['ultra_league_ranking_min_cp'].",
          form = ".$form.", gender = ".$gender.", clean = ".$clean."
      WHERE pokemon_id = ".$_POST['pokemon_id']." AND form = ".$_POST['cur_form']." AND gender = ".$_POST['cur_gender']." 
      AND min_iv = ".$_POST['cur_min_iv']." AND max_iv = ".$_POST['cur_max_iv']."
      AND min_cp = ".$_POST['cur_min_cp']." AND max_cp = ".$_POST['cur_max_cp']."
      AND min_level = ".$_POST['cur_min_level']." AND max_level = ".$_POST['cur_max_level']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_mons"); exit();

}

if ( isset($_POST['update']) && $_POST['update'] == 'Update' && isset($_POST['type']) && $_POST['type'] == 'raids' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 6 ) === "clean_" ) {
      $clean = ltrim($value,'clean_');
    }
  }

  $sql = "UPDATE raid
      SET distance = ".$_POST['distance'].", clean = ".$clean."
      WHERE pokemon_id = ".$_POST['pokemon_id']." AND level = ".$_POST['level']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_raid"); exit();

}

if ( isset($_POST['update']) && $_POST['update'] == 'Update' && isset($_POST['type']) && $_POST['type'] == 'eggs' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 6 ) === "clean_" ) {
      $clean = ltrim($value,'clean_');
    }
  }

  $sql = "UPDATE egg
      SET distance = ".$_POST['distance'].", clean = ".$clean."
      WHERE level = ".$_POST['level']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_egg"); exit();

}


if ( isset($_POST['delete']) && $_POST['delete'] == 'Delete' && isset($_POST['type']) && $_POST['type'] == 'monsters' ) {

  $sql = "DELETE FROM monsters
          WHERE pokemon_id = ".$_POST['pokemon_id']." AND form = ".$_POST['cur_form']."
          AND min_iv = ".$_POST['cur_min_iv']." AND max_iv = ".$_POST['cur_max_iv']."
          AND min_cp = ".$_POST['cur_min_cp']." AND max_cp = ".$_POST['cur_max_cp']."
          AND min_level = ".$_POST['cur_min_level']." AND max_level = ".$_POST['cur_max_level']."
	  AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_delete_mons"); exit();
  
}

if ( isset($_POST['delete']) && $_POST['delete'] == 'Delete' && isset($_POST['type']) && $_POST['type'] == 'raids' ) {

  $sql = "DELETE FROM raid
          WHERE pokemon_id = ".$_POST['pokemon_id']."
          AND level = ".$_POST['level']."
          AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql); 
  header("Location: $redirect_url?return=success_delete_raid"); exit();

}

if ( isset($_POST['delete']) && $_POST['delete'] == 'Delete' && isset($_POST['type']) && $_POST['type'] == 'eggs' ) {

  $sql = "DELETE FROM egg
          WHERE level = ".$_POST['level']."
          AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_delete_egg"); exit();

}


if ( isset($_POST['add_mon']) && $_POST['add_mon'] == 'Submit' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 7 ) === "gender_" ) {
      $gender = ltrim($value,'gender_');
    }
  }

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 6 ) === "clean_" ) {
      $clean = ltrim($value,'clean_');
    }
  }

  foreach ($_POST as $key => $value) {
    if ( substr( $key, 0, 4 ) === "mon_" ) {
	$pokemon_id = ltrim($key,'mon_');
	$sql = "INSERT INTO monsters (
		  id, pokemon_id, distance, ping,
		  min_iv, max_iv, 
		  min_cp, max_cp, 
		  min_level, max_level, 
                  atk, def, sta, template, clean,
		  min_weight, max_weight, form, 
                  max_atk, max_def, max_sta, gender,
		  great_league_ranking, great_league_ranking_min_cp, 
                  ultra_league_ranking, ultra_league_ranking_min_cp
                )
		VALUES (
			'".$_SESSION['id']."', ".$pokemon_id.", ".$_POST['distance'].", '',
			".$_POST['min_iv'].", ".$_POST['max_iv'].", 
			".$_POST['min_cp'].", ".$_POST['max_cp'].", 
			".$_POST['min_level'].", ".$_POST['max_level'].", 
			".$_POST['atk'].", ".$_POST['def'].", ".$_POST['sta'].", 1, ".$clean.",
			".$_POST['min_weight'].", ".$_POST['max_weight'].", 0,
			".$_POST['max_atk'].", ".$_POST['max_def'].", ".$_POST['max_sta'].", ".$gender.",
			".$_POST['great_league_ranking'].", ".$_POST['great_league_ranking_min_cp'].", 
			".$_POST['ultra_league_ranking'].", ".$_POST['ultra_league_ranking_min_cp']."
		)";
	    $result = $conn->query($sql); 
	    if ($pokemon_id == 0) { break; }  
    }
  }
  header("Location: $redirect_url?return=success_added_mons"); exit();

}

if ( isset($_POST['add_raid']) && $_POST['add_raid'] == 'Submit' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $value, 0, 6 ) === "clean_" ) {
      $clean = ltrim($value,'clean_');
    }
  }

  foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 4 ) === "egg_" ) {
	$level = ltrim($key,'egg_');
        $sql = "INSERT INTO egg ( id, ping, clean, template, distance, team, level)
                VALUES ( '".$_SESSION['id']."', '', $clean, 1, ".$_POST['distance'].", 4, ".$level.")";
	$result = $conn->query($sql);
	echo $sql."<br>";
    }
  }

  foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 5 ) === "raid_" ) {
        $level = ltrim($key,'raid_');
        $sql = "INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                VALUES ( '".$_SESSION['id']."', '', $clean, 1, 9000, ".$_POST['distance'].", 4, ".$level.", 0)";
        $result = $conn->query($sql);
	echo $sql."<br>";
    }
  }

    foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 4 ) === "mon_" ) {
        $pokemon_id = ltrim($key,'mon_');
        $sql = "INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                VALUES ( '".$_SESSION['id']."', '', $clean, 1, ".$pokemon_id.", ".$_POST['distance'].", 4, 9000, 0)";
        $result = $conn->query($sql);
	echo $sql."<br>";
     }
  }

  header("Location: $redirect_url?return=success_added_raids"); exit();

}


if ( isset($_GET['action']) && $_GET['action'] == 'delete_all_mons' ) {
	$sql = "DELETE from monsters WHERE id = '".$_SESSION['id']."';";
	$result = $conn->query($sql);
	header("Location: $redirect_url?return=success_delete_mons"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'delete_all_raids' ) {
        $sql = "DELETE from egg WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        $sql = "DELETE from raid WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url?return=success_delete_raids"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'enable' ) {
        $sql = "UPDATE humans set enabled = 1 WHERE id = '".$_SESSION['id']."';";
	$result = $conn->query($sql);
	header("Location: $redirect_url"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'disable' ) {
        $sql = "UPDATE humans set enabled = 0 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url"); exit();
}


if ( isset($_POST['action']) && $_POST['action'] == 'areas' ) {
    $area_list = array();
    foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 5 ) === "area_" ) {
	     $area = ltrim($key,'area_');
	     $area = strtolower($area);
             array_push($area_list, "\"$area\"");
     }
    }
    $area_list = implode(',', $area_list);
    $area_list = "[".$area_list."]";
    $sql = "UPDATE humans set area = '".$area_list."' WHERE id = '".$_SESSION['id']."';";
    $result = $conn->query($sql);
    header("Location: $redirect_url?return=success_update_areas"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'disable_mon_clean' ) {
        $sql = "UPDATE monsters set clean = 0 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'enable_mon_clean' ) {
        $sql = "UPDATE monsters set clean = 1 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'disable_raid_clean' ) {
        $sql = "UPDATE raid set clean = 0 WHERE id = '".$_SESSION['id']."';";
	$result = $conn->query($sql);
        $sql = "UPDATE egg set clean = 0 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url"); exit();
}

if ( isset($_GET['action']) && $_GET['action'] == 'enable_raid_clean' ) {
        $sql = "UPDATE raid set clean = 1 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        $sql = "UPDATE egg set clean = 1 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url"); exit();
}



?>
