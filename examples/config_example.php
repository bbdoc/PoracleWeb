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

// Discord Configuration
$redirect_url="";
$discordBotClientId = "";
$discordBotClientSecret = "";

// Admin User
$admin_id   = "";

// Image Repository
$imgUrl="https://raw.githubusercontent.com/whitewillem/PogoAssets/resized/no_border/";




// ALL SETTINGS AS FROM HERE ARE OPTIONAL

// Quests Options

# Mons pokemons will be extracted from DB.
# If you need other pokemons to added, use this setting.
# List all Pokemon IDs separated by commas
$additional_quest_mons="";


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

