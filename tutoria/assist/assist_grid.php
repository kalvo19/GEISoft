<?php
	if (isset($_SESSION['materia_classe_actual'])) {
		$classe_actual      = 0;
		$idmateria          = $_SESSION['materia_classe_actual'];
		$idgrups            = $_SESSION['grup_classe_actual'];
		$idfranges_horaries = $_SESSION['fh_classe_actual'];
		$idespais_centre    = isset($_REQUEST['idespais_centre']) ? $_REQUEST['idespais_centre'] : 0 ;
		$idprofessors       = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0 ;
		if (validEntryLogProfessor($idprofessors,TIPUS_ACCIO_ENTRACLASSE)) {
			$classe_actual  = 1;
		}
		unset($_SESSION['materia_classe_actual']);
		unset($_SESSION['grup_classe_actual']);
		unset($_SESSION['fh_classe_actual']);
		echo "<h2>La teva classe actual</h2>";
	}
	
	else if (isset($_REQUEST['idmateria'])) {
		include_once('../bbdd/connect.php');
		include_once('../func/generic.php');
		include_once('../func/constants.php');
		$classe_actual      = 0;
		$idmateria          = $_REQUEST['idmateria'];
		$idgrups            = $_REQUEST['idgrups'];
		$idprofessors       = $_REQUEST['idprofessors'];
		$idfranges_horaries = $_REQUEST['idfranges_horaries'];
		$idespais_centre    = isset($_REQUEST['idespais_centre']) ? $_REQUEST['idespais_centre'] : 0 ;
		if ($_REQUEST['act'] == 1) {
			if (validEntryLogProfessor($idprofessors,TIPUS_ACCIO_ENTRACLASSE)) {
				$classe_actual = 1;
			}
			echo "<h2>La teva classe actual</h2>";
		}
	}
	
	else {
	    echo "<h4>En aquesta hora no tens cap classe. Des del menú de la dreta podràs accedir a les teves classes
	      i gu&agrave;rdies d'avui, a més d'altres opcions ... </h4>";
		exit();
	}
	
	mysql_query("SET NAMES 'utf8'");
	$nom_grup = getGrup($idgrups)->nom;
	
	$fechaSegundos = time();
    $strNoCache = "?nocache=$fechaSegundos";
?>
    
<table width="99%" cellpadding="0" cellspacing="0">
<tr>
<td>
	<h4>Franja hor&agrave;ria <?=getLiteralFranjaHoraria($idfranges_horaries)?></h4>
	<h5>Alumnes de <?=getMateria($idmateria)->nom_materia?> de <?=$nom_grup?></h5>   
</td>
<td align="right" valign="top">
<a href="javascript:void(0)" title="Informe assist&egrave;ncia" class="easyui-tooltip" onclick="informeAssistencia(<?=$idgrups?>,<?=$idmateria?>,'<?=$nom_grup?>')"><img src="./images/icons/icon_report.png" height="40"/></a>
<a href="javascript:void(0)" title="Seguiment classe" class="easyui-tooltip" onclick="addSeguiment(<?=$idgrups?>,<?=$idmateria?>,<?=$idfranges_horaries?>)"><img src="./images/icons/icon_class.png" height="40"/></a>
<!--
<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="enviarSMS($idgrups)">
<img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>
-->
</td>
</tr>
</table>

     
    <div id="mainScreen" class="alumnes">  
        <ul>  
            <?php
			   $rsAlumnes = getAlumnesMateriaGrup($idgrups,$idmateria,TIPUS_nom_complet);
			   while ($row = mysql_fetch_assoc($rsAlumnes)) {
			     
				 //$idalumne_agrupament = getAlumneMateriaGrup($idgrups,$idmateria,$row['idalumnes'])->idalumnes_grup_materia;
				 
				 if (exitsIncidenciaAlumne($row['idalumnes'],date("Y-m-d"),$idfranges_horaries)) {
				 	$id_tipus_incidencia = getIncidenciaAlumne($row['idalumnes'],date("Y-m-d"),$idfranges_horaries)->id_tipus_incidencia;
					$idincidencia_alumne = getIncidenciaAlumne($row['idalumnes'],date("Y-m-d"),$idfranges_horaries)->idincidencia_alumne;
				 	$comentari           = getIncidencia($idincidencia_alumne)->comentari;
				 }
				 else {
					$id_tipus_incidencia = 0; 
				 }
				 
				 // Cas de que un alumne estigui amb una sortida
				 if (existAlumneSortidaData($row['idalumnes'],date("y-m-d"),getFranjaHoraria($idfranges_horaries)->hora_inici) != 0) {
					$id_tipus_incidencia = TIPUS_FALTA_ALUMNE_JUSTIFICADA;
				 }
				 // Cas de que un alumne estigui amb una CCC
				 if (existAlumneCCCData($row['idalumnes'],date("y-m-d"),$idfranges_horaries) != 0) {
					$id_tipus_incidencia = TIPUS_FALTA_ALUMNE_CCC;
				 }
				 // Cas de que un alumne tingui més d'una incidència
				 if (exitsIncidenciaAlumnebyDataFranja($row['idalumnes'],date("y-m-d"),$idfranges_horaries) > 1) {
				 	$id_tipus_incidencia = TIPUS_FALTA_ALUMNE_MULTIPLE;
				 }
				  
				 if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_ABSENCIA) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemfalta'>";
				 }
				 else if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_RETARD) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemretard'>";
				 }
				 else if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_SEGUIMENT) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemincidencia'>";
				 }
				 else if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_JUSTIFICADA) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemjustificada'>";
				 }
				 else if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_CCC) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemccc'>";
				 }
				 else if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_MULTIPLE) {
				 	echo "<div id='al".$row['idalumnes']."' class='itemmultiple'>";
				 }
				 else {
				 	echo "<div id='al".$row['idalumnes']."' class='item'>";
				 }
				 
				 echo "<li>";
				 echo substr(getAlumne($row['idalumnes'],TIPUS_cognom1_alumne)." ".getAlumne($row['idalumnes'],TIPUS_cognom2_alumne),0,20)."<br> ";
				 echo substr(getAlumne($row['idalumnes'],TIPUS_nom_alumne),0,23);
				 echo "<table cellspacing=0 cellpadding=0 border=0><tr>";
				 
				 if (isset($_REQUEST['idmateria'])) {
				 	$img_alumne = "../images/alumnes/".$row['idalumnes'].".jpg";
				 }
				 else {
				 	$img_alumne = "./images/alumnes/".$row['idalumnes'].".jpg";
				 }
				 
				 if (file_exists($img_alumne)) {
				   echo "<td width=51><a id='".$row['idalumnes']."' href='javascript:void(0)' data-options='plain:true,toggle:true' onclick='gestioAI(this.id,".$idfranges_horaries.")'><img src='./images/alumnes/".$row['idalumnes'].".jpg".$strNoCache."' width='51' height='70' align='absbottom'></a></td>";
				 }
				 else {
				   echo "<td width=51><a id='".$row['idalumnes']."' href='javascript:void(0)' data-options='plain:true,toggle:true' onclick='gestioAI(this.id,".$idfranges_horaries.")'><img src='./images/alumnes/alumne.png' width='51' height='70'></a></td>";
				 }
				 
				 echo "<td valign='top'>";
				 if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_JUSTIFICADA) {
				 	if (isset($comentari)) {
						echo substr($comentari,0,90);
					}
				 }
				 else {
				 	 $rsTipusFaltes = getTipusFaltaAlumne();
					 while ($rowf = mysql_fetch_assoc($rsTipusFaltes)) {
					   if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_ABSENCIA) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addFalta(this.id)'>".$rowf['tipus_falta']."</a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_RETARD) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addRetard(this.id)'>".$rowf['tipus_falta']."</a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_SEGUIMENT) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addIncident(this.id)'>".$rowf['tipus_falta']."</a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_CCC) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addCCC(this.id)'>".$rowf['tipus_falta']."</a><br>";
					   }
					   
					 }
					 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='cancelar(this.id)'>Cancel.lar</a>";
				 }
				 echo "</td></tr></table>";
                 echo "</li></div>";
			   }
			
			?>   
        </ul> 
        <div class="clear"></div> 
        <br /><br /><br /><br />
    </div>  
    
  

<div id="dlg" class="easyui-dialog" style="width:700px;height:420px;padding:10px 20px"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg-buttons">  
        <form id="fm" method="post" novalidate>             	
            <div class="fitem">  
                <label>Tipus</label>
                <select id="id_tipus_incident" name="id_tipus_incident" class="easyui-combobox" data-options="
					width:600,
                    url:'./incidents_tipus/incidents_tipus_getdata.php',
					idField:'idtipus_incident',
                    valueField:'idtipus_incident',
					textField:'tipus_incident',
					panelHeight:'auto'
                ">
                </select>
                <br /><br />
                <label>Descripci&oacute;</label><br />
                <textarea name="comentari" style="height:250px; width:650px;"></textarea>
            </div>
        </form>  
    </div>  
    <div id="dlg-buttons">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveIncident()">Guardar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelIncident()">Cancelar</a>
    </div>
</div>

<div id="dlg_ccc" class="easyui-dialog" style="width:760px;height:500px;padding:5px 5px"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg-buttons_ccc">  
        <form id="fm_ccc" method="post" novalidate>             	
            <input type="hidden" name="id_tipus_sancio" value="0" />
            <input type="hidden" name="data_inici_sancio" value="" />
            <input type="hidden" name="data_fi_sancio" value="" />
            <div class="fitem">  
            	<label style="font-size:16px; font-weight:bolder">Implica expulsi&oacute; de classe?</label>
            	<input id="expulsio" name="expulsio" type="checkbox" value="S">
                <label style="color:#666666">(marcar aquesta opci&oacute; si es treu l'alumne de classe)</label>
            	<br /><br />
                
                <label style="color:#666666">Tipus d'incidència</label>
                <select id="id_falta" name="id_falta" class="easyui-combobox" data-options="
					required:true,
                    width:250,
                    url:'./ccc_tipus/ccc_tipus_getdata.php',
					idField:'idccc_tipus',
                    valueField:'idccc_tipus',
					textField:'nom_falta',
					panelHeight:'auto'
                ">
                </select>
                
                <br /><br />
                <label style="color:#666666">Descripció breu</label>  <br />
                <select id="id_motius" name="id_motius" class="easyui-combogrid" style="width:735px" data-options="
                    required: false,
                    panelWidth: 735,
                    idField: 'idccc_motius',
                    textField: 'nom_motiu',
                    url: url,
                    method: 'get',
                    columns: [[
                        {field:'nom_motiu',title:'Motiu',width:735}
                    ]],
                    fitColumns: true
                ">
                </select>
                <br /><br />
                <label style="color:#666666">Fets que s'han produït</label> <br />
                <textarea name="descripcio_detallada" style="height:250px; width:700px;"></textarea>
            </div>
        </form>  
    </div>  
    <div id="dlg-buttons_ccc">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveCCC()">Guardar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="cancelCCC()">Cancelar</a>
    </div>
</div>
      
<div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">
</div>
    
<div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <form method="post">
                Desde: <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a: <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
                Per alumne: 
                <select id="c_alumne" class="easyui-combobox" name="state" style="width:400px;">
                    <option value="0">Tots els alumnes ...</option>
                    <?php
					  $rsAlumnes = getAlumnesGrup($idgrups,TIPUS_nom_complet);
					  while($row = mysql_fetch_object($rsAlumnes)){
					  	echo "<option value='".$row->idalumnes."'>".$row->Valor."</option>";
					  }
					?>
                </select>
                <br />
                Percentatge&nbsp;<input id="percentatge" class="easyui-numberbox" value="20" size="5" data-options="precision:0,required:true,min:0,max:100">&nbsp;%&nbsp;
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="IndicadorsGrupMateria(<?=$idgrups?>,<?=$idmateria?>)">Indicadors Grup Mat&egrave;ria</a>&nbsp;
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="IndicadorsAlumneGrupMateria(<?=$idgrups?>,<?=$idmateria?>)">Indicadors Alumne</a>&nbsp;

                <a href="#" onclick="imprimirPDF(<?=$idgrups?>,<?=$idmateria?>)">
                <img src="./images/icons/icon_pdf.png" height="32"/></a>
                <a href="#" onclick="imprimirWord(<?=$idgrups?>,<?=$idmateria?>)">
                <img src="./images/icons/icon_word.png" height="32"/></a>
                <a href="#" onclick="imprimirExcel(<?=$idgrups?>,<?=$idmateria?>)">
                <img src="./images/icons/icon_excel.png" height="32"/></a>
                </form>
                
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload(<?=$idgrups?>,<?=$idmateria?>)">Recarregar</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
	<div id="dlg_seg" class="easyui-dialog" style="width:550px;height:330px; padding-top:10px; padding-left:10px;"  
            closed="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_seg-toolbar">
		<table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <form id="fm_seg" method="post">
                <input type="hidden" id="idgrups" name="idgrups" value="<?=$idgrups?>" />
                <input type="hidden" id="idmateria" name="idmateria" value="<?=$idmateria?>" />
                <input type="hidden" id="idfranges_horaries" name="idfranges_horaries" value="<?=$idfranges_horaries?>" />
                
                Lectiva&nbsp;&nbsp;
                <input id="lectiva" name="lectiva" type="radio" value="1" />&nbsp;S&iacute;&nbsp;
                <input id="lectiva" name="lectiva" type="radio" value="0" />&nbsp;No&nbsp;
                <br /><br />
                Seguiment<br />
                <textarea id="seguiment" name="seguiment" rows="8" cols="60"></textarea>
                <br /><br />
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveSeguiment()">Guardar</a>&nbsp;                
                </form>
            </td>
        </tr>  
    	</table>
    </div>
    
	<div id="dlg_seg-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td align="right">
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_seg').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_ind" class="easyui-dialog" style="width:840px;height:500px;"  
            closed="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_ind-toolbar">
    </div>

	 <div id="dlg_ind-toolbar" style="height:auto">
        <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirPDF(<?=$idgrups?>)">Imprimir</a>  
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_ind').dialog('close')">Tancar</a>
    </div>

	<div id="dlg_sms" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
    </div>
        
    <div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar(<?=$idgrups?>,<?=$idmateria?>,<?=$idprofessors?>,<?=$idfranges_horaries?>)">Tancar</a>
    </div>
    
    <div id="dlg_ai" class="easyui-dialog" style="width:875px;height:225px;padding:5px 5px" maximizable="true" modal="true" closed="true">
        <table id="dg_ai" class="easyui-datagrid" title="" style="width:850px;height:180px"
                data-options="
                    singleSelect: true,
                    url:'./assist_dant/altres_inc_getdata.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_ai_toolbar'
                    
                ">
            <thead>
                <tr>
                    <th sortable="true" data-options="field:'id_tipus_incidencia',width:100,
						formatter:function(value,row){
							return row.tipus_falta;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idtipus_falta_alumne',
                                textField:'tipus_falta',
                                url:'./assist_dant/assist_dant_tf_getdata.php',
								required:true
							}
                            
						}">Tipus falta</th>
                    <th sortable="true" align="left" data-options="field:'id_tipus_incident',width:300,
						formatter:function(value,row){
                            if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_SEGUIMENT?>) {
                            	return row.tipus_incident;
                            }
                            else {
                            	return '';
                            }
						},
						editor:{
							type:'combobox',
							options:{
                                url:'./incidents_tipus/incidents_tipus_getdata.php',
					            idField:'idtipus_incident',
                                valueField:'idtipus_incident',
					            textField:'tipus_incident',
								required:false
							}
						}">Tipus seguiment</th>
                    <th data-options="field:'comentari',width:400,align:'left',editor:{type:'textarea',options:{required:false}}">Comentari</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_ai_toolbar" style="height:auto">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="$('#dlg_ai').dialog('close');">Tancar</a>
    </div>

    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <style type="text/css">  
        .alumnes{  
            /*background:#fafafa;*/
        }  
        .alumnes ul{  
            list-style:none;  
            margin:0;  
            padding:0px;  
        }  
        .alumnes li{  
            display:inline;  
            float:left;  
			width:138px;
			height:165px;
            margin:1px;  
			border:1px dashed #ccc;
			padding-left:1px;
			padding-top:1px;
			overflow: hidden;
        }  
        .item{  
            display:block;
			float:left; 
            text-decoration:none;
			color: #777;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow: hidden;
        } 
		.itemfalta{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#a70e11;
			color: #eee;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        } 
		.itemretard{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#ffcb00;
			color: #222;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        } 
		.itemincidencia{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#6eaff2;
			color: #222;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        }
		.itemccc{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#702c6a;
			color: #eee;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        }
		.itemmultiple{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#fa6f13;
			color: #fff;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        }
		.itemjustificada{  
            display:block;
			float:left; 
            text-decoration:none;
			background-color:#a1d88b;
			color: #009a49;
			margin:1px;
			height: auto;
			width:auto;
			height:auto;
			overflow:auto;
        }
        .item img{  
             
        }  
        .item p{  
            margin:0;
            text-align:left;  
            color: #777;  
			margin:1px;
        }
		.clear {
			clear:both;
			height:1px;
			overflow:hidden;
		}
    </style>  
    
    <script type="text/javascript">
	var url;
	var idalum;
	var idgrups;
	var idmateria;
	var classe_actual = <?= $classe_actual ?>;
	
	$(function(){  
            $('#dg_ai').datagrid({  
				rowStyler:function(index,row){
				    if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_ABSENCIA?>){
						return 'background-color:whitesmoke;color:#be0f34;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_RETARD?>){
						return 'background-color:whitesmoke;color:#ada410;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_SEGUIMENT?>){
						return 'background-color:whitesmoke;color:#002596;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_JUSTIFICADA?>){
						return 'background-color:#a1d88b;color:#009a49;font-weight:bold;';
					}
				}  
            });  
    });
	
	if (classe_actual) {
		/*url = './assist/assist_nou_log_enclasse.php';
		$.post(url,{},function(result){ 
		if (result.success){ }},'json');*/
		
		$.messager.confirm('Confirmar','Ets a classe?',function(r){  
			if (r){
				url = './assist/assist_nou_log_enclasse.php';
				$.post(url,{},function(result){ 
				if (result.success){
					//top.location.reload();
				}
				},'json');
			}
			else {
				url = './assist/assist_nou_log_no_enclasse.php';
				$.post(url,{},function(result){ 
				if (result.success){  
				}
				},'json');
			}
		});
	}
		
	$('#id_motius').combogrid({
			url:'./ccc_motius/ccc_motius_getdata.php',
			valueField:'idccc_motius',
			textField:'nom_motiu'
	});
	
	function gestioAI(idalumnes,idfranges_horaries){  
			//url = './assist_dant/altres_inc_getdata.php';
			var data = '<?=date("Y-m-d")?>';
			$('#dlg_ai').dialog('open').dialog('setTitle','Seguiment');
			$('#dg_ai').datagrid('load',{ 
				idalumnes:idalumnes,
				data: data,
				idfranges_horaries:idfranges_horaries
     		}); 
    }
			
	function addFalta(clicked_id)
	{
		idalum = clicked_id;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		var alum   = '#al'+idalum;
		
		url = './assist/assist_nou_absencia.php';
		$.post(url,{id:idalum,idgrups:idgrups,idmateria:idmateria,idfranges_horaries:idfranges_horaries},function(result){  
            if (result.success){
				if (result.multiple > 1){
					$(alum).css('background-color', '#fa6f13');
					$(alum).css('color', '#fff');
				}
				else {
					$(alum).css('background-color', '#a70e11');
					$(alum).css('color', '#eee');
				}
            } else {
			   location.href = './index.php';
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json'); 
	}
	
	function addRetard(clicked_id)
	{
		idalum = clicked_id;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		var alum = '#al'+idalum;
		
		url = './assist/assist_nou_retard.php';
		$.post(url,{id:idalum,idgrups:idgrups,idmateria:idmateria,idfranges_horaries:idfranges_horaries},function(result){  
            if (result.success){ 
			   if (result.multiple > 1){
					$(alum).css('background-color', '#fa6f13');
					$(alum).css('color', '#fff');
			   }
			   else {
					$(alum).css('background-color', '#ffcb00');
					$(alum).css('color', '#222');
			   }
            } else { 
			   location.href = './index.php'; 
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
	}
	
	function addIncident(clicked_id)
	{
		idgrups            = <?=$idgrups?>;
		idmateria          = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		idalum = clicked_id;
		var alum   = '#al'+idalum;
		
		url = './assist/assist_check_session.php';
		$.post(url,{},function(result){ 
            if (result.success){
            } else { 
			   location.href = './index.php'; 
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
        },'json');

		$('#dlg').dialog('open').dialog('setTitle','Seguiment');
		$('#fm').form('load','./assist/assist_getdata_inc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries);
		
		url = './assist/assist_edita_inc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries;	 
	}
	
	function saveIncident(){  
            $('#fm').form('submit',{  
                url: url, 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){ 
					var result = eval('('+result+')');
					alum = '#al'+result.id;
					
					if (result.multiple > 1){
						$(alum).css('background-color', '#fa6f13');
						$(alum).css('color', '#fff');
					}
					else {
						$(alum).css('background-color', '#6eaff2');
						$(alum).css('color', '#222');
					}
					$('#dlg').dialog('close');
                } 
            });  
    }
	
	function cancelIncident(){  
        $('#dlg').dialog('close');
		var alum   = '#al'+idalum;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		
		/*$(alum).css('background-color', 'whitesmoke');
		$(alum).css('color', '#777');*/
    }
	
	function addCCC(clicked_id)
	{
		idgrups            = <?=$idgrups?>;
		idmateria          = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		idespais_centre    = <?=$idespais_centre?>;
		
		idalum = clicked_id;
		var alum   = '#al'+idalum;
		
		url = './assist/assist_check_session.php';
		$.post(url,{},function(result){ 
            if (result.success){  
            } else { 
			   location.href = './index.php'; 
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
        },'json');
		
		$(alum).css('background-color', '#702c6a');
		$(alum).css('color', '#eee');
		$('#dlg_ccc').dialog('open').dialog('setTitle','CCC');
		$('#fm_ccc').form('load','./assist/assist_getdata_ccc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries+'&idespais_centre='+idespais_centre);
		
		url = './assist/assist_edita_ccc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries+'&idespais_centre='+idespais_centre;	 
	}
	
	function saveCCC(){  
            $('#fm_ccc').form('submit',{  
                url: url, 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){ 
					var result = eval('('+result+')');
                    if (result.msg){
                        $.messager.show({  
                            title: 'Error',  
                            msg: result.msg  
                        });  
                    } else {  
                        $('#dlg_ccc').dialog('close'); 
                    }  
                } 
            });  
    }
	
	function cancelCCC(){  
        $('#dlg_ccc').dialog('close');
		var alum   = '#al'+idalum;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		idespais_centre    = <?=$idespais_centre?>;
		/*$(alum).css('background-color', 'whitesmoke');
		$(alum).css('color', '#777');*/
    }
	
	function cancelar(clicked_id)
	{
		idalum = clicked_id;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		var alum   = '#al'+idalum;
		
		$.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta entrada?',function(r){  
        	$(alum).css('background-color', '#fff');
			$(alum).css('color', '#777');
        	$.post('./assist/assist_esborra.php',{id:idalum,idgrups:idgrups,idmateria:idmateria,idfranges_horaries:idfranges_horaries},function(result){  
                   if (result.success){  
                   } else {  
                       $.messager.show({     
                         title: 'Error',  
                         msg: result.msg  
                       });  
                   }  
        	},'json');  
                     
        });  
	
	}
	
	function informeAssistencia(idgrups,idmateria,nomgrup){
		url = './assist/assist_see.php?idgrups='+idgrups+'&idmateria='+idmateria;
		$('#dlg_inf').dialog('open').dialog('setTitle','Assist&egrave;ncia del grup '+nomgrup);
		$('#dlg_inf').dialog('refresh', url);
    }
	
	function addSeguiment(idgrups,idmateria,idfranges_horaries){
				
		url = './assist/assist_check_session.php';
		$.post(url,{},function(result){ 
            if (result.success){  
            } else { 
			   location.href = './index.php'; 
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
        },'json');
		
		$('#dlg_seg').dialog('open').dialog('setTitle','Seguiment classe');
		
		url = './assist/assist_getdata_seg.php?idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries;
		
		$('#fm_seg').form('load','./assist/assist_getdata_seg.php?idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries);
		
		url = './assist/assist_edita_seg.php';	
    }
	
	function saveSeguiment(){		
			$('#fm_seg').form('submit',{  
                url: url, 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){
					var result = eval('('+result+')');
                    if (result.msg){
                        $.messager.show({  
                            title: 'Error',  
                            msg: result.msg  
                        });  
                    } else {  
                        $('#dlg_seg').dialog('close'); 
                    }  
                } 
            });  
    }
	
	function imprimirPDF(idgrups,idmateria){  
		d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');
		percent  = $('#percentatge').val();
		
		url = './assist/assist_print.php?idgrups='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;
		
		$('#fitxer_pdf').attr('src', url);		
    }
	
	function imprimirWord(idgrups,idmateria){  
		d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');
		percent  = $('#percentatge').val();
		
		url = './assist/assist_print_word.php?idgrups='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;
		
		$('#fitxer_pdf').attr('src', url);		
    }
	
	function imprimirExcel(idgrups,idmateria){  
		d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');
		percent  = $('#percentatge').val();
		
		url = './assist/assist_print_excel.php?idgrups='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;
		
		$('#fitxer_pdf').attr('src', url);		
    }
	
	function doReload(idgrups,idmateria){
	    d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');
		percent  = $('#percentatge').val();
		
	    url = './assist/assist_see.php?idgrups='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;
		
		$('#dlg_inf').dialog('refresh', url);
	}
	
	function IndicadorsGrupMateria(idgrups,idmateria){
	    d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		
		$('#dlg_ind').dialog('open').dialog('setTitle','Indicadors');
	    url = './assist/assist_ind.php?idgrup='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi;
		$('#dlg_ind').dialog('refresh', url);
	}
	
	function IndicadorsAlumneGrupMateria(idgrups,idmateria){
	    d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');
		
		$('#dlg_ind').dialog('open').dialog('setTitle','Indicadors');
	    url = './assist/assist_ind_alum.php?idgrup='+idgrups+'&idmateria='+idmateria+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne;
		
		$('#dlg_ind').dialog('refresh', url);
	}
	
	function enviarSMS(idgrups){  
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
	}
	
	function tancar(grup,materia,professor,idfranges_horaries) {
		    javascript:$('#dlg_sms').dialog('close');
			open1('./assist/assist_grid.php?idgrups='+grup+'&idmateria='+materia+'&idprofessors='+professor+'&idfranges_horaries='+idfranges_horaries+'&act=0',this);
			//$('#dg').datagrid('reload');
	}
				
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

	</script>