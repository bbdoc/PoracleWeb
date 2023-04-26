<?php

$time_start = microtime(true); 

include_once "./config.php";
include_once "./include/db_connect.php";
include_once "./include/functions.php";

if (!isset($_SESSION['admin_id']) && !isset($_SESSION['delegated_id'])) {
        header("Location: $redirect_url");
        exit();
}


if ( isset($_GET['id']) ) {
   $search_id = $_GET['id'];
} else if ( isset($_POST['id']) ) {
   $search_id = $_POST['id'];
}

$dbnames = explode(",", $dbname);

// Check for ID in all DBs

foreach ($dbnames as &$db) {

   $conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $db);

   // Check connection
   if ($conn->connect_errno) {
      echo "Failed to connect to MySQL: " . $conn->connect_error;
      exit();
   }

   $stmt = $conn->prepare("SELECT * from humans WHERE id = ?");
   $stmt->bind_param("s", $search_id);
   $stmt->execute();
   $stmt->store_result();

   if ( $stmt->num_rows > 0 ) {
          $_SESSION['dbname'] = $db;
   } 

   $stmt->close();

}

$conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $_SESSION['dbname']);
$sql = "select id, name, type, notes FROM humans WHERE id = '".$search_id."'"; 
$result = $conn->query($sql);

if ($result->num_rows == 0) {
	header("Location: $redirect_url?return=user_not_found");
	exit();
}

while ($row = $result->fetch_assoc()) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['type']=$row['type'];
        $_SESSION['notes']=$row['notes'];
}

// Get Config Items from API and Store in Session Variables

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "X-Poracle-Secret: $api_secret\r\n"
  )
);

$context = stream_context_create($opts);

// Update Areas to Match New User ID

$areas = file_get_contents("$api_address/api/humans/".rawurlencode($_SESSION['id']), false, $context);
$json = json_decode($areas, true);

if ( $json['status']="ok" ) {
   $_SESSION['areas'] = $json['areas'];
} else {
   session_destroy();
   header("Location: $redirect_url?return=error_api_nok");
   exit();
}

// Reset Admin Account

if ( isset($_SESSION['admin_id']) && $_SESSION['id'] == $_SESSION['admin_id'] )
{ 
	$_SESSION['username'] = $_SESSION['username'];
        $_SESSION['type']=$_SESSION['admin_type'];
	$_SESSION['dbname']=$_SESSION['admin_dbname'];
	$_SESSION['notes']='';
}

// Reset Delegated Account

if ( isset($_SESSION['delegated_id']) && $_SESSION['id'] == $_SESSION['delegated_id'] )
{
        $_SESSION['username'] = $_SESSION['username'];
        $_SESSION['type']=$_SESSION['delegated_type'];
        $_SESSION['dbname']=$_SESSION['delegated_dbname'];
	$_SESSION['notes']='';
}

// Switch to active Profile

$sql = "SELECT current_profile_no FROM humans WHERE id = '" . $_SESSION['id'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $_SESSION['profile'] = $row['current_profile_no'];
}

header("Location: $redirect_url");

?>
