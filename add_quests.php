<?php
include "./header.php";
if ( $disable_quests == "True" ) {
        header("Location: $redirect_url");
        exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<?php
    if ($gAnalyticsId != "") {
        echo '<!-- Google Analytics -->
            <script>
                window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
                ga("create", "' . $gAnalyticsId . '", "auto");
                ga("send", "pageview");
            </script>
            <script async src="https://www.google-analytics.com/analytics.js"></script>
            <!-- End Google Analytics -->';
    }
?>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title; ?> - Add Quests</title>

    <link rel="icon" type="image/x-icon" href="favicon.png" />

    <!-- Custom fonts for this template-->
    <link href="node_modules/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/custom-bootstrap.css?v=<?=time();?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css?v=<?=time();?>">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="search_mons.js?v=<?=time();?>"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("input[type='checkbox']").change(function() {
            var maxAllowed = 100;
            var cnt = $("input[type='checkbox']:checked").length;
            if (cnt > maxAllowed) {
                $(this).prop("checked", "");
                alert('Sorry, you cannot select more than ' + maxAllowed + ' Pokemons at a time!');
            }
        });
    });

    $(document).ready(function() {
        $(window).keydown(function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });

    </script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include "topbar.php" ?>

                <!-- Begin Page Content -->
		<div class="container-fluid col-lg-8 col-md-12">
                <?php echo @$admin_alarm; ?>

                    <!-- Profile Settings Modal -->
                    <?php include "./modal/profile_settings_modal.php"; ?>

                    <!-- Page Heading -->
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-secondary text-center" role="alert">
				<strong><?php echo i8ln("NEW QUEST ALARM"); ?></strong>
                            </div>
                        </div>
                    </div>

                    <form action='./actions/quests.php' method='POST'>

                        <?php if (@$disable_location <> "True") { ?>
                        <div class="form-row align-items-center">
                            <div class="col-sm-12 my-1">
                                <div class="input-group">
                                    <div class="input-group-prepend">
					<div class="input-group-text"><?php echo i8ln("Distance"); ?></div>
                                    </div>
                                    <input type="number" id='distance' name='distance' value='0' min='0'
                                        class="form-control text-center">
                                    <div class="input-group-append">
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
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">

                                    <?php

                                        if ($row['clean'] == 0) {
                                            $checked0 = 'checked';
                                        } else {
                                            $checked0 = '';
                                        }
                                        if ($row['clean'] == 1) {
                                            $checked1 = 'checked';
                                        } else {
                                            $checked1 = '';
                                        }
                                        $clean_0_checked = 0;
                                        $clean_1_checked = 0;
                                        if ($all_quest_cleaned == "1") {
                                            $clean_1_checked = 'checked';
                                        } else {
                                            $clean_0_checked = 'checked';
                                        }

                                        ?>
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

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Select Pokémon Quests you want to add"); ?> (<?php echo i8ln("Pokemons Currently available on Map"); ?>)
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $mons =  get_quest_mons();                                    
                                    foreach ($mons as &$mon) {
                                        $pokemon_name=get_mons($mon);  
                                        $mon_id=str_pad($mon, 3, "0", STR_PAD_LEFT);
                                    ?>
                                <li class='text-center'><input type='checkbox' name='mon_<?php echo $mon; ?>'
                                        id='mon_<?php echo $mon; ?>' />
                                    <label for='mon_<?php echo $mon; ?>'>
                                        <img src='<?php echo $imgUrl; ?>/pokemon_icon_<?php echo $mon_id; ?>_00.png' />
                                        <br><?php echo $mon_id; ?><br><?php echo $pokemon_name; ?>
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

			<!-- Add Search Box -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Or use search below to add another pokemon"); ?>
                                </li>
                            </ol>
                        </nav>

                        <input type='hidden' id='search_type' value='questmon'>
                        <div class='mb-3' id='dvSearchBox'>
			    <input type='text' class='form-control form-control-lg' id='search' placeholder='<?php echo i8ln("Search"); ?>'>
                        </div>

                        <div class='searchmons text-center' id='dvMonsList'>
                            <ul>
                                <!-- Add Empty Div to be used by Ajax to display results -->
                                <div id='display'></div>

                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
				    <?php echo i8ln("Select Quests Items you want to add"); ?>
                                </li>
                            </ol>
                        </nav>
                        <div class='selectionList'>
                            <ul>
                                <?php
                                    $items =  get_quest_items();
                                    foreach ($items as &$item) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='item_<?php echo $item; ?>'
                                        id='item_<?php echo $item; ?>' />
                                    <label for='item_<?php echo $item; ?>'>
                                        <img src='<?php echo $imgUrl; ?>/rewards/reward_<?php echo $item; ?>_1.png' />
                                    </label>
                                </li>
                                <?php
                                    }
                                    ?>
                            </ul>
                        </div>

                        <hr>

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo i8ln("Select Energy Rewards you want to add"); ?>
                                </li>
                            </ol>
                        </nav>

                        <div class='selectionList'>
			    <ul>
                                <li class='text-center'><input type='checkbox' name='energy_0'
                                        id='energy_0' />
                                    <label for='energy_0'>
                                        <img src='<?php echo $imgUrl; ?>/rewards/reward_mega_energy.png' />
					<br><br><?php echo i8ln("ALL"); ?>
                                    </label>
                                </li>
                                <?php
                                    $energy_rewards =  get_quest_energy();
                                    foreach ($energy_rewards as &$energy) {
                                    ?>
                                <li class='text-center'><input type='checkbox' name='energy_<?php echo $energy; ?>'
                                        id='energy_<?php echo $energy; ?>' />
                                    <label for='energy_<?php echo $energy; ?>'>
					<img src='<?php echo $imgUrl; ?>/rewards/reward_mega_energy_<?php echo $energy; ?>.png' />
					<?php $pokemon_name=get_mons($energy); ?>
                                        <br><?php echo str_pad($energy, 3, "0", STR_PAD_LEFT); ?><br><?php echo $pokemon_name; ?>
                                    </label>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>

                        <div class="float-right mb-3 mt-3">
			    <input class="btn btn-primary" type='submit' name='add_quest' value='<?php echo i8ln("Submit"); ?>'>
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

    <!-- Logout Modal-->
    <?php include "./modal/logout_modal.php"; ?>

    <!-- Bootstrap core JavaScript-->
    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="node_modules/jquery.easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

</body>

</html>

