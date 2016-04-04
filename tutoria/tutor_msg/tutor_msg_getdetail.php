<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id   = $_REQUEST['id']; 
$sql  = "select * from missatges_tutor where idmissatges_tutor=$id";
$rs   = mysql_query($sql);
$item = mysql_fetch_array($rs);
?>
    
<table class="dv-table" border="0" style="width:100%;">
<tr>
    <td style="border:0" valign=top width=650>
    <b>Missatge</b><br><?=  nl2br($item['missatge']) ?><br>
    <td width=2>&nbsp;</td>
</tr>
</table>
                            
<?php
mysql_free_result($rs);
mysql_close();
?>