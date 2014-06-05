<?php if (!defined('APPLICATION')) exit();
/*
Based on PrivateCommunity created by Mark O'Sullivan
*/

// Define the plugin:
$PluginInfo['GoogleFirst'] = array(
   'Name' => 'Google First',
   'Description' => 'Requires Private community. Lets the community be searched by Google and implements  <a href="http://googlewebmastercentral.blogspot.ca/2008/10/first-click-free-for-web-search.html"/>Google First Click Free</a>. Please note saavy users can still spoof in without registering.',
   'Version' => '1.0',
   'Author' => "Adrian Speyer",
   'RequiredPlugins' => array('PrivateCommunity' => '1.0'),
);

class GoogleFirstPlugin extends Gdn_Plugin {

 
	public function Gdn_Dispatcher_AppStartup_Handler($Sender) {
		//Check  if user can view discussions	
		if (Gdn::Session()->CheckPermission('Vanilla.Discussions.View')){
		
		//Googlebot has full access
		if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), "googlebot")){
		
		/* More complex check for Googlebot
		$googlebotip = $_SERVER['REMOTE_ADDR']; 
		$googlebothost = gethostbyaddr( $botip ); 
		$verifiedgooglebotip = gethostbyname( $googlebothost );
		if ( $googlebotip = $verifiedbotip ) { 
		 if ( substr($googlebothost, -11) == '.googlebot.') { 
		//place code
		} 
		*/
		
		SaveToConfig('Garden.PrivateCommunity', FALSE);
		}
		//User comes from Google gets access, but cookie is set
		elseif(isset($_SERVER['HTTP_REFERER'])) {
			if (strpos($_SERVER['HTTP_REFERER'], 'google') == TRUE){
			setcookie('gfcf', true, time() + (60 * 60));
			SaveToConfig('Garden.PrivateCommunity',FALSE);
				}
			}
		
		else
		//Otherwise site is shut and require login
         SaveToConfig('Garden.PrivateCommunity',TRUE);
		}
		
		//Now lets look at the cookie set by Google Visits and increments pageviews
		if(isset($_COOKIE['gfcf'])) {
		$fcfvalue = ++$_COOKIE['gfcf'];
		setcookie('gfcf',$fcfvalue, time() + (60 * 60));
		}
		
		//Once Google vistor hit 5 visits
		if ($_COOKIE['gfcf'] >5){
		SaveToConfig('Garden.PrivateCommunity',TRUE);
		}
		
		if ($_COOKIE['gfcf'] >5 && $_COOKIE['gfcf']=6){
		if (!Gdn::Session()->IsValid()) { 
		echo '<script>alert("Your free views are finished. Please signin or apply for a membership");</script>';
		  }
		 }
		 
		 if (Gdn::Session()->IsValid()) { 
		 unset($_COOKIE['gfcf']);
		 }
		 
		}

    public function Setup() {
      // No setup required
		}  
}