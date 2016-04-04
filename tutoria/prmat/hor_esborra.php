<?php
include('../bbdd/connect.php');

$idunitats_classe  = intval($_REQUEST['id']);
$idprofessors      = $_REQUEST['idprofessors'];
$idgrups_materies  = $_REQUEST['idgrups_materies'];

$sql = "DELETE FROM unitats_classe WHERE idunitats_classe=$idunitats_classe";
$result = @mysql_query($sql);

/*$sql = "DELETE FROM prof_agrupament WHERE idprofessors=$idprofessors AND idgrups_materies=$idgrups_materies";
$result = @mysql_query($sql);*/

echo json_encode(array('success'=>true));
/*if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>$sql));
}*/

mysql_close();
?>