<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.0',
   'RegisterPermissions' => array('Plugins.HideComments.View' => 1),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HideCommentsPlugin extends Gdn_Plugin {

public function DiscussionController_AfterCommentFormat_Handler($Sender) { //this is discussion & comments
//public function DiscussionController_AfterCommentBody_Handler($Sender) { //comments only
$canviewcomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
if ($canviewcomments) {
 }
 else
 { 
//$Sender->EventArguments['Object']->FormatBody = "<a href=".Url('/entry/signin')."> This message can be viewed by members only</a>";
$Sender->EventArguments['Comment']->FormatBody= "<a href=".Url('/entry/signin')."> This message can be viewed by members only</a>";
 }
	 }
   public function Setup() {
   } 
}