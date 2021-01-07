<?php

include "./config.php";
include "./db_connect.php";
include "./functions.php";

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

echo $_SESSION['dbname'];
$conn = new mysqli($dbhost.":".$dbport, $dbuser, $dbpass, $_SESSION['dbname']);
$sql = "select id, name, type FROM humans WHERE id = '".$search_id."'"; 
$result = $conn->query($sql);

if ($result->num_rows == 0) {
	header("Location: $redirect_url?return=user_not_found");
	exit();
}

while ($row = $result->fetch_assoc()) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['type']=$row['type'];
}

echo $$_SESSION['id'];
echo $$_SESSION['username'];

// Reset Admin Account

if ( $_SESSION['id'] == $_SESSION['admin_id'] )
{ 
	$_SESSION['username'] = $_SESSION['username'];
        $_SESSION['type']=$_SESSION['admin_type'];
        $_SESSION['dbname']=$_SESSION['admin_dbname'];
}

header("Location: $redirect_url");

?>
