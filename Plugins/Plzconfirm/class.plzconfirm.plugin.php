<?php if (!defined('APPLICATION')) exit();

    $PluginInfo['Plzconfirm'] = array(
    'Name' => 'Please Confirm',
    'Description' => 'If you have a requirement for people to confirm their email to register, this makes it 
more obvious to the prospective member to confirm their email.',
    'Version' => '1.0.1',
    'MobileFriendly' => TRUE,
    'License' => 'GNU GPL2',
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com'
);


class PlzconfirmPlugin extends Gdn_Plugin {

   public function assetModel_styleCss_handler($Sender, $Args) {
	        $Sender->addCssFile('plzconfirm.css', 'plugins/plzconfirm');
	    }

public function base_render_before($Sender) {

$Session = Gdn::session();
if ($Session->isValid()) {
            $Confirmed = val('Confirmed', Gdn::session()->User, true);
            if (UserModel::requireConfirmEmail() && !$Confirmed) {
                echo '<div class="confirmalert"><p> Before continuing, you\'ll need to confirm your email. <a href="{/entry/emailconfirmrequest,url}">Resend confirmation</a>.</p></div>';      
            }
        }
     }

 }
