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

// If you don't want to use Discord but ONLY use Telegram
// If you use both you will be able to set those on Server Settings page

#$enable_telegram = "True";
#$enable_discord = "False";
#$telegram_bot = "YourBotName";
#$telegram_bot_name = "YourBotToken";

// Admin User
$admin_id   = "";


// ALL SETTINGS AS FROM HERE ARE OPTIONAL

// Quests Options

# Mons pokemons will be extracted from DB.
# If you need other pokemons to added, use this setting.
# List all Pokemon IDs separated by commas
$additional_quest_mons="";

