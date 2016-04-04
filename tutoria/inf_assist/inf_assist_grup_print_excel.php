<?php
  header("Content-type: application/vnd.ms-excel");
  header("Content-Disposition: attachment;Filename=Informe.xls");
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  //mysql_query("SET NAMES 'utf8'");
  
  $data_inici = isset($_REQUEST['data_inici']) ? substr($_REQUEST['data_inici'],6,4)."-".substr($_REQUEST['data_inici'],3,2)."-".substr($_REQUEST['data_inici'],0,2) : getCursActual()->data_inici;
  if ($data_inici=='--') {
  	  $data_inici = getCursActual()->data_inici;
  }
  $txt_inici  = isset($_REQUEST['data_inici']) ? $_REQUEST['data_inici'] : '';
  
  $data_fi    = isset($_REQUEST['data_fi'])    ? substr($_REQUEST['data_fi'],6,4)."-".substr($_REQUEST['data_fi'],3,2)."-".substr($_REQUEST['data_fi'],0,2)          : getCursActual()->data_fi;
  if ($data_fi=='--') {
  	  $data_fi = getCursActual()->data_fi;
  }
  $txt_fi     = isset($_REQUEST['data_fi'])    ? $_REQUEST['data_fi'] : '';
  
  if ( isset($_REQUEST['idgrup']) && ($_REQUEST['idgrup']==0) ) {
  	$idgrup = 0;
  }
  else if ( isset($_REQUEST['idgrup']) ) {
    $idgrup = $_REQUEST['idgrup'];
  }
  if (! isset($idgrup)) {
    $idgrup = 0;
  }
  
  $box_al             = isset($_REQUEST['box_al'])             ? $_REQUEST['box_al']             : '';
  $box_ed             = isset($_REQUEST['box_ed'])             ? $_REQUEST['box_ed']             : '';
  $box_faltes         = isset($_REQUEST['box_faltes'])         ? $_REQUEST['box_faltes']         : '';
  $box_retards        = isset($_REQUEST['box_retards'])        ? $_REQUEST['box_retards']        : '';
  $box_justificacions = isset($_REQUEST['box_justificacions']) ? $_REQUEST['box_justificacions'] : '';
  $box_incidencies    = isset($_REQUEST['box_incidencies'])    ? $_REQUEST['box_incidencies']    : '';
  $box_CCC            = isset($_REQUEST['box_CCC'])            ? $_REQUEST['box_CCC']            : '';
 
  $mode_impresio      = isset($_REQUEST['mode_impresio'])      ? $_REQUEST['mode_impresio']      : 0;
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
			float:right;
			width:890px;
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
  
 <?php
  	if (! $mode_impresio) {
  ?>
  <h4 style="margin-bottom:0px">
  <form id="ff" name="ff" method="post">
  Grup 
  <input id="idgrup" name="idgrup" class="easyui-combobox" style="width:320px" data-options="
                	required: false,
                    panelWidth: 320
  ">
  <br /><br />
  <input id="box_al" name="box_al" type="checkbox" value="alumne" checked="checked" />&nbsp;Alumnes&nbsp;
  <input id="box_ed" name="box_ed" type="checkbox" value="general" />&nbsp;Equip docent&nbsp;
  <input id="box_faltes" name="box_faltes" type="checkbox" value="falta" />&nbsp;Faltes&nbsp;
  <input id="box_retards" name="box_retards" type="checkbox" value="retard" />&nbsp;Retards&nbsp;
  <input id="box_justificacions" name="box_justificacions" type="checkbox" value="justificacio" />&nbsp;Justificacions&nbsp;
  <input id="box_incidencies" name="box_incidencies" type="checkbox" value="incidencia" />&nbsp;Seguiments&nbsp;
  <input id="box_CCC" name="box_CCC" type="checkbox" value="CCC" />&nbsp;CCC&nbsp;
  <br />
  Desde <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
  Fins a <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
  </h4>
  <p align="right" style=" border:0px solid #0C6; height:32px; background:whitesmoke;">
  <a href="#" onclick="doSearch()">
  <img src="./images/icons/icon_search.png" height="32"/></a>
  <a href="#" onclick="javascript:imprimirPDF()">
  <img src="./images/icons/icon_pdf.png" height="32"/></a>
  <a href="#" onclick="javascript:imprimirWord()">
  <img src="./images/icons/icon_word.png" height="32"/></a>
  <a href="#" onclick="javascript:imprimirExcel()">
  <img src="./images/icons/icon_excel.png" height="32"/></a>
  </form>
  </p>
  <?php
  	}
  ?>
  
 <div id="resultDiv" style="width:890px; margin-top:-5px;">
  
  <h2 style="margin-bottom:0px">
  Informe d'assist&egrave;ncia
  <a style=" color: #000066; border:0px dashed #CCCCCC; padding:2px 2px 2px 2px ">
  <?php 
  	if ($idgrup != 0) {
		echo getGrup($idgrup)->nom;
	}
   ?></a>
   &nbsp;(<a style=' color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px '><?= $txt_inici ?></a>
   -&nbsp;<a style=' color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px '><?= $txt_fi ?></a>)
  </h2>

 <br />
 
 <div class="right">
 
 <?php
  if ($box_al != '') {
 ?>
 <h5>Alumnes del grup</h5>
 <table>
    <tr>
    	<td>&nbsp;</td>
        <td><strong>ALUMNE</strong></td>
        <td><strong>NUM. FALTES</strong></td>
        <td><strong>NUM. RETARDS</strong></td>
        <td><strong>NUM. JUSTIFICADES</strong></td>
        <td><strong>NUM. SEGUIMENTS</strong></td>
        <td><strong>NUM. CCC</strong></td>
    </tr>
    <?php
		$linea = 1;
		$rsAlumnes = getAlumnesGrup($idgrup,TIPUS_nom_complet);
		while($row = mysql_fetch_object($rsAlumnes)){
		  echo "<tr>";
		  echo "<td valign='top' width='30'>".$linea."</td>";
		  echo "<td valign='top' class='drop'>".$row->Valor."</td>";
		  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)."</td>";
		  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)."</td>";
		  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)."</td>";
		  echo "<td valign='top' width='90' class='drop'>".getTotalIncidenciasAlumne($row->idalumnes,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)."</td>";
		  echo "<td valign='top' width='90' class='drop'>".getTotalCCCAlumne($row->idalumnes,$data_inici,$data_fi)."</td></tr>";
		  $linea++;
		}
	?>
    <tr>
    	<td colspan="7"><strong>Totals</strong></td>
    </tr>
    <tr>
    	<td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalCCCGrup($idgrup,$data_inici,$data_fi)?></td>
    </tr>
 </table>
 <br />
 <?php
  }
 ?>
 
 <?php
  if ($box_ed != '') {
 ?>
 <h5>Equip docent</h5>
 <table>
    <tr>
    	<td><strong>&nbsp;</strong></td>
        <td><strong>Docent</strong></td>
        <td><strong>Materia</strong></td>
        <td><strong>Hores/Set</strong></td>
        <td><strong>FALTES</strong></td>
        <td><strong>RETARDS</strong></td>
        <td><strong>JUSTIFICADES</strong></td>
        <td><strong>SEGUIMENTS</strong></td>
        <td><strong>CCC</strong></td>
    </tr>
    <?php
		$linea         = 1;
		$rsEquipDocent = getProfessorsGrup($idgrup);
		while($row = mysql_fetch_object($rsEquipDocent)){
			echo "<tr>";
			if (isActivat($row->idprofessors)=='S') {
				$isActivat="class='drop'";
			} else {
				$isActivat="";
			}
			echo "<td valign='top' width='30'>".$linea."</td>";
			echo "<td valign='top' ".$isActivat.">".$row->nom."</td>";
			echo "<td valign='top' ".$isActivat.">".$row->materia."</td>";
			echo "<td valign='top' ".$isActivat.">".HoresSetmanals($idgrup,$row->codi_materia)."</td>";
			echo "<td valign='top' ".$isActivat.">".getTotalIncidenciasProfessorGrupMateria($row->idprofessors,$idgrup,$row->codi_materia,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)."</td>";
			echo "<td valign='top' ".$isActivat.">".getTotalIncidenciasProfessorGrupMateria($row->idprofessors,$idgrup,$row->codi_materia,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)."</td>";
			echo "<td valign='top' ".$isActivat.">".getTotalIncidenciasProfessorGrupMateria($row->idprofessors,$idgrup,$row->codi_materia,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)."</td>";
			echo "<td valign='top' ".$isActivat.">".getTotalIncidenciasProfessorGrupMateria($row->idprofessors,$idgrup,$row->codi_materia,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)."</td>";
			echo "<td valign='top' ".$isActivat.">".getTotalCCCProfessorGrup($row->idprofessors,$idgrup,$row->codi_materia,$data_inici,$data_fi)."</td>";
			echo "</tr>";
			$linea++;
		}
	?>
    <tr>
    	<td colspan="7"><strong>Totals</strong></td>
    </tr>
    <tr>
    	<td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalCCCGrup($idgrup,$data_inici,$data_fi)?></td>
    </tr>
 </table>
 <br />
 <?php
  }
 ?>
 
 <?php
  if ($box_faltes != '') {
 ?>
 <h5>Relaci&oacute; de faltes</h5>
 <table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>ALUMNE/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
           
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getAlumne($row->idalumnes,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td></tr>";
						  $linea++;
				   }
				?>          
  </table>
  <br />   
  <?php
  }
  ?> 
        
  <?php
  if ($box_retards != '') {
  ?> 
  <h5>Relaci&oacute; de retards</h5>
  <table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>ALUMNE/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getAlumne($row->idalumnes,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td></tr>";
						  $linea++;
				   }
				?>          
  </table>
  <br />   
  <?php
  }
  ?>
  
  <?php
  if ($box_justificacions != '') {
  ?>     
  <h5>Relaci&oacute; de justificacions</h5>
  <table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>ALUMNE/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>OBSERVACIONS</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='100' class='drop'>".getAlumne($row->idalumnes,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' width='200' class='drop'>".nl2br($row->comentari)."</td></tr>";
						  $linea++;
				   }
				?>          
  </table>
  <br />   
  <?php
  }
  ?>
   
  <?php
  if ($box_incidencies != '') {
  ?>
  <h5>Relaci&oacute; de SEGUIMENTS</h5>
  <table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>TIPUS</strong></td>
                <td><strong>DATA</strong></td>
                <td><strong>F. HOR&Agrave;RIA</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>ALUMNE/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>OBSERVACIONS</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasGrup($idgrup,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='40' class='drop'>".getLiteralTipusIncident($row->id_tipus_incident)->tipus_incident."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='80' class='drop'>".substr(getFranjaHoraria($row->idfranges_horaries)->hora_inici,0,5)."-".substr(getFranjaHoraria($row->idfranges_horaries)->hora_fi,0,5)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getAlumne($row->idalumnes,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' width='200' class='drop'>".nl2br($row->comentari)."</td></tr>";
						  $linea++;
				   }
				?>          
	</table>
    <br />   
	<?php
    }
    ?>
        
	<?php
    if ($box_CCC != '') {
    ?>
    <h5>Relaci&oacute; de CCC</h5>
 	<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>TIPUS CCC</strong></td>
                <td><strong>DATA</strong></td>
                <td><strong>EXPULSI&Oacute;</strong></td>
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>ALUMNE/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>DESCRIPCI&Oacute;</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getCCCGrup($idgrup,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='20'>".$linea."</td>";
						  echo "<td valign='top' width='40' class='drop'>".getLiteralTipusCCC($row->id_falta)->nom_falta."</td>";
						  echo "<td valign='top' width='70' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='40' class='drop'>".$row->expulsio."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getProfessor($row->idprofessor,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getAlumne($row->idalumne,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".(intval($row->idmateria!=0) ? getMateria($row->idmateria)->nom_materia : '')."</td>";
						  echo "<td valign='top' width='300' class='drop'><strong>Desc. breu</strong><br>".getLiteralMotiusCCC($row->id_motius)->nom_motiu;
						  echo "<br><strong>Desc. detallada</strong><br>".nl2br($row->descripcio_detallada)."</td></tr>";
						  $linea++;
				   }
				?>          
	</table>
    <?php
    }
    ?>
        
 </div>
    
<?php
	if (isset($rsAlumnes)) {
    	mysql_free_result($rsAlumnes);
	}
	if (isset($rsIncidencias)) {
    	mysql_free_result($rsIncidencias);
	}
	if (isset($rsEquipDocent)) {
    	mysql_free_result($rsEquipDocent);
	}
?>

</div>

<iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>

<script type="text/javascript">  		
		var url;
		
		function myformatter(date){  
            var y = date.getFullYear();  
            var m = date.getMonth()+1;  
            var d = date.getDate();  
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        }
		
		function myparser(s){  
            if (!s) return new Date();  
            var ss = (s.split('-'));  
            var y = parseInt(ss[0],10);  
            var m = parseInt(ss[1],10);  
            var d = parseInt(ss[2],10);  
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){  
                return new Date(d,m-1,y);
            } else {  
                return new Date();  
            }  
        }
		
		function doSearch(){  
			d_inici = $('#data_inici').datebox('getValue');
			d_fi    = $('#data_fi').datebox('getValue');
			idgrup  = $('#idgrup').combobox('getValue');
			
			url = './inf_assist/inf_assist_grup_see.php?data_inici='+d_inici+'&data_fi='+d_fi+'&idgrup='+idgrup+'&mode_impresio=1';
			
			$('#ff').form('submit',{  
						url: url, 
						onSubmit: function(){  
							return $(this).form('validate');  
						},  
						success: function(result){
							$('#resultDiv').html(result);
							$('#idgrup').combobox('setValue', idgrup);
						}  
			}); 			 
        }  
		
		function imprimirPDF(idgrup){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			idgrup   = $('#idgrup').combobox('getValue');
			
			box_al             = '<?= $box_al ?>';
			box_ed             = '<?= $box_ed ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './inf_assist/inf_assist_grup_print.php?data_inici='+d_inici+'&data_fi='+d_fi+'&idgrup='+idgrup+'&mode_impresio=1';
			url += '&box_al='+box_al+'&box_ed='+box_ed+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirWord(idgrup){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			idgrup   = $('#idgrup').combobox('getValue');
			
			box_al             = '<?= $box_al ?>';
			box_ed             = '<?= $box_ed ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './inf_assist/inf_assist_grup_print_word.php?data_inici='+d_inici+'&data_fi='+d_fi+'&idgrup='+idgrup+'&mode_impresio=1';
			url += '&box_al='+box_al+'&box_ed='+box_ed+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirExcel(idgrup){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			idgrup   = $('#idgrup').combobox('getValue');
			
			box_al             = '<?= $box_al ?>';
			box_ed             = '<?= $box_ed ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './inf_assist/inf_assist_grup_print_excel.php?data_inici='+d_inici+'&data_fi='+d_fi+'&idgrup='+idgrup+'&mode_impresio=1';
			url += '&box_al='+box_al+'&box_ed='+box_ed+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
		
</script>

<script type="text/javascript">
	$('#header').css('visibility', 'hidden');
	$('#footer').css('visibility', 'hidden');
	
	$('#idgrup').combobox({
		url:'./grma/grup_getdata.php',
		valueField:'idgrups',
		textField:'nom'
	});
</script>

<?php
mysql_close();
?>