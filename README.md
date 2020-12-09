# PoracleWeb

REQUIRES MYSQL !

This tool is an add-on to PoracleJS (https://github.com/KartulUdus/PoracleJS)

This is a first attempt at creating a Web Inteface do handle alarms configuration in PoracleJS.

Please not that I'm not a professional Web Developer, so the code might look ugly to some people, but it works. I'm of course open to suggestion on improving the code or adding functionnalities.

It currently only handle Monsters / Raids / Eggs Alarms. Quests and Invasions are not supported yet by are planned features.

A few other features that might make their way into the tool :

- Ability to set a Location.
- Ability to set a Distance globally for all Monsters and/or all Raids/Eggs
- A way to visualize distances drawing a radius on the map from current position.

Any other suggestion is welcome, please use github Issues for your suggestions.

To get started with it :
- As always, copy `config_example.php` to config.php and adapt to your needs
- Have a web Server pointing to your install directory (This tool doesn't include any standalone WebServer)

You will need to configure your Discord Bot settings in config.php. If you use PMSF, you can reuse the same parameters for `discordBotClientId` and `discordBotClientSecret` or find them on the Discord application Portal. `redirect_url` should point to your PoracleWeb base directory and should be configured as a Redirects in your discord bot. 

For those parameters go to :
- https://discord.com/developers/applications
- Select your Bot (or create a new one).
- Go to Oauth2 and add your `http://yourdomain.com/discord_auth.php` (`http://yourdomain.com/`) being your `redirect_url`
- Client ID can be found under "General Information"
- Client Secret can be found under "General Information" by clicking the "Click to reveal" link.
