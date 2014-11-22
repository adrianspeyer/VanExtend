<h1><?php echo T($this->Data('Title')); ?></h1>
<?php
echo $this->Form->Open();
echo $this->Form->Errors();
?>
Report this user to Stop Forum Spam
<?php echo $this->Form->Close('Submit', '', array('class' => 'Button BigButton'));
?>