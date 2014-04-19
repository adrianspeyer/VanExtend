<?php


//below is standard code from config.php

//$Configuration['Database']['Name'] = 'Vanilla';
//$Configuration['Database']['Host'] = 'localhost';
//$Configuration['Database']['User'] = 'Root';
//$Configuration['Database']['Password'] = '';

// need to to fill in below values from config.php, in the future hope to automate the variables needed
$Name = "";
$Host = "";
$User = "";
$Password ="";


$dumpfile = $Name.".gz";
passthru("/usr/bin/mysqldump --opt --host=$Host --user=$User --password=$Password $Name  | gzip> $dumpfile");



?>

<script type="text/javascript">
history.go(-1)
</script>