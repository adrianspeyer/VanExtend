<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Hubspot'] = array(
   'Name' => 'Hubspot',
   'Description' => 'Adds Hubspot tracking, and new users to your Hubspot contacts list. <b>Please note you need to create a form in your Hubspot dashboard, with First Name and email</b>',
   'Version' => '1.0b',
   'SettingsUrl' => '/settings/hubspot',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://adrianspeyer.com'
);

class HubspotPlugin extends Gdn_Plugin {

   public function SettingsController_Hubspot_Create($Sender, $Args = array()) {
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('Title', T('Hubspot Settings'));
	  
	   $Text = '<div class="Info">' .
            sprintf(
                T('Get your GUID from a form in your Hubspot first. It must contain first name and email')
            ) .
            '</div>';
        $Sender->AddAsset('Content', $Text, 'MoreLink');

      $Cf = new ConfigurationModule($Sender);
      $Cf->Initialize(array(
          'Plugins.Hubspot.GUID' => array(),
          'Plugins.Hubspot.PortalID' => array()
          ));

      $Sender->AddSideMenu('dashboard/settings/plugins');
      $Cf->RenderAll();
   }
   
  
  //Add user to hubspot
 	
  public function entryController_registrationSuccessful_handler ($Sender) {
	  
	   $sender->Form->SetFormValue(
            'Target',
            './plugin/Hubspot/views/insert.php'
        );
		
	/* Had to move to seperate file
	
		  $GUIDno = C('Plugins.Hubspot.GUID');
		  $PortalID = C('Plugins.Hubspot.PortalID');

		 $hubspotutk      = $_COOKIE['hubspotutk']; //grab the cookie from the visitors browser.
		 $ip_addr         = $_SERVER['REMOTE_ADDR']; //IP address too.
		 $pageurl 		  = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		 $hs_context      = array(
        'hutk' => $hubspotutk,
        'ipAddress' => $ip_addr,
        'pageUrl' => $pageurl,
        'pageName' => 'Registration';
         );
        $hs_context_json = json_encode($hs_context);

  //Need to populate these variables with values from the form.
    $str_post = "firstname=" . urlencode($firstname) 
    . "&email=" . urlencode($email) 
    . "&hs_context=" . urlencode($hs_context_json); //Leave this one be

   //replace the values in this URL with your portal ID and your form GUID
	$   endpoint = 'https://forms.hubspot.com/uploads/form/v2/.'$portalId.'/.'$GUIDno'.';

		$ch = @curl_init();
		@curl_setopt($ch, CURLOPT_POST, true);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
		@curl_setopt($ch, CURLOPT_URL, $endpoint);
		@curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded'
		));
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response    = @curl_exec($ch); //Log the response from HubSpot as needed.
		$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
		@curl_close($ch);
		echo $status_code . " " . $response;	
*/		
	}
	
 
   //Render tracking
    public function Base_AfterBody_Handler($Sender) {
		if ($Sender->MasterView == 'admin')
			return;
		  $PortalID = C('Plugins.Hubspot.PortalID');
	echo"
		<!-- Start of Async HubSpot Analytics Code -->
  <script type='text/javascript'>
    (function(d,s,i,r) {
      if (d.getElementById(i)){return;}
      var n=d.createElement(s),e=d.getElementsByTagName(s)[0];
      n.id=i;n.src='//js.hs-analytics.net/analytics/'+(Math.ceil(new Date()/r)*r)+'/".$PortalID.".js';
      e.parentNode.insertBefore(n, e);
    })(document,'script','hs-analytics',300000);
  </script>
<!-- End of Async HubSpot Analytics Code -->
";
		
	}
 
}