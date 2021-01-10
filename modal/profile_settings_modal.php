<?php
?>
<div class="modal fade" id="profileSettingsModal" tabindex="-1" role="dialog"
    aria-labelledby="profileSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <?php $avatar = $_SESSION['avatar']; ?>
		<div>
		   <img src='./img/<?php echo rtrim($_SESSION['type'], ':user');?>.png' style='border-radius: 50%; width:40px;'>
                   <img src='<?php echo $avatar; ?>' style='border-radius: 50%; width:40px;'>
                </div>
		<h5 class="modal-title m-2" id="profileSettingsModalLongTitle"><?php echo i8ln("Settings"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
	    </div>
	    <?php echo @$admin_alarm; ?>

            <?php 
               if ($_SESSION['type']=="discord:user" && !isset($admin_alarm) && $enable_telegram == "True") {
                  echo "<center>";
                  echo "<a href='migrate.php'>";
                  echo "<button type='button' class='btn btn-primary mt-2' style='width:95%;'>";
                  echo i8ln("Migrate")." Discord <i class='fas fa-arrow-circle-right'></i> Telegram</i>"; 
                  echo "</button>";
                  echo "</a>";
                  echo "</center>";
	       }
            ?>

            <?php 

               if (isset($allowed_languages)) {
                  echo "<div class='flags'>";
                  $languages = explode(",", $allowed_languages);
                  foreach ($languages as &$language) {
                     echo "<a href='set_language.php?lng=$language'><img src='./img/$language.png' style='width:50px; height:50px;'></a>";
	          }
	          echo "</div>";
	       }

               if ( isset($subs_enable) && $subs_enable == 'True' ) {
                       include "subs_validity.php";
               }

            ?>
            <form action='./form_action.php' method='POST'>
                <div class="modal-body">
                    <table class="table table-borderless text-center" style="margin: auto; width: 90% !important;">
                        <tbody>
                            <tr>
				<th scope="row"><?php echo i8ln("Alerts"); ?></th>
                                <td><input type="checkbox" name="alerts_toggle" id="alerts_toggle" <?php 
                                if ($enabled == "1") {
                                    echo "checked";
                                } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="row">Location</th>
                                <td><input type="checkbox" name="location_toggle" id="location_toggle" <?php 
                                    // if ($latitude != "0.0000000000" && $longitude != "0.0000000000") {
                                    //     echo "checked";
                                    // } ?> disabled data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                            </tr> -->
                            <tr>
				<th scope="row"><?php echo i8ln("ALL Monters Cleaning"); ?></th>
                                <td><input type="checkbox" name="pokes_clean_toggle" id="pokes_clean_toggle" <?php 
                                    if ($all_mon_cleaned == "1") {
                                        echo "checked";
                                    } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                            </tr>
                            <tr>
				<th scope="row"><?php echo i8ln("ALL Raid/Egg Cleaning"); ?></th>
                                <td><input type="checkbox" name="re_clean_toggle" id="re_clean_toggle" <?php 
                                    if ($all_raid_cleaned == "1") {
                                        echo "checked";
                                    } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                            </tr>
                            <tr>
                                <?php
                                if (@$disable_quests <> "True") {
                                ?>
				<th scope="row"><?php echo i8ln("ALL Quests Cleaning"); ?></th>
                                <td><input type="checkbox" name="quests_clean_toggle" id="quests_clean_toggle" <?php 
                                    if ($all_quest_cleaned == "1") {
                                        echo "checked";
                                    } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                                <?php
                                }
                                ?>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="type" name="action" value="profile_settings">
                    <button type="submit" name='update' value='Update' class="btn btn-primary">
			<?php echo i8ln("Save changes"); ?>
                    </button>
		    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo i8ln("Close"); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
