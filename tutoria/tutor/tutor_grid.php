<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $idgrups = $_REQUEST['grup'];
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:550px;">   
    <table id="dg" class="easyui-datagrid" title="Incid&egrave;ncies de <?=getGrup($idgrups)->nom?>" style="height:540px;" 
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './tutor/tutor_getdata.php?idgrups=<?=$idgrups?>',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
            	<th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'data_incidencia',width:85,align:'left',editor:{options:{formatter:myformatter,parser:myparser}}">Data</th>
                <th data-options="field:'hora',width:85,align:'center',sortable:true">Hora</th>
                <th data-options="field:'dia',width:120,align:'center',sortable:true">Dia setmana</th>
                
                <th field="alumne" width="200" sortable="true">Alumne</th>
                <!--<th data-options="field:'idprofessors',width:70,
                            formatter:function(value,row){
								var img = '<img src=\'./images/prof/'+value+'.jpg\' width=30 height=30>';
                                return img;
							}
                           ">Foto</th> -->
                <th sortable="true" align="center" data-options="field:'id_tipus_incidencia',width:120,
						formatter:function(value,row){
							return row.tipus_falta;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idtipus_falta_alumne',
                                textField:'tipus_falta',
                                url:'./tutor/tutor_tf_getdata.php',
								required:true
							}
						}">Tipus falta</th>
                        
                <th sortable="true" align="left" data-options="field:'id_tipus_incident',width:280,
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
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto"> 
    	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="accept()">Acceptar canvis / Habilitar edici&oacute;</a> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="justificarIncidencia()">Justificar falta</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="esborrarIncidencia()">Esborrar falta</a>
        <br /> 
        <a id="justifica_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="justifica()">Justificar per dies</a>&nbsp; 
        <a id="justifica_periode_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="justificaPeriode()">Justificar per periode</a>&nbsp;      
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" />
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-tip" plain="true" onclick="verHorario(<?=$idgrups?>)">Veure horari grup</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-tip" plain="true" onclick="informeAssistencia(<?=$idgrups?>,'<?=getGrup($idgrups)->nom?>')">Informe assist&egrave;ncia</a>
        &nbsp;&nbsp;
        
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="enviarSMS(<?=$idgrups?>,'<?=getGrup($idgrups)->nom?>')">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>&nbsp;
        
        <br />
        Desde: <input id="data_inici_tutor" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        fins a: <input id="data_fi_tutor" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
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
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirHorario(<?=$idgrups?>)">Imprimir</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">  
    </div>
        
    <div id="dlg_inf-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:850" border="0">  
        <tr>  
            <td colspan="2">
                <form method="post">
                Desde&nbsp;<input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a&nbsp;<input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
             </td>
        </tr>     
        <tr>
           <td> 	
                Per alumne&nbsp;<br />
                <select id="c_alumne" class="easyui-combobox" name="c_alumne" style="width:400px;" data-options="valueField:'idalumnes',textField:'Valor'">
                </select>
           </td>
           <td>
                &nbsp;Per mat&egrave;ria&nbsp;<br />
                &nbsp;<select id="c_materia" class="easyui-combobox" name="c_materia" style="width:400px;" data-options="valueField:'idmateria',textField:'materia'">
                </select>
           </td>
        </tr>
        <tr>
           <td colspan="2">
                Percentatge&nbsp;<input id="percentatge" class="easyui-numberbox" value="20" size="5" data-options="precision:0,required:true,min:0,max:100">&nbsp;%&nbsp;
                <a href="#" onclick="imprimirPDF(<?=$idgrups?>)">
                <img src="./images/icons/icon_pdf.png" height="32"/></a>
                <a href="#" onclick="imprimirWord(<?=$idgrups?>)">
                <img src="./images/icons/icon_word.png" height="32"/></a>
                <a href="#" onclick="imprimirExcel(<?=$idgrups?>)">
                <img src="./images/icons/icon_excel.png" height="32"/></a>
                </form>
 		   </td>
        </tr>
        <tr>
        	<td colspan="2">  
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload(<?=$idgrups?>)">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table> 
    </div>
    
    <div id="dlg_sms" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
    </div>
        
    <div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar(<?=$idgrups?>)">Tancar</a>  
    </div>
    
    <div id="dlg_just" class="easyui-dialog" style="width:800px;height:320px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_just-buttons">
            
            <form id="fm_just" method="post" action="./tutor/tutor_justifica_dia.php" novalidate>
            <input id="grup" name="grup" type="hidden" value="<?=$idgrups?>" />
            <div class="fitem">
            Alumne
            <input id="idalumne" name="idalumne" class="easyui-combogrid" style="width:560px" data-options="
                	required: true,
                    panelWidth: 560,
                    idField: 'idalumnes',
                    textField: 'Valor',
                    url: './tutor/alum_getdata.php?idgrups=<?=$idgrups?>',
                    method: 'get',
                    columns: [[
                        {field:'Valor',title:'Alumne',width:560}
                    ]],
                    fitColumns: true
                ">
            </div>
            <br />
            <div class="fitem">    
            Data&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="data_just" name="data_just" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true"></input>&nbsp;
            <!--</div>
            <div class="fitem" style="padding-top:10px;">-->
            &nbsp;&nbsp;
            Abs&egrave;ncies&nbsp;<input id="absencia" name="absencia" type="checkbox" checked="checked" value="1"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Retards&nbsp;<input id="retard" name="retard" type="checkbox" checked="checked" value="1"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Seguiment&nbsp;<input id="incident" name="incident" type="checkbox" checked="checked" value="1"></input>
            </div>
            <br />
            <div class="fitem">    
            Comentari<br />
            <textarea name="comentari" style="height:100px; width:650px;"></textarea>
            </div>
            </form>
            
    </div>
        
    <div id="dlg_just-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveJustificacio()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_just').dialog('close')">Cancel.lar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_just_periode" class="easyui-dialog" style="width:800px;height:320px; padding-left:5px; padding-top:10px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_just_periode-buttons">
            
            <form id="fm_just_periode" method="post" action="./tutor/tutor_justifica_periode.php" novalidate>
            <input id="grup_periode" name="grup_periode" type="hidden" value="<?=$idgrups?>" />
            <div class="fitem">
            Alumne
            <input id="idalumne_periode" name="idalumne_periode" class="easyui-combogrid" style="width:560px" data-options="
                	required: true,
                    panelWidth: 560,
                    idField: 'idalumnes',
                    textField: 'Valor',
                    url: './tutor/alum_getdata.php?idgrups=<?=$idgrups?>',
                    method: 'get',
                    columns: [[
                        {field:'Valor',title:'Alumne',width:560}
                    ]],
                    fitColumns: true
                ">
            </div>
            <br />
            <div class="fitem">    
            Desde&nbsp;&nbsp;<input id="data_just_desde" name="data_just_desde" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true"></input>&nbsp;
            fins a&nbsp;&nbsp;<input id="data_just_finsa" name="data_just_finsa" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser,required:true"></input>&nbsp;
            <!--</div>
            <div class="fitem" style="padding-top:10px;">-->
            &nbsp;&nbsp;
            Abs&egrave;ncies&nbsp;<input id="absencia_periode" name="absencia_periode" type="checkbox" checked="checked" value="1"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Retards&nbsp;<input id="retard_periode" name="retard_periode" type="checkbox" checked="checked" value="1"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            Seguiment&nbsp;<input id="incident_periode" name="incident_periode" type="checkbox" checked="checked" value="1"></input>
            </div>
            <br />
            <div class="fitem">    
            Comentari<br />
            <textarea name="comentari" style="height:100px; width:650px;"></textarea>
            </div>
            </form>
            
    </div>
        
    <div id="dlg_just_periode-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveJustificacioPeriode()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" onclick="javascript:$('#dlg_just_periode').dialog('close')">Tancar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>

    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
		
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
						href:'./tutor/tutor_getdetail.php?id='+row.idincidencia_alumne,
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
		
        function doSearch(){ 
			$('#dg').datagrid('load',{  
				data_inici_tutor: $('#data_inici_tutor').datebox('getValue'),
				data_fi_tutor   : $('#data_fi_tutor').datebox('getValue')  
			});  
			editIndex = undefined;
		} 
		
		function verHorario(idgrups){  
			url = './hor/hor_see.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function imprimirHorario(idgrups){
		    url = './hor/hor_print.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function justifica(){
            var data_just = $('#data_just').datebox('getValue');
			$('#dlg_just').dialog('open').dialog('setTitle','Justificacions per dies');
            //$('#fm_just').form('clear');
            url = './tutor/tutor_justifica_dia.php?data='+data_just+'&grup=<?=$idgrups?>';
        }
		
		function justificaPeriode(){
            var data_just_desde = $('#data_just_desde').datebox('getValue');
			var data_just_finsa = $('#data_just_finsa').datebox('getValue');
			
			$('#dlg_just_periode').dialog('open').dialog('setTitle','Justificacions per periode');
            //$('#fm_just_periode').form('clear');
            url = './tutor/tutor_justifica_periode.php?data_just_desde='+data_just_desde+'&data_just_finsa='+data_just_finsa+'&grup_periode=<?=$idgrups?>';
        }
			
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg').datagrid('selectRow', index)
							    .datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('dg').datagrid('selectRow', editIndex);
				}
			}
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
					url = './tutor/tutor_nou.php';
					nou_registre = 0;
				}
				else {
					url = './tutor/tutor_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idincidencia_alumne'];
				}
				afterEdit(url,$('#dg').datagrid('getRows')[editIndex]['id_tipus_incidencia'],
							  $('#dg').datagrid('getRows')[editIndex]['id_tipus_incident']);
				
				editIndex = undefined;
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
		
		function accept(){			
			editIndex = undefined;
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				var row = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './tutor/tutor_nou.php';
					nou_registre = 0;
				}
				else {
					url = './tutor/tutor_edita.php?id='+row.id_tipus_incidencia;
				}
				saveItem(url,row);
			}
		}
		
		function reject(){
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function justificarIncidencia(){ 
		  var rows    = $('#dg').datagrid('getSelections');
		  
		  if (rows){ 
			   var ss_in = [];
			   for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss_in.push(row.idincidencia_alumne);
			   }
			   			   
			   url = './tutor/tutor_justifica.php';
			   
			   $.messager.prompt('Confirmar', 'Justifiquem aquestes faltes?', function(r){
                    if (r){ 
			   /*$.messager.confirm('Confirmar','Justifiquem aquestes faltes?',function(r){  
                    if (r){ */ 
                        $.post(url,{
								comentari:r,
								idincidencia_alumne:ss_in},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Faltes justificades correctament!','info');
								$('#dg').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Faltes justificades erroniament!','error');
								 
                                $.messager.show({  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json'); 
                    }  
               });  
			}
		}
		
		function esborrarIncidencia(){ 
		  var rows    = $('#dg').datagrid('getSelections');
		  //endEditing();
		  
		  if (rows){ 
			   var ss_in = [];
			   for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss_in.push(row.idincidencia_alumne);
			   }
			   			   
			   url = './tutor/tutor_esborra.php';
			   
			   $.messager.confirm('Confirmar','Esborrem aquestes faltes?',function(r){  
                    if (r){  
                        $.post(url,{
								idincidencia_alumne:ss_in},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Faltes esborrades correctament!','info');
								$('#dg').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Faltes esborrades erroniament!','error');
								 
                                $.messager.show({  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json'); 
                    }  
               });  
			}
		}
		
		function saveJustificacio(){ 
			$.messager.confirm('Confirmar','Procedim amb les justificacions?',function(r){  
                    if (r){ 
						  $('#fm_just').form('submit',{
								onSubmit: function(){
									return $(fm_just).form('validate');
								},
								success:function(data){
									$('#dg').datagrid('reload');
									$.messager.alert('Informaci&oacute;','Justificacions processades correctament!','info');
								}
						   });
	  				}
				});
		}
		
		function saveJustificacioPeriode(){ 
			$.messager.confirm('Confirmar','Procedim amb les justificacions?',function(r){  
                    if (r){ 
						  $('#fm_just_periode').form('submit',{
								onSubmit: function(){
									return $(fm_just_periode).form('validate');
								},
								success:function(data){
									$('#dg').datagrid('reload');
									$.messager.alert('Informaci&oacute;','Justificacions processades correctament!','info');
								}
						   });
	  				}
				});
		}

		
		function saveItem(url,row){ 			
	
			$.post(url,{id_tipus_incidencia:row.id_tipus_incidencia,id_tipus_incident:row.id_tipus_incident},function(result){  
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
		
		function afterEdit(url,field1,field2){
			$.post(url,{id_tipus_incidencia:field1,id_tipus_incident:field2},function(result){  
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
		
		function informeAssistencia(idgrups,nomgrup){ 
			$('#c_alumne').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});
			
			$('#c_materia').combobox({
				url:'./tutor/materies_getdata.php?idgrups='+idgrups,
				valueField:'idmateria',
				textField:'materia'
			}); 
			url = './assist_adm/assist_adm_see.php?idgrups='+idgrups;
			$('#dlg_inf').dialog('open').dialog('setTitle','Assistencia del grup '+nomgrup);
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function imprimirPDF(idgrups){  
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirWord(idgrups){  
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print_word.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirExcel(idgrups){  
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print_excel.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function doReload(idgrups){
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_see.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idmateria='+c_materia+'&percent='+percent;
			
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function tancar(grup) {
		    javascript:$('#dlg_sms').dialog('close');
			open1('./tutor/tutor_grid.php?grup='+grup,this);
			//$('#dg').datagrid('reload');
		}
		
		function doReloadSMS(idgrups,nomgrup){
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('refresh', url);
		}
		
		function enviarSMS(idgrups,nomgrup){  
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
		}
		
	</script>
        
    <style type="text/css">  
        #fm{  
            margin:0;  
            padding:10px 30px;  
        }
		#fm_just{  
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