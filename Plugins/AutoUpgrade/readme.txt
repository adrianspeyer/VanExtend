Auto Upgrade

This is a basic auto upgrade I created for my use, that I decide to share. As I work on making it better, or if you have code suggestions, it would be appreciated. Code is hosted on Github


Basics:
Open up db-backup.php to include your database information from conf/config.php before you FTP to your site. 
I know this is not the prettiest way, but I am open to code improvements.
All backups are kept in the add-on folders (db-backup & file backup). If you need access, just open your FTP.

Next step is just to enable the add-on. A new menu item will appear in your settings. Steps of the upgrade follow: http://vanillaforums.org/page/installation-upgrade

The following is on my to-do list, but any help one wants to provide is welcome:
1- Notificiation a new version of Vanilla is available.
2- download new Vanilla to tmp folder
3- create a better way to connect to database
4- need better way to get path for files, right now using relative path. Might cause an issue on some installs.
5- Evetual goal is upgrade to copy over new files, skipping those not modified/unchanged
6 - Automatic way to place vanilla in maintencae while this happens

Please make sure you test this on a demosite for your install first.
		
		