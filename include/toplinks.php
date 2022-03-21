
<?php

$user_id = $_SESSION['id'];
$profile_id = $_SESSION['profile'];
$sql = "
   SELECT COUNT(*) AS 'Total', 'monsters' AS 'Type' FROM monsters WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'raid' AS 'Type' FROM raid WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'egg' AS 'Type' FROM egg WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'quest' AS 'Type' FROM quest WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'invasion' AS 'Type' FROM invasion WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'lures' AS 'Type' FROM lures WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'nests' AS 'Type' FROM nests WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
   UNION
   SELECT COUNT(*) AS 'Total', 'gym' AS 'Type' FROM gym WHERE id = '{$user_id}' AND profile_no = '{$profile_id}'
";

$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
   switch ($row['Type']) {
      case 'monsters':
         $num_mon_tracked = $row['Total'];
         break;
      case 'raid':
         $num_raid_tracked = $row['Total'];
         break;
      case 'egg':
         $num_raid_tracked += $row['Total'];
         break;
      case 'quest':
         $num_quest_tracked = $row['Total'];
         break;
      case 'invasion':
         $num_invasion_tracked = $row['Total'];
         break;
      case 'lures':
         $num_lure_tracked = $row['Total'];
         break;
      case 'nests':
         $num_nest_tracked = $row['Total'];
         break;
      case 'gym':
         $num_gym_tracked = $row['Total'];
         break;
   };
}

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
      <?php if (@$disable_nests <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=nest">
         <button type="button" class="btn btn-dark w-100">
         <img src="img/nav/nest.png" style="width:22px;height:22px;filter: brightness(0%) invert(1);">
         <br><font color=white><?php echo $num_nest_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_gyms <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=gym">
         <button type="button" class="btn btn-dark w-100">
         <img src="<?php echo "$uicons_gym/gym/0.png?"; ?>" style="width:22px;height:22px;filter: grayscale(100%);">
         <br><font color=white><?php echo $num_gym_tracked; ?></font>
         </button>
         </a>
      </td>
      <?php } ?>
   </tr>
</table>
<hr>
