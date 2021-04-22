
<table style="table-layout: fixed; width: 100%;">
   <tr align=center>
      <?php if (@$disable_mons <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=pokemon">
         <button type="button" class="btn btn-dark w-100">
	 <img src="img/nav/mons.png" style="width:22px;height:22px;filter: grayscale(100%);">
	 </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_raids <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=raid">
         <button type="button" class="btn btn-dark w-100">
         <img src="img/nav/raid.svg" style="width:22px;height:22px;filter: brightness(100%);">
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_quests <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=quest">
         <button type="button" class="btn btn-dark w-100">
         <img src="img/nav/quest.png" style="width:22px;height:22px;filter: brightness(0%) invert(1);">
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_invasions <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=invasion">
         <button type="button" class="btn btn-dark w-100">
         <img src="img/nav/invasion.png" style="width:22px;height:22px;filter: grayscale(100%);">
         </button>
         </a>
      </td>
      <?php } ?>
      <?php if (@$disable_lures <> "True") { ?>
      <td>
         <a href="<?php echo $redirect_url; ?>?type=display&page=lure">
         <button type="button" class="btn btn-dark w-100">
         <img src="img/nav/lure.png" style="width:22px;height:22px;filter: grayscale(100%);">
         </button>
         </a>
      </td>
      <?php } ?>
   </tr>
</table>
<hr>
