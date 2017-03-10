<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data('Title')); ?></h1>
<?php
echo $this->Form->Open();
?>
<p>
    <?php echo T('Report this user to Stop Forum Spam and:'); ?>
</p>
<div style="text-align: center">
<img src="<?php echo Url('/plugins/ReportSpam/design/images/stop-spam.png'); ?>" alt="Stop forum spam">
</div>
<?php echo $this->Form->Errors();?>
<div class="P">
    <?php echo $this->Form->CheckBox(
        'DeleteContent',
        T('Delete this content.'),
        array('value' => '1', 'checked' => 'checked'));
    ?>
</div>
<div class="P">
    <?php echo $this->Form->CheckBox('BanUser', T("Ban this user.")) ?>
</div>
<div class="P">
    <?php echo $this->Form->CheckBox('BanUserDelete', T("Ban this user & delete all content.")) ?>
</div>

<?php echo $this->Form->Close('Submit', '', array('class' => 'Button BigButton')); ?>
