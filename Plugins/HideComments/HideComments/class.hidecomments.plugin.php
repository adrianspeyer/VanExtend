<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideComments'] = array(
   'Name' => 'Hide Comments',
   'Description' => 'This allows forum owners to hide comments from some, but allows discussion to be seen.',
   'Version' => '1.3',
   'RegisterPermissions' => array('Plugins.HideComments.View' => 0),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HideCommentsPlugin extends Gdn_Plugin {

  public function DiscussionController_BeforeDiscussionRender_Handler($Sender, $Args) {
	$hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
			if (!Gdn::Session()->IsValid() || $hidecomments) { 
						
				// kills all comments -- Thanks to Linc for the assist
				$BlankDataSet = new Gdn_DataSet();
				$Sender->SetData('Comments', $BlankDataSet);
					

				//closes discussions
				 $Discussion = $Sender->Data('Discussion');
				 SetValue('Closed', $Discussion, 1);  
				 $Sender->SetData('Discussion', $Discussion);
			}
		    }
		}

   public function DiscussionController_AfterDiscussion_Handler($Sender) { 
	 $hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
			if (!Gdn::Session()->IsValid() || $hidecomments) { 
				echo "<div class='Foot Closed'>".(T('Comment on this discussion can be viewed by members only. ')).Anchor(T('Apply for membership.'), Url('../entry/signin'));".</div>";
			}
         	    }	
  		}

   public function DiscussionController_AfterCommentFormat_Handler($Sender) { 		
      $hidecomments = Gdn::Session()->CheckPermission('Plugins.HideComments.View');
		if (!CheckPermission('Garden.Moderation.Manage')){
		   if (!Gdn::Session()->IsValid() || $hidecomments) { 
	
			//clean up css
			echo '<style>
			div.CommentsWrap{display:none;}
			div.Note.Closed{display:none;}
			div.note.Closed.SignInOrRegister{display:none;}
			div.MessageForm {display:none;}
			</style>';
			}
		   }
	      }
}
