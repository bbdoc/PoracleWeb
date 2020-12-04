<?php
   session_start();
   $conn = new mysqli($dbhost, $dbuser, $dbpass, $_SESSION['dbname']);
?>
