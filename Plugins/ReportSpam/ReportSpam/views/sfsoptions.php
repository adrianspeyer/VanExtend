<h1><?php echo T($this->Data('Title')); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
Report this user to Stop Forum Spam
<p style="text-align: center">
<img  src="<?php echo Url('/plugins/ReportSpam/design/images/stop-spam.png'); ?>" alt="Stop forum spam">
</p>
<?php
   echo '<div class="P">', $this->Form->CheckBox('DeleteContent', T("Delete this content.")), '</div>';
   echo '<div class="P">', $this->Form->CheckBox('BanUser', T("Ban this user.")), '</div>';
   echo '<div class="P">', $this->Form->CheckBox('BanUserDelete', T("Ban this user & delete all content.")), '</div>';
?>
<?php echo $this->Form->Close('Submit', '', array('class' => 'Button BigButton'));
?>
	