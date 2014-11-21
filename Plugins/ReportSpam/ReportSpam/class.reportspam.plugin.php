<?php if (!defined('APPLICATION')) exit();


// Define the plugin:
$PluginInfo['ReportSpam'] = array(
   'Name' => 'Report Spam',
   'Description' => 'Reports Spam to Stop Forum Spam.',
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'SettingsUrl' => '/settings/reportspam',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => 'Adrian Speyer'
);

class ReportSpamPlugin extends Gdn_Plugin {
  public function Base_DiscussionOptions_Handler($Sender, $Args) {
    if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
	
	 $SFSkey = C('Plugins.ReportSpam.apiKey');
	
    $Sender->EventArguments['DiscussionOptions']['ReportSpam'] = array('Label' => T('Report Forum Spam'), 'Url' => 'http://stopforumspam.com/add.php?username='.urlencode(isset($Args['Discussion']->InsertName) ? $Args['Discussion']->InsertName : $Args['Discussion']->FirstName).'&ip_addr='.urlencode($Args['Discussion']->InsertIPAddress).'&evidence='.urlencode($Args['Discussion']->Body).'&email='.urlencode($Args['Discussion']->InsertEmail).'&api_key='.$SFSkey, 'Class' => 'Popup');
   //Figure out how to push to URL, hide APIKEY
	 
	/* function PostToHost($data) {
	   $fp = fsockopen("www.stopforumspam.com",80);
	   fputs($fp, "POST /add.php HTTP/1.1\n" );
	   fputs($fp, "Host: www.stopforumspam.com\n" );
	   fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
	   fputs($fp, "Content-length: ".strlen($data)."\n" );
	   fputs($fp, "Connection: close\n\n" );
	   fputs($fp, $data);
	   fclose($fp);
	}
	PostToHost("username=USERNAME&ip_addr=IPADDRESS&email=EMAILADDRESS&api_key=XXXXXXXXXXXXX&evidence=YYYYYYYYYY");
	*/
	   
		/*consider deleting content */	
   }
  }

  public function DiscussionController_CommentOptions_Handler($Sender, $Args) {
    if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
	
	 $SFSkey = C('Plugins.ReportSpam.apiKey');
       
      $Sender->EventArguments['CommentOptions']['ReportSpam'] = array('Label' => T('Report Forum Spam'), 'Url' => 'http://stopforumspam.com/add.php?username='.urlencode($Args['Author']->Name).'&ip_addr='.urlencode($Args['Author']->InsertIPAddress).'&evidence='.urlencode($Args['Comment']->Body).'&email='.urlencode($Args['Author']->Email).'&api_key='.$SFSkey, 'Class' => 'Popup');
    //Figure out how to push to URL, hide APIKEY
	/* function PostToHost($data) {
   $fp = fsockopen("www.stopforumspam.com",80);
   fputs($fp, "POST /add.php HTTP/1.1\n" );
   fputs($fp, "Host: www.stopforumspam.com\n" );
   fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
   fputs($fp, "Content-length: ".strlen($data)."\n" );
   fputs($fp, "Connection: close\n\n" );
   fputs($fp, $data);
   fclose($fp);
  }
   PostToHost("username=USERNAME&ip_addr=IPADDRESS&email=EMAILADDRESS&api_key=XXXXXXXXXXXXX&evidence=YYYYYYYYYY");
   */
	}
	
	/*consider deleting content */
	
  }

 public function SettingsController_ReportSpam_Create($Sender, $Args = array()) {
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('Title', T('Enter StopForumSpam API key'));

	   $Text = '<div class="Info">'.
         sprintf(T('Get more your StopForumSpam API key %s.'), Anchor(T('here'), 'http://www.stopforumspam.com/signup')).
         '</div>';
      $Sender->AddAsset('Content', $Text, 'MoreLink');
   
	  
      $Cf = new ConfigurationModule($Sender);
      $Cf->Initialize(array(
          'Plugins.ReportSpam.apiKey' => array()
          ));
		  
		 
      $Sender->AddSideMenu('dashboard/settings/plugins');
      $Cf->RenderAll();
   }	
	
	
}	