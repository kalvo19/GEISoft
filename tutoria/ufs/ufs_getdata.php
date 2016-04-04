<?php
include('../bbdd/connect.php');
mysql_query("SET NAMES 'utf8'");

$s_pe    = isset($_POST['s_pe']) ? $_POST['s_pe'] : '';

$sql  = "SELECT ufs.*,mmuf.*,pe.Acronim_pla_estudis FROM unitats_formatives ufs ";
$sql .= "INNER JOIN moduls_materies_ufs mmuf ON ufs.idunitats_formatives=mmuf.id_mat_uf_pla ";
$sql .= "INNER JOIN plans_estudis pe ON mmuf.idplans_estudis=pe.idplans_estudis ";
$sql .= "WHERE mmuf.id_mat_uf_pla<>0 ";

if (isset($_POST['s_pe'])) {
   $sql .= "AND mmuf.idplans_estudis=".$s_pe;
}

/*$myFile = "log.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, $sql."<br>");*/

$rs = mysql_query($sql);

$result = array();
while($row = mysql_fetch_object($rs)){
	array_push($result, $row);
}

echo json_encode($result);

mysql_free_result($rs);
mysql_close();
?>

