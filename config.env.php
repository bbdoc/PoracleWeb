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

// Discord Configuration

$redirect_url           = getenv("REDIRECT_URL") ?: "";
$discordBotClientId     = getenv("DISCORD_BOT_CLIENT_ID") ?: "";
$discordBotClientSecret = getenv("DISCORD_BOT_CLIENT_SECRET") ?: "";

// If you don't want to use Discord but ONLY use Telegram
// If you use both you will be able to set those on Server Settings page

$enable_telegram        = getenv("ENABLE_TELEGRAM") ?: "";
$enable_discord         = getenv("ENABLE_DISCOR") ?: "";
$telegram_bot           = getenv("TELEGRAM_BOT") ?: "";

// Admin User

$admin_id                = getenv("ADMIN_ID") ?: "";

// Quests Options

# Mons pokemons will be extracted from DB.
# If you need other pokemons to added, use this setting.
# List all Pokemon IDs separated by commas

$additional_quest_mons = getenv("ADDITIONAL_QUEST_MONS") ?: "";

