<?php if (!defined('APPLICATION')) exit();

class vanBasicThemeHooks implements Gdn_IPlugin {
	
   public function Setup() {
		return TRUE;
   }
   public function OnDisable() {
      return TRUE;
   }
   
}