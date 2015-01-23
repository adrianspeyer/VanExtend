<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HomeAlone'] = array(
   'Name' => 'Home Alone',
   'Description' => 'This plugin creates a new permission "HomeAlone" which when used with permissions moderation.manage, esnures all content of the Admins cannot be deleted from profile page.',
   'Version' => '1.0',
   'RegisterPermissions' => array('Plugins.HomeAlone.Manage' => 0),
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class HomeAlonePlugin extends Gdn_Plugin {

public function ProfileController_BeforeProfileOptions_Handler($Sender) {
	$isHomeAlone = Gdn::Session()->CheckPermission('Plugins.HomeAlone.Manage');
		if ($isHomeAlone) {
			$Session = Gdn::Session();
			$Controller = Gdn::Controller();
			$UserID = $Controller->User->UserID;
		if($Controller->User->Admin) {
		unset($Controller->EventArguments['ProfileOptions']);  
				}
			}
		 }
}