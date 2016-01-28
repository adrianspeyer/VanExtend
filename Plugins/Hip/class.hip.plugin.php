<?php if (!defined('APPLICATION')) exit();
    $PluginInfo['Hip'] = array(
    'Name' => 'Hide Admin IP',
    'Description' => 'This plugin will change IP for the Admin.',
    'Version' => '1.0',
    'MobileFriendly' => TRUE,
    'License' => 'GNU GPL2',
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com'
);

// Inspired by https://vanillaforums.org/discussion/comment/218076#Comment_218076
class HipPlugin extends Gdn_Plugin {

public function Base_AppStartup_Handler() {
    $session = gdn::Session();

    if($session->User->Admin){
    Gdn::Request()->RequestAddress('0.0.0.0.1'); //change to whatever IP you want.
     }
   }
 }


