<?php if (!defined('APPLICATION')) die(); ?>

<style type="text/css">
.settings {
	text-align: right;
}
</style>

<?php
//call settings
$PiwikInstall = C('Plugins.Piwik.PiwikInstall');
$PiwikSiteID = C('Plugins.Piwik.PiwikSiteID');
$PiwikAuthKey = C('Plugins.Piwik.PiwikAuthKey');
?>


<?php

if (empty($PiwikAuthKey)) {
    echo "
	<style type=\"text/css\">
.piwikiframe {
	display:none;
}
</style>
	";
}
?>



<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">
function toggleDiv(divId) {
   $("#"+divId).toggle();
}
</script>



<script type="text/javascript" language="JavaScript"><!--
function HideContent(d) {
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
document.getElementById(d).style.display = "block";
}
function ReverseDisplay(d) {
if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
else { document.getElementById(d).style.display = "none"; }
}
//--></script>


<?php //if (isset($PiwikAuthKey)) 
if (strlen($PiwikAuthKey) > 0)
{
echo "
<div class=\"settings\">
<a href=\"javascript:ReverseDisplay('piwiksettings')\"> Settings</a></div>
<div id=\"piwiksettings\" style=\"display:none; padding-left: 20px; padding-right: 20px; padding-bottom:20px;\">
";
} 
else
{
echo "
<div class=\"settings\">
<a href=\"javascript:toggleDiv('piwiksettings');\">Settings</a></div>
<div id=\"piwiksettings\">
";
}
?>


<?php $this->ConfigurationModule->Render(); ?>


	<div class="Info">To use this plugin, you need to have installed <a href="http://www.piwik.org/" target="_blank">Piwik</a>. Once enabled, Piwik
		will start tracking users on your forum.</div>
</div>		
		
		

<div class="piwikiframe">		
<iframe src="<?php echo $PiwikInstall ?>/index.php?module=Widgetize&action=iframe&moduleToWidgetize=Dashboard&actionToWidgetize=index&idSite=<?php echo $PiwikSiteID ?>&period=week&date=yesterday&token_auth=<?php echo $PiwikAuthKey ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="1000 px"></iframe>
</div>

