<?php

// DB configuration
// Note that you can set multiple values in dbname if using multiple DBs
// $dbname should then be a comma separated list of values
// Users should register to only one DB or tool will redirect them randomly

$dbhost     = getenv("PORACLE_DB_HOST") ?: "127.0.0.1";
$dbname     = getenv("PORACLE_DB_DATABASE");
$dbuser     = getenv("PORACLE_DB_USERNAME");
$dbpass     = getenv("PORACLE_DB_PASSWORD");
$dbport     = getenv("PORACLE_DB_PORT") ?: "3306";

// Scanner DB Configuration (scan_dbtype should be MAD or RDM)

$scan_dbtype     = getenv("SCANNER_DB_TYPE") ?: "MAD";
$scan_dbhost     = getenv("SCANNER_DB_HOST") ?: "127.0.0.1";
$scan_dbname     = getenv("SCANNER_DB_DATABASE") ?: "";
$scan_dbuser     = getenv("SCANNER_DB_USERNAME") ?: "";
$scan_dbpass     = getenv("SCANNER_DB_PASSWORD") ?: "";
$scan_dbport     = getenv("SCANNER_DB_PORT") ?: "3306";

// Enable Disable Elements

$disable_areas       = getenv("DISABLE_AREAS") ?: "False";
$disable_location    = getenv("DISABLE_LOCATION") ?: "False";
$disable_nominatim   = getenv("DISABLE_NOMINATIM") ?: "False";
$disable_mons        = getenv("DISABLE_MONS") ?: "False";
$disable_raids       = getenv("DISABLE_RAIDS") ?: "False";
$disable_quests      = getenv("DISABLE_QUESTS") ?: "False";
$disable_invasions   = getenv("DISABLE_INVASIONS") ?: "False";
$disable_lures       = getenv("DISABLE_LURES") ?: "False";
$disable_profiles    = getenv("DISABLE_PROFILES") ?: "False";

$site_is_https       = getenv("SITE_IS_HTTPS") ?: "True";

// Telegram Login

$enable_telegram    = getenv("ENABLE_TELEGRAM") ?: "False";
$telegram_bot       = getenv("TELEGRAM_BOT") ?: "MyBot_bot";

// PORACLE API

$api_address       = getenv("API_ADDRESS") ?: "http://127.0.0.1:4201";
$api_secret        = getenv("API_SECRET") ?: "MySecret";

// Admin User

$admin_id                = getenv("ADMIN_ID") ?: "";
$admin_disable_userlist  = getenv("ADMIN_DISABLE_USERLIST") ?: "False";


// Discord Configuration

$redirect_url           = getenv("REDIRECT_URL") ?: "";
$discordBotClientId     = getenv("DISCORD_BOT_CLIENT_ID") ?: "";
$discordBotClientSecret = getenv("DISCORD_BOT_CLIENT_SECRET") ?: "";

// Language Settings

$allowed_languages      = getenv("ALLOWED_LANGUAGES") ?: "en";

// Image Repository
$imgUrl                 = getenv("IMG_URL") ?: "https://raw.githubusercontent.com/whitewillem/PogoAssets/resized/no_border/";

// Other Configuration Items

$max_pokemon            = getenv("MAX_POKEMON") ?: "721";
$custom_title           = getenv("CUSTOM_TITLE") ?: "";
$register_command       = getenv("REGISTER_COMMAND") ?: "!poracle";
$location_command       = getenv("LOCATION_COMMAND") ?: "!location";

// Quests Options

# Mons pokemons will be extracted from DB.
# If you need other pokemons to added, use this setting.
# List all Pokemon IDs separated by commas
$additional_quest_mons = getenv("ADDITIONAL_QUEST_MONS") ?: "";

// Debug Mode (True/False)
$debug                = getenv("DEBUG") ?: 'False';

// Google Analytics
$gAnalyticsId        = getenv("GOOGLE_ANALYTICS_ID") ?: "";

