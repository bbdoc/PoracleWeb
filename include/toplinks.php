
<?php

$sql = "select count(*) count FROM monsters WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_mon_tracked = $row['count']; }

$sql = "select count(*) count FROM raid WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_raid_tracked = $row['count']; }

$sql = "select count(*) count FROM egg WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_raid_tracked += $row['count']; }

$sql = "select count(*) count FROM quest WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_quest_tracked = $row['count']; }

$sql = "select count(*) count FROM invasion WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_invasion_tracked = $row['count']; }

$sql = "select count(*) count FROM lures WHERE id = '" . $_SESSION['id'] . "' AND profile_no = '" . $_SESSION['profile'] . "'";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) { $num_lure_tracked = $row['count']; }

?>

<table style="table-layout: fixed; width: 100%;">
   <tr align=center>
      <?php if (@$disable_mons <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=pokemon">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/mons.png" style="width:22px;height:22px;filter: grayscale(100%);">
         <br><font color=white><?php echo $num_mon_tracked; ?></font>
	 </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_raids <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=raid">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/raid.svg" style="width:22px;height:22px;filter: brightness(100%);">
         <br><font color=white><?php echo $num_raid_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_quests <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=quest">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/quest.png" style="width:22px;height:22px;filter: brightness(0%) invert(1);">
         <br><font color=white><?php echo $num_quest_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_invasions <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=invasion">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/invasion.png" style="width:22px;height:22px;filter: grayscale(100%);">
         <br><font color=white><?php echo $num_invasion_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_lures <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=lure">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/lure.png" style="width:22px;height:22px;filter: grayscale(100%);">
         <br><font color=white><?php echo $num_lure_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
   </tr>
</table>
<hr>
