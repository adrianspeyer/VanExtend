<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideAuthor'] = array(
   'Name' => 'HideAuthor',
   'Description' => 'Author info is hidden from guests',
   'Version' => '1.0',
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.adrianspeyer.com',
   'MobileFriendly' => TRUE,
    'License' => 'GNU GPL2'
);

class HideAuthorPlugin extends Gdn_Plugin {
	public function DiscussionController_BeforeCommentDisplay_Handler($Sender, $Args) {
			if (!CheckPermission('Garden.Moderation.Manage')){
				if (!Gdn::Session()->IsValid()) { 
					$Args['Author'] ='';
				}
			}
		}
		
		
	public function DiscussionController_BeforeDiscussionDisplay_Handler($Sender, $Args) {
			if (!CheckPermission('Garden.Moderation.Manage')){
				if (!Gdn::Session()->IsValid()) { 
					$Args['Author'] ='';
				}
			}
		}

}
