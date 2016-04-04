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
 
 <div style="width:1060px;">
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
            </tr>
            
                <?php
				   $linea = 1;
				   while($row = mysql_fetch_object($rsAlumnes)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='500' class='drop'>".$row->Valor."</td>";
						  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)."</td>";
						  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)."</td>";
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
    </tr>
    <tr>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)?></td>
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
						  echo "<td valign='top' width='50'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='400' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
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
						  echo "<td valign='top' width='50'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='400' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
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
						  echo "<td valign='top' width='50'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
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
						  echo "<td valign='top' width='50'>".$linea."</td>";
						  echo "<td valign='top' width='100' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='400' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' class='drop'>".$row->comentari."</td></tr>";
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

<?php
mysql_free_result($rsAlumnes);
mysql_close();
?>