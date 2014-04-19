<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo T($this->Data['PluginDescription']); ?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li><?php
      echo $this->Form->Label('Analytics Solution', 'Plugin.Adblock.RenderCondition');
      echo $this->Form->DropDown('Plugin.Adblock.RenderCondition',array(
		 'None'   => '<-select a solution->',
         'Piwik'   => 'Piwik',
         'GoogleAnalytics'     => 'Google Analytics'
      ));
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>