<?php
//$conn_sms = @mysql_connect('127.0.0.1','root','');
$conn_sms = @mysql_connect('geisoft.cat:3306','consulta_sms','consulta');
if (!$conn_sms) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('sms_geisoft', $conn_sms);
?>
