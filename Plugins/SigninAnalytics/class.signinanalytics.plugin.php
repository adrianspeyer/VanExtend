<?php if (!defined('APPLICATION')) exit();

// Define the plugin:
$PluginInfo['SigninAnalytics'] = array(
   'Name' => 'Signin Analytics',
   'Description' => 'Track which kind of signin types are used. Works for Google Analytics Classic and Universal. Most accurate when you add this to conf/config.php: <b>$Configuration[\'Garden\'][\'SignIn\'][\'Popup\'] = FALSE;</b>',
   'Version' => '1.0',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'SettingsUrl' => '/plugin/signinanalytics',
   'MobileFriendly' => TRUE,
   'Author' => "Adrian Speyer",
   'AuthorUrl' => 'http://www.adrianspeyer.com',
   'License' => 'GNU GPL2'
);

class SigninAnalyticsPlugin extends Gdn_Plugin {

   public function __construct() {
   }

   public function PluginController_SigninAnalytics_Create($Sender) {
      $Sender->Title('Signin Analytics');
      $Sender->AddSideMenu('plugin/signinanalytics');
      $Sender->Form = new Gdn_Form();
      
      $this->Dispatch($Sender, $Sender->RequestArgs);
   }
   
   public function Controller_Index($Sender) {
      
      $Sender->Permission('Garden.Settings.Manage');
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));
	  $Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
	   'Plugin.SigninAnalytics.RenderCondition'    => 'all',
      ));
      
      $Sender->Form->SetModel($ConfigurationModel);
      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
		} else {
         $ConfigurationModel->Validation->ApplyRule('Plugin.SigninAnalytics.RenderCondition', 'Required');
         $Saved = $Sender->Form->Save();
         if ($Saved) {
            $Sender->StatusMessage = T("Your changes have been saved.");
         }
      }

      $Sender->Render($this->GetView('SigninAnalytics.php'));
   }
   

   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = $Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Forum', 'Signin Analytics', 'plugin/signinanalytics', 'Garden.Settings.Manage');
   }
  
  
   public function Setup() {
   
      // Set up the plugin's default values
      SaveToConfig('Plugin.SigninAnalytics.RenderCondition', 'all');

   }
  
		public function Base_AfterBody_Handler($Sender) {
			$RenderCondition = C('Plugin.SigninAnalytics.RenderCondition', 'all');
			  $Type = (GetValue('Solution', $Sender->EventArguments['Solution']) == '1') ? "Classic" : "Universal";
				if ($RenderCondition == "Universal" ) {
						echo "
						<script>
						/*Social Sign-in */
						$('a.SocialIcon.SocialIcon-Google,a.SocialIcon.SocialIcon-Google.HasText').one('click', function() {
						 ga('send', 'event', 'button', 'click', 'sso-google');
						});

						$('a.SocialIcon.SocialIcon-OpenID,a.SocialIcon.SocialIcon-OpenID.HasText').one('click', function() {
						 ga('send', 'event', 'button', 'click', 'sso-openid');
						});

						$('a.SocialIcon.SocialIcon-Twitter,a.SocialIcon.SocialIcon-Twitter.HasText').one('click', function() {
						 ga('send', 'event', 'button', 'click', 'sso-twitter');
						});

						$('a.SocialIcon.SocialIcon-Facebook,a.SocialIcon.SocialIcon-Facebook.HasText').one('click', function() {
						 ga('send', 'event', 'button', 'click', 'sso-facebook');
						});

						/*Standard Sign-in */
						$('input#Form_SignIn.Button.Primary').one('click', function() {
						 ga('send', 'event', 'button', 'click', 'standard-signin');
						});
						</script>
						";
						}

				if ($RenderCondition == "Classic" ) {
						echo "
						<script>
						/*Social Sign-in */
						$('a.SocialIcon.SocialIcon-Google,a.SocialIcon.SocialIcon-Google.HasText').one('click', function() {
						 _gaq.push(['_trackEvent', 'signin', 'sso-google', 'true',,true]);
						});

						$('a.SocialIcon.SocialIcon-OpenID,a.SocialIcon.SocialIcon-OpenID.HasText').one('click', function() {
						_gaq.push(['_trackEvent', 'signin', 'sso-openid', 'true',,true]);
						});

						$('a.SocialIcon.SocialIcon-Twitter,a.SocialIcon.SocialIcon-Twitter.HasText').one('click', function() {
						_gaq.push(['_trackEvent', 'signin', 'sso-twitter', 'true',,true]);
						});

						$('a.SocialIcon.SocialIcon-Facebook,a.SocialIcon.SocialIcon-Facebook.HasText').one('click', function() {
						_gaq.push(['_trackEvent', 'signin', 'sso-facebook', 'true',,true]);
						});

						/*Standard Sign-in */
						$('input#Form_SignIn.Button.Primary').one('click', function() {
						_gaq.push(['_trackEvent', 'signin', 'standard-vanilla', 'true',,true]);
						});
						</script>
						";
				 }

}
		

 
		public function OnDisable() {
			  RemoveFromConfig('Plugin.SigninAnalytics.RenderCondition');
		   }
   
}