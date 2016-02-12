<?php if (!defined('APPLICATION')) exit();

    $PluginInfo['Plzconfirm'] = array(
    'Name' => 'Please Confirm',
    'Description' => 'Make it much clearer need to confirm email.',
    'Version' => '1.0',
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
                $Message = formatString(t('<div class="confirmalert"><p> Before continuing, you\'ll need to confirm your email. <a href="{/entry/emailconfirmrequest,url}">Resend confirmation</a>.</p></div>.', '<div class="confirmalert"><p> Before continuing, you\'ll need to confirm your email. <a href="{/entry/emailconfirmrequest,url}">Resend confirmation</a>.</p></div>'));
                $Sender->informMessage($Message,'');
            }
        }
     }

 }
