<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HideRant'] = array(
   'Name' => 'HideRant',
   'Description' => 'Posts tagged with "rant" have author info hidden from guests. PROTOTYPE - Does not work',
   'Version' => '1.0.1',
   'RequiredPlugins' => array('Tagging' => '1.8'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class HideRantPlugin extends Gdn_Plugin {

 /* public function discussionModel_afterSaveDiscussion_handler($Sender) */

   public function DiscussionController_BeforeDiscussionDisplay_Handler($Sender, $Args) {


				//Get discussionID that is being shown
				$DiscussionID =$Sender->EventArguments['DiscussionID'];

				  //Get List of tags to reduce count for
				  $TagDataSet = Gdn::SQL()->Select('TagID')
						->From('tagdiscussion')
						->Where('DiscussionID =',$DiscussionID)
						->Get();
				  if ($SingleTag = "rant"||"Rant") {
               $Args['Author'] ='Unknown';
               $Author = Gdn::userModel()->getID($Comment->InsertUserID);

               }
			}


   public function DiscussionsController_AfterCountMeta_Handler  ($Sender, $Args) {
/*
if ($SingleTag = "rant"||"Rant") {
      if (val('FirstUser', $Args)) {
         $Sender->EventArguments['FirstUser'] = "Unknown";
         $Sender->EventArguments['LastUser'] = "Unknown";
               }
         }
     }
*/

         public function categoriesController_afterDiscussionLabels_handler($Sender, $Args) {

         //   $Sender->EventArguments['LastComment'] = "Unknown";

   }


}
