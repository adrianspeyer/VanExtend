<?php if (!defined('APPLICATION')) exit(); ?>
<h2 class="H"><?php echo $this->Data('Title'); ?></h2>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
   <?php if ($this->Data('ForceEditing') && $this->Data('ForceEditing') != FALSE) { ?>
      <div class="Warning"><?php echo sprintf(T("You are editing %s's dyslexia settings"),$this->Data('ForceEditing')); ?></div>
   <?php } ?>
   <li>
      <?php
         echo $this->Form->Label('Dyslexia Font', 'DyslexiaFont');
         echo Wrap('Do you wish to enable Dyslexia Font?', 'div');
         echo $this->Form->DropDown('DyslexiaFont', $this->Data('DyslexiaFontOptions'));
      ?>
   </li>
</ul>
<?php echo $this->Form->Close('Save', '', array('class' => 'Button Primary'));