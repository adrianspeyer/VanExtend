<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideRant'] = array(
   'Name' => 'HideRant',
   'Description' => 'Posts tagged with "rant" have author info hidden from guests',
   'Version' => '1.0',
   'RequiredPlugins' => array('Tagging' => '1.8'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class HideRantPlugin extends Gdn_Plugin {
	public function DiscussionController_BeforeDiscussionDisplay_Handler($Sender, $Args) {

				
				//Get discussionID that is being deleted 
				$DiscussionID =$Sender->EventArguments['DiscussionID'];

				  //Get List of tags to reduce count for
				  $TagDataSet = Gdn::SQL()->Select('TagID')
						->From('tagdiscussion')
						->Where('DiscussionID =',$DiscussionID)
						->Get();
				  if ($SingleTag = "rant"||"Rant") {
					$Args['Author'] =''; 
					}
			}
	
}
