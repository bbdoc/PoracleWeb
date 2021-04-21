<?php

if ( $disable_mons == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
		<div class="container-fluid col-lg-8 col-md-12">
                <?php echo @$admin_alarm; ?>

                    <!-- Profile Settings Modal -->
		    <?php include "./modal/profile_settings_modal.php"; ?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW MONSTER ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php
                        $clean_0_checked = "";
                        $clean_1_checked = "";
                        if ($all_mon_cleaned == "1") {
                            $clean_1_checked = 'checked';
                        } else {
                            $clean_0_checked = 'checked';
                        }

                    ?>

                    <form action='./actions/monsters.php' method='POST'>

                        <?php if (@$disable_location <> "True") { ?>
                        <div class="form-row align-items-center">
			    <div class="col-sm-12 my-1">
				<div class="input-group">
                                    <div class="btn-group btn-group-toggle ml-1" data-toggle="buttons" style="width:100%;">
				    <label class="btn btn-secondary">
					<input type="radio" name="use_areas" id="use_areas" value="areas" checked onclick="areas()"><?php echo i8ln("Use Areas"); ?>
				    </label>
                                    <label class="btn btn-secondary mr-2">
                                        <input type="radio" name="use_areas" id="use_areas" value="distance" onclick="areas()"><?php echo i8ln("Set Distance"); ?>
                                    </label>
                                    </div>
                                </div>
				<div class="input-group mt-2">
                                    <input type="number" id='distance' name='distance' value='0' min='0' style="display:none;"
                                        class="form-control text-center">
                                    <div class="input-group-append" id="distance_label" style="display:none;">
                                        <span class="input-group-text"><?php echo i8ln("meters"); ?></span>
                                    </div>
                                </div>
                            </div>
			</div>
			<?php } else { ?>
                           <input type="hidden" id='distance' name='distance' value='0' min='0'>
                        <?php } ?>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
					    &nbsp;&nbsp;&nbsp;&nbsp;<?php echo i8ln("IV"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_iv' name='min_iv' size=1 value='-1' min='-1' max='100'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
                                    <input type='number' id='max_iv' name='max_iv' size=1 value='100' min='-1' max='100'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
					    &nbsp;&nbsp;&nbsp;<?php echo i8ln("CP"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_cp' name='min_cp' size=1 value='0' min='0' max='9000'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
                                    <input type='number' id='max_cp' name='max_cp' size=1 value='9000' min='0'
                                        max='9000' class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text">&nbsp;&nbsp;&nbsp;<?php echo i8ln("LVL"); ?>&nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <input type='number' id='min_level' name='min_level' size=1 value='0' min='0'
                                        max='50' class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
                                    <input type='number' id='max_level' name='max_level' size=1 value='40' min='0'
                                        max='50' class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("Weight"); ?></div>
                                    </div>
                                    <input type='number' id='min_weight' name='min_weight' size=2 value='0' min='0'
                                        max='9000000' class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("MIN"); ?></div>
                                    </div>
                                    <input type='number' id='max_weight' name='max_weight' size=4 value='9000000'
                                        min='0' max='9000000' class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("MAX"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<label><?php echo i8ln("MIN STATS"); ?></label>
                                <div class="input-group">
                                    <input type='number' id='atk' name='atk' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                                    </div>
                                    <input type='number' id='def' name='def' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                                    </div>
                                    <input type='number' id='sta' name='sta' size=1 value='0' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("STA"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<label><?php echo i8ln("MAX STATS"); ?></label>
                                <div class="input-group">
                                    <input type='number' id='max_atk' name='max_atk' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<div class="input-group-text"><?php echo i8ln("ATK"); ?></div>
                                    </div>
                                    <input type='number' id='max_def' name='max_def' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("DEF"); ?></span>
                                    </div>
                                    <input type='number' id='max_sta' name='max_sta' size=1 value='15' min='0' max='15'
                                        class="form-control text-center">
                                    <div class="input-group-append">
					<span class="input-group-text"><?php echo i8ln("STA"); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<label><?php echo i8ln("PvP Great"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("MIN Rank"); ?></div>
                                    </div>
                                    <input type='number' id='great_league_ranking' name='great_league_ranking' size=1
				        value='<?php echo $MaxRank; ?>' min='0' max='<?php echo $MaxRank; ?>' class="form-control text-center">
                                    <div class="input-group-prepend">
					<span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                                    </div>
                                    <input type='number' id='great_league_ranking_min_cp'
                                        name='great_league_ranking_min_cp' size=1 value='<?php echo $GreatMinCP; ?>' min='<?php echo $GreatMinCP; ?>' max='4096'
                                        class="form-control text-center">
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
				<label><?php echo i8ln("PvP Ultra"); ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("MIN Rank"); ?></div>
                                    </div>
                                    <input type='number' id='ultra_league_ranking' name='ultra_league_ranking' size=1
                                        value='<?php echo $MaxRank; ?>' min='0' max='<?php echo $MaxRank; ?>' class="form-control text-center">
                                    <div class="input-group-prepend">
					<span class="input-group-text"><?php echo i8ln("MIN CP"); ?></span>
                                    </div>
                                    <input type='number' id='ultra_league_ranking_min_cp'
                                        name='ultra_league_ranking_min_cp' size=1 value='<?php echo $UltraMinCP; ?>' min='<?php echo $UltraMinCP; ?>' max='4096'
                                        class="form-control text-center">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
					    <div class="input-group-text"><?php echo i8ln("Gender"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_0" value="gender_0" checked><?php echo i8ln("All"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_1" value="gender_1"><?php echo i8ln("Male"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
					<input type="radio" name="gender" id="gender_2" value="gender_2"><?php echo i8ln("Female"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
					    <div class="input-group-text"><?php echo i8ln("Clean"); ?></div>
                                        </div>
                                    </div>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_0" value="clean_0"
                                            <?php echo $clean_0_checked; ?>>
					<?php echo i8ln("No"); ?>
                                    </label>
                                    <label class="btn btn-secondary">
                                        <input type="radio" name="clean" id="clean_1" value="clean_1"
                                            <?php echo $clean_1_checked; ?>>
					<?php echo i8ln("Yes"); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($allowed_templates["mons"])) {
                            echo '<div class="form-row align-items-center">
                                <div class="col-sm-12 my-1">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Template</div>
                                            </div>
                                        </div>';
                                        foreach ( $allowed_templates["mons"]as $key => $name ) {
                                            echo '<label class="btn btn-secondary">';
                                            echo '<input type="radio" name="template" id="' . $key . '" value="' . $key . '">';
                                            echo $name . '</label>';
                                        }
                                    echo '</div>
                                </div>
                            </div>';
                        } ?>

                        <hr>

                        <div class='searchmons text-center'>
                            <ul>
                                <li><input type='checkbox' name='mon_0' id='mon_0' />
                                    <label for='mon_0' style='padding:15px;' class='text-uppercase'>
					<?php echo i8ln("Apply to all Pokémon"); ?>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="alert alert-info alert-dismissible fade show" role="alert" id='dvAlertTypeAll'>
			    <?php echo i8ln("Type"); ?> <strong>ALL</strong> <?php echo i8ln("to display all Pokémon"); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

			<!-- Add Search Box -->
                        <input type='hidden' id='search_type' value='mon'>
                        <div class='mb-3' id='dvSearchBox'>
			    <input type='text' class='form-control form-control-lg' id='search' placeholder='<?php echo i8ln("Search") ?>'>
                        </div>

                        <div class='searchmons text-center' id='dvMonsList'>
                            <ul>
                                <!-- Add Empty Div to be used by Ajax to display results -->
                                <div id='display'></div>

                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_mon' value='<?php echo i8ln("Submit"); ?>'>
                            <a href='<?php echo $redirect_url ?>'>
				<button type="button" class="btn btn-secondary"><?php echo i8ln("Cancel"); ?></button>
                            </a>
                        </div>

                    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>

</html>

