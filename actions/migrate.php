<?php

   include "../config.php";
   include "../db_connect.php";

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

include "./action_error.php";

