<?php

?>

<div class="modal fade" id="SwitchProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel"><?php echo i8ln("Switch Profile"); ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
	    <div class="modal-body">
	    <center>
            <?php echo i8ln("Active Profile is shown in green below"); ?><br>
	    <?php echo i8ln("Choose Profile to view or activate"); ?></center>
            <hr>

            <?php 

            // Check Currently Active Profile

            $sql = "SELECT current_profile_no FROM humans WHERE id = '" . $_SESSION['id'] . "'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { $active_profile = $row['current_profile_no']; }

            // Check User's existing Profiles
            $sql = "SELECT profile_no, name, area, latitude, longitude, active_hours FROM profiles WHERE id = '" . $_SESSION['id'] . "'";
            $result = $conn->query($sql);
            
	    if ( $result->num_rows == 0 ) {
		    echo "<center>";
		    echo i8ln("You currently don't have any profile configured").".<br>";
		    echo i8ln("Default Profile is being used").".<hr>";
		    echo i8ln("By clcking 'Create New Profile' below you will assign a name to this default Profile").".<br><br>";
		    echo i8ln("You will then have the option to create additional Profiles").".<br>";
		    echo "</center>";
	    } else {
                    echo "<div id='profile' class='areasform text-uppercase text-center'>";
                    echo "<form action='./actions/switch_profile.php' method='POST'>";

                    echo "<ul>\n";
		    while ($row = $result->fetch_assoc()) {
                       if ($_SESSION['profile'] == $row['profile_no']) { $checked="checked"; }  else { $checked=""; }
                       if ($active_profile == $row['profile_no']) { $active="background-color: #4CAF50;"; }  else { $active=""; }
                       echo "<li><input type='radio' name='profile' value=".$row['profile_no']." id='profile_".$row['profile_no']."' $checked/>\n";
		       echo "<label for='profile_".$row['profile_no']."' style='width:350px; $active'><font style='font-size:12px;'>";
		       echo $row['name'];
		       echo "</font></label>\n";
                       echo "</li>\n";
                    }
                    echo "</ul>\n";
		    echo "</div>";
            }

            ?>

            </div>
            <div class="modal-footer">
		<button type='submit' name='view' value='View' class='btn btn-primary'><?php echo i8ln('View'); ?></button>
		<button type='submit' name='activate' value='Activate' class='btn btn-primary'><?php echo i8ln('Activate'); ?></button>
		<button type='button' class='btn btn-secondary' data-dismiss='modal'><?php echo i8ln('Close'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="AddProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo i8ln("Create Profile"); ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
	    <center>
            <form action='./actions/switch_profile.php' method='POST'>
            <?php
	    $sql = "SELECT profile_no, name, area, latitude, longitude, active_hours FROM profiles WHERE id = '" . $_SESSION['id'] . "'";
	    $result = $conn->query($sql);
	    if ( $result->num_rows == 0 ) {
		    echo i8ln("You currently don't have any profile configured").".<br>";
		    echo i8ln("Please Name default Profile");
	    } else {
		    echo i8ln("Choose the name of your new Profile");
	    }
            ?>

            </center>
            <hr>

            <div class="input-group mt-2">
                <input type="text" id='profile_name' name='profile_name' class="form-control text-center" autocomplete="off">
            </div>
 
            </div>
            <div class="modal-footer">
                <button type='submit' name='create' value='Create' class='btn btn-primary'><?php echo i8ln('Validate'); ?></button>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'><?php echo i8ln('Close'); ?></button>
            </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="RenameProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo i8ln("Rename Profile"); ?></h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <center>
            <form action='./actions/switch_profile.php' method='POST'>
	    <?php echo i8ln("Choose a New Profile Name").".<br>"; ?>
            </center>
            <hr>

            <div class="input-group mt-2">
                <input type="text" id='profile_name' name='profile_name' class="form-control text-center" autocomplete="off">
            </div>

            </div>
            <div class="modal-footer">
                <button type='submit' name='rename' value='rename' class='btn btn-primary'><?php echo i8ln('Validate'); ?></button>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'><?php echo i8ln('Close'); ?></button>
            </form>
            </div>
        </div>
    </div>
</div>

