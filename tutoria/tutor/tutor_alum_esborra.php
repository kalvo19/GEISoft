<?php
include('../bbdd/connect.php');

$idalumnes_grup_materia = intval($_REQUEST['id']);

$sql = "DELETE FROM alumnes_grup_materia WHERE idalumnes_grup_materia=$idalumnes_grup_materia";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();
?>

<?php
/*include('../bbdd/connect.php');

$idalumnes_grup_materia = intval($_REQUEST['id']);

$sql = "DELETE FROM alumnes_grup_materia WHERE idalumnes_grup_materia=$idalumnes_grup_materia";

$result = @mysql_query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('msg'=>'Algunos errores ocurrieron.'));
}

mysql_close();*/
?>