<h1><?php echo T($this->Data('Title')); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
Report this user to Stop Forum Spam
<?php
   echo '<div class="P">', $this->Form->CheckBox('DeleteContent', T("Also delete this content.")), '</div>';
//https://github.com/vanilla/vanilla/blob/0321c0551fb2ead494bbef0b4126a29b9c4880f5/applications/vanilla/views/discussion/helper_functions.php#L238
//https://github.com/vanilla/vanilla/blob/0321c0551fb2ead494bbef0b4126a29b9c4880f5/applications/vanilla/views/discussion/helper_functions.php#L328
?>
<?php echo $this->Form->Close('Submit', '', array('class' => 'Button BigButton'));
?>
	