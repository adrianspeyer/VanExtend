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
   'Author' => "Adrian",
   'AuthorUrl' => 'http://adrianspeyer.com'
);


class DyslexiaPlugin extends Gdn_Plugin {

   public function ProfileController_Dyslexia_Create($Sender) { 
      $Expiration = time() - 172800;
      $Expire = 0;
      $UserID = ((Gdn::Session()->IsValid()) ? Gdn::Session()->UserID : 0);
      $KeyData = $UserID."-{$Expiration}";
      Gdn_CookieIdentity::SetCookie('DyslexiaFont', $KeyData, array($UserID, $Expiration, 'dyslexia'), $Expire);
      Redirect("/", 302);
   }

   
   public function Base_Render_Before($Sender) {
	$Sender->AddAsset('Foot', Wrap(Anchor(T('Enable Dyslexia Friendly Font'), '/profile/dyslexia/1'),'div class ="DyslexiaOptions"'),'DyslexiaFont');	
	$Dyslexia = Gdn_CookieIdentity::GetCookiePayload('DyslexiaFont');
	if ($Dyslexia !== FALSE){
	$Sender->AddCssFile('dyslexic.css', 'plugins/Dyslexia');
	$Sender->AddAsset('Head', '<style>.DyslexiaOptions { padding-left: 10px; color: #7C8589;} .Dashboard .DyslexiaOptions { display: none; }</style>');
	     }
	}	
 
}