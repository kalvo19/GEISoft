<?php
//include('../bbdd/connect.php');
include('../bbdd/connect_sms.php');
//include_once('../func/constants.php');
//include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id   = $_REQUEST['id']; 
$sql  = "select * from vista_log_sms where id_env=$id";
$rs   = mysql_query($sql);
$item = mysql_fetch_array($rs);

//$aa = explode('-',$id);

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
fclose($fp);*/
		
?>
    
<table class="dv-table" border="0" style="width:100%;">
<tr>
    <td style="border:0" valign=top width="60">
    <b>Tlf. destinatari</b><br><?= $item['telefon'] ?><br>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top width=250>
    <b>Contingut</b><br><?= $item['content'] ?><br>
    <td width=2>&nbsp;</td>
</tr>
</table>
                            
<?php
mysql_free_result($rs);
mysql_close();
?>