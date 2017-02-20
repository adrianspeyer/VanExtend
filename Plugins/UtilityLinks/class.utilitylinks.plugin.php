<?php if (!defined('APPLICATION')) exit();

$PluginInfo['UtilityLinks'] = array(
	'Name' => 'Utility Links',
	'Description' => 'Adds handy links for checking, upgrading, refreshing or modifying your Vanilla Install.',
	'Version' => '1.0.7',
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
			$Attrs = array('class' => 'Utility Links');
			$Menu->AddItem('Utility Links', 'Utility Links', FALSE, $Attrs);


			$Menu->AddLink('Utility Links', T('DB Structure Upgrade'), 'dashboard/utility/structure/all');
			$Menu->AddLink('Utility Links',T('DB Update Counts'), 'dashboard/dba/counts');
			$Menu->AddLink('Utility Links', T('Utility Update'),'dashboard/utility/update');


			//If Feed Discussions enabled offer check feeds
		    if (Gdn::PluginManager()->CheckPlugin('FeedDiscussions') && C('Plugins.FeedDiscussions', TRUE)) {
			$Menu->AddLink('Utility Links', T('Check Feeds'), '/plugin/feeddiscussions/checkfeeds');
			}

			//If Using Profile Extender version 3.0.2 enable profile export
			$chkPEInfo = Gdn::pluginManager()->getPluginInfo(ProfileExtender, Gdn_PluginManager::ACCESS_PLUGINNAME);
                        $version = val('Version', $chkPEInfo);
			if ($version = '3.0.2') {
			   if (Gdn::PluginManager()->CheckPlugin('ProfileExtender') && C('Plugins.ProfileExtender', TRUE)) {
			   $Menu->AddLink('Utility Links',  T('Export User Profiles'), 'utility/exportProfiles');
			  }
			}

			//If greater than 2.2 don't show
			if (APPLICATION_VERSION < 2.2) {
			$Menu->AddLink('Utility Links', T('Set Default Roles'), '/dashboard/role/defaultroles');
			     }
			 }
	}

	public function Setup() {
	   }
}
