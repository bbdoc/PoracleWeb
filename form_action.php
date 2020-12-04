<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracle Configurator</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

</head>

<body style="background-color:#FFFFFF; color: grey;">
<br>


<?php 

include "./config.php";
include "./db_connect.php";

if ( $_POST['update'] == 'Update' && $_POST['type'] == 'monsters' ) {

  $sql = "UPDATE monsters  
      SET distance = ".$_POST['distance'].",
          min_iv = ".$_POST['min_iv'].", max_iv = ".$_POST['max_iv'].",
          min_cp = ".$_POST['min_cp'].", max_cp = ".$_POST['max_cp'].",
          min_level = ".$_POST['min_level'].", max_level = ".$_POST['max_level'].",
          min_weight = ".$_POST['min_weight'].", max_weight = ".$_POST['max_weight'].",
          atk = ".$_POST['atk'].", def = ".$_POST['def'].", sta = ".$_POST['sta'].",
          great_league_ranking = ".$_POST['great_league_ranking'].", great_league_ranking_min_cp = ".$_POST['great_league_ranking_min_cp'].",
	  ultra_league_ranking = ".$_POST['ultra_league_ranking'].", ultra_league_ranking_min_cp = ".$_POST['ultra_league_ranking_min_cp']."
      WHERE pokemon_id = ".$_POST['pokemon_id']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_mons");

}

if ( $_POST['update'] == 'Update' && $_POST['type'] == 'raids' ) {

  $sql = "UPDATE raid
      SET distance = ".$_POST['distance']."
      WHERE pokemon_id = ".$_POST['pokemon_id']." AND level = ".$_POST['level']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_raid");

}

if ( $_POST['update'] == 'Update' && $_POST['type'] == 'eggs' ) {

  $sql = "UPDATE egg
      SET distance = ".$_POST['distance']."
      WHERE level = ".$_POST['level']."
      AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_update_egg");

}


if ( $_POST['delete'] == 'Delete' && $_POST['type'] == 'monsters' ) {

  $sql = "DELETE FROM monsters
          WHERE pokemon_id = ".$_POST['pokemon_id']."
	  AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_delete_mons");
  
}

if ( $_POST['delete'] == 'Delete' && $_POST['type'] == 'raids' ) {

  $sql = "DELETE FROM raid
          WHERE pokemon_id = ".$_POST['pokemon_id']."
          AND level = ".$_POST['level']."
          AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql); 
  header("Location: $redirect_url?return=success_delete_raid");

}

if ( $_POST['delete'] == 'Delete' && $_POST['type'] == 'eggs' ) {

  $sql = "DELETE FROM egg
          WHERE level = ".$_POST['level']."
          AND id = '".$_SESSION['id']."';";

  $result = $conn->query($sql);
  header("Location: $redirect_url?return=success_delete_egg");

}


if ( $_POST['add_mon'] == 'Submit' ) {

  foreach ($_POST as $key => $value) {
    if ( substr( $key, 0, 4 ) === "mon_" ) {
	$pokemon_id = ltrim($key,'mon_');
	$sql = "INSERT INTO monsters (
		  id, pokemon_id, distance, ping,
		  min_iv, max_iv, 
		  min_cp, max_cp, 
		  min_level, max_level, 
                  atk, def, sta, template, 
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
			".$_POST['atk'].", ".$_POST['def'].", ".$_POST['sta'].", 1,
			".$_POST['min_weight'].", ".$_POST['max_weight'].", 0,
			".$_POST['max_atk'].", ".$_POST['max_def'].", ".$_POST['max_sta'].", 0, 
			".$_POST['great_league_ranking'].", ".$_POST['great_league_ranking_min_cp'].", 
			".$_POST['ultra_league_ranking'].", ".$_POST['ultra_league_ranking_min_cp']."
		)";
	    $result = $conn->query($sql);
            header("Location: $redirect_url?return=success_added_mons");

    }
  }

}

if ( $_POST['add_raid'] == 'Submit' ) {

  foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 4 ) === "egg_" ) {
	$level = ltrim($key,'egg_');
        $sql = "INSERT INTO egg ( id, ping, clean, template, distance, team, level)
                VALUES ( '".$_SESSION['id']."', '', 0, 1, ".$_POST['distance'].", 4, ".$level.")";
	$result = $conn->query($sql);
	echo $sql."<br>";
    }
  }

  foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 5 ) === "raid_" ) {
        $level = ltrim($key,'raid_');
        $sql = "INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                VALUES ( '".$_SESSION['id']."', '', 0, 1, 9000, ".$_POST['distance'].", 4, ".$level.", 0)";
        $result = $conn->query($sql);
	echo $sql."<br>";
    }
  }

    foreach ($_POST as $key => $value) {
     if ( substr( $key, 0, 4 ) === "mon_" ) {
        $pokemon_id = ltrim($key,'mon_');
        $sql = "INSERT INTO raid ( id, ping, clean, template, pokemon_id, distance, team, level, form)
                VALUES ( '".$_SESSION['id']."', '', 0, 1, ".$pokemon_id.", ".$_POST['distance'].", 4, 9000, 0)";
        $result = $conn->query($sql);
	echo $sql."<br>";
     }
  }

  header("Location: $redirect_url?return=success_added_raids");

}


if ( $_GET['action'] == 'delete_all_mons' ) {
	$sql = "DELETE from monsters WHERE id = '".$_SESSION['id']."';";
	$result = $conn->query($sql);
	header("Location: $redirect_url?return=success_delete_mons");
}

if ( $_GET['action'] == 'delete_all_raids' ) {
        $sql = "DELETE from egg WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        $sql = "DELETE from raid WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url?return=success_delete_raids");
}

if ( $_GET['action'] == 'enable' ) {
        $sql = "UPDATE humans set enabled = 1 WHERE id = '".$_SESSION['id']."';";
	$result = $conn->query($sql);
        header("Location: $redirect_url");
}

if ( $_GET['action'] == 'disable' ) {
        $sql = "UPDATE humans set enabled = 0 WHERE id = '".$_SESSION['id']."';";
        $result = $conn->query($sql);
        header("Location: $redirect_url");
}


if ( $_POST['action'] == 'areas' ) {
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
    header("Location: $redirect_url?return=success_update_areas");
}


?>
