<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Piwik'] = array(
	'Name' => 'Piwik Analytics',
	'Description' => 'Piwik Analytics. Adds Piwik to footer. Requires themes with AfterBody event. Also allows you to see your stats from your Vanilla Install.',
	'MobileFriendly' => TRUE,
	'Version' => '2.6.5',
	'Author' => 'Adrian Speyer',
	'AuthorEmail' => 'adriansprojects+vanilla@gmail.com',
	'AuthorUrl' => 'http://www.adrianspeyer.com/',
	'SettingsUrl' => 'settings/piwik',
	
);

class PiwikPlugin implements Gdn_IPlugin {
	
	public function Base_GetAppSettingsMenuItems_Handler($Sender)
	{
	    $Sender->Permission('Garden.Settings.Manage');
		$LinkText = 'Piwik Analytics';
		$Menu = $Sender->EventArguments['SideMenu'];
		$Menu->AddItem('Forum', T('Forum'));
		$Menu->AddLink('Forum', $LinkText, 'settings/piwik', 'Garden.Settings.Manage');
	}	
		
	public function SettingsController_Piwik_Create($Sender) {
		$Sender->Permission('Garden.Settings.Manage');
		$Sender->AddSideMenu();
		$Sender->Title('Piwik Analytics');
		$ConfigurationModule = new ConfigurationModule($Sender);
		$ConfigurationModule->RenderAll = True;
		$Schema = array(
		'Plugins.Piwik.PiwikInstall' => array('LabelCode' => 'PiwikInstall', 'Control' => 'TextBox', 'Default' => C('Plugins.Piwik.PiwikInstall', 'http://www.example.com/piwik/')),
        'Plugins.Piwik.PiwikSiteID' => array('LabelCode' => 'PiwikSiteID', 'Control' => 'TextBox', 'Default' => C('Plugins.Piwik.PiwikSiteID', '1')),
		'Plugins.Piwik.PiwikAuthKey' => array('LabelCode' => 'PiwikAuthKey', 'Control' => 'TextBox', 'Default' => C('Plugins.Piwik.PiwikAuthKey', ''))
	);
		$ConfigurationModule->Schema($Schema);
		$ConfigurationModule->Initialize();
		$Sender->View = dirname(__FILE__) . DS . 'views' . DS . 'settings.php';
		$Sender->ConfigurationModule = $ConfigurationModule;
		$Sender->Render();
	}
	
	
	
	public function Base_AfterBody_Handler($Sender) {
		if ($Sender->MasterView == 'admin') return;
		$PiwikInstall = C('Plugins.Piwik.PiwikInstall');
		$PiwikSiteID = C('Plugins.Piwik.PiwikSiteID');
		$PiwikAuthKey = C('Plugins.Piwik.PiwikAuthKey');


	$ImageTrack =$PiwikInstall;
	$PiwikInstall= str_replace(array('http', 'https'), array('', ''), $PiwikInstall); 

	$last = $PiwikInstall[strlen($PiwikInstall)-1];
	
	if ($last != "/") { 
		$PiwikInstall .= "/";
	}
			
		if ($PiwikInstall) 
			echo 
		<<<Piwik
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "$PiwikInstall";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "$PiwikSiteID"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript>
<!-- Piwik Image Tracker -->
<img src="$ImageTrack/piwik.php?idsite=$PiwikSiteID&amp;rec=1" style="border:0" alt="" />
<!-- End Piwik -->
</noscript>
<!-- End Piwik Code -->
Piwik;
	}
	public function Setup() {
	}
}
