<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['Adblock'] = array(
   'Name' => 'Adblock Tracker',
   'Description' => 'Provides Vanilla Forum owners with info to see how many of their visitors block ads. Supports Google Analytics Classic and Piwik Async code. You must have tracking code in the header, and save your preferred solution before it starts tracking.',
   'Version' => '1.0.1',
   'RequiredApplications' => array('Vanilla' => '2.0.18.4'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'SettingsUrl' => '/plugin/adblock',
   'Author' => "Adrian Speyer",
   'License' => 'GNU GPL2',
   'AuthorUrl' => 'http://www.adrianspeyer.com'
);

class AdblockPlugin extends Gdn_Plugin {

   public function __construct() {
   }

   public function Base_Render_Before($Sender) {
      $Sender->AddCssFile('adblock.css', 'plugins/adblock');
      $Sender->AddJsFile('adcheck.js', 'plugins/adblock');
   }
   
   public function PluginController_Adblock_Create($Sender) {
      $Sender->Title('Adblock Tracking');
      $Sender->AddSideMenu('plugin/adblock');
      $Sender->Form = new Gdn_Form();
      
      $this->Dispatch($Sender, $Sender->RequestArgs);
   }
   
   public function Controller_Index($Sender) {
      
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));
		$Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
	   'Plugin.Adblock.RenderCondition'    => 'all',
      ));
      
      $Sender->Form->SetModel($ConfigurationModel);
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
		} else {
         $ConfigurationModel->Validation->ApplyRule('Plugin.Adblock.RenderCondition', 'Required');
         $Saved = $Sender->Form->Save();
         if ($Saved) {
            $Sender->StatusMessage = T("Your changes have been saved.");
         }
      }

      $Sender->Render($this->GetView('adblock.php'));
   }
   

   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Forum', 'Adblock', 'plugin/adblock', 'Garden.Settings.Manage');
   }
  
  
   public function Setup() {
   
      // Set up the plugin's default values
      SaveToConfig('Plugin.Adblock.RenderCondition', 'all');

   }
  
public function Base_AfterBody_Handler($Sender) {
$RenderCondition = C('Plugin.Adblock.RenderCondition', 'all');
      $Type = (GetValue('Solution', $Sender->EventArguments['Solution']) == '1') ? "Piwik" : "Google Analytics";
if ($RenderCondition == "GoogleAnalytics" ) {

echo 
<<<adblockga
<script type="text/javascript">
if (document.getElementById("faker") != undefined)
{
_gaq.push(['_setCustomVar', 5, 'adblock','unblocked',2]);
_gaq.push(['_trackEvent', 'adblock', 'unblocked', 'false',,true]);
}
else{
_gaq.push(['_setCustomVar', 5, 'adblock','blocked',2]);
_gaq.push(['_trackEvent', 'adblock', 'blocked', 'true',,true]);
}
</script>
adblockga;
}

if ($RenderCondition == "Piwik" ) {

echo 
<<<adblockpw
<script type="text/javascript">
if (document.getElementById("faker") != undefined)
{
_paq.push(['setCustomVariable',  
5,
"adblock",
"false",
"visit"
]); 
_paq.push(['trackPageView']);}    
else
{
_paq.push(['setCustomVariable',  
5,
"adblock",
"true",
"visit"
]); 
_paq.push(['trackPageView']);}    
</script>
adblockpw;
}
}

 
public function OnDisable() {
      RemoveFromConfig('Plugin.Adblock.RenderCondition');
   }
   
}


