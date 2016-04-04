<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$idgrups    = isset($_REQUEST['idgrups']) ? $_REQUEST['idgrups'] : 0 ;

$sql  = "SELECT DISTINCT(agm.idalumnes),ca.Valor ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes            a ON agm.idalumnes         = a.idalumnes ";
$sql .= "INNER JOIN contacte_alumne   ca ON agm.idalumnes         = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies    gm ON agm.idgrups_materies  = gm.idgrups_materies ";	 
$sql .= "INNER JOIN grups              g ON gm.id_grups           = g.idgrups ";
$sql .= "INNER JOIN materia            m ON gm.id_mat_uf_pla      = m.idmateria ";
$sql .= "WHERE a.activat='S' AND g.idgrups=".$idgrups." AND ca.id_tipus_contacte=".TIPUS_nom_complet;	

$sql .= " UNION ";

$sql .= "SELECT DISTINCT(agm.idalumnes),ca.Valor ";
$sql .= "FROM alumnes_grup_materia agm ";
$sql .= "INNER JOIN alumnes             a ON agm.idalumnes        = a.idalumnes ";
$sql .= "INNER JOIN contacte_alumne    ca ON agm.idalumnes        = ca.id_alumne ";
$sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";	 
$sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
$sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
$sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
$sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
$sql .= "WHERE a.activat='S' AND g.idgrups=".$idgrups." AND ca.id_tipus_contacte=".TIPUS_nom_complet;	

$sql .= " ORDER BY 2  ";
$rsAlumnes = mysql_query($sql);

?>

<style type="text/css">

@page {
	margin: 1cm;
}

body {
  font-family: sans-serif;
  margin: 1.5cm 0;
}

#header,
#footer {
  position: fixed;
  left: 0;
  right: 0;
  color: #aaa;
  font-size: 0.9em;
}

#header {
  top: 0;
  border-bottom: 0.1pt solid #aaa;
  margin-bottom:15px;
}

#footer {
  bottom: 0;
  border-top: 0.1pt solid #aaa;
}

#header table,
#footer table {
  width: 100%;
  border-collapse: collapse;
  border: none;
}

#header td,
#footer td {
  padding: 0;
  width: 50%;
}

.page-number {
  text-align: right;
}

.page-number:before {
  content: " " counter(page);
}

hr {
  page-break-after: always;
  border: 0;
}

</style>

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

<div id="header">
  <table>
    <tr>
      <td>
	  <b><?= getDadesCentre()->nom ?></b><br />
      <?= getDadesCentre()->adreca ?>&nbsp;&nbsp;
      <?= getDadesCentre()->cp ?>&nbsp;<?= getDadesCentre()->poblacio ?>
      </td>
      <td style="text-align: right;">
      		<?php
				$img_logo = '../images/logo.jpg';
                if (file_exists($img_logo)) {
                	echo "<img src='".$img_logo."'>";
				}
			?>
      </td>
    </tr>
  </table>
</div>

<div id="footer">
  <table>
    <tr>
      <td>
        <?= getDadesCentre()->tlf ?>&nbsp;
      </td>
      <td align="right">
  		<?= getDadesCentre()->email ?>
      </td>
    </tr>
  </table>
</div>

<div class="left">
		&nbsp;
</div>
<div class="right">
<?php

while($row = mysql_fetch_object($rsAlumnes)){
	$idalumnes  = $row->idalumnes;

	echo "<table width=80% align='center'>";
	echo "<tr>";
	echo "<td class='drop'>Estimada familia de <b>".$row->Valor."</b><br><br>";
	echo "Detallem a continuació les dades per poder accedir a la plataforma Geisoft per families.<br><br>";
	echo "Adreça internet: <b>".$_SERVER['SERVER_NAME']."</b><br><br>";
	echo "Usuari: <b>".getValorTipusContacteFamilies($idalumnes,TIPUS_login)."</b><br><br>";
	echo "Contrasenya: <b>".getValorTipusContacteFamilies($idalumnes,TIPUS_contrasenya_notifica)."</b><br><br><br>";
	echo "Atentament,<br><br>";
	echo "</td></tr></table>";
}
?>
</div>

<?php	
mysql_free_result($rsAlumnes);
mysql_close();
?>