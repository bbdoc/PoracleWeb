
<?php

  include "../config.php";
  include "../db_connect.php";

  if(session_status() == PHP_SESSION_NONE){
    session_start();
  }

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

  $_SESSION['profile'] = $_POST['profile'];

  if ( isset($_POST['activate']) ) { 

	  $sql = "SELECT area, latitude, longitude from profiles WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '".$_POST['profile']."'";
          $result = $conn->query($sql); 
	  while ($row = $result->fetch_assoc()) { 
		  $area = $row['area']; 
		  $latitude = $row['latitude']; 
		  $longitude = $row['longitude']; 
	  }

	  $sql = "UPDATE humans 
		  SET area = '".$area."', 
		      latitude = '".$latitude."', 
		      longitude = '".$longitude."', 
                      current_profile_no = '".$_POST['profile']."'
		   WHERE id = '" . $_SESSION['id'] . "'";
          $result = $conn->query($sql);
	  header("Location: $redirect_url?return=success_switch_profile_activate");


  } else if ( isset($_POST['view']) ) {

	  header("Location: $redirect_url?return=success_switch_profile_view");
	  
  }


?>
