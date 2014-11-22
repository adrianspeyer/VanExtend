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
   	//API Key
     $SFSkey = C('Plugins.ReportSpam.APIKey');
     if (!$SFSkey){$Sender->InformMessage(T('You cannot report spam until your enter an API Key.'), 'Dismissable');}
     $Sender->EventArguments['DiscussionOptions']['ReportSpam'] = array('Label' => T('Report Forum Spam'), 'Url' => '/discussion/SFSOptions/discussion/'.$Args['Discussion']->DiscussionID, 'Class' => 'Popup');
   }
  }

   public function DiscussionController_CommentOptions_Handler($Sender, $Args) {
    if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
      $Sender->EventArguments['CommentOptions']['ReportSpam'] = array('Label' => T('Report Forum Spam'), 'Url' => '/discussion/SFSOptions/comment/'.$Args['Comment']->CommentID.'/#Comment_'.$Args['Comment']->CommentID, 'Class' => 'Popup');
      }
	}
   

    public function DiscussionController_SFSOptions_Create($Sender, $Args) {
      if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {

		//APIkey
		 $SFSkey = C('Plugins.ReportSpam.APIKey');
		 
		 //get arguments
		if (count($Sender->RequestArgs) != 2) {
		throw new Gdn_UserException('Bad Request', 400);
		}

		list($context, $contextID) = $Sender->RequestArgs;
		$content = getRecord($context,$contextID);
		$Sender->setData('content', $content);
		$Sender->Form = new Gdn_Form();

		if ($Sender->Form->AuthenticatedPostBack() === true) {
			 if ($Sender->Form->ErrorCount() == 0) {
				$FormValues = $Sender->Form->FormValues();
				$contextID = val('contextID', $FormValues);
				$context = val('context', $FormValues);
				$content = getRecord($context,$contextID);
				//var_dump('SampleData:', $content);
				$this->PostToHost("username=".urlencode($content['InsertName'])."&ip_addr=".urlencode($content['InsertIPAddress'])."&email=".urlencode($content['InsertEmail'])."&api_key=".$SFSkey."&evidence=".urlencode($content['Body']));
			   }  
		}
			else {
		$Sender->Form->AddHidden('context', $context);
		$Sender->Form->AddHidden('contextID', $contextID);
		}

		$Title = t('Stop Forum Spam Report');
		$Data = array(     
		'Title' => $Title 
		);


		$Sender->SetData($Data);
		$Sender->Form->SetData($Data);

		$Sender->Render('sfsoptions','', 'plugins/ReportSpam');

			}
		  }
      
     public function CommentController_SFSOptions_Create($Sender, $Args) {

     if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
	 
	 	//APIkey
		 $SFSkey = C('Plugins.ReportSpam.APIKey');

		 //get arguments
		if (count($Sender->RequestArgs) != 2) {
		throw new Gdn_UserException('Bad Request', 400);
		}

		list($context, $contextID) = $Sender->RequestArgs;
		$content = getRecord($context,$contextID);
		$Sender->setData('content', $content);
		$Sender->Form = new Gdn_Form();

		if ($Sender->Form->AuthenticatedPostBack() === true) {
			 if ($Sender->Form->ErrorCount() == 0) {
				$FormValues = $Sender->Form->FormValues();
				$contextID = val('contextID', $FormValues);
				$context = val('context', $FormValues);
				$content = getRecord($context,$contextID);
				//var_dump('SampleData:', $content);
				$this->PostToHost("username=".urlencode($content['InsertName'])."&ip_addr=".urlencode($content['InsertIPAddress'])."&email=".urlencode($content['InsertEmail'])."&api_key=".$SFSkey."&evidence=".urlencode($content['Body']));
			   }
		}
			else {
		$Sender->Form->AddHidden('context', $context);
		$Sender->Form->AddHidden('contextID', $contextID);
		}

		$Title = t('Stop Forum Spam Report');
		$Data = array(     
		'Title' => $Title 
		);


		$Sender->SetData($Data);
		$Sender->Form->SetData($Data);

		$Sender->Render('sfsoptions','', 'plugins/ReportSpam');

			}
		  }
      
	public function PostToHost($data) {
	   $fp = fsockopen("www.stopforumspam.com",80);
	   fputs($fp, "POST /add.php HTTP/1.1\n" );
	   fputs($fp, "Host: www.stopforumspam.com\n" );
	   fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
	   fputs($fp, "Content-length: ".strlen($data)."\n" );
	   fputs($fp, "Connection: close\n\n" );
	   fputs($fp, $data);
	   fclose($fp);
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
          'Plugins.ReportSpam.APIKey' => array()
          ));
		  
		 
      $Sender->AddSideMenu('dashboard/settings/plugins');
      $Cf->RenderAll();
   }	
	
}	