<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Assist&egrave;ncia altres dies"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
                fixed: true,
				url: './assist_dant/assist_dant_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th field="alumne" width="270" sortable="true">Alumne</th>
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
								required:true,
                                onSelect: function(){
                                	endEditing();
                            	}
							}
                            
						}">Tipus falta</th>
                <th sortable="true" align="left" data-options="field:'id_tipus_incident',width:200,
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
                <th data-options="field:'comentari',width:450,align:'left',editor:{type:'textarea',options:{required:false}}">Comentari</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Dia&nbsp;<input id="data" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        &nbsp;<br />
        Classes&nbsp;<br />
        <select id="classes" class="easyui-combogrid" style="width:660px" data-options="
            panelWidth: 660,
            idField: 'idagrups_materies',
            textField: 'materia',
            url: url,
            method: 'get',
            columns: [[
                {field:'hora',title:'Hora',width:90},
                {field:'grup',title:'Grup',width:120},
                {field:'materia',title:'Materia',width:370},
                {field:'espaicentre',title:'Espai',width:70}
            ]],
            fitColumns: true
        ">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" />
        <a id="gestio_ai" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true,disabled:true" onclick="gestioAI()">Altres incid&egrave;ncies</a>
        &nbsp;&nbsp;
        <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar canvis</a>
        <a id="can_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
        <a id="del_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar entrada</a>
        &nbsp;&nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true,disabled:true" onclick="verHorario()">Veure horari grup</a>&nbsp;
        <a id="assist_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-print',plain:true,disabled:true" onclick="informeAssistencia()">Informe Assist&egrave;ncia</a>
        &nbsp;
        <a id="absencies_dates" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" disabled="true" onclick="absenciesPeriode()">Abs&egrave;ncies entre dates</a>
       
        <?php
        	if (getDadesCentre()->prof_env_sms) {
		?>
        <a id="sms_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,disabled:true" onclick="enviarSMS()">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>
        <?php
			}
		?>
        
    </div>
    </div>
    
    
    <div id="dlg_hor" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
    </div>
    
    <div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">  
	</div>
    
	<div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <form method="post">
                Desde:<input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a:<input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
                Per alumne: 
                <select id="c_alumne" class="easyui-combobox" name="c_alumne" style="width:400px;" data-options="valueField:'idalumnes',textField:'Valor'">
                </select>
                <br />
                Percentatge&nbsp;<input id="percentatge" class="easyui-numberbox" value="20" size="3" data-options="precision:0,required:true,min:0,max:100">&nbsp;%&nbsp;
                <a href="#" onclick="imprimirPDF()"><img src="./images/icons/icon_pdf.png" height="32"/></a>
                <a href="#" onclick="imprimirWord()"><img src="./images/icons/icon_word.png" height="32"/></a>
                <a href="#" onclick="imprimirExcel()"><img src="./images/icons/icon_excel.png" height="32"/></a>
                </form>
    	        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload()">Recarregar</a>
                
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
	</div>

	<div id="dlg_sms" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
	</div>
        
	<div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar()">Tancar</a>  
	</div>
    
    <div id="dlg_ai" class="easyui-dialog" style="width:875px;height:400px;padding:5px 5px" modal="true" closed="true">
        <table id="dg_ai" class="easyui-datagrid" title="Incid&egrave;ncies registrades" style="width:850px;height:355px"
                data-options="
                    iconCls: 'icon-edit',
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
                <th sortable="true" align="left" data-options="field:'id_tipus_incident',width:250,
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
                <th data-options="field:'comentari',width:450,align:'left',editor:{type:'textarea',options:{required:false}}">Comentari</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_ai_toolbar" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append_ai()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem_ai()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept_ai()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="tancar_ai()">Tancar</a>
    </div>
    
    <div id="dlg_ind" class="easyui-dialog" style="width:800px;height:500px;"  
            closed="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_ind-toolbar">
    </div>

	 <div id="dlg_ind-toolbar" style="height:auto">
        <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirInforme(<?=$idgrups?>)">Imprimir</a>  
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_ind').dialog('close')">Tancar</a>
    </div>
    
    <div id="dlg_abs_periode" class="easyui-dialog" style="width:700px;height:320px; padding-left:5px; padding-top:10px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_abs_periode-buttons">
            
            <form id="fm_abs_periode" method="post" novalidate>
            <div class="fitem">
            Alumne
            <select id="idalumne_abs" class="easyui-combobox" name="idalumne_abs" style="width:400px;" data-options="valueField:'idalumnes',textField:'Valor'">
            </select>
            </div>
            <br />
            <div class="fitem">    
            Desde&nbsp;&nbsp;<input id="data_abs_desde" name="data_abs_desde" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true"></input>&nbsp;
            fins a&nbsp;&nbsp;<input id="data_abs_finsa" name="data_abs_finsa" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true"></input>&nbsp;
            </div>
            <br />
            <div class="fitem">    
            Comentari<br />
            <textarea name="comentari" style="height:100px; width:650px;"></textarea>
            </div>
            </form>
            
    </div>
        
    <div id="dlg_abs_periode-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveAbsenciesPeriode()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" onclick="javascript:$('#dlg_abs_periode').dialog('close')">Tancar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>

    <script type="text/javascript">  
        var url;
		var editIndex       = undefined;
		var nou_registre    = 0;
		var nou_registre_ai = 0;
		var editIndex_ai    = undefined;
		var idgrups;
		var nom_grup;
		
		$('#classes').combogrid({
			url: 'nodata.php',
		});
		
		$('#classes').combogrid({
			onSelect: function(date){
				$('#c_alumne').combo({
					url:'./tutor/tutor_alum_getdata.php?idgrups=27',
					valueField:'idalumnes',
					textField:'Valor'
				});
				
				$('#gestio_ai').linkbutton('enable');
				$('#horari_button').linkbutton('enable');
				$('#assist_button').linkbutton('enable');
				$('#absencies_dates').linkbutton('enable');
				$('#sms_button').linkbutton('enable');
			}
		});
		
		$('#data').datebox({
			onSelect: function(date){
				var theDate = new Date(date);
				var theDay  = theDate.getDay();
				url = './assist_dant/classes_getdata.php?dia='+theDay;
				
				$('#classes').combogrid({
					url: url,
				});
				
			}
		});
		
		$(function(){  
            $('#dg').datagrid({  
				view: detailview,  
				detailFormatter:function(index,row){
					return '<div class="ddv" style="padding:5px 0"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
						border:false,
						cache:false,
						href:'./assist_dant/assist_dant_getdetail.php?id='+row.idalumnes,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				},
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
		
        function doSearch(){ 		   
			var g = $('#classes').combogrid('grid');	
			var r = g.datagrid('getSelected');
			
			$('#gestio_ai').linkbutton('enable');
			$('#add_button').linkbutton('enable');
			$('#can_button').linkbutton('enable');
			$('#del_button').linkbutton('enable');	
			
			if (r){ 
				$('#dg').datagrid('load',{  
					data               : $('#data').datebox('getValue'),
					grup_materia       : $('#classes').combogrid('getValue'),
					idfranges_horaries : r.idfranges_horaries
				});
				editIndex = undefined;
			}
		} 
		
		function gestioAI(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){
				$('#dlg_ai').dialog('open').dialog('setTitle','Incid&egrave;ncies');
				$('#dg_ai').datagrid('load',{ 
					idalumnes:row.idalumnes,
					data: row.data,
					idfranges_horaries:row.idfranges_horaries
     			});
            }  
        }
		
		function onClickRow(index){
			var row = $('#dg').datagrid('getSelected'); 
			
			//if (row.id_tipus_incidencia!=<?=TIPUS_FALTA_ALUMNE_JUSTIFICADA?>){
				if (editIndex != index){
					if (endEditing()){
						$('#dg').datagrid('selectRow', index)
									.datagrid('beginEdit', index);
						editIndex = index;
					} else {
						$('dg').datagrid('selectRow', editIndex);
					}
				}
			//}
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
			    var row = $('#dg').datagrid('getSelected');
				
				var ed  = $('#dg').datagrid('getEditor', {index:editIndex,field:'id_tipus_incidencia'});
				var tipus_falta = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['tipus_falta'] = tipus_falta;
				
				var ed  = $('#dg').datagrid('getEditor', {index:editIndex,field:'id_tipus_incident'});
				var tipus_incident = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['tipus_incident'] = tipus_incident;
				
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './assist_dant/assist_dant_nou.php';
					nou_registre = 0;
				}
				else {
					url = './assist_dant/assist_dant_edita.php';
				}
				afterEdit(url,
					$('#dg').datagrid('getRows')[editIndex]['idalumnes_grup_materia'],
					$('#dg').datagrid('getRows')[editIndex]['id_tipus_incidencia'],
					$('#dg').datagrid('getRows')[editIndex]['id_tipus_incident'],
					$('#dg').datagrid('getRows')[editIndex]['idprofessors'],
					$('#dg').datagrid('getRows')[editIndex]['data'],
					$('#dg').datagrid('getRows')[editIndex]['idfranges_horaries'],
					$('#dg').datagrid('getRows')[editIndex]['comentari']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function endEditing_ai(){
			if (editIndex_ai == undefined){return true}			
			if ($('#dg_ai').datagrid('validateRow', editIndex_ai)){
			    var row_p = $('#dg').datagrid('getSelected');
				var row   = $('#dg_ai').datagrid('getSelected');
				
				var ed  = $('#dg_ai').datagrid('getEditor', {index:editIndex_ai,field:'id_tipus_incidencia'});
				var tipus_falta = $(ed.target).combobox('getText');
				$('#dg_ai').datagrid('getRows')[editIndex_ai]['tipus_falta'] = tipus_falta;
				
				var ed  = $('#dg_ai').datagrid('getEditor', {index:editIndex_ai,field:'id_tipus_incident'});
				var tipus_incident = $(ed.target).combobox('getText');
				$('#dg_ai').datagrid('getRows')[editIndex_ai]['tipus_incident'] = tipus_incident;
				
				$('#dg_ai').datagrid('endEdit', editIndex_ai);
				
				if (nou_registre_ai) { 
					url = './assist_dant/altres_inc_nou.php';
					nou_registre_ai = 0;
				}
				else {
					url = './assist_dant/altres_inc_edita.php';
				}
				afterEdit_ai(url,
					row_p.idalumnes_grup_materia,
					$('#dg_ai').datagrid('getRows')[editIndex_ai]['id_tipus_incidencia'],
					$('#dg_ai').datagrid('getRows')[editIndex_ai]['id_tipus_incident'],
					row_p.idprofessors,
					row_p.data,
					row_p.idfranges_horaries,
					$('#dg_ai').datagrid('getRows')[editIndex_ai]['comentari']);
				
				editIndex_ai = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function append(){
			if (endEditing()){
				$('#dg').datagrid('appendRow',{});
				nou_registre = 1;
				
				editIndex = $('#dg').datagrid('getRows').length-1;
				$('#dg_mat').datagrid('selectRow', editIndex)
						    .datagrid('beginEdit', editIndex);
			}	
		}
		
		function append_ai(){
			if (endEditing_ai()){
				$('#dg_ai').datagrid('appendRow',{});
				nou_registre_ai = 1;
				
				editIndex_ai = $('#dg_ai').datagrid('getRows').length-1;
				$('#dg_ai').datagrid('selectRow', editIndex_ai)
						   .datagrid('beginEdit', editIndex_ai);
			}
			
		}
		
		function accept(){			
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				var row = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './assist_dant/assist_dant_nou.php';
					nou_registre = 0;
				}
				else {
					url = './assist_dant/assist_dant_edita.php';
				}

				saveItem(url,row);
			}
		}
		
		function accept_ai(){
			if (endEditing_ai()){
				$('#dg_ai').datagrid('acceptChanges');
				var row_a = $('#dg_ai').datagrid('getSelected');
				var row_p = $('#dg').datagrid('getSelected');
										
				if (nou_registre_ai) { 
					url = './assist_dant/altres_inc_nou.php';
					nou_registre_ai = 0;
				}
				else {
					url = './assist_dant/altres_inc_edita.php?id='+row_a.idincidencia_alumne;
				} 
				saveItem_ai(url,row_a,row_p);
			}
		}
		
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta entrada?',function(r){  
                    if (r){  
                        $.post('./assist_dant/assist_dant_esborra.php',{
														idalumnes_grup_materia:row.idalumnes_grup_materia,
														id_tipus_incidencia:row.id_tipus_incidencia,
														idprofessors:row.idprofessors,
														data:row.data,
														idfranges_horaries:row.idfranges_horaries,
														comentari:row.comentari},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');
								editIndex = undefined; 
                            } else {  
                                $.messager.show({ 
                                    title: 'Error',  
                                    msg: result.errorMsg  
                                });  
                            }  
                        },'json');  
                    }  
                });  
            }  
        }
		
		function destroyItem_ai(){  
            var row_p = $('#dg').datagrid('getSelected');
			var row_a = $('#dg_ai').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta entrada?',function(r){  
                    if (r){  
                        $.post('./assist_dant/altres_inc_esborra.php',{
														idalumnes_grup_materia:row_p.idalumnes_grup_materia,
														id_tipus_incidencia:row_a.id_tipus_incidencia,
														idprofessors:row_p.idprofessors,
														data:row_p.data,
														idfranges_horaries:row_p.idfranges_horaries,
														comentari:row_a.comentari},function(result){  
                            if (result.success){  
                                $('#dg_ai').datagrid('reload');
								editIndex_ai = undefined; 
                            } else {  
                                $.messager.show({ 
                                    title: 'Error',  
                                    msg: result.errorMsg  
                                });  
                            }  
                        },'json');  
                    }  
                });  
            }  
        }
		
		function saveItem(url,row){ 			
	
			$.post(url,{idalumnes_grup_materia:row.idalumnes_grup_materia,
						id_tipus_incidencia:row.id_tipus_incidencia,id_tipus_incident:row.id_tipus_incident,idprofessors:row.idprofessors,
						data:row.data,idfranges_horaries:row.idfranges_horaries,comentari:row.comentari},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');
			   $('#dg').datagrid('reload'); 
			   editIndex = undefined;
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');  
        }
		
		function saveItem_ai(url,row_a,row_p){ 			
	
			$.post(url,{
				id_tipus_incidencia:row_a.id_tipus_incidencia,
				id_tipus_incident:row_a.id_tipus_incident,
				comentari:row_a.comentari,
				idprofessors:row_p.idprofessors,
				idalumnes_grup_materia:row.idalumnes_grup_materia,
				data:row_p.data,
				idfranges_horaries:row_p.idfranges_horaries},function(result){  
            if (result.success){  
               $('#dg_ai').datagrid('reload');
			   editIndex_ai = undefined;
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2,field3,field4,field5,field6,field7){
			$.post(url,{idalumnes_grup_materia:field1,
						id_tipus_incidencia:field2,id_tipus_incident:field3,idprofessors:field4,data:field5,
						idfranges_horaries:field6,comentari:field7},function(result){  
            if (result.success){  
			   //$('#dg').datagrid('reload');    
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');  
        }
		
		function afterEdit_ai(url,field1,field2,field3,field4,field5,field6,field7){
			$.post(url,{idalumnes_grup_materia:field1,
						id_tipus_incidencia:field2,id_tipus_incident:field3,idprofessors:field4,data:field5,
						idfranges_horaries:field6,comentari:field7},function(result){  
            if (result.success){  
			   //$('#dg').datagrid('reload');    
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');  
        }
		
		function verHorario(){  
            var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			url = './hor/hor_see.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function informeAssistencia(){  
		    var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;
						
			$('#c_alumne').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});
			
			/*d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			
			url = './assist/assist_see.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups;*/
			
			grup_materia = $('#classes').combobox('getValue');
			url = './assist/assist_see.php?grup_materia='+grup_materia;
			
			$('#dlg_inf').dialog('open').dialog('setTitle','Assistencia del grup '+nomgrup);
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function imprimirPDF(){  
			/*var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;*/
			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			grup_materia = $('#classes').combobox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirWord(){			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			grup_materia = $('#classes').combobox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print_word.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;			
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirExcel(){			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			grup_materia = $('#classes').combobox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print_excel.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;			
			$('#fitxer_pdf').attr('src', url);
		}
				
		function doReload(){
			/*var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;*/
			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			grup_materia = $('#classes').combobox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_see.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&percent='+percent;
			
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function enviarSMS(){
		    var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			//nomgrup = r.grup;
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
		}
		
		function tancar() {
		    javascript:$('#dlg_sms').dialog('close');
			$('#dlg_main').panel('refresh');
			//open1('./assist_dant/assist_dant_grid.php');
			//$('#dg').datagrid('reload');
		}
		
		function absenciesPeriode(){						
			var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
						
			$('#idalumne_abs').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});
			
			$('#dlg_abs_periode').dialog('open').dialog('setTitle','Abs&egrave;ncies per periode');
        }
		
		function saveAbsenciesPeriode(){ 
			var data_abs_desde = $('#data_abs_desde').datebox('getValue');
			var data_abs_finsa = $('#data_abs_finsa').datebox('getValue');
			var grup_materia   = $('#classes').combobox('getValue');
			
			var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			
			url = './assist_dant/assist_dant_absencies_periode.php?data_abs_desde='+data_abs_desde+'&data_abs_finsa='+data_abs_finsa+'&grup='+idgrups+'&grup_materia='+grup_materia;
			
			$.messager.confirm('Confirmar','Procedim?',function(r){  
                    if (r){ 
						  $('#fm_abs_periode').form('submit',{
							    url: url,
								onSubmit: function(){
									return $(fm_abs_periode).form('validate');
								},
								success:function(data){
									//$('#dg').datagrid('reload');
									$.messager.alert('Informaci&oacute;','Abs&egrave;ncies processades correctament!','info');
								}
						   });
	  				}
				});
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
		
		function tancar_ai(){
			$('#dg_ai').datagrid('rejectChanges');
			editIndex_ai = undefined;
			editIndex      = undefined;
			$('#dlg_ai').dialog('close');
		}
	</script>
        
    <style type="text/css">  
        #fm{  
            margin:0;  
            padding:10px 30px;  
        }  
        .ftitle{  
            font-size:14px;  
            font-weight:bold;  
            padding:5px 0;  
            margin-bottom:10px;  
            border-bottom:1px solid #ccc;  
        }  
        .fitem{  
            margin-bottom:5px;  
        }  
        .fitem label{  
            display:inline-block;  
            width:80px;  
        }  
    </style>