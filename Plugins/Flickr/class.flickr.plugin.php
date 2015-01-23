<?php if (!defined('APPLICATION')) exit();
/*
Ensure flickr ID can be saved
*/

// Define the plugin:
$PluginInfo['Flickr2'] = array(
   'Name' => 'Flickr Reloaded',
   'Description' => 'This allows users to have a Flickr Gallery on their profile.',
   'Version' => '1.0.1',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);


class Flickr2Plugin extends Gdn_Plugin {

public function ProfileController_AfterAddSideMenu_Handler($Sender) {
      if (!Gdn::Session()->CheckPermission('Garden.SignIn.Allow'))
         return;

      $SideMenu = $Sender->EventArguments['SideMenu'];
      $ViewingUserID = Gdn::Session()->UserID;

      if ($Sender->User->UserID == $ViewingUserID) {
         $SideMenu->AddLink('Options', Sprite('').' '.T('Flickr Settings'), '/profile/flickr', FALSE, array('class' => 'Popup'));
      } else {
         $SideMenu->AddLink('Options', Sprite('').' '.T('Flickr Settings'), UserUrl($Sender->User, '', 'flickr'), 'Garden.Users.Edit', array('class' => 'Popup'));
      }
   }

  public function ProfileController_Flickr_Create($Sender) {
      $Sender->Permission('Garden.SignIn.Allow');
      $Sender->Title("Flickr Settings");

      list($UserReference, $Username) = $Args;

      $Sender->GetUserInfo($UserReference, $Username);
      $UserPrefs = Gdn_Format::Unserialize($Sender->User->Preferences);
      if (!is_array($UserPrefs)){
	  $UserPrefs = array();
	  }
	
      $UserID = $ViewingUserID = Gdn::Session()->UserID;

      if ($Sender->User->UserID != $ViewingUserID) {
         $Sender->Permission('Garden.Users.Edit');
         $UserID = $Sender->User->UserID;
      }
	  

      $FlickrID = GetValue('FlickrID', $UserPrefs);

      $Sender->Form->SetValue('FlickrID', $FlickrID);
      

		 // If seeing the form for the first time...
       if ($Sender->Form->AuthenticatedPostBack()) {
         $FlickrID = $Sender->Form->GetValue('FlickrID');
		 
	   if (empty($FlickrID)) {
		$Sender->Form->AddError('FlickrID is invalid');
	   }
		 
		 if ($Sender->Form->ErrorCount() == 0) {
            Gdn::UserModel()->SavePreference($UserID, 'FlickrID', $FlickrID);
            $Sender->InformMessage(T("Your changes have been saved."));
         }
      } 

      $Sender->Render('flickr', '', 'plugins/Flickr');
	} 

     public function ProfileController_AfterRenderAsset_Handler($Sender) {
		 
		  if (!Gdn::Session()->IsValid())
         return;

		$UserPrefs = Gdn_Format::Unserialize($Sender->User->Preferences);
		  if (!is_array($UserPrefs)){
		  $UserPrefs = array();
		}	   
	   $FlickrID = GetValue('FlickrID', $UserPrefs);
      
			/* Modify for Number of Pics & Size */
			$NumPics = '10';
			$PicSize = 's';  
			/* End  */
				
			$FlickrCode = '<div id="flickr_tab"><script type="text/javascript"
			src="http://www.flickr.com/badge_code_v2.gne?count='.$NumPics.'
			&display=latest&size=' .$PicSize. '&layout=x&source=user&user='.htmlspecialchars($FlickrID).'">
			</script></div>';
		  
		  
		  // Add flickr stream to panel
      $Sender->AddAsset('Panel', $FlickrCode, 'FlickrPlugin');
 }  
  
   public function Base_Render_Before($Sender) {
 
  $Sender->AddCSSFile('plugins/Flickr/design/flickr.css');
	
	}
   
   public function Setup()
	{}
	
	public function OnDisable()
	{}
}	
	
 