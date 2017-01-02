<?php if (!defined('APPLICATION')) die();

$PiwikInstall = c('Plugins.Piwik.PiwikInstall');
$PiwikSiteID = c('Plugins.Piwik.PiwikSiteID');
$PiwikAuthKey = c('Plugins.Piwik.PiwikAuthKey');

if ($PiwikAuthKey) : ?>
<div class="piwikiframe">
    <iframe src="<?php echo $PiwikInstall ?>/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Dashboard&actionToWidgetize=index&idSite=<?php echo $PiwikSiteID;
        ?>&period=week&date=yesterday&token_auth=<?php echo $PiwikAuthKey ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="1000px"></iframe>
</div>
<?php else : ?>
<div class="Info">
    To use this plugin, you need to have installed <a href="http://www.piwik.org/" target="_blank">Piwik</a>.
    Once enabled, Piwik will start tracking users on your forum.
</div>
<?php endif;