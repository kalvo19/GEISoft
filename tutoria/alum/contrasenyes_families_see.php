<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$sql       = "SELECT a.idalumnes,ca.Valor FROM alumnes a ";
$sql      .= "INNER JOIN contacte_alumne ca ON ca.id_alumne=a.idalumnes ";
$sql      .= "WHERE a.activat='S' AND ca.id_tipus_contacte=".TIPUS_nom_complet;
$sql      .= " ORDER BY 2 ";
$rsAlumnes = mysql_query($sql);

?>

<style type="text/css">
		.left{
			width:20px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			/*float:right;*/
		}
		.right table{
			background:#E0ECFF;
		}
		.right td{
			background:#fafafa;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:left;
			border:1px solid #499B33;
			background:#fafafa;
			/*width:100px;*/
		}
		.assigned{
			border:1px solid #BC2A4D;
		}
		
	</style>

<div class="left">
		&nbsp;
</div>
<div class="right">
<?php
$fila = 1;
echo "<table width=95%>";
echo "<tr>";
echo "<td class='title'></td>";
echo "<td class='title'><strong>Alumne</strong></td>";
echo "<td class='title'><strong>Login familia</strong></td>";
echo "<td class='title'><strong>Contrasenya</strong></td>";
echo "<td class='title'><strong>EMail</strong></td>";
echo "<td class='title'><strong>Tlf.SMS</strong></td>";
echo "</tr>";

while($row = mysql_fetch_object($rsAlumnes)){
	$idalumnes  = $row->idalumnes;
	
	echo "<tr>";
	echo "<td width='30'>".$fila."</td>";
	echo "<td width='440' class='drop'>".$row->Valor."</td>";
	echo "<td width='160' class='drop'>".getValorTipusContacteFamilies($idalumnes,TIPUS_login)."</td>";
	echo "<td width='60' class='drop'>".getValorTipusContacteFamilies($idalumnes,TIPUS_contrasenya_notifica)."</td>";
	echo "<td width='60' class='drop'>".getValorTipusContacteFamilies($idalumnes,TIPUS_email)."</td>";
	echo "<td width='50' class='drop'>".getValorTipusContacteFamilies($idalumnes,TIPUS_mobil_sms)."</td>";
		
	$fila++;
	echo "</tr>";
}
?>
</div>

<?php	
mysql_free_result($rsAlumnes);
mysql_close();
?>