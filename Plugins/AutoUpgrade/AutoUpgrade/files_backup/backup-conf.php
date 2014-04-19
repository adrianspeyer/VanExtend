
<?php
$conf =  '../../../conf/config.php';
$newconf = 'config.php.bak';

if (!copy($conf, $newconf)) {
    echo "failed to copy $conf...\n";
}
?>
<script type="text/javascript">
history.go(-1)
</script>

