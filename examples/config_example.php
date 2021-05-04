
<?php

// DB configuration
// Note that you can set multiple values in dbname if using multiple DBs
// $dbname should then be a comma separated list of values
// Users should register to only one DB or tool will redirect them randomly

$dbhost     = "127.0.0.1";
$dbname     = "";
$dbuser     = "";
$dbpass     = "";
$dbport     = "3306";

// Scanner DB Configuration (scan_dbtype should be MAD or RDM)

$scan_dbtype     = "MAD";
$scan_dbhost     = "127.0.0.1";
$scan_dbname     = "";
$scan_dbuser     = "";
$scan_dbpass     = "";
$scan_dbport     = "3306";

// Enable Disable Elements

$disable_profiles    = "False";
$disable_areas       = "False";
$disable_location    = "False";
$disable_nominatim   = "False";
$disable_mons        = "False";
$disable_raids       = "False";
$disable_quests      = "False";
$disable_invasions   = "False";
$disable_lures       = "False";
$disable_nests       = "False";
$disable_geomap      = "False";

$site_is_https       = "True";

// To use different allowed templates for different areas you can use below
// code block. 
// if ($_SERVER['HTTP_HOST'] == 'area1.example.com') {
//      $allowed_templates = []; // different set of templates
// } else if ($_SERVER['HTTP_HOST'] == 'area2.example.com') {
//      $allowed_templates = []; // different set of templates
// }
// Disable template options by removing or commenting below code block.
$allowed_templates = [
	"mons" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	],
	"raids" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	],
	"eggs" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	],
	"quests" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	],
	"invasions" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	],
	"lures" => [
	        1 => "Template 1",
	        2 => "Template 2",
	        3 => "Template 3",
		4 => "Template 4",
		"Named_Template" => "Template NAME"
	]
];

// Telegram Login

$enable_telegram    = "False";
$telegram_bot       = "MyBot_bot";

// PORACLE API

$api_address="http://127.0.0.1:4201";
$api_secret="MySecret";

// Donation Pages

#$paypalUrl = "";
#$patreonUrl = "";

// Custom Page

#$custom_page_name    = "";
#$custom_page_url     = "";
#$custom_page_icon    = "fas fa-globe-europe";    # Any Free Icon from https://fontawesome.com/icons (Use Full Class)
#$custom_profile_msg  = "";

// Admin User

$admin_id                = "";
$admin_disable_userlist  = "False";


// Discord Configuration

$redirect_url="";
$discordBotClientId = "";
$discordBotClientSecret = "";

// Language Settings

$allowed_languages="en,fr";

// Image Repository 
$imgUrl="https://raw.githubusercontent.com/whitewillem/PogoAssets/resized/no_border/";

// Other Configuration Items

$max_pokemon="721";
#$custom_title="";
$register_command="!poracle";
$location_command="!location";

// Quests Options

# Mons pokemons will be extracted from DB.
# If you need other pokemons to added, use this setting.
# List all Pokemon IDs separated by commas
$additional_quest_mons="";

// Debug Mode (True/False)
$debug='False';

// Google Analytics
$gAnalyticsId = "";

