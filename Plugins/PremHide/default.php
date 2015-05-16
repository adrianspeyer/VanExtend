<?php 
$PluginInfo['PremHide'] = array(
   'Description' => 'This plugin allows you to insert Premium Content which will be hidden with [prem][/prem] shortcodes.',
   'Version' => '1.0.8',
   'MobileFriendly' => TRUE,
	'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com',
    'License' => 'GNU GPL2'
);

class PremHidePlugin extends Gdn_Plugin {
  public function DiscussionController_AfterCommentFormat_Handler($Sender) {
         $Session = Gdn::Session();
        $pattern = '#\[prem\](.*?)\[\/prem\]#si';
        $body = $Sender->EventArguments['Object']->FormatBody;
        if (($Session->IsValid())) {
        $pattern = '#\[.?prem\]#si';
        $context = preg_replace($pattern, "", $body); 
        }  else {
         $LoginStr = "<p>".T("Please")." <a href='".Url('/entry/signin')."'>".T("Login ")."</a>".T("To See Premium Content")."</p>";
         $context = preg_replace($pattern, $LoginStr, $body);
        }  
        $Sender->EventArguments['Object']->FormatBody = $context;
    }

	//suggestion by peregrine
   public function PostController_AfterCommentFormat_Handler($Sender) {
    $this->DiscussionController_AfterCommentFormat_Handler($Sender);
   }
}
