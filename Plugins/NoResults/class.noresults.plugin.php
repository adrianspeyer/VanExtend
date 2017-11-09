<?php if (!defined('APPLICATION')) exit();
$PluginInfo['NoResults'] = array(
   'Name' => 'No Results',
   'Description' => "Allows users to continue a search Google or DuckDuckGo for content from your site.",
   'Version' => '1.1',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

	class NoResultsPlugin extends Gdn_Plugin {
	
		 public function SearchController_PagerInit_Handler($Sender, $Args) {
           $host=  Gdn::Request()->Host();
		   $SearchTerm = $_GET['Search'];
		   echo '<b>Another Option</b>: Try your search on <a href="https://www.google.com/search?q=site:'.$host.'+'.$SearchTerm.'" target="_blank">Google</a> or <a href="https://duckduckgo.com/?q=site:'.$host.'+'.$SearchTerm.'" target="_blank">DuckDuckGo</a>';
		 }		 
		 
}
