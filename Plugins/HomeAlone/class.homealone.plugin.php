<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HomeAlone'] = array(
   'Name' => 'Home Alone',
   'Description' => 'This plugin creates a new permission "HomeAlone" which when used with permissions moderation.manage, ensures all content of the Admins cannot be deleted from their profile page.',
   'Version' => '1.0',
   'RegisterPermissions' => array('Plugins.HomeAlone.Manage' => 1),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
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
