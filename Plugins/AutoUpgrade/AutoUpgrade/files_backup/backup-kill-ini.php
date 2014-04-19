<?php

//kill all ini  files.
foreach(glob('../../../cache/*.ini') as $file){
     unlink($file);
}
?>


<script type="text/javascript">
history.go(-1)
</script>
