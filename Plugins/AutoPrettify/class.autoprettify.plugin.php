<?php if (!defined('APPLICATION')) die();

$PluginInfo['AutoPrettify'] = array
(
   'Description' => 'Adds google-code-prettify, with line numbers when users use <\code> tags. Only works if you use HTML editor.',
   'Version' => '1.0',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class AutoPrettify extends Gdn_Plugin
{
    var $inline = '<script type="text/javascript">$(function(){prettyPrint();});</script><style type="text/css">div.Message code {background-color:#ddd !important}</style>';

    public function Base_Render_Before($Sender)
    {
        $Sender->AddCssFile($this->GetResource('prettify/prettify.css', FALSE, FALSE));
        $Sender->AddJsFile($this->GetResource('prettify/prettify.js', FALSE, FALSE));
        $Sender->Head->AddString($this->inline);
    }

	 public function DiscussionController_AfterCommentFormat_Handler($Sender) {
        $body = $Sender->EventArguments['Object']->FormatBody;
		$codepatterns = array();
		$codepatterns[0] = '#\<code>#si';
		$codepatterns[1] = '#\<\/code\>#si';
		$codereplacements = array();
		$codereplacements[1] = '<pre class="prettyprint linenums">';
		$codereplacements[0] = '</pre>';
		echo preg_replace($codepatterns, $codereplacements, $body);
        $Sender->EventArguments['Object']->FormatBody = $context;
    }
	
    public function Setup() {}
}
