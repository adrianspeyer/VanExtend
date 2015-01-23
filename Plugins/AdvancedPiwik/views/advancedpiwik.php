<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo "This allows you to decide if you wish to include member name as a custom variable in your Piwik Stats"; ?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li><?php
      echo $this->Form->Label('No Memeber Name', 'Plugin.AdvancedPiwik.RenderCondition');
      echo $this->Form->DropDown('Plugin.AdvancedPiwik.RenderCondition',array(
		 'No Member Name'   => 'Do Not Include Member Name',
         'Include Member Name'   => 'Include Member Name',
      ));
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>