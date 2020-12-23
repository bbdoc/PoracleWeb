<?php
?>
<div class="modal fade" id="profileSettingsModal" tabindex="-1" role="dialog"
    aria-labelledby="profileSettingsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action='./form_action.php' method='POST'>
                <div class="modal-header">
                    <?php $avatar = "https://cdn.discordapp.com/avatars/" . $_SESSION['id'] . "/" . $_SESSION['avatar'] . ".png"; ?>
                    <div><img src='<?php echo $avatar; ?>' style='border-radius: 50%; width:40px;'>
                    </div>
                    <h5 class="modal-title m-2" id="profileSettingsModalLongTitle">Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table class="table table-borderless text-center" style="margin: auto; width: 90% !important;">
                        <tbody>
                            <tr>
                                <th scope="row">Alerts</th>
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
                                <th scope="row">Monters Cleaning</th>
                                <td><input type="checkbox" name="pokes_clean_toggle" id="pokes_clean_toggle" <?php 
                                    if ($all_mon_cleaned == "1") {
                                        echo "checked";
                                    } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                            </tr>
                            <tr>
                                <th scope="row">Raid/Egg Cleaning</th>
                                <td><input type="checkbox" name="re_clean_toggle" id="re_clean_toggle" <?php 
                                    if ($all_raid_cleaned == "1") {
                                        echo "checked";
                                    } ?> data-toggle="toggle" data-onstyle="success" data-offstyle="danger"
                                        data-size="sm"></td>
                            </tr>
                            <tr>
                                <?php
                                if ($disable_quests <> "True") {
                                ?>
                                <th scope="row">Quests Cleaning</th>
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

                    <hr>

                    <?php

                    if ($latitude == "0.0000000000" && $longitude == "0.0000000000") {
                    ?>
                    <div class="alert alert-warning" role="alert">
                        Your Location is not set and cannot be set here!
                    </div>
                    <p>Please set it in discord using <code><?php echo $location_command; ?></code> command.</p>
                    <?php
                    } else if (isset($mapURL) && $mapURL <> "") {
                    ?>
                    <div class="alert alert-success" role="alert">
                        Your Location is set to: <?php echo round($latitude, 4); ?>, <?php echo round($longitude, 4); ?>
                    </div>

                    <?php
                        $mapURL = str_replace('#LAT#', $latitude, $mapURL);
                        $mapURL = str_replace('#LON#', $longitude, $mapURL);
                        ?>
                    <div class='text-center' >
                        <img src='<?php echo $mapURL; ?>' width=300>
                    </div>
                    <?php
                    }

                    ?>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="type" name="action" value="profile_settings">
                    <button type="submit" name='update' value='Update' class="btn btn-primary">
                        Save changes
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>