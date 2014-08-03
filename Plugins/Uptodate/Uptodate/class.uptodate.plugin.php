<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Uptodate'] = array(
	'Name' => 'Up To Date',
	'Description' => 'Checks your Vanilla Version.',
	'Version' => '1.0',
	'SettingsPermission' => 'Garden.AdminUser.Only',
	'Author' => 'Adrian Speyer'
);

class UptodatePlugin implements Gdn_IPlugin {
	
	public function Base_Render_Before($Sender) {
	{
	      if (Gdn::Session()->CheckPermission('Garden.AdminUser.Only')) {		
		
			//version check file will be created, if it has checked before.
			$versionckr = "plugins/Uptodate/version.txt";
		
			//Create file if it does not exist
			if (!file_exists($versionckr)){
			
			//get Vanilla version from add-ons at Vanillaforums.org
			$versiondata = file_get_contents('http://vanillaforums.org/addon/vanilla-core?DeliveryMethod=JSON&DeliveryType=DATA');
				$ossversion = json_decode($versiondata);
				$version = $ossversion->{'Version'}; 
				$file = fopen("plugins/Uptodate/version.txt","w");
				fwrite($file,$version);
				fclose($file);
			}
		
		//Checks if a previous version check has has happened (right now check is every 7 days)
		  if (file_exists($versionckr)&& (time() - filemtime($versionckr) <= 7 * 86400)) {
		
							//Grabs current version of Vanilla install
							$VanillaVersion = APPLICATION_VERSION;

							//Read version from txt file
							$version = array_shift(file($versionckr));

							//Compares versions, and if old version prints message
							if ($VanillaVersion < $version){
								echo '<div style="color:#00FF00; background-color:red;text-align:center;"><b>Your version of Vanilla is out of date. Current version:'.$version.'</b></div>';
															}		
							//deletes file
							if ($VanillaVersion > $version){
								unlink($versionckr);
															}		
				}
			
			}	
		}
	}
	 public function Setup() {
    }
}
