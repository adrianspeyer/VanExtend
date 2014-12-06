<?php if (!defined('APPLICATION')) exit();
$PluginInfo['Alert'] = array(
   'Name' => 'Alert Discussion',
   'Description' => "Allows you to tag a disucssion with an alert tag so CSS can be added",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'RegisterPermissions' => array('Plugins.Alert.Manage'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

	class AlertPlugin extends Gdn_Plugin {
	
	/**
    * Add CSS
    */
		public function Base_Render_Before($Sender) {
		  $Sender->Head->AddCss('/plugins/alert/design/alert.css');
		 }
	
		public function Setup() {
			  Gdn::Structure()
				 ->Table('Discussion')
				 ->Column('Alert', 'int', '0')
				 ->Set();
		   }
   
	   /**
		* Make a discussion an alert via checkbox.
		*/
	   
	   public function PostController_AfterDiscussionFormOptions_Handler($Sender) {
		  if (Gdn::Session()->CheckPermission('Plugins.Alert.Manage'))
			 echo $Sender->Form->CheckBox('Alert', T('Alert'), array('value' => '1'));
			}
		 
	   /**
		* Allow mark discussion as an Alert.
		*/
	   public function Base_DiscussionOptions_Handler($Sender, $Args) {
		  $Discussion = $Args['Discussion'];
		  $Alert = GetValue('Alert', $Discussion);
		  $NewAlert = (int)!$Alert; 
				if (CheckPermission('Plugins.Alert.Manage')) {
					 $Label = T($Alert ? 'No Alert' : 'Alert');
					 $Url = "/discussion/alert?discussionid={$Discussion->DiscussionID}&alert=$NewAlert";
					 
					 // Deal with inconsistencies in how options are passed
				if (isset($Sender->Options)) {
						$Sender->Options .= Wrap(Anchor($Label, $Url, 'iAlert'), 'li');
					 }
					 else {
						$Args['DiscussionOptions']['Alert'] = array(
						   'Label' => $Label,
						   'Url' => $Url,
						   'Class' => 'iAlert'
						   );
						}
					}
			   }

	   /**
		* Handle discussion option menu Alert action (simple toggle).
		*/
	   public function DiscussionController_Alert_Create($Sender, $Args) {
	   if (CheckPermission('Plugins.Alert.Manage')){
		  // Get discussion
		  $DiscussionID = $Sender->Request->Get('discussionid');
		  $Discussion = $Sender->DiscussionModel->GetID($DiscussionID);
		  if (!$Discussion) {
			 throw NotFoundException('Discussion');
		  }
		  // Toggle Alert
		  $Alert = GetValue('Alert', $Discussion) ? 0 : 1;
		  
		  // Update DateLastComment & redirect
		  $Sender->DiscussionModel->SetProperty($DiscussionID, 'Alert', $Alert);
		  Redirect($_SERVER['HTTP_REFERER']);
			}
		  }
	   
		/**
		* Add Alert CSS tag to conversation
		*/

		  public function Base_BeforeDiscussionMeta_Handler($Sender, $Args) {
			$Alert = GetValue('Alert', GetValue('Discussion', $Args));
			  
			  if ($Alert) {
				 echo ' <span class="Tag Tag-Alert">'.T('Alert').'</span> ';	  
				 }
			   }  
		
		 /**
		* Add CSS class to conversation
		*/ 
		 public function DiscussionsController_BeforeDiscussionName_Handler($Sender, $Args) {
			 $Alert = GetValue('Alert', GetValue('Discussion', $Args));
			 if ($Alert) {
				$Sender->EventArguments['CssClass'] .= " Alert";
				}
			  }		 
}