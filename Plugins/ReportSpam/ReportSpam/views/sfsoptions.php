<h1><?php echo T($this->Data('Title')); ?></h1>
<?php
echo $this->Form->Open();
?>
Report this user to Stop Forum Spam
<div style="text-align: center">
<img  src="<?php echo Url('/plugins/ReportSpam/design/images/stop-spam.png'); ?>" alt="Stop forum spam">
</div>
<?php echo $this->Form->Errors();?>
<?php
	  $Attributes = array(
					 'value' => '1', 
					 'checked' => 'checked'
				 );
   echo '<div class="P">', $this->Form->CheckBox('DeleteContent', T("Delete this content."), $Attributes), '</div>';
   echo '<div class="P">', $this->Form->CheckBox('BanUser', T("Ban this user.")), '</div>';
   echo '<div class="P">', $this->Form->CheckBox('BanUserDelete', T("Ban this user & delete all content.")), '</div>';
?>
<?php echo $this->Form->Close('Submit', '', array('class' => 'Button BigButton'));
?>