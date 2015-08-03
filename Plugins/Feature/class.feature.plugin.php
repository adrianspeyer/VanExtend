<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Feature'] = array(
   'Name' => 'Feature',
   'Description' => "Allows those with the permission to 'feature' a discussion",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

class FeaturePlugin extends Gdn_Plugin {
	
	/**
    * Add CSS
    */
		public function Base_Render_Before($Sender) {
		  $Sender->Head->AddCss('/plugins/Feature/design/Feature.css');
		 }
	
		public function Setup() {
			  Gdn::Structure()
				 ->Table('Discussion')
				 ->Column('Feature', 'int', '0')
				 ->Set();
		   }
   
	   /**
		* Make a discussion an Feature via checkbox.
		*/
	   
	   public function PostController_AfterDiscussionFormOptions_Handler($Sender) {
		  if (Gdn::Session()->CheckPermission('Plugins.Feature.Manage'))
			 echo $Sender->Form->CheckBox('Feature', T('Feature'), array('value' => '1'));
			}
		 
	   /**
		* Allow mark discussion as an Feature.
		*/
	   public function Base_DiscussionOptions_Handler($Sender, $Args) {
		  $Discussion = $Args['Discussion'];
		  $Feature = GetValue('Feature', $Discussion);
		  $NewFeature = (int)!$Feature; 
				if (CheckPermission('Plugins.Feature.Manage')) {
					 $Label = T($Feature ? 'No Feature' : 'Feature');
					 $Url = "/discussion/Feature?discussionid={$Discussion->DiscussionID}&Feature=$NewFeature";
					 
					 // Deal with inconsistencies in how options are passed
				if (isset($Sender->Options)) {
						$Sender->Options .= Wrap(Anchor($Label, $Url, 'iFeature'), 'li');
					 }
					 else {
						$Args['DiscussionOptions']['Feature'] = array(
						   'Label' => $Label,
						   'Url' => $Url,
						   'Class' => 'iFeature'
						   );
						}
					}
			   }

	   /**
		* Handle discussion option menu Feature action (simple toggle).
		*/
	   public function DiscussionController_Feature_Create($Sender, $Args) {
	   if (CheckPermission('Plugins.Feature.Manage')){
		  // Get discussion
		  $DiscussionID = $Sender->Request->Get('discussionid');
		  $Discussion = $Sender->DiscussionModel->GetID($DiscussionID);
		  if (!$Discussion) {
			 throw NotFoundException('Discussion');
		  }
		  // Toggle Feature
		  $Feature = GetValue('Feature', $Discussion) ? 0 : 1;
		  
		  
		  // Update DateLastComment & redirect
		  $Sender->DiscussionModel->SetProperty($DiscussionID, 'Feature', $Feature);
		  $Sender->DiscussionModel->SetProperty($DiscussionID, 'Announce', $Feature);
		  Redirect($_SERVER['HTTP_REFERER']);
			}
		  }
	   
		/**
		* Add Feature CSS tag to conversation
		*/

		  public function Base_BeforeDiscussionMeta_Handler($Sender, $Args) {
			$Feature = GetValue('Feature', GetValue('Discussion', $Args));
			  
			  if ($Feature) {
				 echo ' <span class="Tag Tag-Feature">'.T('Feature').'</span> ';	  
				 }
			   }  
		
		 /**
		* Add CSS class to conversation
		*/ 
		 public function DiscussionsController_BeforeDiscussionName_Handler($Sender, $Args) {
			 $Feature = GetValue('Feature', GetValue('Discussion', $Args));
			 if ($Feature) {
				$Sender->EventArguments['CssClass'] .= " Feature";
				}
			  }		 
}