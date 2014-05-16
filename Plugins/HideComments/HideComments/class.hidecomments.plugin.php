<?php if (!defined('APPLICATION')) exit();

/* Mucho thanks to Kasper & Lincoln
*/

$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.1',
   'RegisterPermissions' => array('Plugins.HideComments.View' => 1),
   //'RegisterPermissions' => array('Vanilla.Comments.View' => 'Vanilla.Discussions.View'),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HideCommentsPlugin extends Gdn_Plugin {

	public function DiscussionController_AfterCommentFormat_Handler($Sender) { 
	  //$canviewcomments = Gdn::Session()->CheckPermission('Vanilla.Comments.View');
	  $canviewcomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
	    if (!$canviewcomments) { 
			$Sender->EventArguments['Comment']->FormatBody = Anchor(T('This comment can be viewed by members only'), Url('/entry/signin'));
		}
	 }   
}