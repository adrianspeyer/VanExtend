<?php if (!defined('APPLICATION')) exit();

//hat-tip to HGtonight for the idea/solution: http://vanillaforums.org/discussion/comment/218703/#Comment_218703

$PluginInfo['csscat'] = array(
   'Name' => 'CSS Category',
   'Description' => "With this plugin users who added a custom CSS, will have this added to the discussion list.",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

	class csscatPlugin extends Gdn_Plugin {

		 /**
		* Add CSS class to conversation
		*/ 
		 public function DiscussionsController_BeforeDiscussionName_Handler($Sender, $Args) {
				 $Sender->EventArguments['CssClass'] .= ' category-' . Gdn_Format::Url($Sender->EventArguments['Discussion']->Category);
			  }		 			  
			 
 }