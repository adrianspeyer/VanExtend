<?php if (!defined('APPLICATION')) exit();
$PluginInfo['NoGender'] = array(
   'Name' => 'Hide Gender',
   'Description' => "Hides gender on registration.",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class NoGender extends Gdn_Plugin {
		
		 public function Base_Render_Before($Sender) {
		  $Sender->Head->AddCss('/plugins/NoGender/design/nogender.css');
		 }
}	