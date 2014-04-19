<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['AdvancedPiwik'] = array(
   'Name' => 'Advanced Piwik',
   'Description' => 'Adds custom variables to track: categories, if user is logged in,role type and even username (optional).Please Note:<b>This plugin does not install Piwik tracker. You must have Piwik Async Tracking code already installed</b>.',
   'Version' => '1.0.0',
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
    'SettingsUrl' => '/plugin/advancedpiwik',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'MobileFriendly' => TRUE,
   'License' => 'GNU GPL2',
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://www.adrianspeyer.com'
);

class AdvancedPiwikPlugin extends Gdn_Plugin {

   public function PluginController_AdvancedPiwik_Create($Sender) {
      $Sender->Title('Advanced Piwik Tracking');
      $Sender->AddSideMenu('plugin/advancedpiwik');
      $Sender->Form = new Gdn_Form();
      
      $this->Dispatch($Sender, $Sender->RequestArgs);
   }
   
   public function Controller_Index($Sender) {
      
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));
		$Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
	   'Plugin.AdvancedPiwik.RenderCondition'    => 'all',
      ));
      
      $Sender->Form->SetModel($ConfigurationModel);
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
		} else {
         $ConfigurationModel->Validation->ApplyRule('Plugin.AdvancedPiwik.RenderCondition', 'Required');
         $Saved = $Sender->Form->Save();
         if ($Saved) {
            $Sender->StatusMessage = T("Your changes have been saved.");
         }
      }

      $Sender->Render($this->GetView('advancedpiwik.php'));
   }
   

   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Forum', 'Advanced Piwik', 'plugin/advancedpiwik', 'Garden.Settings.Manage');
   }
   
      public function Setup() {
       // Set up the plugin's default values
      SaveToConfig('Plugin.AdvancedPiwik.RenderCondition', 'all');
   }
  
//This will show you if user is logged in or not   
public function Base_AfterBody_Handler($Sender) { 

//Tells Piwik if User is Logged in
 if (!Gdn::Session()->IsValid()){
			echo "<script>
_paq.push(['setCustomVariable',1,'UserType','Anonymous','visit']);";}
				else			
		{echo "<script> ;
_paq.push(['setCustomVariable',1,'UserType','Logged In','visit']); ";
}

//Tells Piwik if which Categories are viewed
    $Category = CategoryModel::Categories(GetValue('CategoryID', $Discussion));
		 if (isset($Category)) {
		$catid = $Sender->CategoryID;
		$cname = $Category[$catid]['Name'];
echo "
_paq.push(['setCustomVariable',2,'Category','".$cname."','page']);";
       }

//Gets you membername

$RenderCondition = C('Plugin.AdvancedPiwik.RenderCondition', 'all');
$Type = (GetValue('No Memeber Name', $Sender->EventArguments['No Memeber Name']) == '1') ? "No Member Name" : "Include Member Name";
if ($RenderCondition == "Include Member Name" ) {
$membername = (Gdn::Session()->User->Name);
if (($membername)!== null) {
echo "
_paq.push(['setCustomVariable',3,'Member Name','".$membername."','visit']);";
}
}
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
         

	$roletype = implode(', ', $Roles);
	echo "
_paq.push(['setCustomVariable',4,'RoleType','".$Role['Name']."','visit']);
_paq.push(['trackPageView']);
</script>
	";
	}
    
   public function OnDisable() {
      RemoveFromConfig('Plugin.AdvancedPiwik.RenderCondition');
   }
   
}


