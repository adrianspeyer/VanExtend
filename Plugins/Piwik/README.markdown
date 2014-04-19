#Piwik Analytics for Vanilla Forums#

Adds Piwik to footer. Requires themes with AfterBody event. Also allows you to see your stats from your Vanilla Install.

Piwik is the open source Google Analytics. 

Once you have Piwik running on your server, just fill in the information for your install and site id.

If you want to look at your stats from within your Vanilla install, just add your Piwik API key. (You can find it in the API tab in your Piwik install). Please note it's not needed to record stats, it provides you with you stats via iframe.

If you wish to include the whole Piwik in an iframe, remeber you need to edit your config/config.ini.php and add the following line:

[General]
enable_framed_pages=1

If you also wish to enable the "Settings" pages (Manage websites, Users, etc.) to load in an iframe you can also add the setting:
enable_framed_settings=1

As a best practice, I would create a new user/API with a view only access rather than using the Super Admin API key if there is shared access to the backend with other users. 

#Changelog#
As of 2.5.0, now compatible with Vanilla Version 2.1b1. Thanks @peregrine for tips on upgrade.

As of 2.6.0 Using the new Piwik Tracking Code from Piwik Version 1.12

Now mobile friendly as of 2.6.5

More detailed instructions at: http://www.statstory.com/piwik-in-vanilla-forums-plugin/