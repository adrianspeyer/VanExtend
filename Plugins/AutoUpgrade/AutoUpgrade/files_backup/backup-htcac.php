
<?php
$htac =  './../../../.htaccess';
$newhtac = 'htaccess.bak';

if (!copy($htac, $newhtac)) {
    echo "failed to copy $htac...\n";
}


?>


<script type="text/javascript">
history.go(-1)
</script>
