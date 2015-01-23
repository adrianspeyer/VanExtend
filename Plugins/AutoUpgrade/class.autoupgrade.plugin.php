<?php if (!defined('APPLICATION')) exit();
/*
Automatic Upgrade Alpha
*/

// Define the plugin:
$PluginInfo['AutoUpgrade'] = array(
   'Name' => 'Auto Upgrade',
   'Description' => 'Automatic Upgrade of your Vanilla install',
   'Version' => '0.3',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);


class AutoUpgrade extends Gdn_Plugin {

	
   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
	  $Menu->AddLink('Site Settings', 'Auto Upgrade', 'settings/autoupgrade');
   }
   
   public function SettingsController_AutoUpgrade_Create($Sender) {
    $Sender->Permission('Garden.Settings.Manage');
    $Sender->Title('Upgrade Vanilla');
   	$Sender->AddSideMenu('settings/autoupgrade');
   	$Sender->Render($this->GetView('upgrade.php'));
   }
	 
	public function Setup() {

	}
}