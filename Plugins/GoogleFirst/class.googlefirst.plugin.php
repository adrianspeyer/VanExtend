<?php if (!defined('APPLICATION')) exit();
/*
Based on PrivateCommunity created by Mark O'Sullivan
*/

// Define the plugin:
$PluginInfo['GoogleFirst'] = array(
   'Name' => 'Google First',
   'Description' => 'Lets the community be searched by Google and implements a lazy <a href="http://googlewebmastercentral.blogspot.ca/2008/10/first-click-free-for-web-search.html"/>Google First Click Free</a>. Please note saavy users can still spoof in without registering.',
   'Version' => '1.0',
   'Author' => "Adrian Speyer",
   'RequiredPlugins' => array('PrivateCommunity' => '1.0'),
);

class GoogleFirstPlugin extends Gdn_Plugin {

 
  public function Gdn_Dispatcher_AppStartup_Handler($Sender) {
      if (Gdn::Session()->CheckPermission('Vanilla.Discussions.View')){
   //Googlebot has full access
if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot")){
 SaveToConfig('Garden.PrivateCommunity', FALSE);
}
elseif(isset($_SERVER['HTTP_REFERER'])) {
		if (strpos($_SERVER['HTTP_REFERER'], 'google') == TRUE){
		SaveToConfig('Garden.PrivateCommunity',FALSE);
	}}
	
else
         SaveToConfig('Garden.PrivateCommunity',TRUE);
	}
	}
	
   public function Setup() {
      // No setup required
   }  
}