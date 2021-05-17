
<nav class="navbar fixed-top navbar-expand navbar-dark bg-dark" id="header">
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a data-trigger="#navbar_main">
          <i class="fas fa-bars nav-icon"></i>
        </a>
      </li>
    </ul>
  </div>

  <a class="navbar-brand" href=".">
    <?php echo $title; ?>&nbsp;
    <?php if ( $_SESSION['number_of_profiles'] > 1) { ?>
    <span class="badge badge-info"><?php echo $_SESSION['profile_name']; ?></span>
    <?php } ?>
  </a>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto" style="position:absolute;right:1%">
      <li class="nav-item dropdown">
        <?php 
           $language_count=0;
           $languages = explode(",", $allowed_languages);
	   foreach ($languages as &$language) { $language_count++; } 
	   if ( $language_count > 1) {
        ?>
	   <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	   <img src="img/<?php echo $_SESSION['locale']; ?>.png" style="height:35px;width:35px">
           </a>
        <?php } ?>
	<div class="dropdown-menu language-dropdown" aria-labelledby="languageDropdown">

        <?php
        if (isset($allowed_languages)) {
           $languages = explode(",", $allowed_languages);
	   foreach ($languages as &$language) { 
              if ($language <> $_SESSION['locale']) { 
        ?>

         <a class="dropdown-item" href="actions/set_language.php?lng=<?php echo $language; ?>">
            <img src="img/<?php echo $language; ?>.png" style="height:30px;width:30px"> <?php echo strtoupper(i8ln("$language")); ?>
         </a>
         <div class="dropdown-divider"></div>

	<?php 
	   } 
	      } 
	         } 
        ?>

        </div>
      </li>
    </ul>
  </div>
</nav>

<nav id="navbar_main" class="offcanvas navbar navbar-light bg-light border">
  <ul class="navbar-nav mr-auto" style="margin-top: -9px;">
    <div class="accordion" id="accordion-test">

      <div class="card z-depth-0 bordered">
	<div class="card-header card-header-navbar" id="heading-pages" class="heading-title">
           <table>
              <tr>
                 <td width=100%>
	            <img class="img-profile rounded-circle" src="<?php echo $avatar ?>">
		    <span class="mr-2 d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?>
                    </span>
                 </td>
		 <td>
		    <form action='./actions/profile.php' method='POST'>
                    <input type="hidden" id="type" name="action" value="alarms_settings">
                    <input onChange="this.form.submit()" type="checkbox" name="alerts_toggle" id="alerts_toggle" <?php if ($enabled == "1") { echo "checked"; } ?>
		     data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-size="sm">
                    </form>
		 </td>
	      </tr>
           </table>

	   <?php if ( isset($custom_profile_msg) ) { ?>
              <button type='button' class='btn mt-3' style='width:100%; background-color:white; border: 2px solid darkgreen; padding:0px;'>
              <font color=darkgreen size=2><?php echo $custom_profile_msg; ?></font>
              </button>
           <?php } ?>

	   <?php if ($_SESSION['type']=="discord:user" && !isset($admin_alarm) && $enable_telegram == "True") { ?>
	   <a href='<?php echo $redirect_url; ?>?type=display&page=migrate'>
              <button type='button' class='btn mt-1' style='width:100%; background-color:white; border: 2px solid darkblue; padding:0px;'>
	      <font color=darkblue size=2><?php echo i8ln("Migrate"); ?> Discord <i class='fas fa-arrow-circle-right'></i> Telegram</i></font>
	      </button>
              </a>
           <?php } ?>


        </div>
      </div>

      <div class="card z-depth-0 bordered">
        <div class="card-header card-header-navbar" id="heading-pages" class="heading-title" style="margin-top:-5px;margin-bottom:-5px;">
        <li class="nav-item dropdown no-arrow">
	    <a class="nav-link dropdown-toggle" href="<?php echo $redirect_url; ?>">
               <font color="grey"><i class="fas fa-tachometer-alt"></i></font> <?php echo i8ln('Dashboard'); ?>
            </a>
        </li>
        </div>
      </div>


      <div class="card z-depth-0 bordered">
        <div class="card-header card-header-navbar" id="heading-pages" class="heading-title" data-toggle="collapse" data-target="#collapse-pages" aria-expanded="false" aria-controls="collapse-pages">
          <h6 class="heading-title">
            <i class="fas fa-search"></i></i>&nbsp;&nbsp;<?php echo i8ln('Trackings'); ?>
          </h6>
        </div>
        <div id="collapse-pages" class="collapse" aria-labelledby="heading-pages" data-parent="#accordion-test">
          <div class="card-body">
    
            <?php if (@$disable_mons <> "True") { ?>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=pokemon">
               <img src="img/nav/mons.png" style="width:22px;height:22px;filter: grayscale(100%);"> <?php echo i8ln('Pokémon'); ?>
              </a>
            <?php } ?>

            <?php if (@$disable_raids <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=raid">
                <img src="img/nav/raid.svg" style="width:22px;height:22px;filter: brightness(40%);"> <?php echo i8ln('Raids'); ?>
              </a>
            <?php } ?>

            <?php if (@$disable_quests <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=quest">
                <img src="img/nav/quest.png" style="width:22px;height:22px;filter: grayscale(100%);"> <?php echo i8ln('Quests'); ?>
              </a>
            <?php } ?>

            <?php if (@$disable_invasions <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=invasion">
                <img src="img/nav/invasion.png" style="width:22px;height:22px;filter: grayscale(100%);"> <?php echo i8ln('Invasions'); ?>
              </a>
            <?php } ?>

            <?php if (@$disable_lures <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=lure">
                <img src="img/nav/lure.png" style="width:22px;height:22px;filter: grayscale(100%);"> <?php echo i8ln('Lures'); ?>
              </a>
	    <?php } ?>

            <?php if (@$disable_nests <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=nest">
                <img src="img/nav/nest.png" style="width:22px;height:22px;filter: grayscale(100%);"> <?php echo i8ln('Nests'); ?>
              </a>
            <?php } ?>

    
          </div>
        </div>
      </div>

      <div class="card z-depth-0 bordered">
        <div class="card-header card-header-navbar" id="heading-settings" data-toggle="collapse" data-target="#collapse-settings" aria-expanded="false" aria-controls="collapse-settings">
          <h6 class="heading-title">
            <i class="fas fa-cog"></i>&nbsp;&nbsp;<?php echo i8ln('Settings'); ?>
          </h6>
        </div>
        <div id="collapse-settings" class="collapse" aria-labelledby="heading-settings" data-parent="#accordion-test">
	  <div class="card-body">

              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=area">
                <i class="fas fa-map-marked-alt"></i>&nbsp;&nbsp; <?php echo i8ln('Areas & Location'); ?>
	      </a>
              <?php if (@$disable_profiles <> "True") { ?>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=profiles">
                <i class="fas fa-users"></i>&nbsp;&nbsp; <?php echo i8ln('Profiles'); ?>
	      </a>
              <?php }  ?>
	      <div class="dropdown-divider"></div>
              <a class="dropdown-item" style="position:relative;left:-3px;" href="<?php echo $redirect_url; ?>?type=display&page=cleaning">
                <i class="fas fa-eraser"></i>&nbsp;&nbsp; <?php echo i8ln('Cleaning'); ?>
              </a>

          </div>
        </div>
      </div>

      <?php if (isset($patreonUrl) || isset($paypalUrl)) { ?>
          <div class="card z-depth-0 bordered">
            <div class="card-header card-header-navbar" id="heading-donate" data-toggle="collapse" data-target="#collapse-donate" aria-expanded="false" aria-controls="collapse-donate">
              <h6 class="heading-title">
                <i class="fas fa-donate"></i>&nbsp;&nbsp;<?php echo i8ln('Donate'); ?>
              </h6>
            </div>
            <div id="collapse-donate" class="collapse" aria-labelledby="heading-donate" data-parent="#accordion-test">
              <div class="card-body">
      
                <?php if (isset($patreonUrl)) { ?>
                  <a class="dropdown-item" href="<?php echo $patreonUrl; ?>">
                    <i class="fab fa-patreon"></i> <?php echo i8ln('Patreon'); ?>
                  </a>
                  <?php
                  if (isset($paypalUrl)) { ?>
                    <div class="dropdown-divider"></div>
                    <?php
                  }
                } ?>
                <?php if (isset($paypalUrl)) { ?>
                  <a class="dropdown-item" href="<?php echo $paypalUrl; ?>">
                    <i class="fab fa-paypal"></i> <?php echo i8ln('PayPal'); ?>
                  </a>
                <?php } ?>
      
              </div>
            </div>
          </div>
      <?php } ?>

      <?php if ( isset($_SESSION['admin_id']) || isset($_SESSION['delegated_id']) || in_array($_SESSION['id'],$_SESSION['user_admins']) ) { ?>

          <div class="card z-depth-0 bordered">
            <div class="card-header card-header-navbar" id="heading-admin" data-toggle="collapse" data-target="#collapse-admin" aria-expanded="false" aria-controls="collapse-admin">
              <h6 class="heading-title">
                <i class="fas fa-user-shield fa-fw"></i> <?php echo i8ln('Admin Tools'); ?>
              </h6>
            </div>
            <div id="collapse-admin" class="collapse" aria-labelledby="heading-admin" data-parent="#accordion-test">
              <div class="card-body">
		  <?php if ( isset($_SESSION['admin_id']) || in_array($_SESSION['id'],$_SESSION['user_admins']) ) { ?>
		  <a class="dropdown-item" href="<?php echo $redirect_url; ?>?type=display&page=manage_users">
                    <i class="fas fa-users-cog"></i> <?php echo i8ln('Users Management'); ?>
		  </a>
                  <div class="dropdown-divider"></div>
		  <?php } ?>
                  <?php if ( isset($_SESSION['admin_id']) || $_SESSION['delegated_count'] > 0 ) { ?>
                  <a class="dropdown-item" href="<?php echo $redirect_url; ?>?type=display&page=manage_channels">
                    <i class="fas fa-bullhorn"></i> <?php echo i8ln('Channel Management'); ?>
                  </a>
                  <?php } ?>
                  <?php if ( isset($_SESSION['admin_id']) ) { ?>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?php echo $redirect_url; ?>?type=display&page=server_settings">
                    <i class="fas fa-cogs"></i> <?php echo i8ln('Server Settings'); ?>
		  </a>
                  <?php } ?>
              </div>
            </div>
          </div>

      <?php } ?>

      <?php if ( isset($custom_page_name)) { ?>
      <div class="card z-depth-0 bordered">
        <div class="card-header card-header-navbar" id="heading-pages" class="heading-title" style="margin-top:-5px;margin-bottom:-5px;">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="<?php echo $custom_page_url; ?>">
               <i class="<?php echo $custom_page_icon; ?>"></i>&nbsp;&nbsp;<?php echo i8ln($custom_page_name); ?>
            </a>
        </li>
        </div>
      </div>
      <?php } ?>

      <div class="card z-depth-0 bordered">
	<div class="card-header card-header-navbar" id="heading-pages" class="heading-title" style="margin-top:-5px;margin-bottom:-5px;">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="modal" data-target="#logoutModal" id="logout">
               <i class="fas fa-sign-out-alt fa-fw"></i> <?php echo i8ln('Logout'); ?>
            </a>
        </li>
        </div>
      </div>

    </div>


  </ul>
</nav>

<div class="screen-overlay"></div>
