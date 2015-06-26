<?php if (!defined('APPLICATION')) exit();

$PluginInfo['UtilityLinks'] = array(
	'Name' => 'Utility Links',
	'Description' => 'Adds handy links for checking, upgrading, refreshing or modifying your Vanilla Install.',
	'Version' => '1.0.4',
	'SettingsPermission' => 'Garden.Settings.Manage',
	'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com',
    'License' => 'GNU GPL2'
);
class UpdateLinksPlugin extends Gdn_Plugin {
	
	public function Base_GetAppSettingsMenuItems_Handler($Sender)
	{
	    if (Gdn::Session()->CheckPermission('Garden.Settings.Manage')) {
			$Menu = $Sender->EventArguments['SideMenu'];

			$dbuptxt = 'DB Structure Upgrade';
			$Menu->AddLink('Utility Links', $dbuptxt, 'dashboard/utility/structure/all');	

			$dbupctxt = 'DB Update Counts';
			$Menu->AddLink('Utility Links', $dbupctxt, 'dashboard/dba/counts');
			
			$dbupbtxt = 'Utility Update';
			$Menu->AddLink('Utility Links', $dbupbtxt, 'dashboard/utility/update');
		
		
			//If Feed Discussions enabled offer check feeds
			if (C('EnabledPlugins.FeedDiscussions', TRUE)) {
			$feedupdtxt = 'Check Feeds';
			$Menu->AddLink('Utility Links', $feedupdtxt, '/plugin/feeddiscussions/checkfeeds');		
			}

			//If greater than 2.2 don't show
			if (APPLICATION_VERSION < 2.2) {
			$dbupdtxt = 'Set Default Roles';
			$Menu->AddLink('Utility Links', $dbupdtxt, '/dashboard/role/defaultroles');	
			     } 
			 }	
	}		
	
	public function Setup() {
	   }
}