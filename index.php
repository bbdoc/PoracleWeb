
<?php 

include "./header.php";

echo "<center><br>";

  if ($area == "[]") { 
          echo "<font color='darkred'><b>You have not set any area yet</font></b><br><br>";
	  $areas = ""; 
  } else { 
          echo "You are currently receiving alarms for the following area(s) :<br>";
	  $areas = explode(",", $area); 

     echo "<ul>";
     foreach($areas as $key => $area)
     { 
       $area=str_replace('"', '', $area); 
       $area=str_replace('[', '', $area); 
       $area=str_replace(']', '', $area); 
       echo "<li><input type='checkbox' name='areav_$area' id='areav_$area' checked onclick='return false;'/>\n";
       echo "<label for='areav_$area' style='width:200px;'>".strtoupper($area);
       echo "</li>\n";
     }
     echo "</ul>";

  }


  // Add Hidden Fancy Box Profile
  include "./fancy/fancy_profile.php";

  // Add Hidden Fancy Box Area
  include "./fancy/fancy_areas.php";

  echo "<a data-fancybox data-src='#areas' href='javascript:;' style='text-decoration: none;'>";
  echo "<button class='button_update' style='width:150px;'>Select Areas</button>";
  echo "</a>";

  // Show Monsters Alarms

  echo "<hr><br><p><b>Monsters you are tracking</b></p>\n";
  echo "<font size=2><i>Click on any Alarm to edit your tracking parameters</i></font></p><br>\n";
  if ($all_mon_cleaned == '1' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaning activated on all Monsters</code></p><br>\n"; }
  echo "<a href='./add_mons.php'><button class='button_update' style='width:150px;'>Add New</button></a>\n";
  echo "<a href='./form_action.php?action=delete_all_mons'><button class='button_delete' style='width:150px;' onclick='return confirm_mon_delete();'>Delete All</button></a>\n";
  echo "<br><br>\n";

  $sql = "select * FROM monsters WHERE id = '".$_SESSION['id']."' ORDER BY pokemon_id, form";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Check Images only if Form <> Normal and Substitude if necessary
    $PkmnImg="$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_".str_pad($row['form'], 2, "0", STR_PAD_LEFT).".png";
    if ( $row['form'] <> 0 ) { 
       if (false === file_get_contents("$PkmnImg",0,null,0,1)) {
          $pokemon_name=get_mons($row['pokemon_id']); 
	  $PkmnImg_50 = "<font size=5><strong>".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."</strong></font><br>$pokemon_name";
	  $PkmnImg_100 = "<font size=8><strong>".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."</strong></font><br>$pokemon_name";
       } 
       else {  
          $PkmnImg_50 = "<img width=50 src='$PkmnImg'>"; 
          $PkmnImg_100 = "<img width=100 src='$PkmnImg'>";
       }
    }
    else { 
       $PkmnImg_50 = "<img width=50 src='$PkmnImg'>"; 
       $PkmnImg_100 = "<img width=100 src='$PkmnImg'>";
    }

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_pokemons.php";

    echo "<a data-fancybox data-src='#mon_".$row['pokemon_id']."_".$row['form']."_".$row['min_cp']."_".$row['max_cp']."_".$row['min_iv']."_".$row['max_iv']."_".$row['min_level']."_".$row['max_level']."' href='javascript:;'>\n";
    echo "<button>\n";
    echo "<font size=1>\n";
    echo "<table width=100%><tr>\n";
   
    if ( $row['pokemon_id'] == '0' ) {
      echo "<td><div class='img_div'><font size=5><strong>&nbsp;ALL</strong></font></div></td>";
    } else {
      echo "<td><div class='img_div'><center>$PkmnImg_50</center></div></td>";
    }
    echo "<td width=100%>";

    if ($row['distance'] <> '0' ) {
      echo "<p><b>Distance : </b>".$row['distance']."</p>";
    }
    if ($row['min_iv'] <> '-1' || $row['max_iv'] <> '100' ) {
      echo "<p><b>IV : </b>".$row['min_iv']." - ".$row['max_iv']."</p>";
    } else { echo "<b>IV : </b>ALL"; }
    if ($row['min_cp'] <> '0' || $row['max_cp'] <> '9000' ) {
      echo "<p><b>CP : </b>".$row['min_cp']." - ".$row['max_cp']."</p>";
    }
    if ($row['min_level'] <> '0' || $row['max_level'] <> '40' ) {
      echo "<p><b>Level : </b>".$row['min_level']." - ".$row['max_level']."</p>";
    }
    if ($row['atk'] <> '0' || $row['def'] <> '0' || $row['sta'] <> '0' || $row['max_atk'] <> '15' || $row['max_def'] <> '15' || $row['max_sta'] <> '15' ) {
      echo "<p><b>Stats : </b>".$row['atk']."/".$row['def']."/".$row['sta']." - ".$row['max_atk']."/".$row['max_def']."/".$row['max_sta']."</p>";;
    }
    if ($row['great_league_ranking'] <> '4096' || $row['great_league_ranking_min_cp'] <> '0' ) {
      echo "<p><b>Great : </b>top".$row['great_league_ranking']." @".$row['great_league_ranking_min_cp']."</p>";
    }
    if ($row['ultra_league_ranking'] <> '4096') {
      echo "<p><b>Ultra : </b>top".$row['ultra_league_ranking']." @".$row['ultra_league_ranking_min_cp']."</p>";
    }
    if ($row['min_weight'] <> '0' || $row['max_weight'] <> '9000000' ) {
      echo "<p><b>CP : </b>".$row['min_weight']." - ".$row['max_weight']."</p>";
    }

    if ($row['form'] <> '0' ) {
      $form_name=get_form_name($row['pokemon_id'],$row['form']);
      echo "<p style='margin-top:5px;'><code>".$form_name."</code></p>"; 
    }
    if ($row['gender'] == '1' ) { echo "<p style='margin-top:5px;'><code>Male</code></p>"; }
    if ($row['gender'] == '2' ) { echo "<p style='margin-top:5px;'><code>Female</code></p>"; }
    if ($row['clean'] == '1' && $all_mon_cleaned == '0' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaned</code></p>"; }

    echo "</td>";
    echo "</tr></table>";

    echo "</font>";
    echo "</button>";
    echo "</a>";

  }

  // Show Eggs & Raids

  echo "<hr><br><p><b>Eggs & Raids you are tracking</b></p>\n";
  echo "<i><font size=2>Click on any Alarm to edit your tracking parameters</font></i></p><br>";
  if ($all_raid_cleaned == '1' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaning activated on all Raids/Eggs</code></p><br>"; }
  echo "<a href='./add_raids.php'><button class='button_update' style='width:150px;'>Add New</button></a>\n";
  echo "<a href='./form_action.php?action=delete_all_raids'><button class='button_delete' style='width:150px;' onclick='return confirm_raid_delete();'>Delete All</button></a>\n";
  echo "<br><br>\n";

  $sql = "select * FROM egg WHERE id = '".$_SESSION['id']."' ORDER BY level";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_eggs.php";

    echo "<a data-fancybox data-src='#egg_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<img width=50 src='$imgUrl/egg".$row['level'].".png'><br><br>\n";
    echo "<font size=1>";
    echo "Eggs Level ".$row['level'];
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance']."<br>";
    }
    if ($row['clean'] == '1' && $all_raid_cleaned == '0' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaned</code></p>"; }

    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }

  $sql = "select * FROM raid WHERE id = '".$_SESSION['id']."' AND pokemon_id = 9000 ORDER BY level";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_raids.php";

    echo "<a data-fancybox class='various' data-src='#raid_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<img width=50 src='$imgUrl/egg".$row['level'].".png'><br><br>\n";
    echo "<font size=1>";
    echo "Raids Level ".$row['level'];
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance'];
    }
    if ($row['clean'] == '1' && $all_raid_cleaned == '0' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaned</code></p>"; }
    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }

  $sql = "select * FROM raid WHERE id = '".$_SESSION['id']."' AND pokemon_id <> 9000 ORDER BY pokemon_id";
  $result = $conn->query($sql);

  while($row = $result->fetch_assoc()) {

    // Add Hidden Fancy Boxes
    include "./fancy/fancy_raids.php";

    $pokemon_name=get_mons($row['pokemon_id']);
    echo "<a data-fancybox class='various' data-src='#raid_".$row['level']."' href='javascript:;' style='text-decoration: none;'>";
    echo "<button style='width:100px; height:130px;'>\n";
    echo "<font size=1>";
    echo "<img width=50 src='$imgUrl/pokemon_icon_".str_pad($row['pokemon_id'], 3, "0", STR_PAD_LEFT)."_00.png'><br><br>$pokemon_name\n";
    if ($row['distance'] <> '0' ) {
      echo "<br>Distance : ".$row['distance'];
    }
    if ($row['clean'] == '1' && $all_raid_cleaned == '0' ) { echo "<p style='margin-top:5px;'><code class='clean'>cleaned</code></p>"; }
    echo "</font>\n";
    echo "</button>\n";
    echo "</a>\n";

  }

echo "<br><br>";
?>

</body>
