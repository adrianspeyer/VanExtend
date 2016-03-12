<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideAuthor'] = array(
   'Name' => 'HideAuthor',
   'Description' => 'Author info is hidden from guests',
   'Version' => '1.0',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
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
