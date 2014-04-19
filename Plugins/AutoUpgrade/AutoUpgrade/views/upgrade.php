<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<?php if (!defined('APPLICATION')) exit(); ?>
</head><body>
<center>
<h1>This is the Automatic Upgrade Settings Page</h1>
<p>This is an alpha release for upgrading an existing Vanilla 2.0 Forum</p>
</center>

<table style="text-align: left; width: 90%; margin-left: auto; margin-right: auto;" border="0" cellpadding="4" cellspacing="2">

  <tbody>
    <tr>
      <td style="vertical-align: top;"><span style="font-weight: bold;">Download
the latest version of Vanilla </span><a style="font-weight: bold;" target="_blank" href="http://vanillaforums.org/get/vanilla-core">here.</a><br>
      
<div style="margin-left: 40px;">
1. Make sure you are signed in with the Super User created when you FIRST INSTALLED Vanilla Forums<br>
2. Back up your <a href="../plugins/AutoUpgrade/db_backup/db-backup.php">database.</a><br>
3. Back up your <a href="../plugins/AutoUpgrade/files_backup/backup-uploads.php">uploads</a> folder<br>
4. Back up your <a href="../plugins/AutoUpgrade/files_backup/backup-conf.php">conf/config.php</a> file
 and <a href="../plugins/AutoUpgrade/files_backup/backup-htcac.php">.htaccess</a> file.<br>
5. Unarchive the new files and copy them over your existing files.<br>
6. <a href="../plugins/AutoUpgrade/files_backup/backup-kill-ini.php">Delete all</a> *.ini files in your cache/ folder<br>
7. Navigate to the page in your forum that to complete a <a href="../utility/structure">database upgrade scan.</a><br>
8.Navigate to your forum and test away!
 </div>
 
 **All backups are kept in the add-on folders (db-backup & file backup). If you need access, just open your FTP.**
				
<!--TO DO
Steps following http://vanillaforums.org/page/installation-upgrade
1 download new Vanilla to tmp folder
2 need better connection to database
3 need better way to get path. want to avoid relative path. ensure right paths
4 copy over new files, skipping those not modified--way to place vanilla in maintencae while this happens?
end of TO DO-->		
		
		
      </td>
    </tr>
  </tbody>
</table>

</body></html>