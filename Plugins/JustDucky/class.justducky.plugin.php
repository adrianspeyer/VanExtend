<?php if (!defined('APPLICATION')) exit();

$PluginInfo['JustDucky'] = array(
   'Name' => 'Just Ducky',
   'Description' => "Replaces standard Vanilla search which a DuckDuckGo.com searchbox. Use {duck_searchbox} in your template. See readme for more details.",
   'Version' => '1.0.2',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com',
   'License' => 'GNU GPL2'
);

	function duckSearchbox($params, &$smarty) {
	    echo'<iframe src="http://duckduckgo.com/search.html?width=175&site='.Url('/', TRUE).'&prefill=Search" style="overflow:hidden;margin:0;padding:0;width:258px;height:40px;" frameborder="0"></iframe>';
	   }
	
	class JustDuckyPlugin extends Gdn_Plugin {
		public function Gdn_Smarty_init_handler($Sender) {
	  $Sender->register_function('duck_searchbox', 'duckSearchbox');
	  }
	}