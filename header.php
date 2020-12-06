<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POracleWeb</title>
  <link rel="icon" type="image/x-icon" href="favicon.png"/>
  <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
  <script type="text/javascript">
    function confirm_mon_delete() {
       if (confirm('This will delete all your Pokemon Alarms and cannot be undone, are you sure ?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    function confirm_raid_delete() {
       if (confirm('This will delete all your Eggs & Raids Alarms and cannot be undone, are you sure ?')) {
           yourformelement.submit();
       } else {
           return false;
       }
    }
    </script>

</head>

<?php

if($_SESSION['username']) {

   $avatar = "https://cdn.discordapp.com/avatars/".$_SESSION['id']. "/".$_SESSION['avatar'].".png";

  // Exit if user not registered to Poracle

  $sql = "SELECT * from humans WHERE id = '".$_SESSION['id']."'";
  $result = $conn->query($sql);
  if ( $result->num_rows == 0 ) {
          echo "<div id='navbar'>";
          echo"<table>";
          echo "<tr valign='middle'>";
          echo "<td><b><font color=white size=4>PoracleWeb</font></b> </td>";
          echo "<td width=100%> </td>";
          echo "<td><b><font color=white size=2>".$_SESSION['username']."</font></b> </td>";
          echo "<td><img src='".$avatar."' style='border-radius: 50%; width:40px; border: 1px solid darkgreen'></td>";
          echo "</tr>";
          echo "</table>";
          echo "</div>";
          echo "<center>";
	  echo "<br><font color='darkred'><b>Please Register to Poracle first before using this tool.</font></b><br>";

	  echo "</center>";
  }

  $sql = "select area, latitude, longitude, enabled from humans WHERE id = '".$_SESSION['id']."'";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
        $area=$row['area'];
        $latitude=$row['latitude'];
        $longitude=$row['longitude'];
        $enabled=$row['enabled'];
  }

  if ( $enabled == "1") { $enabled_color="&#128994;";} else { $enabled_color="&#128308;";}
  if ( $latitude == "0.0000000000" && $longitude == "0.0000000000" ) {$location_color="&#128308;";} else { $location_color="&#128994;";}
?>

<body>

<div id="navbar">
  <a data-fancybox data-src='#profile' href='javascript:;' style='text-decoration: none;'>
  <table>
  <tr valign='middle'>
    <td><b><font color=white size=4>PoracleWeb</font></b> </td>
    <td width=100%> </td>
    <td><b><font color=white size=2><?php echo $_SESSION['username'] ?></font></b> </td>
    <td><img src='<?php echo $avatar ?>' style='border-radius: 50%; width:40px; border: 1px solid darkgreen'></td>
    <td nowrap><p style='line-height: 1.5;'><font size=2 color=white>Alarms<br>Location</font></p></td>
    <td nowrap><p style='line-height: 1.5;'><font size=2 color=white><?php echo $enabled_color ?><br><?php echo $location_color ?></font></p></td>
  </tr>
  </table>
  </a>
</div>

<?php

if ( isset($_GET['return']) && $_GET['return'] == 'success_added_raids' ) { echo "<div class='success_msg'>Successfully Added Raids Alarm(s)</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_update_mons' ) { echo "<div class='success_msg'>Successfully Updated Monster Alarm</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_update_raid' ) { echo "<div class='success_msg'>Successfully Updated Raid Alarm</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_update_egg' ) { echo "<div class='success_msg'>Successfully Updated Egg Alarm</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_delete_mons' ) { echo "<div class='success_msg'>Successfully Deleted Monster Alarm(s)</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_added_mons' ) { echo "<div class='success_msg'>Successfully Added Monster Alarm(s)</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_delete_raids' ) { echo "<div class='success_msg'>Successfully Deleted Eggs & Raids Alarm(s)</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_delete_raid' ) { echo "<div class='success_msg'>Successfully Deleted Raid Alarm</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_delete_egg' ) { echo "<div class='success_msg'>Successfully Deleted Egg Alarm</div>"; }
if ( isset($_GET['return']) && $_GET['return'] == 'success_update_areas' ) { echo "<div class='success_msg'>Successfully Updated List of Areas</div>"; }

} else {
  echo "<center><br>";
  echo '<h2>Welcome to the <br>Poracle Alarm Management</h2>';
  echo '<h4>Please Log In to access your current Alarm Config</h4>';
  echo '<h4>Clic on below Discord icon to log in</h4>';
  echo '<p><a href="./discord_auth.php?action=login"><img width=100 src="./discord.jpg"></a></p>';
  echo '<br><p>Note that you need a valid registration on the poracle server to get access to this service</p>';
  echo "</center>";
  exit();
}

?>

<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>


</body>
</html>

