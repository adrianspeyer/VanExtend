<?php if(!defined('APPLICATION')) die();
/**
*
* # Maintenance Plugin for Vanilla 2 #
* You can change closed.php to be whatever you want to be shown while you are closed for upgrade.
* 
*
*/

// Define the plugin:
$PluginInfo['Maintenance'] = array(
   'Name' => 'Closed For Maintenance',
   'Description' => 'Maintenance plugin for Vanilla 2, allows you to close your site to do maintenance',
   'Version' => '0.8.1',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'

);

class Maintenance  extends Gdn_Plugin {

    public function Base_Render_Before($Sender) {
		if ( Gdn::Session()->CheckPermission('Garden.Settings.Manage'))
		{
		echo '<div style="color:#00FF00; background-color:red;text-align:center;"><b>The site is currently in Maintenance Mode</b></div>';
		}
		else
	header( 'Location:'.$Url.'./plugins/Maintenance/closed.php' ) ;
}
    public function Setup() {
    }
}
