# PoracleWeb


[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]

---

REQUIRES MYSQL !

This tool is an add-on to [PoracleJS](https://github.com/KartulUdus/PoracleJS)

This is a first attempt at creating a Web Inteface to handle alarms configuration in PoracleJS.

Please note that I'm not a professional Web Developer, so the code might look ugly to some people, but it works. I'm of course open to suggestion on improving the code or adding functionalities.

---

**PoracleWeb handles:** (unchecked are planned features)
- [x] Monsters
- [x] Raids & Eggs
- [x] Quests
- [ ] Invasions

Any other suggestions are welcome, please use [GitHub Issues][issues-url] for your suggestions.

---

### Prerequisites

* [NodeJS & NPM](https://nodejs.org/en/download/)
* [Composer](https://getcomposer.org/download/)

---

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/bbdoc/PoracleWeb.git
   ```
2. Install NPM packages
   ```sh
   npm install
   ```
3. Copy `config_example.php` to `config.php` and adapt to your needs
4. Have a Web Server pointing to your install directory (This tool doesn't include any standalone WebServer)

5. You will need to configure your Discord Bot settings in config.php. If you use PMSF, you can reuse the same parameters for `discordBotClientId` and `discordBotClientSecret` or find them on the [Discord application Portal](https://discord.com/developers/applications). `redirect_url` should point to your PoracleWeb base directory and should be configured as a Redirects in your Discord bot. 

For those parameters go to :
- [Discord application Portal](https://discord.com/developers/applications)
- Select your Bot (or create a new one).
- Go to OAuth2 and add your `https://yourdomain.com/discord_auth.php` (`https://yourdomain.com`) being your `redirect_url`
- Client ID can be found under "General Information"
- Client Secret can be found under "General Information" by clicking the "Click to reveal" link.

---

## Contributing

1. Fork the Project
2. Clone your forked project
```sh
git clone https://github.com/YourUserName/PoracleWeb.git
```
3. Create your New Feature branch (`git checkout -b new_feature`)
4. Create a new remote for the upstream repo with the command:
```sh
git remote add upstream https://github.com/bbdoc/PoracleWeb
```
3. Commit your Changes
```sh
git commit -m 'Add some New Feature'
```
4. Push to the Branch
```sh
git push origin new_feature
```

[contributors-shield]: https://img.shields.io/github/contributors/bbdoc/PoracleWeb.svg?style=for-the-badge
[contributors-url]: https://github.com/bbdoc/PoracleWeb/graphs/contributors
[forks-shield]: https://img.shields.io/github/forks/bbdoc/PoracleWeb.svg?style=for-the-badge
[forks-url]: https://github.com/bbdoc/PoracleWeb/network/members
[stars-shield]: https://img.shields.io/github/stars/bbdoc/PoracleWeb.svg?style=for-the-badge
[stars-url]: https://github.com/bbdoc/PoracleWeb/stargazers
[issues-shield]: https://img.shields.io/github/issues/bbdoc/PoracleWeb.svg?style=for-the-badge
[issues-url]: https://github.com/bbdoc/PoracleWeb/issues
