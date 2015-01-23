<?php if (!defined('APPLICATION')) exit();
/**
 * Dyslexia Plugin: uses font from https://github.com/antijingoist/open-dyslexic
 * 
 * @author Adrian
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL
 * @package Addons
 */

$PluginInfo['Dyslexia'] = array(
   'Name' => 'Dyslexia',
   'Description' => "Allows users with Dyslexia to select Open Dyslexic, a font friendly for those with Dyslexia to change forum font via a link in the footer.",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class DyslexiaPlugin extends Gdn_Plugin {


 public function ProfileController_AfterAddSideMenu_Handler($Sender) {
      if (!Gdn::Session()->CheckPermission('Garden.SignIn.Allow'))
         return;

      $SideMenu = $Sender->EventArguments['SideMenu'];
      $ViewingUserID = Gdn::Session()->UserID;

      if ($Sender->User->UserID == $ViewingUserID) {
         $SideMenu->AddLink('Options', Sprite('').' '.T('Dyslexia Settings'), '/profile/dyslexia', FALSE, array('class' => 'Popup'));
      } else {
         $SideMenu->AddLink('Options', Sprite('').' '.T('Dyslexia Settings'), UserUrl($Sender->User, '', 'dyslexia'), 'Garden.Users.Edit', array('class' => 'Popup'));
      }
   }

  public function ProfileController_Dyslexia_Create($Sender) {
      $Sender->Permission('Garden.SignIn.Allow');
      $Sender->Title("Dyslexia Settings");

      $Args = $Sender->RequestArgs;
      if (sizeof($Args) < 2)
         $Args = array_merge($Args, array(0, 0));
      elseif (sizeof($Args) > 2)
         $Args = array_slice($Args, 0, 2);

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
      $DyslexiaFont = GetValue('Dyslexia.Font', $UserPrefs, '0');
      $Sender->Form->SetValue('DyslexiaFont', $DyslexiaFont);

      $Sender->SetData('DyslexiaFontOptions', array(
          '0' => "Disabled",
          '1' => 'Enabled'
      ));

		
		 // If seeing the form for the first time...
      if ($Sender->Form->IsPostBack()) {
         $Dyslexia = $Sender->Form->GetValue('DyslexiaFont', '0');
         if ($Dyslexia  != $DyslexiaFont) {
            Gdn::UserModel()->SavePreference($UserID, 'Dyslexia.Font', $Dyslexia);
            $Sender->InformMessage(T("Your changes have been saved."));
         }
      } 

      $Sender->Render('dyslexia', '', 'plugins/Dyslexia');
	} 
    
   public function Base_Render_Before($Sender) {
   
   if (!Gdn::Session()->IsValid())
         return;

      $UserPrefs = Gdn_Format::Unserialize(Gdn::Session()->User->Preferences);
      if (!is_array($UserPrefs))
         $UserPrefs = array();

      $DyslexiaFont = GetValue('Dyslexia.Font', $UserPrefs);

		if ($DyslexiaFont == 1)
		{	
		$Sender->AddCssFile('dyslexic.css', 'plugins/Dyslexia');  	
		}
	}
   
}