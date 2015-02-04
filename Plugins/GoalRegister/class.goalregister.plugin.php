<?php if (!defined('APPLICATION')) exit();

$PluginInfo['GoalRegister'] = array(
   'Name' => 'Goal Register',
   'Description' => "Fires an event in Universal Google Analytics when user registers, which can use as a goal measurement.",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class GoalRegisterPlugin extends Gdn_Plugin {
	
  public function EntryController_RegisterBeforePassword_Handler($Sender){
	echo "<script> ga('send', 'event', 'button', 'signup', 'user-registered');</script>";	
	}
   /** No setup. */
   public function Setup() { }
}