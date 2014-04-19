<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['GoBackMobile'] = array(
   'Name' => 'Go Back Mobile',
   'Description' => 'Lets users go back to mobile site, after they have looked at the full version of your site',
   'Version' => '1.0.3',
   'RequiredApplications' => array('Vanilla' => '2.0.18'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://www.adrianspeyer.com'
);

##
## Hat Tip to HGtonight for the Profile Magic Controller Tip 
##

class GoBackMobile extends Gdn_Plugin {	

 public function ProfileController_Mobile_Create($Sender) { 
      $Expiration = time() - 172800;
      $Expire = 0;
      $UserID = ((Gdn::Session()->IsValid()) ? Gdn::Session()->UserID : 0);
      $KeyData = $UserID."-{$Expiration}";
      Gdn_CookieIdentity::SetCookie('VanillaNoMobile', $KeyData, array($UserID, $Expiration, 'force'), $Expire);
      Redirect("/", 302);
   }

public function Base_Render_Before($Sender) {
$ForceNoMobile = Gdn_CookieIdentity::GetCookiePayload('VanillaNoMobile');
if ($ForceNoMobile !== FALSE && is_array($ForceNoMobile)&& in_array('force', $ForceNoMobile))
$Sender->AddAsset('Foot', Wrap(Anchor(T('Back to Mobile Site'), '/profile/mobile/1'),'div id="MBG"'),'Mobile Link');	
$Sender->AddCssFile('addi.css', 'plugins/GoBackMobile');

}
}
?>