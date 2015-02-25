<?php if (!defined('APPLICATION')) exit();
$PluginInfo['20Share'] = array(
   'Name' => '2000 Share',
   'Description' => "Allows you to add Digg, Delicious and Reddit share icons to recent discussion",
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

	class NinetySharePlugin extends Gdn_Plugin {
	
	public function Base_Render_Before($Sender) {
		  $Sender->Head->AddCss('/plugins/20share/design/20share.css');
		 }
	   
	   public function DiscussionsController_AfterDiscussionTitle_Handler($Sender) {
		     $DiscussionUrl = $Sender->EventArguments['DiscussionUrl'];   
			 $DiscussionTitle = $Sender->EventArguments['DiscussionName'];
			 echo'
		<ul class="NinetySocialBookmarks">
		<li class="AddTo">
		 <a target="_blank" href="http://reddit.com/submit?url='.urlencode($DiscussionUrl).'&amp;title='.urlencode($DiscussionTitle).'"><img src="./plugins/20share/design/reddit.png" width="16" height="16" alt="Add to Reddit" title="Add to Reddit" /></a>
		 </li>
		 <li class="AddTo">
		 <a target="_blank" href="https://delicious.com/save?v=5&amp;provider=Vanilla&amp;noui=1&amp;jump=close&amp;url='.urlencode($DiscussionUrl).'&amp;title='.urlencode($DiscussionTitle).'"><img src="./plugins/20share/design/delicious.png" width="16" height="16" alt="Add to delicious" title="Add to delicious" /></a>
		 </li>
		  <li class="AddTo">
         <a target="_blank" href="http://digg.com/submit?phase=2&amp;url='.urlencode($DiscussionUrl).'"><img src="./plugins/20share/design/digg.png" width="16" height="16" alt="Digg it" title="Add to Digg" /></a>
		 </li>
		 </ul>';
			}
}
	
	