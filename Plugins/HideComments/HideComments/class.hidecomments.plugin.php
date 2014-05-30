<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.2',
   'RegisterPermissions' => array('Plugins.HideComments.View' => 0),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HideCommentsPlugin extends Gdn_Plugin {

        public function DiscussionController_AfterDiscussion_Handler($Sender) { 
		
		$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
		  if (!Gdn::Session()->IsValid() || $hidecomments) { 
			echo "<div class='Foot Closed'>".(T('Comment on this discussion can be viewed by members only. ')).Anchor(T('Apply for membership.'), Url('../entry/signin'));".</div>";
			}
		}	
	}
		
	public function DiscussionController_BeforeCommentDisplay_Handler($Sender, $Args) {
		$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
			if (!CheckPermission('Garden.Moderation.Manage')){
				if (!Gdn::Session()->IsValid() || $hidecomments) { 
					$Args['Comment'] ='';
					$Args['Author'] ='';

					echo '<style>
					div.CommentsWrap{display:none;}
					div.Note.Closed{display:none;}
					div.note.Closed.SignInOrRegister{display:none;}
					div.MessageForm {display:none;}
					</style>';
					
					// Created blanks view of comments.php
					//$this->GetView('comments.php'); // -- did not work
					//$Sender->Render($this->GetView('comments.php')); //-- whole site show up in loop
					//$this->Render();	//-- Add with line above and comment is at top, rest of site below.
					
				}
			}
		}
	
	
	/*
	public function DiscussionController_BeforeCommentsRender_Handler(&$Sender, $Args) {
		// instead of css solution
			/*
			 $Comments = $Sender->Data('Comments');
			 if ($Comments) {
			 foreach ($Comments as $Comment) {
				$Comment->Body = "";
				}
			}
			
		}

	*/

	/*  
	public function DiscussionController_AfterCommentFormat_Handler($Sender) { 
		
		$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
		   if (!Gdn::Session()->IsValid() || $hidecomments) { 
		  	 $Session = Gdn::Session();
		        $Controller = GDN::Controller();
			     unset($Controller->EventArguments['Comment']->FormatBody);
	
			//find way to do without css
			echo '<style>
			div.CommentsWrap{display:none;}
			div.Note.Closed{display:none;}
			div.note.Closed.SignInOrRegister{display:none;}
			div.MessageForm {display:none;}
			</style>';
				}
			}
   	}
*/
	
      public function DiscussionController_BeforeDiscussionRender_Handler($Sender, $Args) {
	 	$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
		    if (!Gdn::Session()->IsValid() || $hidecomments) { 
			 $Discussion = $Sender->Data('Discussion');
		         SetValue('Closed', $Discussion, 1);  
		         $Sender->SetData('Discussion', $Discussion);
			}
		}
	}
}