<?php
if (!isset($_SESSION['admin_id']) && !isset($_SESSION['channels_admin'])) {
	header("Location: $redirect_url"); 
	exit();
} 

$num_dbs=0;
$dbnames = explode(",", $dbname);
foreach ($dbnames as &$db) {
	$num_dbs=$num_dbs+1;
}

?>

<!-- Title -->

<h4 class="modal-title m-2">
    <center><?php echo i8ln("Channel Management"); ?></center>
</h4>

<!-- BACK TO OWN ACCOUNT -->

<?php  if ( isset($_SESSION['admin_id']) && $_SESSION['admin_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['admin_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php  } else if ( isset($_SESSION['delegated_id']) && $_SESSION['delegated_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['delegated_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php } ?>

<!-- Discord Channels -->

<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['discord']['channels']) > 0 ) {

   $dbnames = explode(",", $dbname);
   foreach ($dbnames as &$db) {
   
      $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      $sql = "select id, name, type FROM humans WHERE type like 'discord:channel' ORDER by name";
      $result = $conn->query($sql); 
      ?>
   
   <?php if ($result->num_rows <> 0) { ?>
   
      <hr>
      
      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
      				<strong><?php echo i8ln("DISCORD CHANNELS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
              </div>
          </div>
      </div>
      
      <div id='discord' class='areasform text-uppercase text-center'>
      
          <?php while ($row = $result->fetch_assoc()) { ?>

   	     <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['id'],$_SESSION['delegated_channels']['discord']['channels']) ) { ?>

             <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                 class="btn btn-primary btn-icon-split mr-2 mt-1">
                 <span class="icon text-white-50">
                     <i class="fab fa-discord"></i>
                 </span>
                    <span class="text" style="width:250px;">
                       <?php echo $row['name']; ?>
                       <?php if (@$admin_channel_id == "True") { ?>
                          <font size=2><br><?php echo $row['id']."</font>"; ?>
                       <?php } ?>
                    </span>

	     </a>

             <?php } ?>
      
          <?php } ?>
      </div>
      
      <?php } ?>
   
   <?php } ?>

<?php } ?>
   
   
   <!--  Telegram Channels -->
   
<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['telegram']['channels']) > 0 ) {

   $dbnames = explode(",", $dbname);
   foreach ($dbnames as &$db) {
   
      $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
         		       $sql = "select id, name, type FROM humans WHERE type in ('telegram:channel','telegram:group') ORDER by name";
      $result = $conn->query($sql);
      ?>
   
   <?php if ($result->num_rows <> 0) { ?>
   
      <hr>
      
      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
      				<strong><?php echo i8ln("TELEGRAM CHANNELS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
              </div>
          </div>
      </div>
      
      <div id='discord' class='areasform text-uppercase text-center'>
      
          <?php while ($row = $result->fetch_assoc()) { ?>

	     <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['id'],$_SESSION['delegated_channels']['telegram']['channels']) ) { ?>

                <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                    class="btn btn-info btn-icon-split mr-2 mt-1">
                    <span class="icon text-white-50">
                        <i class="fab fa-telegram"></i>
                    </span>
		    <span class="text" style="width:250px;">
		       <?php echo $row['name']; ?>
		       <?php if (@$admin_channel_id == "True") { ?>
		          <font size=2><br><?php echo $row['id']."</font>"; ?>
                       <?php } ?>
                    </span>
                </a>
      
	     <?php } ?>

          <?php } ?>
      
      </div>
      
      <?php } ?>
      
   <?php } ?>

<?php } ?>
   
   <!--  Webhooks -->
   
<?php

if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || count($_SESSION['delegated_channels']['discord']['webhooks']) > 0 ) {

   $dbnames = explode(",", $dbname);
   foreach ($dbnames as &$db) {
   
      $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      $sql = "select id, name, type FROM humans WHERE type like 'webhook' ORDER by name";
      $result = $conn->query($sql);
      ?>
   
   <?php if ($result->num_rows <> 0) { ?>
   
      <hr>
      
      <!-- Heading -->
      <div class="row">
          <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
      				<strong><?php echo i8ln("WEBHOOKS LIST"); ?></strong>
                  <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
              </div>
          </div>
      </div>
      
      <div id='discord' class='areasform text-uppercase text-center'>
      
          <?php while ($row = $result->fetch_assoc()) { ?>

             <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['poracle_admin']) || in_array($row['name'],$_SESSION['delegated_channels']['discord']['webhooks']) ) { ?>

             <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
                 class="btn btn-secondary btn-icon-split mr-2 mt-1">
                 <span class="icon text-white-50">
                     <font size=1>WH</font>
                 </span>
                    <span class="text" style="width:250px;">
                       <?php echo $row['name']; ?>
                       <?php if (@$admin_channel_id == "True") { ?>
                          <font size=2><br><?php echo $row['id']."</font>"; ?>
                       <?php } ?>
                    </span>
             </a>
      
	     <?php } ?>

          <?php } ?>
      
      </div>
      
      <?php } ?>
      
   <?php } ?>

<?php } ?>
   
