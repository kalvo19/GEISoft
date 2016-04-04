<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
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
  
  if ( isset($_REQUEST['c_alumne']) && ($_REQUEST['c_alumne']==0) ) {
  	$c_alumne = 0;
  }
  else if ( isset($_REQUEST['c_alumne']) ) {
    $c_alumne = $_REQUEST['c_alumne'];
  }
  if (! isset($c_alumne)) {
    $c_alumne = 0;
  }
  
  $box_dg             = isset($_REQUEST['box_dg'])             ? $_REQUEST['box_dg']             : '';
  $box_faltes         = isset($_REQUEST['box_faltes'])         ? $_REQUEST['box_faltes']         : '';
  $box_retards        = isset($_REQUEST['box_retards'])        ? $_REQUEST['box_retards']        : '';
  $box_justificacions = isset($_REQUEST['box_justificacions']) ? $_REQUEST['box_justificacions'] : '';
  $box_incidencies    = isset($_REQUEST['box_incidencies'])    ? $_REQUEST['box_incidencies']    : '';
  $box_CCC            = isset($_REQUEST['box_CCC'])            ? $_REQUEST['box_CCC']            : '';
  
  $curs_escolar       = getCursActual()->idperiodes_escolars; 
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
  	if ($mode_impresio) {
?>

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
<?php
  	}
?>

<?php
  	if (! $mode_impresio) {
?>
  <h4 style="margin-bottom:0px; margin-top:-5px;">
 
  <form id="ff" name="ff" method="post">
  <table bgcolor="whitesmoke" width="100%">
  <tr>
  	<td>
      <input id="box_dg" name="box_dg" type="checkbox" value="dg" checked="checked" />&nbsp;Dades globals&nbsp;
      <input id="box_faltes" name="box_faltes" type="checkbox" value="falta" checked="checked" />&nbsp;Faltes&nbsp;
      <input id="box_retards" name="box_retards" type="checkbox" value="retard" checked="checked" />&nbsp;Retards&nbsp;
      <input id="box_justificacions" name="box_justificacions" type="checkbox" value="justificacio" checked="checked" />&nbsp;Justificacions&nbsp;
      <input id="box_incidencies" name="box_incidencies" type="checkbox" value="incidencia" checked="checked" />&nbsp;Seguiments&nbsp;
      <input id="box_CCC" name="box_CCC" type="checkbox" value="CCC" checked="checked" />&nbsp;CCC&nbsp;
      &nbsp;&nbsp;&nbsp;
      <br />Desde <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
      Fins a <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
    </td>
    <td align="right">
      <a href="#" onclick="doSearch(<?= $c_alumne ?>)">
      <img src="./images/icons/icon_search.png" height="32"/></a>
      <a href="#" onclick="javascript:imprimirPDF(<?= $c_alumne ?>)">
      <img src="./images/icons/icon_pdf.png" height="32"/></a>
      <a href="#" onclick="javascript:imprimirWord(<?= $c_alumne ?>)">
      <img src="./images/icons/icon_word.png" height="32"/></a>
      <a href="#" onclick="javascript:imprimirExcel(<?= $c_alumne ?>)">
      <img src="./images/icons/icon_excel.png" height="32"/></a>
    </td>
  </tr>
  </table>
  </form>
  </h4>

<?php
  	}
?>


 <div id="resultDiv" style="width:890px;">
  <br />
  <h5>
  &nbsp;Informe de faltes de l'alumne <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= getAlumne($c_alumne,TIPUS_nom_complet) ?></a>
 </h5>
 <div class="right">
 <table>
    <tr>
        <td><strong>NUM. FALTES</strong></td>
        <td><strong>NUM. RETARDS</strong></td>
        <td><strong>NUM. JUSTIFICADES</strong></td>
        <td><strong>NUM. SEGUIMENTS</strong></td>
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
 
 <?php
  if ($box_dg != '') {
 ?>
  <h5>Mat&egrave;ries cursades</h5>
 <table>
    <tr>
    	<td>&nbsp;</td>
        <td><strong>Grup</strong></td>
        <td><strong>Mat&egrave;ria</strong></td>
        <td><strong>DIES LECTIUS</strong></td>
        <td><strong>NUM. FALTES</strong></td>
        <td><strong>NUM. RETARDS</strong></td>
        <td><strong>NUM. JUSTIFICADES</strong></td>
        <td><strong>NUM. SEGUIMENTS</strong></td>
        <td><strong>NUM. CCC</strong></td>
    </tr>
    
    <?php
		$linea = 1;
		$rsMateries = getMateriesAlumne($curs_escolar,$c_alumne);
		while($row = mysql_fetch_object($rsMateries)){
		  
		  $grup_materia  = existGrupMateria($row->idgrups,$row->id_mat_uf_pla);
		  $total_classes = classes_entre_dates($data_inici,$data_fi,$grup_materia,$curs_escolar);
		  $escicleloe    = isMateria($row->id_mat_uf_pla) ? 0 : 1 ;
		 /* if ($escicleloe) {
			 $data_inici = getGrupMateria($grup_materia)->data_inici;
			 $txt_inici  = substr($data_inici,8,2)."-".substr($data_inici,5,2)."-".substr($data_inici,0,4);
			 $data_fi    = getGrupMateria($grup_materia)->data_fi;
			 $txt_fi     = substr($data_fi,8,2)."-".substr($data_fi,5,2)."-".substr($data_fi,0,4);
		  }*/
		  
		  $total_absencies = getTotalIncidenciasAlumneGrupMateria($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$row->idgrups,$row->id_mat_uf_pla,$data_inici,$data_fi);
		  
		  $total_retards = getTotalIncidenciasAlumneGrupMateria($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$row->idgrups,$row->id_mat_uf_pla,$data_inici,$data_fi);
		 
		  $total_justificacions = getTotalIncidenciasAlumneGrupMateria($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$row->idgrups,$row->id_mat_uf_pla,$data_inici,$data_fi);
		  
		  $total_seguiments = getTotalIncidenciasAlumneGrupMateria($c_alumne,TIPUS_FALTA_ALUMNE_SEGUIMENT,$row->idgrups,$row->id_mat_uf_pla,$data_inici,$data_fi);
		  
		  $total_ccc = getTotalCCCAlumneGrupMateria($c_alumne,$row->idgrups,$row->id_mat_uf_pla,$data_inici,$data_fi);
		  
		  
		  echo "<tr>";
		  echo "<td valign='top' width='30'>".$linea."</td>";
		  echo "<td valign='top' width='60' class='drop'>".substr($row->grup,0,20)."</td>";
		  echo "<td valign='top' class='drop'>".$row->materia."</td>";
		  echo "<td valign='top' class='drop' width='50'>".$total_classes."</td>";
		  echo "<td valign='top' width='90' class='drop'>";
		  if ($total_absencies != 0) {
			  echo "<strong>".$total_absencies."</strong>&nbsp;(".round(($total_absencies/$total_classes)*100,2)."%)";
		  }
		  echo "</td>";
		  
		  echo "<td valign='top' width='90' class='drop'>";
		  if ($total_retards != 0) {
			  echo "<strong>".$total_retards."</strong>&nbsp;(".round(($total_retards/$total_classes)*100,2)."%)";
		  }
		  echo "</td>";
		  
		  echo "<td valign='top' width='90' class='drop'>";
		  if ($total_justificacions != 0) {
			  echo "<strong>".$total_justificacions."</strong>&nbsp;(".round(($total_justificacions/$total_classes)*100,2)."%)";
		  }
		  echo "</td>";
		  
		  echo "<td valign='top' width='90' class='drop'>";
		  if ($total_seguiments != 0) {
			  echo "<strong>".$total_seguiments."</strong>&nbsp;(".round(($total_seguiments/$total_classes)*100,2)."%)";
		  }
		  echo "</td>";
		  
		  echo "<td valign='top' width='90' class='drop'>";
		  if ($total_ccc != 0) {
			  echo "<strong>".$total_ccc."</strong>&nbsp;(".round(($total_ccc/$total_classes)*100,2)."%)";
		  }
		  echo "</td>";
		  
		  $linea++;
		}
	?>
    <tr>
    	<td colspan="8"><strong>Totals</strong></td>
    </tr>
    <tr>
    	<td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'>&nbsp;</td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_SEGUIMENT,$data_inici,$data_fi)?></td>
        <td class='drop'><?=getTotalCCCAlumne($c_alumne,$data_inici,$data_fi)?></td>
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
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
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
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
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
                <td><strong>PROFESSOR/A</strong></td>
                <td><strong>MAT&Egrave;RIA</strong></td>
            </tr>
            
                <?php
				   $linea         = 1;
				   $rsIncidencias = getIncidenciasAlumne($c_alumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi);
				   while($row = mysql_fetch_object($rsIncidencias)){
						  echo "<tr>";
						  echo "<td valign='top' width='30'>".$linea."</td>";
						  echo "<td valign='top' width='90' class='drop'>".substr($row->data,8,2)."-".substr($row->data,5,2)."-".substr($row->data,0,4)."</td>";
						  echo "<td valign='top' width='200' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
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
        <h5>Relaci&oacute; de seguiments</h5>
 		<table>
            <tr>
                <td>&nbsp;</td>
                <td><strong>TIPUS</strong></td>
                <td><strong>DATA</strong></td>
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
						  echo "<td valign='top' width='50' class='drop'>".getProfessor($row->idprofessors,TIPUS_nom_complet)."</td>";
						  echo "<td valign='top' width='50' class='drop'>".getMateria($row->id_mat_uf_pla)->nom_materia."</td>";
						  echo "<td valign='top' width='300' class='drop'>".nl2br($row->comentari)."</td>";
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
                <td><strong>MAT&Egrave;RIA</strong></td>
                <td><strong>GRUP</strong></td>
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
						  echo "<td valign='top' width='50' class='drop'>".(intval($row->idgrup!=0) ? getGrup($row->idgrup)->nom : '')."</td>";
						  echo "<td valign='top' width='300' class='drop'><strong>Desc. breu</strong><br>".getLiteralMotiusCCC($row->id_motius)->nom_motiu;
						  echo "<br><strong>Desc. detallada</strong><br>".nl2br($row->descripcio_detallada)."</td>";
						  $linea++;
				   }
				?>          
		</table>
  <?php
  }
  ?>
        
 </div>
 
<?php
	if (isset($rsMateries)) {
    	mysql_free_result($rsMateries);
	}
	if (isset($rsIncidencias)) {
    	mysql_free_result($rsIncidencias);
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
		
		function doSearch(c_alumne){  
			d_inici = $('#data_inici').datebox('getValue');
			d_fi    = $('#data_fi').datebox('getValue');
			
			url = './alum_automat/alum_automat_see.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&mode_impresio=1';
			
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
		
		function imprimirPDF(c_alumne){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			
			box_dg             = '<?= $box_dg ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './alum_automat/alum_automat_print.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&mode_impresio=1';
			url += '&box_dg='+box_dg+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirWord(c_alumne){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			
			box_dg             = '<?= $box_dg ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './alum_automat/alum_automat_print_word.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&mode_impresio=1';
			url += '&box_dg='+box_dg+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirExcel(c_alumne){  
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			
			box_dg             = '<?= $box_dg ?>';
			box_faltes         = '<?= $box_faltes ?>';
			box_retards        = '<?= $box_retards ?>';
			box_justificacions = '<?= $box_justificacions ?>';
			box_incidencies    = '<?= $box_incidencies ?>';
			box_CCC            = '<?= $box_CCC ?>';
			
			url  = './alum_automat/alum_automat_print_excel.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&mode_impresio=1';
			url += '&box_dg='+box_dg+'&box_faltes='+box_faltes+'&box_retards='+box_retards;
			url += '&box_justificacions='+box_justificacions+'&box_incidencies='+box_incidencies+'&box_CCC='+box_CCC;
			
			$('#fitxer_pdf').attr('src', url);
		}
				
</script>

<script type="text/javascript">
	$('#header').css('visibility', 'hidden');
	$('#footer').css('visibility', 'hidden');		
</script>

<?php
mysql_close();
?>