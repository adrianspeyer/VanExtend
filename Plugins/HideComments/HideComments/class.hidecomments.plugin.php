<?php if (!defined('APPLICATION')) exit();

// Mucho thanks to Kasper
$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.0',
   'RegisterPermissions' => array('Vanilla.Comment.View' => 'Vanilla.Discussion.View'),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HideCommentsPlugin extends Gdn_Plugin {
	public function DiscussionController_AfterCommentFormat_Handler($Sender) { //this is discussion & comments
		$canviewcomments = Gdn::Session()->CheckPermission('Vanilla.Comment.View');
		
		if (!$canviewcomments) { 
			$Sender->EventArguments['Comment']->FormatBody = Anchor(T('This comment can be viewed by members only'), Url('/entry/signin'));
		}
	}
}
