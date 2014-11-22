<?php
if (!defined('APPLICATION')) { exit(); }

//Thanks to John for patience and time

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
		
		$UserModel = Gdn::UserModel();
		$User = $UserModel->GetID($Args['Discussion']->InsertUserID);
		if ($UserModel->CheckPermission($User, 'Garden.Moderation.Manage')) { 
			return;
		}
		
        if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
            $Sender->EventArguments['DiscussionOptions']['ReportSpam'] = array(
                'Label' => T('Report Forum Spam'),
                'Url' => '/discussion/SFSOptions/discussion/' . $Args['Discussion']->DiscussionID,
                'Class' => 'Popup'
            );
        }
    }

    public function DiscussionController_CommentOptions_Handler($Sender, $Args) {
        
		$UserModel = Gdn::UserModel();
		$User = $UserModel->GetID($Args['Comment']->InsertUserID);
		if ($UserModel->CheckPermission($User, 'Garden.Moderation.Manage')) { 
			return;
		}
		
		if (Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
            $Sender->EventArguments['CommentOptions']['ReportSpam'] = array(
                'Label' => T('Report Forum Spam'),
                'Url' => '/discussion/SFSOptions/comment/' . $Args['Comment']->CommentID . '/#Comment_' . $Args['Comment']->CommentID,
                'Class' => 'Popup'
            );
        }
    }


    public function DiscussionController_SFSOptions_Create($Sender, $Args) {
		if (!Gdn::Session()->CheckPermission('Garden.Moderation.Manage')) {
			return;
		}			
		 
		 //API Key
		$SFSkey = C('Plugins.ReportSpam.APIKey');
		if (!$SFSkey) {
			$Sender->InformMessage(T('You cannot report spam until your enter an API Key.'), 'Dismissable');
		}

		//get arguments
		if (count($Sender->RequestArgs) != 2) {
			throw new Gdn_UserException('Bad Request', 400);
		}

		list($context, $contextID) = $Sender->RequestArgs;
		$content = getRecord($context, $contextID);
		$Sender->setData('content', $content);
		$Sender->Form = new Gdn_Form();
				
		if ($Sender->Form->AuthenticatedPostBack() === true) {
			if ($Sender->Form->ErrorCount() == 0) {
				$this->SendToSFS($content['InsertName'], $content['InsertIPAddress'], $content['InsertEmail'], $content['Body']);
			    $Sender->InformMessage(T('Your spam report has been sent.'));
			}
		} else {
			$Sender->Form->AddHidden('context', $context);
			$Sender->Form->AddHidden('contextID', $contextID);
		}

		$Title = t('Stop Forum Spam Report');
		$Data = array(
			'Title' => $Title
		);


		$Sender->SetData($Data);
		$Sender->Form->SetData($Data);

		$Sender->Render('sfsoptions', '', 'plugins/ReportSpam');
    }

    public function SendToSFS($Username, $IP, $Email, $Evidence) {

        $data = "username=" . urlencode($Username) . "&ip_addr=" . urlencode(
                $IP
            ) . "&email=" . urlencode(
                $Email
            ) . "&api_key=" . C('Plugins.ReportSpam.APIKey') . "&evidence=" . urlencode($Evidence);

        $fp = fsockopen("www.stopforumspam.com", 80);
        fputs($fp, "POST /add.php HTTP/1.1\n");
        fputs($fp, "Host: www.stopforumspam.com\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: " . strlen($data) . "\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, $data);
        fclose($fp);
    }

    public function SettingsController_ReportSpam_Create($Sender, $Args = array()) {
        $Sender->Permission('Garden.Settings.Manage');
        $Sender->SetData('Title', T('Enter StopForumSpam API key'));

        $Text = '<div class="Info">' .
            sprintf(
                T('Get more your StopForumSpam API key %s.'),
                Anchor(T('here'), 'http://www.stopforumspam.com/signup')
            ) .
            '</div>';
        $Sender->AddAsset('Content', $Text, 'MoreLink');


        $Cf = new ConfigurationModule($Sender);
        $Cf->Initialize(
            array(
                'Plugins.ReportSpam.APIKey' => array()
            )
        );


        $Sender->AddSideMenu('dashboard/settings/plugins');
        $Cf->RenderAll();
    }

}	