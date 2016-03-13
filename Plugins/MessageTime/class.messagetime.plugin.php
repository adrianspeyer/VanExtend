<?php if (!defined('APPLICATION')) exit();

    $PluginInfo['messagetime'] = array(
    'Name' => 'Message Time',
    'Description' => 'Welcome a user, with appropriate message based on time.',
    'Version' => '1.0.1',
    'MobileFriendly' => TRUE,
    'License' => 'GNU GPL2',
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com'
);


class MessagetimePlugin extends Gdn_Plugin {

public function profileController_afterPreferencesDefined_handler($Sender) { //need a better function
    $Session = Gdn::Session();
    $username = Gdn::Session()->User->Name;


    $allmessages = array
    (", how does it feel, to be on your own like a rolling stone!",
     ", really you're here again?",
     ", you're the best, better than all the rest!",
     ", STOP in the name of love!",
     ", Smell ya later, stinker"
     );

	$totalmessages = (count($allmessages));
	$mnmbr = (rand(0,($totalmessages)));
	$messagetime = $allmessages[$mnmbr];

    if($Session->CheckPermission('Garden.SignIn.Allow')) {
      $Sender->InformMessage('Howdy '.$username.$messagetime);
    }
  }
}
