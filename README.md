# PoracleWeb

REQUIRES MYSQL !

This tool is an add-on to PoracleJS (https://github.com/KartulUdus/PoracleJS)

This is a first attempt at creating a Web Inteface to handle alarms configuration in PoracleJS.

Please note that I'm not a professional Web Developer, so the code might look ugly to some people, but it works. I'm of course open to suggestion on improving the code or adding functionalities.

It currently only handle Monsters / Raids / Eggs Alarms. Quests and Invasions are not supported yet by are planned features.

A few other features that might make their way into the tool :

- Ability to set a Location.
- Ability to set a Distance globally for all Monsters and/or all Raids/Eggs.
- A way to visualize distances drawing a radius on the map from current position.

Any other suggestions are welcome, please use GitHub Issues for your suggestions.

To get started with it :
- As always, copy `config_example.php` to config.php and adapt to your needs
- Have a Web Server pointing to your install directory (This tool doesn't include any standalone WebServer)

You will need to configure your Discord Bot settings in config.php. If you use PMSF, you can reuse the same parameters for `discordBotClientId` and `discordBotClientSecret` or find them on the Discord application Portal. `redirect_url` should point to your PoracleWeb base directory and should be configured as a Redirects in your Discord bot. 

For those parameters go to :
- https://discord.com/developers/applications
- Select your Bot (or create a new one).
- Go to OAuth2 and add your `https://yourdomain.com/discord_auth.php` (`https://yourdomain.com/`) being your `redirect_url`
- Client ID can be found under "General Information"
- Client Secret can be found under "General Information" by clicking the "Click to reveal" link.
