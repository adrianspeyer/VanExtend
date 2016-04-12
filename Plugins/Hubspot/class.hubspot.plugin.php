<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Hubspot'] = array(
   'Name' => 'Hubspot',
   'Description' => 'Adds Hubspot tracking, and new users to your Hubspot contacts list. We use Hubspot Events JavaScript API to achieve this.
        Events are only available to Hubspot Enterprise customers.',
   'Version' => '1.0.4',
   'SettingsUrl' => '/settings/hubspot',
   'SettingsPermission' => 'Garden.Settings.Manage',
   'RequiredApplications' => array('Vanilla' => '2.1'),
   'MobileFriendly' => true,
   'Author' => 'Adrian Speyer',
   'AuthorUrl' => 'http://adrianspeyer.com',
);

/**
 * Class HubspotPlugin
 */
class HubspotPlugin extends Gdn_Plugin {

    /**
     * Settings page.
     *
     * @param $sender
     */
    public function settingsController_hubspot_create($sender) {
        $sender->permission('Garden.Settings.Manage');
        $sender->setData('Title', t('Hubspot Settings'));

        $text = '<div class="Info">'.t('Enter your Hubspot account number.').'</div>';
        $sender->addAsset('Content', $text, 'MoreLink');

        $cf = new ConfigurationModule($sender);
        $cf->initialize([
            'Plugins.Hubspot.PortalID' => [],
        ]);

        $sender->addSideMenu('dashboard/settings/plugins');
        $cf->renderAll();
    }

    /**
     * Render tracking.
     *
     * @param $sender
     */
    public function base_afterBody_handler($sender) {
        $PortalID = c('Plugins.Hubspot.PortalID');
        if (!$PortalID) {
            return;
        }

        if (Gdn::session()->isValid()) {
            // EMAIL TRACKING
            $user = Gdn::session()->User;
            echo '
            <script>
                var _hsq = _hsq || [];
                _hsq.push(["identify",{email: "'.htmlspecialchars($user->Email).'", firstname: "'.htmlspecialchars($user->Name).'"}]);
            </script>';
        }

        echo "
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