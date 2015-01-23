<?php if (!defined('APPLICATION')) exit();

/* 
Thanks to Lincoln for the assist
*/

// Define the plugin:
$PluginInfo['AlwaysEdit'] = array(
   'Name' => 'Always Edit',
   'Description' => 'This plugin overrides author edit limit to allow you to create a role where some authors can always edit their own posts.',
   'Version' => '1.0.1',
   'RegisterPermissions' => array('Plugins.AlwaysEdit.Edit' => 1),
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class AlwaysEditPlugin extends Gdn_Plugin {

public function Gdn_Dispatcher_AppStartup_Handler($Sender) {
$canAlwaysEdit = Gdn::Session()->CheckPermission('Plugins.AlwaysEdit.Edit');
if ($canAlwaysEdit) {
SaveToConfig('Garden.EditContentTimeout', -1, FALSE);
		 }
	 }

   public function Setup() {

   } 
   
}