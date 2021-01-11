<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PoracleWeb</title>
  <link rel="icon" type="image/x-icon" href="favicon.png" />
  <link rel="stylesheet" type="text/css" href="../css/style.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

</head>

<body style="background-color:#FFFFFF; color: grey;">
  <br>

  <?php

#  echo "<table>";
#      foreach ($_POST as $key => $value) {
#          echo "<tr>";
#          echo "<td>";
#          echo $key;
#          echo "</td>";
#          echo "<td>";
#          echo $value;
#          echo "</td>";
#          echo "</tr>";
#      }
#  echo "</table>";

  include "../config.php";
  include "../db_connect.php";

  ?>

  <center>
    <div style="max-width:90%">
      <font size=6 color="darkred">Oops...</font><br><br>
      <img src='img/homer.png' width=250><br><br>
      Looks like Something went wrong, you shouldn't be here.<br><br>
      Please get back to homepage:<br><br>
      <a href="<?php echo $redirect_url; ?>">
        <font size=5>PoracleWeb</font>
      </a>
    </div>
  </center>

  <?php


if (isset($_POST['migrate']) && $_POST['migration_type'] == "discord2telegram" ) {

  $source_id=$_POST['discord_id'];
  $target_id=$_POST['telegram_id'];
  $target_user=$_POST['telegram_user'];
  $target_avatar=$_POST['telegram_avatar'];
  $target_type="telegram:user";

  # DISABLE FOREIGN KEY CHECKS
  $sql = "SET foreign_key_checks = 0;";
  $result = $conn->query($sql);

  # UPDATE HUMANS TABLE
  $stmt = $conn->prepare("UPDATE humans set id = ?, type = ?, name = ? where id = ?;");
  $rs = $stmt->bind_param("ssss", $target_id, $target_type, $target_user, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE MONSTERS TABLE
  $stmt = $conn->prepare("UPDATE monsters set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE EGG TABLE
  $stmt = $conn->prepare("UPDATE egg set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE RAID TABLE
  $stmt = $conn->prepare("UPDATE raid set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE QUEST TABLE
  $stmt = $conn->prepare("UPDATE quest set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE INVASION TABLE
  $stmt = $conn->prepare("UPDATE invasion set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # UPDATE WEATHER TABLE
  $stmt = $conn->prepare("UPDATE weather set id = ? where id = ?;");
  $rs = $stmt->bind_param("ss", $target_id, $source_id);
  $rs = $stmt->execute();
  $stmt->close();

  # ENABLE FOREIGN KEY CHECKS
  $sql = "SET foreign_key_checks = 1;";
  $result = $conn->query($sql);

  # CONNECT NEW USER
  $_SESSION['id']=$target_id;
  $_SESSION['username']=$target_user;
  $_SESSION['avatar']=$target_avatar;
  $_SESSION['type']=$target_type;

  # UPDATE ADMIN ID
  unset($_SESSION['admin_id']);
  if (isset($admin_id)) {
        $admins = explode(",", $admin_id);
  }
  foreach ($admins as &$admin) {
    if ($_SESSION['id'] == $admin)
    {
        $_SESSION['admin_id'] = $_SESSION['id'];
        $_SESSION['admin_username'] = $_SESSION['username'];
        $_SESSION['admin_dbname'] = $_SESSION['dbname'];
        $_SESSION['admin_type'] = $_SESSION['type'];
     }
  }

  header("Location: $redirect_url?return=success_migrate&mig_source=Discord&mig_target=Telegram");
  exit();

}

?>
