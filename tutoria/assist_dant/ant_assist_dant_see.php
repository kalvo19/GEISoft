<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $idgrups = $_REQUEST['idgrups'];
  $data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : '1989-1-1';
  if ($data_inici=='--') {
  	  $data_inici = '1989-1-1';
  }
  $txt_inici  = isset($_REQUEST['data_inici']) ? $_REQUEST['data_inici'] : '';
  
  $data_fi    = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : '2189-1-1';
  if ($data_fi=='--') {
  	  $data_fi = '2189-1-1';
  }
  $txt_fi     = isset($_REQUEST['data_fi'])    ? $_REQUEST['data_fi'] : '';
  
  if ( isset($_REQUEST['c_alumne']) && ($_REQUEST['c_alumne']==0) ) {
  	$c_alumne = 0;
  }
  else if ( isset($_REQUEST['c_alumne']) ) {
    $c_alumne = $_REQUEST['c_alumne'];
  }
  if (! isset($c_alumne)) {
    $c_alumne = 0;
  }
  
  $rsAlumnes = getAlumnesGrup($idgrups,TIPUS_nom_complet);
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
			width:5px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			float:right;
			width:1000px;
		}
		.right table{
			background:#E0ECFF;
			width:100%;
		}
		.right td{
			background:#fafafa;
			text-align:left;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			/*width:95px;*/
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:center;
			/*border:1px solid #499B33;*/
			background:#fafafa;
			/*width:100px;*/
		}
		.assigned{
			border:1px solid #BC2A4D;
		}
		.alumne {
			background:#FFFFFF;
			text-align:left;
			width:400px;
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

 <div style="width:1000px;">
 <?php
    if ($c_alumne == 0) {
 ?>	
 
 <h5 style="margin-bottom:0px">
  &nbsp;Informe de faltes del grup <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= getGrup($idgrups)->nom ?></a>
  &nbsp;Desde el <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px "><?= $txt_inici ?></a>
  &nbsp;&nbsp;fins al <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px "><?= $txt_fi ?></a>
 </h5>  
 <br />
	<div class="left">
		&nbsp;
	</div>
	<div class="right">
		<table>
         	<tr>
                <td>&nbsp;</td>
            	<td><strong>ALUMNE</strong></td>
                <td><strong>NUM. FALTES</strong></td>
                <td><strong>NUM. RETARDS</strong></td>
                <td><strong>NUM. JUSTIFICADES</strong></td>
                <td><strong>NUM. INCID&Egrave;NCIES</strong></td>
                <td><strong>NUM. CCC</strong></td>
            </tr>
            
                <?php
				   $linea = 1;
				   while($row = mysql_fetch_object($rsAlumnes)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='350' class='drop'>".$row->Valor."</td>";
						  echo "<td valign='top' width='70' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".getTotalCCCAlumne($row->idalumnes,$data_inici,$data_fi)."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>
	</div>

<?php
    }
	else {
?>
  <h5 style="margin-bottom:0px">
  &nbsp;Informe de faltes de l'alumne <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= getAlumne($c_alumne,TIPUS_nom_complet) ?></a>
  &nbsp;Desde el <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px "><?= $txt_inici ?></a>
  &nbsp;&nbsp;fins al <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px "><?= $txt_fi ?></a>
 </h5><br />
 <div class="left">
		&nbsp;
 </div>
 <div class="right">
 <table>
    <tr>
        <td><strong>NUM. FALTES</strong></td>
        <td><strong>NUM. RETARDS</strong></td>
        <td><strong>NUM. JUSTIFICADES</strong></td>
        <td><strong>NUM. INCID&Egrave;NCIES</strong></td>
        <td><strong>NUM. CCC</strong></td>
    </tr>
    <tr>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalCCCAlumne($c_alumne,$data_inici,$data_fi)?></td>
    </tr>
 </table>
        <br />
        <h5>Relaci&oacute; de faltes</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>
        
        <br />
        <h5>Relaci&oacute; de retards</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>
        
        <br />
        <h5>Relaci&oacute; de justificacions</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>OBSERVACIONS</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' class='drop'>".nl2br($row->comentari)."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>

        <br />
        <h5>Relaci&oacute; de incid&egrave;ncies</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>TIPUS INCIDENT</strong></td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>OBSERVACIONS</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='40' class='drop'>".getLiteralTipusIncident($row->id_tipus_incident)->tipus_incident."</td>";
						  echo "<td valign='top' width='70' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' width='300' class='drop'>".nl2br($row->comentari)."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>
        
        <br />
        <h5>Relaci&oacute; de CCC</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>TIPUS CCC</strong></td>
                <td><strong>DATA</strong></td>
                <td><strong>EXPULSI&Oacute;</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>DESCRIPCI&Oacute;</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getCCCAlumne($c_alumne,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='40' class='drop'>".getLiteralTipusCCC($row->id_falta)->nom_falta."</td>";
						  echo "<td valign='top' width='70' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='40' class='drop'>".$row->expulsio."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getProfessor($row->idprofessor,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".(intval($row->idmateria!=0) ? getMateria($row->idmateria)->nom_materia : '')."</td>";
						  echo "<td valign='top' width='400' class='drop'><strong>Desc. breu</strong><br>".getLiteralMotiusCCC($row->id_motius)->nom_motiu;
						  echo "<br><strong>Desc. detallada</strong><br>".nl2br($row->descripcio_detallada)."</td></tr>";
						  $linea++;
				   }
				?>          
		</table>
 </div>
 
<?php
    mysql_free_result($rsIncidencias);
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