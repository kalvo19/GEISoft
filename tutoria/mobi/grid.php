<?php
	session_start();
	/* if (isset($_SESSION['materia_classe_actual'])) {
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
	else */
	if (isset($_REQUEST['idmateria'])) {
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
		exit();
	}
	
	mysql_query("SET NAMES 'utf8'");
	$nom_grup = getGrup($idgrups)->nom;
	$curs_escolar         = $_SESSION['curs_escolar'];
	$curs_escolar_literal = $_SESSION['curs_escolar_literal'];
		
	$fechaSegundos = time();
    $strNoCache = "?nocache=$fechaSegundos";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tutoria|Asistencia|Faltas</title>
    <meta name="Description" content="Gestión de faltas de assistencia">
    <meta name="Keywords" content="Tutoria,assitencia,aplicatiu,aplicatiu de tutoria,gestion faltas de asistencia,gestion horarios,gestion guardias,asistencia alumnos">
    <meta name="robots" content="index, follow" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/icons/favicon.ico">  
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/easyui.css">  
    <link rel="stylesheet" type="text/css" href="../css/icon.css">  
    <link rel="stylesheet" type="text/css" href="../css/demo.css"> 
    <script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script> 
    
    <script type="text/javascript">  
		var registre_entrada = 'NOT_INIT';
		
		function open1(url,a){
			currPageItem = $(a).text();
			$('body>div.menu-top').menu('destroy');
			$('body>div.window>div.window-body').window('destroy');
			$('#content').panel('refresh',url);
		}
		
		function registreEntrada(op,idprofessors) {
			var url   = '';
			var texte = '';
			var operacio;
			
			if (registre_entrada == 'NOT_INIT') {
				operacio = op;
			}
			else {
				operacio = registre_entrada;
			}


			if (operacio) {
				url              = '../ctrl_prof/ctrl_prof_reg_in.php';
				texte            = 'Registre Sortida';
				txt_confirm      = 'Registrem l\'entrada?';
				icono			 = 'icon-undo';
				registre_entrada = 0;
			}
			else {
				url              = '../ctrl_prof/ctrl_prof_reg_out.php';
				texte            = 'Registre Entrada';
				txt_confirm      = 'Registrem la sortida?';
				icono			 ='icon-redo';
				registre_entrada = 1;
			}
			$.messager.confirm('Confirmar',txt_confirm,function(r){  
                    if (r){  
                        $.post(url,{idprofessors:idprofessors},function(result){  
                            if (result.success){  
                                $('#registre').linkbutton({				
									text: texte,
									iconCls: icono,
									plain: true,
								});
                            } else { 
							    $.messager.alert('Error','Registre d\'entrada erroni!','error');
                            }  
                        },'json'); 
                    }  
            });
		}
		
		function sortir(idprofessors) {
			var url = '../ctrl_prof/ctrl_prof_reg_out_home.php';
			
			if (idprofessors == 0) {
				location.href = '../logout.php'; 
			}
			else {
				$.messager.confirm('Confirmar','VOLS SORTIR DEL SISTEMA?',function(r){  
				if (r){
					location.href = '../logout.php';
				}
				});

			}
       }
	</script>
</head>

<body class="easyui-layout">
    <div data-options="region:'north',border:true" style="overflow:hidden">
        <table width="100%" height="45" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="155"><a href="home.php"><img src="../images/left_ribbon.png" border="0"></a></td>
            <td valign="top" width="60">
                <?php
				$img_prof_path = '../images/prof/'.$idprofessors.'.jpg';
                if (file_exists($img_prof_path)) {
                	echo "<img src='".$img_prof_path."' height='45'>";
				}
				?>
            </td>
            
            <td valign="top">
                <?php 
				if (isset($_SESSION['professor'])) {
					echo "Hola&nbsp;<strong>".getProfessor($idprofessors,TIPUS_nom_complet)."</strong>";
				}
				else if (isset($_SESSION['alumne'])){
					echo "Hola&nbsp;<strong>".getAlumne($idalumnes,TIPUS_nom_complet)."</strong>";
				}
				?>
                
                <br>
                Curs&nbsp;<strong><?= $curs_escolar_literal ?></strong>
            </td>
            <td valign="top">
            	<?php 
				if (isset($_SESSION['professor'])) {
            		$hora_entra_centre = strtotime(getLastLogProfessor($idprofessors,date("y-m-d"),TIPUS_ACCIO_ENTROALCENTRE));
				    $hora_surt_centre  = strtotime(getLastLogProfessor($idprofessors,date("y-m-d"),TIPUS_ACCIO_SURTODELCENTRE));
					
					if ($hora_entra_centre > $hora_surt_centre) {
						//echo "<a id='registre' href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-undo' plain='true' onClick='registreEntrada(0,".$idprofessors.")'>Registre Sortida</a>";
					}
					else {
						//echo "<a id='registre' href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-redo' plain='true' onClick='registreEntrada(1,".$idprofessors.")'>Registre Entrada</a>";
					}
				}
				?>
            <td valign="top" align="right">
               <a href="javascript:void(0)" title="Sortir del sistema" onClick="sortir(<?=$idprofessors?>)" class="easyui-tooltip">
               <img src="../images/logout.png" height="25" border="0"></a>
            </td>
          </tr>
        </table>
    </div>
    
    <div data-options="region:'south',border:false" style="overflow:hidden">
        <div class="panel-header" style="padding:0 0 0 5px;border-width:1px 0;">
            <span class="panel-title" style="line-height:30px">
            	<strong><?=getDiaSetmana(date('w'))?>,&nbsp;<?=date('d')?></strong>
                &nbsp;de&nbsp;<strong><?=getMes(date('m'))?>&nbsp;</strong>del&nbsp;<strong><?=date('Y')?>.</strong>
                <strong><?= date("H:i")." h"?>.&nbsp;</strong>
            </span>
            <div style="clear:both"></div>
        </div>
    </div>
    
    <div data-options="region:'center',border:false"> 
            <h2>Franja hor&agrave;ria <?=getLiteralFranjaHoraria($idfranges_horaries)?><br />
            Alumnes de <?=substr(getMateria($idmateria)->nom_materia,0,40)?> de <?=$nom_grup?></h2>
        <ul style="margin-top:-15px;">  
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
				 echo substr(getAlumne($row['idalumnes'],TIPUS_cognom1_alumne)." ".getAlumne($row['idalumnes'],TIPUS_cognom2_alumne),0,17)."<br>";
				 echo substr(getAlumne($row['idalumnes'],TIPUS_nom_alumne),0,17);
				 echo "<table width=100% cellspacing=0 cellpadding=0 border=0><tr>";
				 
				 if (isset($_REQUEST['idmateria'])) {
				 	$img_alumne = "../images/alumnes/".$row['idalumnes'].".jpg";
				 }
				 else {
				 	$img_alumne = "../images/alumnes/".$row['idalumnes'].".jpg";
				 }
				 
				 if (file_exists($img_alumne)) {
				   echo "<td width=51><a id='".$row['idalumnes']."' href='javascript:void(0)' data-options='plain:true,toggle:true' onclick='gestioAI(this.id,".$idfranges_horaries.")'><img src='../images/alumnes/".$row['idalumnes'].".jpg".$strNoCache."' width='51' height='70' align='absbottom'></a></td>";
				 }
				 else {
				   echo "<td width=51><a id='".$row['idalumnes']."' href='javascript:void(0)' data-options='plain:true,toggle:true' onclick='gestioAI(this.id,".$idfranges_horaries.")'><img src='../images/alumnes/alumne.png' width='51' height='70'></a></td>";
				 }
				 
				 echo "<td valign='top'>";
				 if ($id_tipus_incidencia==TIPUS_FALTA_ALUMNE_JUSTIFICADA) {
				 	echo substr($comentari,0,60);
				 }
				 else {
				 	 $rsTipusFaltes = getTipusFaltaAlumne();
					 while ($rowf = mysql_fetch_assoc($rsTipusFaltes)) {
					   if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_ABSENCIA) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addFalta(this.id)'><span style='font-size:16px'>".substr($rowf['tipus_falta'],0,3)."</span></a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_RETARD) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addRetard(this.id)'><span style='font-size:16px'>".substr($rowf['tipus_falta'],0,3)."</span></a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_SEGUIMENT) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addIncident(this.id)'><span style='font-size:16px'>".substr($rowf['tipus_falta'],0,3)."</span></a><br>";
					   }
					   else if ($rowf['idtipus_falta_alumne']==TIPUS_FALTA_ALUMNE_CCC) {
						 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='addCCC(this.id)'>".substr($rowf['tipus_falta'],0,3)."</a><br>";
					   }
					   
					 }
					 echo "<a id='".$row['idalumnes']."' href='javascript:void(0)' class='easyui-linkbutton' data-options='plain:true,toggle:true' onclick='Cancelar(this.id)'><span style='font-size:16px'>Can</span></a>";
				 }
				 echo "</td></tr></table>";
                 echo "</li></div>";
			   }
			
			?>   
        </ul> 
        <div class="clear"></div> 
        <br /><br /><br /><br />
        <br /><br /><br /><br />
    </div>  

<div id="dlg" class="easyui-dialog" style="width:270px;height:300px;padding:1px 2px"  
            closed="true" maximized="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg-buttons">  
        <form id="fm" method="post" novalidate>             	
            <div class="fitem">  
                <label>Tipus d'incidència</label>
                <select id="id_tipus_incident" name="id_tipus_incident" class="easyui-combobox" data-options="
					width:250,
                    url:'../incidents_tipus/incidents_tipus_getdata.php',
					idField:'idtipus_incident',
                    valueField:'idtipus_incident',
					textField:'tipus_incident',
					panelHeight:'auto'
                ">
                </select>
                <br />
                <label>Descripció de la incidència</label><br />  
                <textarea name="comentari" style="height:200px; width:95%;"></textarea>
            </div>
        </form>  
    </div>  
    <div id="dlg-buttons">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveIncident()">Guardar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" onClick="cancelIncident()">Tancar</a>
    </div>
</div>

<div id="dlg_ccc" class="easyui-dialog" style="width:auto;height:auto;padding:1px 1px"  
            closed="true" collapsible="true" maximized="true" resizable="true" maximizable="true" toolbar="#dlg-buttons_ccc">  
        <form id="fm_ccc" method="post" novalidate>
            <input type="hidden" name="id_tipus_sancio" value="0" />
            <input type="hidden" name="data_inici_sancio" value="" />
            <input type="hidden" name="data_fi_sancio" value="" />            	
            <div class="fitem">  
            	<label style="font-size:16px; font-weight:bolder">Implica expulsi&oacute; de classe?</label>
            	<input id="expulsio" name="expulsio" type="checkbox" value="S">
                <label style="color:#666666">(marcar aquesta opci&oacute; si es treu l'alumne de classe)</label>
            	<br />
                
                <label>Tipus d'incidència</label>
                <select id="id_falta" name="id_falta" class="easyui-combobox" data-options="
                    required:true,
                    width:120,
                    url:'../ccc_tipus/ccc_tipus_getdata.php',
					idField:'idccc_tipus',
                    valueField:'idccc_tipus',
					textField:'nom_falta',
					panelHeight:'auto'
                ">
                </select>
                <br />
                <label>Descripció breu</label><br />
                <select id="id_motius" name="id_motius" class="easyui-combobox" data-options="
                    required:false,
                    width:220,
                    url:'../ccc_motius/ccc_motius_getdata.php',
                    valueField:'idccc_motius',
					textField:'nom_motiu',
					panelHeight:'auto'
                ">
                </select>
                <br />
                <label>Fets que s'han produït</label>  
                <textarea name="descripcio_detallada" style="height:130px;width:98%"></textarea>
            </div>
        </form>  
    </div>  
    <div id="dlg-buttons_ccc">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onClick="saveCCC()">Guardar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" onClick="cancelCCC()">Tancar</a>
    </div>
</div>

    <div id="dlg_ai" class="easyui-dialog" style="width:175px;height:25px;padding:5px 5px" closed="true" collapsible="true" maximized="true" resizable="true" maximizable="true">
        <table id="dg_ai" class="easyui-datagrid" title="" style="width:auto;height:auto"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'../assist_dant/altres_inc_getdata.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_ai_toolbar'
                    
                ">
            <thead>
                <tr>
                    <th sortable="true" data-options="field:'id_tipus_incidencia',
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
                    <th sortable="true" align="left" data-options="field:'id_tipus_incident',
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
						}">Tipus incident</th>
                    <th data-options="field:'comentari',align:'left',editor:{type:'textarea',options:{required:false}}">Comentari</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_ai_toolbar" style="height:auto">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="$('#dlg_ai').dialog('close');">Tancar</a>
    </div>
    
    <style type="text/css">  
	    .products{  
            /*background:#fafafa;  */
        }  
        ul{  
            list-style:none;  
            margin:0;  
            padding:0px;
			font:13px normal Geneva, Arial, Helvetica, sans-serif;
        }  
        li{  
            display:inline;  
            float:left;  
			width:113px;
			height:175px;
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
		
		$.messager.confirm('Confirmar','Ets a classe?',function(r){  
			if (r){
				url = '../assist/assist_nou_log_enclasse.php';
				$.post(url,{},function(result){ 
				if (result.success){
					//top.location.reload();
				}
				},'json');
			}
			else {
				url = '../assist/assist_nou_log_no_enclasse.php';
				$.post(url,{},function(result){ 
				if (result.success){  
				}
				},'json');
			}
		});
	}
	
	function gestioAI(idalumnes,idfranges_horaries){  
			var data = '<?=date("Y-m-d")?>';
			$('#dlg_ai').dialog('open').dialog('setTitle','Incid&egrave;ncies');
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
		$(alum).css('background-color', '#a70e11');
		$(alum).css('color', '#eee');
		url = '../assist/assist_nou_absencia.php';
		
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
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
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
		$(alum).css('background-color', '#ffcb00');
		$(alum).css('color', '#222');
		url = '../assist/assist_nou_retard.php';
		
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
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
	}
	
	function addIncident(clicked_id)
	{
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		idalum = clicked_id;
		var alum   = '#al'+idalum;
		
		$('#dlg').dialog('open').dialog('setTitle','Incidència');
		$('#fm').form('load','../assist/assist_getdata_inc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries);
		
		url = '../assist/assist_edita_inc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries;	 
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
					$.messager.alert('Incidents','Incid&egrave;ncia registrada correctament!','info');
					//$('#dlg').dialog('close');
                }  
            });  
    }
	
	function cancelIncident(){  
        $('#dlg').dialog('close');
		var alum   = '#al'+idalum;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		
		/*$(alum).css('background-color', '#fff');
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
		
		url = '../assist/assist_check_session.php';
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
		$('#fm_ccc').form('load','../assist/assist_getdata_ccc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries+'&idespais_centre='+idespais_centre);
		
		url = '../assist/assist_edita_ccc.php?id='+clicked_id+'&idgrups='+idgrups+'&idmateria='+idmateria+'&idfranges_horaries='+idfranges_horaries+'&idespais_centre='+idespais_centre;	 
	}
	
	function saveCCC(){  
            $('#fm_ccc').form('submit',{  
                url: url, 
                onSubmit: function(){  
                    return $(this).form('validate');  
                },  
                success: function(result){ 
					$.messager.alert('CCC','CCC registrada correctament!','info');
					//$('#dlg_ccc').dialog('close');
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
	
	function Cancelar(clicked_id)
	{
		idalum = clicked_id;
		idgrups = <?=$idgrups?>;
		idmateria = <?=$idmateria?>;
		idfranges_horaries = <?=$idfranges_horaries?>;
		var alum   = '#al'+idalum;
		
		$.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta entrada?',function(r){  
        	$(alum).css('background-color', '#FFF');
			$(alum).css('color', '#777');
        	$.post('../assist/assist_esborra.php',{id:idalum,idgrups:idgrups,idmateria:idmateria,idfranges_horaries:idfranges_horaries},function(result){  
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
			
	function doReload(idgrups,nomgrup){
	    d_inici  = $('#data_inici').datebox('getValue');
		d_fi     = $('#data_fi').datebox('getValue');
		c_alumne = $('#c_alumne').combobox('getValue');

	    url = '../assist/assist_see.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne;
		$('#dlg_inf').dialog('refresh', url);
	}
		
	</script>

</body>
</html>

<?php
mysql_free_result($rsAlumnes);
mysql_free_result($rsTipusFaltes);
mysql_close();
?>