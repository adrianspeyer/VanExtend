<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.1',
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
		if ($hidecomments) { 
			echo "<div class='Foot Closed'>".(T('This comment can be viewed by members only. ')).Anchor(T('Apply for membership.'), Url('../entry/signin'));".</div>";
			}
	}
	
	public function DiscussionController_BeforeCommentsRender_Handler(&$Sender, $Args) {
		// instead of css solution	
	}
	
	
       public function DiscussionController_AfterCommentFormat_Handler($Sender) { 
	
		$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
	   
		  	if ($hidecomments) { 
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

      public function DiscussionController_BeforeDiscussionRender_Handler($Sender, $Args) {
	
	 	$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
	
			if ($hidecomments) { 
			 $Discussion = $Sender->Data('Discussion');
		         SetValue('Closed', $Discussion, 1);  
		         $Sender->SetData('Discussion', $Discussion);
			}
	}	
	
}
