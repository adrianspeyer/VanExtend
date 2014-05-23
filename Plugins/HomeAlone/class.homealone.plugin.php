<?php if (!defined('APPLICATION')) exit();

$PluginInfo['HomeAlone'] = array(
   'Name' => 'Home Alone',
   'Description' => 'This plugin prevents admin or system user content from being deleted.',
   'Version' => '1.0',
   'RegisterPermissions' => array('Plugins.HomeAlone.Manage' => 1),
   'Author' => "Adrian",
   'AuthorUrl' => 'http://www.vanillaforums.com',
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
    'License' => 'GNU GPL2'
);

class HomeAlonePlugin extends Gdn_Plugin {

public function UserController_BeforeDeleteContent($Sender){
$isHomeAlone = Gdn::Session()->CheckPermission('Plugins.HomeAlone.Manage');
if ($isHomeAlone) {
 $this->Permission('Garden.Moderation.Manage');
     $User = $UserModel->GetID($UserID);
      if (GetValue('Admin', $User) == 230)
         throw ForbiddenException("@You may not delete content of a System user.");
      elseif (GetValue('Admin', $User))
         throw ForbiddenException("@You may not delete content of an Admin.");	 
}}
   public function Setup() {
   }    
}