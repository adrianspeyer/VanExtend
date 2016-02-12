<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Hubspot'] = array(
   'Name' => 'Hubspot',
   'Description' => 'Adds Hubspot tracking, and new users to your Hubspot contacts list. We use Hubspot events to achieve this.
Events are only available to Enterprise customers.',
   'Version' => '1.0.3',
   'SettingsUrl' => '/settings/hubspot',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => true,
   'Author' => 'Adrian Speyer',
   'AuthorUrl' => 'http://adrianspeyer.com',
);

class HubspotPlugin extends Gdn_Plugin
{
    public function settingsController_hubspot_create($Sender, $Args = array())
    {
        $Sender->Permission('Garden.Settings.Manage');
        $Sender->SetData('Title', T('Hubspot Settings'));

        $Text = '<div class="Info">'
      .T('Enter your Hubspot ID, which is your Hubspot Account number.')
      .'</div>';

        $Sender->AddAsset('Content', $Text, 'MoreLink');

        $Cf = new ConfigurationModule($Sender);
        $Cf->Initialize(array(
          'plugins.hubspot.portalid' => array(),
          ));

        $Sender->AddSideMenu('dashboard/settings/plugins');
        $Cf->RenderAll();
    }

   //Render tracking
    public function base_AfterBody_Handler($Sender)
    {
        $portalid = C('plugins.hubspot.portalid');

        if ($session->isValid()) {
            $usermail = Gdn::Session()->User->Email;
            $username = Gdn::Session()->User->Name;
        }

   //Render EMAIL TRACKING
   if (isset($usermail, $portalid)) {
       echo'
       <script>
          var _hsq = _hsq || [];
          _hsq.push(["identify", {
           email: "'.$usermail.'",
           firstname: "'.$username.'"
         }]);
      </script>';
   }

        if (isset($portalid)) {
            echo"
        <!-- Start of Async HubSpot Analytics Code -->
           <script type='text/javascript'>
             (function(d,s,i,r) {
               if (d.getElementById(i)){return;}
               var n=d.createElement(s),e=d.getElementsByTagName(s)[0];
               n.id=i;n.src='//js.hs-analytics.net/analytics/'+(Math.ceil(new Date()/r)*r)+'/".$portalid.".js';
               e.parentNode.insertBefore(n, e);
             })(document,'script','hs-analytics',300000);
           </script>
      <!-- End of Async HubSpot Analytics Code -->
      ";
        }
    }
}
