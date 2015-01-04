<?php if (!defined('APPLICATION')) exit();
$PluginInfo['BadBoy'] = array(
   'Name' => 'Bad Boy',
   'Description' => "An implementation of <a href='http://bad-behavior.ioerror.us/'>Bad Behaviour</a> for Vanilla Forums. Currently it will run in a degraded mode as logging is not available.",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

	class BadBoyPlugin extends Gdn_Plugin {
	
	/**
    * Add Bad Behaviour
    */
		public function Base_Render_Before($Sender) {
		  require_once('plugins/BadBoy/bad-behavior-generic.php');
		 }
}