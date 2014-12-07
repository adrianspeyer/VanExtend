<?php if (!defined('APPLICATION')) exit(); ?>
<h2 class="H"><?php echo $this->Data('Title'); ?></h2>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
<ul>
   <?php if ($this->Data('ForceEditing') && $this->Data('ForceEditing') != FALSE) { ?>
      <div class="Warning"><?php echo sprintf(T("You are editing %s's flickr settings"),$this->Data('ForceEditing')); ?></div>
   <?php } ?>
   <li>
      <?php
         echo Wrap('Please enter you Flickr ID', 'div');
		  echo $this->Form->Label;
          echo $this->Form->TextBox('FlickrID', $this->Data('FlickrID')); ?>
     </li>
	Don't know your FlickrID? Find your Flickr ID <a href ="http://idgettr.com" target="_blank" >here</a>	  
</ul>

<?php echo $this->Form->Close('Save', '', array('class' => 'Button Primary'));