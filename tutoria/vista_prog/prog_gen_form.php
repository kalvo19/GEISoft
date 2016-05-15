<?php

$programacio = json_decode($_REQUEST["programacio"]);
print_r($programacio);
echo $programacio->modul;

?>

<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <textarea id="editor1" name="editor1">&lt;p&gt;Initial value.&lt;/p&gt;</textarea>
</div>
    
<script type="text/javascript">
    CKEDITOR.replace('editor1');
</script>


