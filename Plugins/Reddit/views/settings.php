<?php if (!defined('APPLICATION')) exit; ?>

<style type="text/css">
    .Configuration {
        margin: 0 20px 20px;
        background: #f5f5f5;
        float: left;
    }
    .ConfigurationForm {
        padding: 20px;
        float: left;
    }
    #Content form .ConfigurationForm ul {
        padding: 0;
    }
    #Content form .ConfigurationForm input.Button {
        margin: 0;
    }
    .ConfigurationHelp {
        border-left: 1px solid #aaa;
        margin-left: 340px;
        padding: 20px;
    }
    .ConfigurationHelp img {
        width: 99%;
    }
    .ConfigurationHelp a img {
        border: 1px solid #aaa;
    }
    .ConfigurationHelp a:hover img {
        border: 1px solid #777;
    }
    .ConfigurationHelp ol {
        list-style-type: decimal;
        padding-left: 20px;
    }
    input.CopyInput {
        font-family: monospace;
        color: #000;
        width: 240px;
        font-size: 12px;
        padding: 4px 3px;
    }
</style>

<h1><?php echo $this->Data('Title'); ?></h1>

<?php
$Form    = $this->Form;
$Request = Gdn::Request();
$Domain  = $Request->Domain() ? $Request->Domain().'/' : '';
$WebRoot = $Request->WebRoot() ? $Request->WebRoot().'/' : '';

echo $Form->Open();
echo $Form->Errors();
?>

<div class="Info">
    <?php echo T('Reddit Connect allows users to sign in using their Reddit account.', 'Reddit Connect allows users to sign in using their Reddit account. <b>You must register your application with Reddit for this plugin to work.</b>'); ?>
</div>

<div class="Configuration">

    <div class="ConfigurationForm">

        <ul>
            <li><?php echo $Form->Label('Client ID', 'ClientID') . $Form->TextBox('ClientID'); ?></li>
            <li><?php echo $Form->Label('Client Secret', 'Secret') . $Form->TextBox('Secret'); ?></li>
            <li><?php echo $Form->CheckBox('SocialSignIn', 'Enable Social Sign In'); ?></li>
            <li><?php echo $Form->CheckBox('SocialReactions', 'Enable Social Reactions'); ?></li>
        </ul>

        <?php echo $Form->Button('Save', array('class' => 'Button SliceSubmit')); ?>

    </div>

    <div class="ConfigurationHelp">

        <p><strong><?php echo T('How to set up Reddit Connect'); ?></strong></p>

        <ol>
            <li><?php echo T('You must register your Vanilla Forum with Reddit at'); ?>: <a href="https://ssl.reddit.com/prefs/apps" target="_blank">https://ssl.reddit.com/prefs/apps</a></li>
            <li><?php echo T('Set the <strong>Redirect URL</strong> which would be'); ?> <code><?php echo $Domain . $WebRoot; ?>entry/connect/reddit</code></li>
            <li><?php echo T('After registering, copy the "Client ID" and "Secret ID" into the form on this page and click Save.'); ?></li>
        </ol>

        <p><?php echo Anchor(Img('/plugins/Reddit/design/help-consumervalues-sm.png', array('style' => 'max-width: 763px;')), '/plugins/Reddit/design/help-consumervalues-sm.png', array('target' => '_blank')); ?></p>

    </div>

</div>

<?php
echo $Form->Close();
