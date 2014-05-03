<?php if (!defined('APPLICATION')) exit();

class simpleThemeHooks implements Gdn_IPlugin {
    
 public function base_render_before($sender) {
    $sender->setData('TransientKey', Gdn::session()->transientKey());
}
    
   public function Setup() {
		return TRUE;
   }
   public function OnDisable() {
      return TRUE;
   }
   
}