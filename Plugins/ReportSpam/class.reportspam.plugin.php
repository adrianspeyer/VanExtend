<?php if (!defined('APPLICATION')) exit();
//Thanks to John for patience and time

$PluginInfo['ReportSpam'] = array(
    'Name' => 'Report Spam',
    'Description' => 'Reports Spam to Stop Forum Spam.',
    'Version' => '1.0.5',
    'RequiredApplications' => array('Vanilla' => '2.3'),
    'SettingsUrl' => '/settings/reportspam',
    'SettingsPermission' => 'Garden.Settings.Manage',
    'MobileFriendly' => TRUE,
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com',
    'License' => 'GNU GPL2'
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
            if (!$this->SendToSFS(
                $content['InsertName'],
                ipDecode($content['InsertIPAddress']),
                $content['InsertEmail'],
                $content['Body'],
                $Sender
            )
            ) {
				//Message back from Stop Forum Spam
                $HTMLResponse = $Sender->Data('Response');
                $Sender->Form->AddError("Error: ".strip_tags($HTMLResponse));
            }
            if ($Sender->Form->ErrorCount() == 0) {
                //delete content
                $FormValues = $Sender->Form->FormValues();
                $DeleteContent = val('DeleteContent', $FormValues);
                if ($DeleteContent == '1') {
                    if ($context == 'comment') {
                        $Model = new CommentModel();
                    } elseif ($context == 'discussion') {
                        $Model = new DiscussionModel();
                    }
                    $Model->Delete($contextID);
                }

                $BanUser = val('BanUser', $FormValues);
                $BanUserDelete = val('BanUserDelete', $FormValues);
                if ($BanUser == '1' || $BanUserDelete == '1') {
                    $UserModel = Gdn::UserModel();
                    $UserModel->Ban(
                        $content['InsertUserID'],
                        array(
                            'DeleteContent' => $BanUserDelete,
                            'Reason' => 'Spam'
                        )
                    );
                    $Sender->InformMessage(T('Your spam report has been sent.'), 'Dismissable');
                }
                $Sender->JsonTarget('', '', 'Refresh');
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

    public function SendToSFS($Username, $IP, $Email, $Evidence, $Sender) {

        $data = "username=" . urlencode($Username) . "&ip_addr=" . urlencode($IP) . "&email="
	  . urlencode($Email) . "&api_key=" . C('Plugins.ReportSpam.APIKey') . "&evidence="
          . urlencode($Evidence);

        $Proxy = new ProxyRequest();
        $Response = $Proxy->Request(
            array(
                'URL' => 'http://www.stopforumspam.com/add.php',
                'Method' => 'POST',
                'PreEncodePost' => true
            ),
            $data,
            '',
            array('Accept' => 'application/x-www-form-urlencoded')
        );

        $Sender->SetData('Response', $Response);

        if ($Proxy->ResponseStatus == 200) {
            return true;
        }
        return false;
    }

    public function SettingsController_ReportSpam_Create($Sender, $Args = array()) {
        $Sender->Permission('Garden.Settings.Manage');
        $Sender->SetData('Title', T('Enter StopForumSpam API key'));

        $Text = '<div class="Info">' .
            sprintf(
                T('Get your StopForumSpam API key %s.'),
                Anchor(T('here'), 'http://www.stopforumspam.com/signup')
            ) .
            '</div>';
        $Sender->AddAsset('Content', $Text, 'MoreLink');


        $Cf = new ConfigurationModule($Sender);
        $Cf->Initialize(
            array(
                'Plugins.ReportSpam.APIKey' => ''
            )
        );


        $Sender->AddSideMenu('dashboard/settings/plugins');
        $Cf->RenderAll();
    }

}	
