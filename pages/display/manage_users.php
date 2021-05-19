<?php
if ( !isset($_SESSION['admin_id']) && !isset($_SESSION['users_admin']) ) { 
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
    <center><?php echo i8ln("Users Management"); ?></center>
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

<?php } else if ( isset($_SESSION['delegated_id']) && $_SESSION['delegated_id'] <> $_SESSION['id']) { ?>

<hr>
<center>
    <a href="admin_connect.php?id=<?php echo $_SESSION['delegated_id']; ?>">
        <button type="button" class="btn btn-success" style="width:300px;">
            <?php echo i8ln("Back to own Account"); ?>
        </button>
    </a>
</center>

<?php } ?>


<!-- User Access -->

<hr>
<form action='./admin_connect.php' method='POST' class="row g-2 justify-content-center">
    <div class="col-auto justify-center mb-1 mt-2">
        <input type='text' autocomplete='off' class='form-control' id='id' name='id'
            placeholder='<?php echo i8ln("Discord or Telegram ID") ?>'>
    </div>
    <div class="col-auto justify-center mb-1 mt-2">
        <input class="btn btn-primary" type='submit' name='update'
            value='<?php echo i8ln("Go"); ?>'>
    </div>
</form>

<!-- Discord Users List -->

<?php

if (@$admin_disable_userlist <> "True" ) {

   $dbnames = explode(",", $dbname);
   foreach ($dbnames as &$db) {

      // Get Discord Users
     
      $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      $sql = "select id, name, type FROM humans WHERE type like 'discord:user' ORDER by type,name";
      $result = $conn->query($sql);
      ?>

      <?php if ($result->num_rows <> 0) { ?>

         <hr>

         <!-- Heading -->
         <div class="row">
           <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
  	      <strong><?php echo i8ln("USERS LIST"); ?> - DISCORD</strong>
                  <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
              </div>
           </div>
         </div>

         <div class='text-uppercase text-center'>

         <?php while ($row = $result->fetch_assoc()) { ?>
            <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
    	       class="btn btn-<?php if ($row['type']=='discord:user') { echo "primary"; } else { echo "info"; } ?> btn-icon-split m-1">
            <span class="icon text-white-50"> <i class="fab fa-<?php echo rtrim($row['type'], ':user');?>"></i> </span>
            <span class="text" style="min-width: 130px;"><font size=1><?php echo $row['name']; ?></font></span>
            </a>
         <?php } ?>

      </div>

      <?php } 

      // Get Discord Users

      $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);
      $sql = "select id, name, type FROM humans WHERE type like 'telegram:user' ORDER by type,name";
      $result = $conn->query($sql);
      ?>

      <?php if ($result->num_rows <> 0) { ?>

         <hr>

         <!-- Heading -->
         <div class="row">
           <div class="col">
              <div class="alert alert-secondary text-center" role="alert">
              <strong><?php echo i8ln("USERS LIST"); ?> - TELEGRAM</strong>
                  <?php if ($num_dbs > 1) { echo "<br>".$db; } ?>
              </div>
           </div>
         </div>

         <div class='text-uppercase text-center'>

         <?php while ($row = $result->fetch_assoc()) { ?>
            <a href="admin_connect.php?id=<?php echo $row['id']; ?>"
               class="btn btn-<?php if ($row['type']=='discord:user') { echo "primary"; } else { echo "info"; } ?> btn-icon-split m-1">
            <span class="icon text-white-50"> <i class="fab fa-<?php echo rtrim($row['type'], ':user');?>"></i> </span>
            <span class="text" style="min-width: 130px;"><font size=1><?php echo $row['name']; ?></font></span>
            </a>
         <?php } ?>

      </div>

      <?php } ?>

   <?php } ?>

<?php } ?>

