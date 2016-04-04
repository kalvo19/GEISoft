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
			width:2px;
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
        <?= getDadesCentre()->tlf ?>&nbsp;&nbsp;<?= getDadesCentre()->email ?>
      </td>
      <td align="right">
  		<div class="page-number"></div>
      </td>
    </tr>
  </table>
</div>

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

<script type="text/javascript">
	$('#header').css('visibility', 'hidden');
	$('#footer').css('visibility', 'hidden');
</script>

<?php	
mysql_free_result($rsAlumnes);
mysql_close();
?>