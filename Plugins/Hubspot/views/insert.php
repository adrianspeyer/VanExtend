<?php if (!defined('APPLICATION')) exit(); ?>
<?php
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
    $str_post = "firstname=" . urlencode($User) 
    . "&email=" . urlencode($EmailKey) 
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
		