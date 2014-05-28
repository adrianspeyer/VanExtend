<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['Debugit'] = array(
   'Name' => 'Debug It',
   'Description' => 'This plugin allows you to turn on native Vanilla Debug mode without modifying config via FTP. Will not work if plugin/dashboard issue. In this case add <b>$Configuration[\'Debug\'] = TRUE;</b> to conf/config. Remember to disable when done so user do not see debug issues. ',
   'Version' => '1.1',
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class DebugitPlugin extends Gdn_Plugin {

	public function Gdn_Dispatcher_AppStartup_Handler($Sender) {
	$Session = Gdn::Session();
	if ($Session->CheckPermission('Garden.Settings.Manage')) {
	SaveToConfig('Debug', TRUE);
	}
}
   
	public function Setup() {
   }

	public function OnDisable() {
    	SaveToConfig('Debug', FALSE);
   }   
    
}
