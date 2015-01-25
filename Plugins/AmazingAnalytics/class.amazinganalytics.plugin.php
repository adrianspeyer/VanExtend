<?php if (!defined('APPLICATION')) exit();
//Custom Variable info for GA at : https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingCustomVariables
// Define the plugin:
$PluginInfo['AmazingAnalytics'] = array(
   'Name' => 'Amazing Analytics',
   'Description' => 'Amazing Analytics provide extra data into your Google Analytics, and works only with UNIVERSAL ANALYTICS. It will add data on which Category are most viewed(as events). You will need to create two dimensions in your Google Analytics console. The first dimension will determine if a user is logged in vs not logged in. Scope type should be "session".The second dimension, will advise you the highest RoleType of visitor. Scope type used should be "User". By default we take slots 1 and 2. If these slots are already takenyou will need to modify the code according to the proper dimensions to use. Learn more about Google Analytics dimensions in Universal Aanalytics here: https://developers.google.com/analytics/devguides/platform/customdimsmets.',
   'Version' => '2.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://www.vanillaforums.com'
);

class AmazingAnalyticsPlugin extends Gdn_Plugin {


//This will show you if user is logged in or not   
public function Base_AfterBody_Handler($Sender) {
	
	$Category = CategoryModel::Categories($Sender->Data('CategoryID'));
		if (isset($Category)) {
			$catid = $Sender->CategoryID;
			$cname = $Category['Name'];
			echo "<script>ga('send', 'event', 'category', 'view', '".$cname."');</script>";
		 }

		 echo "<script>"; 
		 
		if (!Gdn::Session()->IsValid()){
			echo "var dimensionValue = 'Anonymous';
			ga('set', 'dimension1', dimensionValue);";
			}
		else			
		   {echo "var dimensionValue = 'Logged In';
			ga('set', 'dimension1', dimensionValue);";
		   }
		echo "</script>";

//Gets you userid number
//$userid = (Gdn::Session()->UserID);


//This will print out a users highest role title
 $RoleModel = new RoleModel();
            $Roles = $RoleModel->GetByUserID(Gdn::Session()->UserID)->ResultArray();
            foreach ($Roles as $Role) {
               if (is_numeric($Expr)) {
                  $Result = $Expr == GetValue('RoleID', $Role);
               } else {
                  $Result = Gdn_Condition::TestValue(GetValue('Name', $Role), $Expr);
               }
			   }
         

echo "<script>"; 
	$roletype = implode(', ', $Roles);
	echo "var dimensionValue = '".$Role['Name']."';
    ga('set', 'dimension2', dimensionValue);";
	echo "</script>"; 

//Work on getting data from profile extender -- will not work without user creating appropriate dimensions first
/*
$ProfileFields = Gdn::UserModel()->GetMeta((Gdn::Session()->UserID), 'Profile.%', 'Profile.');
 if ($ProfileFields) {
 echo '<script>';
$vc=3; //starts profile extender at 3rd variable
foreach($ProfileFields as $PR=>$PR_value)
  {
  echo "_gaq.push(['_setCustomVar', '".$vc."','".$PR."','".$PR_value."', 1 ]);";
  $vc++;
if($vc==5) break; //stops this custom var at 5 which is max
  }
  echo '</script>';
}
*/ 
}
   public function Setup() {
   }
}
