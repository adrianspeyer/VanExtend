<?php if (!defined('APPLICATION')) exit();
/*
Ensure flickr ID can be saved
*/

// Define the plugin:
$PluginInfo['Flickr'] = array(
   'Name' => 'Flickr Reloaded',
   'Description' => 'This allows users to have a Flickr Gallery on their profile.',
   'Version' => '1.0.1',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
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
      if (!is_array($UserPrefs))
         $UserPrefs = array();

      $UserID = $ViewingUserID = Gdn::Session()->UserID;

      if ($Sender->User->UserID != $ViewingUserID) {
         $Sender->Permission('Garden.Users.Edit');
         $UserID = $Sender->User->UserID;
      }

      $Sender->SetData('ForceEditing', ($UserID == Gdn::Session()->UserID) ? FALSE : $Sender->User->Name);
      $FlickrID = GetValue('FlickrID');
      $Sender->Form->SetValue('FlickrID', $FlickrID);

      $Sender->SetData('FlickrID', $FlickrID);    
      
		 // If seeing the form for the first time...
      if ($Sender->Form->IsPostBack()) {
         $FlickrID = $Sender->Form->GetValue('FlickrID');
         if ($FlickrID != $FlickrID) {
            Gdn::UserModel()->SavePreference($UserID, 'FlickrID', $FlickrID);
            $Sender->InformMessage(T("Your changes have been saved."));
         }
      } 

      $Sender->Render('flickr', '', 'plugins/Flickr');
	} 

     public function ProfileController_AfterRenderAsset_Handler($Sender) {

	 if (empty($FlickrID)){$FlickrID = '49912244@N05';}

		  if (!empty($FlickrID))
		  {
			  
			/* Modify for Number of Pics & Size */
			$NumPics = '10';
			$PicSize = 's';  
			/* End  */
				
			$FlickrCode = '<div id="flickr_tab"><script type="text/javascript"
			src="http://www.flickr.com/badge_code_v2.gne?count='.$NumPics.'
			&display=latest&size=' .$PicSize. '&layout=x&source=user&user='.$FlickrID.'">
			</script></div>';
		  }
		  
		  // Add flickr stream to panel
      $Sender->AddAsset('Panel', $FlickrCode, 'FlickrPlugin');
 }  
  
   public function Base_Render_Before($Sender) {
   
   if (!Gdn::Session()->IsValid())
         return;

      $UserPrefs = Gdn_Format::Unserialize(Gdn::Session()->User->Preferences);
      if (!is_array($UserPrefs))
         $UserPrefs = array();

      $FlickrID = GetValue('FlickrID', $UserPrefs);
	  
      $Sender->AddCSSFile('plugins/Flickr/design/flickr.css');
	
	}
   
   public function Setup()
	{
		$Structure = Gdn::Structure();
		
		// Create the database table & columns for the Plugin
		$Structure->Table('User')
	        ->Column('FlickrID', 'varchar(255)', TRUE)
	        ->Set(FALSE, FALSE);
	}
	public function OnDisable()
	{}
}	
	
 