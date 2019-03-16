# TS3 User Verify

This was a small project i worked on using the Teamspeak 3 PHP Server Query Framework, it allows users who are not part of the 'verified' server group to verify. Once they select their name from the drop down it will poke them with a verification code, once they enter it back into the site it will add them to the 'verified' server group and create them a channel, move them into it and assign them a defined channel group

## Getting Started

Its fairly easy to get started with. Just download the files, then edit the config.php file with your server query details, 'verified' tag ID and the tag ID of the channel group you wish to assign them. You must make sure all of the files have file perms 775!

## Built With

* [PlanetTeamspeak](https://github.com/planetteamspeak/ts3phpframework) - The PHP Framework used

## Authors

* **Dan Barrett** - *Initial work* - [Dan B](https://dbarrett.uk)

## License

This project is licensed under the MIT License
