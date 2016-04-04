<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$id        = intval($_REQUEST['idplans_estudis']);
$activat   = $_REQUEST['activat'];
$Nom_plan_estudis    = $_REQUEST['Nom_plan_estudis'];
$Acronim_pla_estudis = $_REQUEST['Acronim_pla_estudis'];

$sql = "update plans_estudis set activat='$activat',Nom_plan_estudis='$Nom_plan_estudis',Acronim_pla_estudis='$Acronim_pla_estudis' where idplans_estudis=$id";

/*
$fp = fopen("log.txt","a");
fwrite($fp, $sql . PHP_EOL);
foreach($_REQUEST as $key=>$value)
   fwrite($fp,"$key:$value<br>");
fclose($fp);
*/

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>