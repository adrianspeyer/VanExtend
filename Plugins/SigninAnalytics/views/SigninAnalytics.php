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
      echo $this->Form->Label('Google Analytics Type', 'Plugin.SigninAnalytics.RenderCondition');
      echo $this->Form->DropDown('Plugin.SigninAnalytics.RenderCondition',array(
		 'None'   => '<-select a solution->',
         'Classic'   => 'Classic',
         'Universal'     => 'Universal'
      ));
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>