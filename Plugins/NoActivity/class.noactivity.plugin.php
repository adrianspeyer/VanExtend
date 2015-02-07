<?php if (!defined('APPLICATION')) exit();

$PluginInfo['NoActivity'] = array(
   'Name' => 'No Activity',
   'Description' => 'This plugin removes Activity from the profile page.',
   'Version' => '1.0',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class NoActivityPlugin extends Gdn_Plugin {

	public function ProfileController_BeforeStatusForm_Handler($Sender) {
		   SaveToConfig('Garden.Profile.ShowActivities', FALSE);	
		}
	
	public function OnDisable() {
		   SaveToConfig('Garden.Profile.ShowActivities', TRUE);
		}   	
}
