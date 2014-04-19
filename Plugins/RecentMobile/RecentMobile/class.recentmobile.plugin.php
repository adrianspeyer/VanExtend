<?php if (!defined('APPLICATION')) exit();

/**
 * 'Recent Mobile' plugin for Vanilla Forums.
 *  Adds a "Recent Discussion" button to mobile theme, when user has category as homepage. It also sets a "Categories" menu item when user is on recent discussions.
 */
 
$PluginInfo['RecentMobile'] = array(
	'Name' => 'Recent Mobile',
	'Description' => 'Adds a "Recent Discussion" button to mobile theme, when user has category as homepage. It also sets a "Categories" menu item when user is on recent discussions.',
	'Version' => '1.0',
	'Author' => "Adrian Speyer",
	'License' => 'GNU GPLv2',
	'MobileFriendly' => TRUE
);

/**
 * Allows users to see recent discussions on mobile if category is homepage.
 *
 * @package RecentMobile
 */
class RecentMobilePlugin extends Gdn_Plugin {
   
   /**
    * Adds "Recent Discussions" to main menu on mobile when category first is set as homepage.
    **/
   public function Base_Render_Before($Sender) {
      // Add "Recent Discussions" to main menu
	 
      if (IsMobile() && $Sender->Menu && Gdn::Session()->IsValid()) {
	   if (($Sender->SelfUrl) == 'discussions'){
		   if (C('Plugins.RecentMobile.ShowInMenu', TRUE)){
			$Sender->Menu->AddLink('Categories', T('Categories'), '/categories');		   
		  }}
		  else
		  {
		   if (C('Plugins.RecentMobile.ShowInMenu', TRUE)){
		    $Sender->Menu->AddLink('RecentMobile', T('Discussions'), '/discussions');
		  }
	}}}  
		  
   	public function Setup() {
		return TRUE;
	}
	
}