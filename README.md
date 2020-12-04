# PoracleWeb

This tool is an add-on to PoracleJS (https://github.com/KartulUdus/PoracleJS)

It requires PR #151 (PvP Stats Tracking - https://github.com/KartulUdus/PoracleJS/pull/151) to be installed.

This is a first attempt at creating a Web Inteface do handle alarms configuration in PoracleJS.

Please not that I'm not a professional Web Developer, so the code might look ugly to some people, but it works. I'm of course open to suggestion on improving the code or adding functionnalities.

It currently only handle Monsters / Raids / Eggs Alarms. Quests and Invasions are not supported yet by are planned features.

A few other features that might make their way into the tool :

- Ability to set a Location.
- Ability to set a Distance globally for all Monsters and/or all Raids/Eggs
- Ability to set clean to True per alarm but also globally.
- A way to visualize distances drawing a radius on the map from current position.
- Search tool when trying to add pokemon alarms.

Any other suggestion is welcome, please use github Issues for your suggestions.

To get started with it :
- As always, copy config_example.php to config.php and adapt to your needs
- Have a web Server pointing to your install directory (This tool doesn't include any standalone WebServer)
