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

            <?php 

            if ($_SESSION['profile_name'] <> i8ln("Default")) {
               echo i8ln("Active Profile is shown in green below")."<br>";
	       echo i8ln("Choose Profile to view or activate")."</center>";
	       echo "<hr>";
	    }

            // Check Currently Active Profile

            $sql = "SELECT current_profile_no FROM humans WHERE id = '" . $_SESSION['id'] . "'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) { $active_profile = $row['current_profile_no']; }

            // Check User's existing Profiles
            $sql = "SELECT profile_no, name, area, latitude, longitude, active_hours FROM profiles WHERE id = '" . $_SESSION['id'] . "' ORDER BY profile_no";
            $result = $conn->query($sql);
            
            echo "<div id='profile' class='areasform text-uppercase text-center'>";
            echo "<form action='./actions/switch_profile.php' method='POST'>";

	    echo "<ul style='padding-left: 0;'>\n";

	    while ($row = $result->fetch_assoc()) {
                       if ($_SESSION['profile'] == $row['profile_no']) { $checked="checked"; }  else { $checked=""; }
                       if ($active_profile == $row['profile_no']) { $active="background-color: #4CAF50;"; }  else { $active=""; }
		       echo "<li style='width:90%; margin:0; align:center;'>";
		       echo "<input type='radio' name='profile' value=".$row['profile_no']." id='profile_".$row['profile_no']."' $checked/>\n";
		       echo "<label for='profile_".$row['profile_no']."' style='$active'>";
		       echo "<font style='font-size:12px;'>".$row['name']."</font>";
		       echo "</label>\n";
		       echo "</li><br>\n";
	    }

            echo "</ul>\n";
            echo "</div>";

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


                    <div class="modal fade" id="DeleteProfile" tabindex="-1" role="dialog"
                        aria-labelledby="DeleteProfile" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="DeleteLocationModalTitle">
                                        <?php echo i8ln("Delete Profile"); ?>
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <?php echo i8ln("This will delete this Profile"); ?><br>
                                    <?php if ( $_SESSION['profile'] <> 1) { echo i8ln("AND ALL ASSOCIATED TRACKINGS")."!<br>"; } ?><br>
                                    <?php echo i8ln("Are you sure?"); ?>
                                </div>
                                <div class="modal-footer">
                                    <a href="./actions/switch_profile.php?action=delete"
                                        class="btn btn-danger"><?php echo i8ln("DELETE"); ?></a>
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal"><?php echo i8ln("CANCEL"); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>


