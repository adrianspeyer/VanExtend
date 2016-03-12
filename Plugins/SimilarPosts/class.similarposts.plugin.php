<?php if (!defined('APPLICATION')) exit();

    $PluginInfo['similarposts'] = array(
    'Name' => 'Similar Posts',
    'Description' => 'Show similar posts.',
    'Version' => '0.1',
    'MobileFriendly' => TRUE,
    'RequiredPlugins' => array('Tagging' => '1.0.1'),
    'License' => 'GNU GPL2',
    'Author' => "Adrian Speyer",
    'AuthorUrl' => 'http://adrianspeyer.com'
);


class SimilarpostsPlugin extends Gdn_Plugin {


 public function assetModel_styleCss_handler($Sender, $Args) {
        $Sender->addCSSFile('topics.css', 'plugins/SimilarPosts');
    }



public function DiscussionController_AfterDiscussionFilters_Handler($sender) {

echo panelHeading(t('Topics of Interest'));

$TagModule = new TagModule($Sender);
            echo '<span id="TOI">'.$TagModule.'</span>';


 }
}
