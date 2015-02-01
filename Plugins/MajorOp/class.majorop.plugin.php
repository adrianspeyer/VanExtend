<?php if (!defined('APPLICATION')) exit();

$PluginInfo['MajorOp'] = array(
   'Name' => 'MajorOp',
   'Description' => 'Displays original post on successive pages of a discussion',
   'Version' => '1.0',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);


class MajorOp extends Gdn_Plugin {	

	 public function DiscussionController_AfterDiscussionTitle_Handler($Sender) {
	 
		$Discussion = GetValue('Discussion', $Sender->EventArguments);
		$Author = $Discussion->InsertName;
		$AuthorID = $Discussion->InsertUserID;
		  if ($Sender->Data('Page') > 1){
			echo '<h3 class="MOP">Original Post:</h3>';
			echo '<div class="MajorOP">'.SliceString(Gdn_Format::PlainText($Discussion->Body, $Discussion->Format)).'</div>';
			echo '<h3 class="MOPA">Started by:<a href="'.Gdn_Url::WebRoot(TRUE).'/profile/'.$AuthorID.'/'.$Author.'"> '.$Author.'</a></h3>';
		  }
	}
	 
	public function Base_Render_Before($Sender) {
	$Sender->AddCssFile('majorop.css', 'plugins/MajorOP');
	}
	 
    public function Setup() {
		return TRUE;
   }
	
   public function OnDisable() {
      return TRUE;
   }
}