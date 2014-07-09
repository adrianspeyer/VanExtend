<?php if (!defined('APPLICATION')) exit();
//Custom Variable info for GA at : https://developers.google.com/analytics/devguides/collection/gajs/gaTrackingCustomVariables
// Define the plugin:
$PluginInfo['AmazingAnalytics'] = array(
   'Name' => 'Amazing Analytics',
   'Description' => 'Amazing Analytics provide extra data into your Google Analytics. It will add data on which Category are most viewed(as events). It will add custom varaibles when a user is logged in vs not logged in. It will add a custom variable of the highest role type a user currenlty posseses. If you are using the profile extender add-on, you will also get data for first 3 fields added. Please make sure this data is not personally identifiable information, as this is against Google Analytics TOS -- eg name, telephone number.',
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.0.18.8'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'MobileFriendly' => TRUE,
   'SettingsPermission' => 'Garden.Settings.Manage',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://www.vanillaforums.com'
);

class AmazingAnalyticsPlugin extends Gdn_Plugin {


//This will tell Google which category is being viewed
public function DiscussionController_DiscussionInfo_Handler($Sender) {
        $Category = CategoryModel::Categories(GetValue('CategoryID', $Discussion));
		 if (isset($Category)) {
		$catid = $Sender->CategoryID;
		$cname = $Category[$catid]['Name'];
		echo "<script>_gaq.push(['_trackEvent', 'Category', 'View', '".$cname."']);</script>";
       }
   }

//This will show you if user is logged in or not   
public function Base_AfterBody_Handler($Sender) {
 echo "<script>"; 
 if (!Gdn::Session()->IsValid()){
 
			echo "_gaq.push(['_setCustomVar', 1,'UserType', 'Anonymous', 2 ]);";}
				else			
		{echo "_gaq.push(['_setCustomVar', 1,'UserType', 'Logged In', 2 ]);";
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
	echo "_gaq.push(['_setCustomVar', 2,'RoleType', '".$Role['Name']."', 1 ]);";
	echo "</script>"; 


//Work on getting data from profile extender
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
}
   public function Setup() {
   }
}
