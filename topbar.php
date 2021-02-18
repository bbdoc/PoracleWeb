
<!-- Topbar -->

<nav class="navbar fixed-top navbar-expand navbar-dark topbar mb-4 static-top shadow"
    style="background-color: #000000;">

    <a class="navbar-brand" href="<?php echo $redirect_url; ?>"><?php echo $title; ?></a>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- Nav Item - User Information -->
	<li class="nav-item dropdown no-arrow" id="Dropdown">

            <a class="nav-link dropdown-toggle" href="#" data-toggle="modal" data-target="#profileSettingsModal">
               <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
	       <img class="img-profile rounded-circle" src="<?php echo $avatar ?>">
	    </a>

        </li>

        <!-- Nav Item - Profile Selector -->
        <li class="nav-item dropdown no-arrow">
	    <a href="#" class="nav-link dropdown-toggle" data-toggle="modal" data-target="#SwitchProfile" >
            <div class="numberCircle"><?php echo str_pad($_SESSION['profile'],2,0,STR_PAD_LEFT)?></div>
            </a>
        </li>
        <?php include "./modal/switch_profile.php"; ?>

        <div class="topbar-divider d-none d-sm-block"></div>

        <?php if (isset($_SESSION['admin_id'])) { ?>
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="./admin_tools.php" >
               <i class="fas fa-user-shield fa-fw"></i>
            </a>
        </li>
        <?php } ?>

        <!-- Nav Item - Logout -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="modal" data-target="#logoutModal">
               <i class="fas fa-sign-out-alt fa-fw"></i>
            </a>
        </li>

    </ul>

</nav>

<!-- End of Topbar -->
