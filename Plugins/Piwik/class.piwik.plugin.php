<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Piwik'] = [
    'Name' => 'Piwik Analytics',
    'Description' => 'Adds Piwik tracking to forum and adds stats viewing in Dashboard.',
    'Version' => '2.7',
    'SettingsUrl' => '/settings/piwik',
    'RequiredApplications' => ['Vanilla' => '2.3'],
    'MobileFriendly' => true,
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com',
    'License' => 'GNU GPLv2'
];

/**
 * Class PiwikPlugin
 */
class PiwikPlugin extends Gdn_Plugin {

    /**
     * Add settings to 2.3 and lower Dashboard menu.
     *
     * @param Gdn_Controller $Sender
     */
    public function base_getAppSettingsMenuItems_handler($Sender) {
        if (version_compare(APPLICATION_VERSION, '2.3.100') === -1) {
            $Sender->permission('Garden.Settings.Manage');
            $Menu = $Sender->EventArguments['SideMenu'];
            $Menu->addItem('Forum', t('Forum'));
            $Menu->addLink('Forum', 'Piwik Analytics', 'settings/piwikanalytics', 'Garden.Settings.Manage');
        }
    }

    /**
     * Adds items to 2.4 Dashboard menu.
     *
     * @param DashboardNavModule $sender
     */
    public function dashboardNavModule_init_handler($sender) {
        $section = [
            'permission' => 'Garden.Settings.Manage',
            'section' => 'Analytics',
            'title' => 'Analytics',
            'description' => 'Piwik Integration',
            'url' => '/dashboard/settings/piwikanalytics'
        ];
        $sender->registerSection($section);
        $sender->addGroupToSection('Analytics', t('Analytics'), 'analytics');
        $sender->addLinkToSectionIf(
            'Garden.Community.Manage',
            'Analytics',
            t('Piwik Analytics'),
            'dashboard/settings/piwikanalytics',
            'analytics.my-piwik'
        );
    }

    /**
     * Settings page.
     *
     * @param SettingsController $sender
     */
    public function settingsController_piwik_create($sender) {
        $sender->permission('Garden.Settings.Manage');
        $sender->addSideMenu();
        $sender->title('Piwik Analytics');
        $sender->setData('Title', t('Piwik Analytics'));

        $configurationModule = new ConfigurationModule($sender);
        $configurationModule->initialize([
            'Plugins.Piwik.PiwikInstall' => ['LabelCode' => 'PiwikInstall', 'Control' => 'TextBox', 'Default' => c('Plugins.Piwik.PiwikInstall', '//www.example.com/piwik/')],
            'Plugins.Piwik.PiwikSiteID' => ['LabelCode' => 'PiwikSiteID', 'Control' => 'TextBox', 'Default' => c('Plugins.Piwik.PiwikSiteID', '1')],
            'Plugins.Piwik.PiwikAuthKey' => ['LabelCode' => 'PiwikAuthKey', 'Control' => 'TextBox', 'Default' => c('Plugins.Piwik.PiwikAuthKey', '')]
        ]);
        $configurationModule->renderAll();
    }

    /**
     *
     *
     * @param $sender
     */
    public function settingsController_piwikAnalytics_create($sender) {
        Gdn_Theme::section('Analytics');
        $sender->render('analytics', '', 'plugins/Piwik');
    }

    /**
     * Insert tracking code on all non-Dashboard pages.
     *
     * @param Gdn_Controller $Sender
     */
    public function base_afterBody_handler($Sender) {
        if (inSection('Dashboard')) {
            return;
        }

        $ImageTrack = $PiwikInstall = c('Plugins.Piwik.PiwikInstall');
        $PiwikSiteID = c('Plugins.Piwik.PiwikSiteID');
        $PiwikInstall = str_replace(['http:', 'https:'], '', $PiwikInstall);

        if (substr($PiwikInstall, -1) != '/') {
            $PiwikInstall .= '/';
        }
            
        if ($PiwikInstall) {
            echo <<<Piwik
            <!-- Piwik -->
            <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="$PiwikInstall";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', '$PiwikSiteID']);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
            })();
            </script>
            <noscript><img src="$ImageTrack/piwik.php?idsite=$PiwikSiteID" style="border:0" alt="" /></noscript>
            <!-- End Piwik -->
Piwik;
        }
    }
}
