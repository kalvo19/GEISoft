<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   if (isset($_SESSION['sortida'])) {
   		unset($_SESSION['sortida']);
   }
   
   $idgrups = $_REQUEST['grup'];
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:550px;">  
    <table id="dg" class="easyui-datagrid" title="Gestionar CCC" style="height:540px;" 
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './tutor_ccc/tutor_ccc_getdata.php?idgrups=<?=$idgrups?>',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th data-options="field:'data_ccc',width:85,align:'left',editor:{options:{formatter:myformatter,parser:myparser}}">Data</th>
                <th field="hora" width="85" sortable="true">Hora</th>
				<th field="nom_falta" width="110" sortable="true">Tipus CCC</th>
                <th field="expulsio" width="60" sortable="true">Expulsi&oacute;</th>
                <th field="alumne" width="270" sortable="true">Alumne</th>
                <th field="professor" width="270" sortable="true">Professor</th>           
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto"> 
        Desde: <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        fins a: <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a> 
        &nbsp;&nbsp;
        <a id="gestio_sancio" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true,disabled:true"  plain="true" onclick="gestioSancio()">Editar CCC</a>
        <a id="imprimir_ccc" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-print',plain:true,disabled:true" onclick="imprimirCCC()">Imprimir CCC</a>
        <!--<a id="informesCCC" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-help',plain:true"  plain="true" onclick="informesCCC()">Informes</a>-->
        
        <a id="informesCCC" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-help',plain:true"  plain="true" onclick="informesCCC(<?=$idgrups?>)">Informes</a>&nbsp;
        
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="enviarSMS(<?=$idgrups?>,'<?=getGrup($idgrups)->nom?>')">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>
        <!--<a id="estadistiquesCCC" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-help',plain:true"  plain="true" onclick="estadistiquesCCC()">Dades estad&iacute;stiques</a>-->
        
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
        <img src="./images/block_green.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;En curs
        <img src="./images/block_yellow.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Per comen&ccedil;ar
        <img src="./images/block_red.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Per establir sanci&oacute;
    </div>
    </div>
    
	<div id="dlg_sancio" class="easyui-dialog" style=" padding-left:5px; padding-top:5px; width:800px;height:500px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_sancio-buttons">
        	<form id="fm_sancio" method="post" novalidate>
            <input type="hidden" name="id_tipus_sancio" value="0" />
            <input type="hidden" name="data_inici_sancio" value="" />
            <input type="hidden" name="data_fi_sancio" value="" />
            <div>  
            	<label style="font-size:16px; font-weight:bolder">Implica expulsi&oacute; de classe?</label>
            	<input id="expulsio" name="expulsio" type="checkbox" value="S">
                <label style="color:#666666">(marcar aquesta opci&oacute; si es treu l'alumne de classe)</label>
            	<br /><br />
                
                <label style="width:150px; color:#666666">Data incid&egrave;ncia</label>
            	<input id="data_incident" name="data_incident" class="easyui-datebox" data-options="required: true,formatter:myformatter,parser:myparser">
            	&nbsp;&nbsp;&nbsp;
                
                <label>Tipus d'incid&egrave;ncia</label>
                <select id="id_falta" name="id_falta" class="easyui-combobox" data-options="
					required: true,
                    width:120,
                    url:'./ccc_tipus/ccc_tipus_getdata.php',
					idField:'idccc_tipus',
                    valueField:'idccc_tipus',
					textField:'nom_falta',
					panelHeight:'auto'
                ">
                </select>
                <br /><br />
                
                <label style="width:150px; color:#666666">Motiu</label><br />
                <select id="id_motius" name="id_motius" class="easyui-combogrid" style="width:760px" data-options="
                    required: false,
                    panelWidth: 760,
                    idField: 'idccc_motius',
                    textField: 'nom_motiu',
                    url: url,
                    method: 'get',
                    columns: [[
                        {field:'nom_motiu',title:'',width:760}
                    ]],
                    fitColumns: true
                ">
                </select>
                <br /><br />
                
                <label>Fets que s'han produ&iuml;t</label>  
                <textarea name="descripcio_detallada" style="height:245px; width:770px;"></textarea>
            </div>
                        
        	</form>
    </div>
        
    <div id="dlg_sancio-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveSancio()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_sancio').dialog('close')">Cancel.lar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;" data-options="maximized:true,maximizable:true,closed:true,resizable:true,modal:true"   
            toolbar="#dlg_inf-toolbar">
    </div>
    
    <div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%;">  
        <tr>  
            <td>
                <form id="fm_informes" method="post">
                Desde: <input id="data_inici_informe" name="data_inici_informe" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a: <input id="data_fi_informe" name="data_fi_informe" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;<br />
				<img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
                Criteri:
                <select id="criteri" name="criteri" class="easyui-combobox">
                    <option value="CAP"></option>
                    <option value="idgrup">Grup</option>
                    <option value="idalumne">Alumne</option>
                    <!--<option value="idprofessor">Professor</option>-->
                </select>
                
                &nbsp;Valor: 
                <input id="valor_criteri" name="valor_criteri" class="easyui-combobox" style="width:525px" data-options="
                	required: false,
                    panelWidth: 525,
                    idField: 'idgrups',
                    textField: 'nom',
                    url:  './tutor/grup_getdata.php?idgrups=<?=$idgrups?>'
                ">
               
                </form>
                <img src="./images/block_blue.png" height="1" width="100%" align="absmiddle" /> 
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload()">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirInforme()">Imprimir</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_est" class="easyui-dialog" style="width:900px;height:600px;" data-options="iconCls:'icon-print',maximized:true,maximizable:true,closed:true,resizable:true,modal:true"   
            toolbar="#dlg_est-toolbar">
    </div>
    
    <div id="dlg_est-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%;">  
        <tr>  
            <td>
                <form id="fm_estadistiques" method="post">
                Desde: <input id="data_inici_estad" name="data_inici_estad" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" />&nbsp;
        		Fins a: <input id="data_fi_estad" name="data_fi_estad" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" />&nbsp;<br />          
                </form>
                <img src="./images/block_blue.png" height="1" width="100%" align="absmiddle" /> 
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload_estad()">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_est').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_sms" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
    </div>
        
    <div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar(<?=$idgrups?>)">Tancar</a>  
    </div>

    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <script type="text/javascript">  
        var url;
		var editIndex    = undefined;
		var nou_registre = 0;
		var today        = new Date();
		var data_inici_sancio;
		var data_fi_sancio;
		var idgrups      = <?= $idgrups?>;
		
		$.extend($.fn.datagrid.defaults.editors, {
		combogrid: {
				init: function(container, options){
					var input = $('<input type="text" class="datagrid-editable-input">').appendTo(container); 
					input.combogrid(options);
					return input;
				},
				destroy: function(target){
					$(target).combogrid('destroy');
				},
				getValue: function(target){
					return $(target).combogrid('getValue');
				},
				setValue: function(target, value){
					$(target).combogrid('setValue', value);
				},
				resize: function(target, width){
					$(target).combogrid('resize',width);
				}
			}
		});
				
		$('#criteri').combobox({
			onSelect: function(date){	
			    criteri = $('#criteri').combobox('getValue');
				switch (criteri) {
					case 'idgrup':
						url         = './tutor/grup_getdata.php?idgrups='+idgrups ;
						index_combo = 'idgrups' ;
						valor_combo = 'nom' ;
						break;
					case 'idmateria':
						url         = './grma/mat_getdata.php' ;
						index_combo = 'id' ;
						valor_combo = 'nom' ;
						break;
					case 'idalumne':
						url         = './tutor/alum_getdata.php?idgrups='+idgrups ;
						index_combo = 'idalumnes' ;
						valor_combo = 'Valor' ;
						break;
					case 'idprofessor':
						url         = './ccc_adm/prof_getdata.php' ;
						index_combo = 'id_professor' ;
						valor_combo = 'Valor' ;
						break;
					case 'id_falta':
						url         = './ccc_adm/tipus_getdata.php' ;
						index_combo = 'idccc_tipus' ;
						valor_combo = 'nom_falta' ;
						break;
					case 'id_tipus_sancio':
						url         = './ccc_adm/mesures_getdata.php' ;
						index_combo = 'idccc_tipus_mesura' ;
						valor_combo = 'ccc_nom' ;
						break;
				} 
		
				$('#valor_criteri').combobox({
					url:url,
					valueField:index_combo,
					textField:valor_combo
				});
			}
		});
		
		$('#id_motius').combogrid({
			url:'./ccc_motius/ccc_motius_getdata.php',
			valueField:'idccc_motius',
			textField:'nom_motiu'
		});
		
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
						href:'./tutor_ccc/tutor_ccc_getdetail.php?id='+row.idccc_taula_principal,
						onLoad:function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						}
					});
					$('#dg').datagrid('fixDetailRowHeight',index);
				},
				rowStyler:function(index,row){
				    data_inici_sancio = new Date(row.data_inici_sancio);
					data_fi_sancio = new Date(row.data_fi_sancio);
					if (row.id_tipus_sancio === null) {
						return 'background-color:whitesmoke;color:#be0f34;font-weight:bold;';
						//return 'background-color:#a70e11;color:#ccc;font-weight:bold;';
					}
					if (today>data_fi_sancio){
						return 'background-color:whitesmoke;color:#CCC;';
					}
					if (today<data_inici_sancio){
						//return 'background-color:#ffcb00;color:#af8900;font-weight:bold;';
						return 'background-color:whitesmoke;color:#af8900;font-weight:bold;';
					}
					if (today>=data_inici_sancio && today<=data_fi_sancio ){
						return 'background-color:#a1d88b;color:#009a49;font-weight:bold;';
					}
				}
            });  
        });
				
		function doSearch(){ 
			$('#dg').datagrid('load',{  
				data_inici: $('#data_inici').datebox('getValue'),
				data_fi   : $('#data_fi').datebox('getValue')  
			});  
		}
		
		function doReload(){
			d_inici       = $('#data_inici_informe').datebox('getValue');
			d_fi          = $('#data_fi_informe').datebox('getValue');
			criteri       = $('#criteri').combobox('getValue');			
			valor_criteri = $('#valor_criteri').combobox('getValue');
			
			url = './ccc_adm/ccc_adm_inf.php?criteri='+criteri+'&valor_criteri='+valor_criteri+'&data_inici='+d_inici+'&data_fi='+d_fi;
			
			$('#dlg_inf').dialog('refresh', url);
		} 
		
		function doReload_estad(){
			d_inici       = $('#data_inici_estad').datebox('getValue');
			d_fi          = $('#data_fi_estad').datebox('getValue');
			
			url = './ccc_adm/ccc_adm_see.php?data_inici='+d_inici+'&data_fi='+d_fi;
			
			$('#dlg_est').dialog('refresh', url);
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
		
		function tancar(grup) {
		    javascript:$('#dlg_sms').dialog('close');
			open1('./tutor_ccc/tutor_ccc_grid.php?grup='+idgrups,this);
			//$('#dg').datagrid('reload');
		}
		
		function informesCCC(idgrups){  
			//url = './ccc_adm/ccc_adm_inf.php?criteri=idgrup&valor_criteri='+idgrups;
			url = './ccc_adm/ccc_adm_inf.php?criteri=CAP&valor_criteri=0';
			$('#dlg_inf').dialog('open').dialog('setTitle','Informe CCC');
			$('#dlg_inf').dialog('refresh', url);
    	}
		
		function estadistiquesCCC(){  
			url = './ccc_adm/ccc_adm_see.php';
			$('#dlg_est').dialog('open').dialog('setTitle','Dades estad&iacute;stiques CCC');
			$('#dlg_est').dialog('refresh', url);
    	}
	
		function imprimirInforme(){
			d_inici       = $('#data_inici_informe').datebox('getValue');
			d_fi          = $('#data_fi_informe').datebox('getValue');
			criteri       = $('#criteri').combobox('getValue');
			valor_criteri = $('#valor_criteri').combobox('getValue');
		
			url = './ccc_adm/ccc_adm_print_inf.php?criteri='+criteri+'&valor_criteri='+valor_criteri+'&data_inici='+d_inici+'&data_fi='+d_fi; 
			$('#fitxer_pdf').attr('src', url);
    	}
		
		function gestioSancio(){  
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlg_sancio').dialog('open').dialog('setTitle','Establir sanci&oacute;');
				$('#fm_sancio').form('load','./ccc_adm/ccc_adm_load.php?id='+row.idccc_taula_principal);				
				
				url = './ccc_adm/ccc_adm_edita.php?id='+row.idccc_taula_principal;
            }
        }

        function saveSancio(){		
			$('#fm_sancio').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
					var result = eval('('+result+')');
                    if (result.errorMsg){
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    } else {
                        $('#dlg_sancio').dialog('close');     
                        $('#dg').datagrid('reload'); 
						editIndex = undefined;
						
						$('#gestio_sancio').linkbutton('disable');
						$('#esborrar_ccc').linkbutton('disable');
						$('#imprimir_ccc').linkbutton('disable');
                    }
                }
            });
        }
		
		function novaCCC(){  
           $('#dlg_nova_ccc').dialog('open').dialog('setTitle','Nova CCC');
				
		   url = './ccc_adm/ccc_adm_edita.php?id=0';
        }
		
		function saveCCC(){		
			$('#fm_nova_ccc').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
					var result = eval('('+result+')');
                    if (result.errorMsg){
                        $.messager.show({
                            title: 'Error',
                            msg: result.errorMsg
                        });
                    } else {
                        $('#dlg_nova_ccc').dialog('close');     
                        $('#dg').datagrid('reload'); 
						editIndex = undefined;
						
						$('#gestio_sancio').linkbutton('disable');
						$('#esborrar_ccc').linkbutton('disable');
						$('#imprimir_ccc').linkbutton('disable');
                    }
                }
            });
        }
		
		function esborrarCCC(){ 
		  var row = $('#dg').datagrid('getSelected');
		  
		  if (row){ 
			   url = './ccc_adm/ccc_adm_esborra.php';
			   
			   $.messager.confirm('Confirmar','Esborrem aquesta CCC?',function(r){  
                    if (r){  
                        $.post(url,{
								id:row.idccc_taula_principal},function(result){  
                            if (result.success){  
                                   $.messager.alert('Informaci&oacute;','CCC esborrada correctament!','info');
								   $('#dg').datagrid('reload');
								   editIndex = undefined;
								   $('#gestio_sancio').linkbutton('disable');
								   $('#esborrar_ccc').linkbutton('disable');
								   $('#imprimir_ccc').linkbutton('disable');
                            } else { 
							    $.messager.alert('Error','CCC esborrada erroniament!','error');
								 
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
		
		function imprimirCCC(){  
			var row = $('#dg').datagrid('getSelected');
		    if (row) {
				url = './ccc_adm/ccc_adm_printCCC.php?id='+row.idccc_taula_principal;
				
				$('#fitxer_pdf').attr('src', url);
			}
    	}
		
		function onClickRow(index){
				var row = $('#dg').datagrid('getSelected');
				
				if (editIndex != index){
					if (endEditing()){
						$('#dg').datagrid('selectRow', index)
								.datagrid('beginEdit', index);
						editIndex = index;
					} else {
						$('#dg').datagrid('selectRow', editIndex);
					}
				}
				$('#gestio_sancio').linkbutton('enable');
				$('#esborrar_ccc').linkbutton('enable');
				$('#imprimir_ccc').linkbutton('enable');
		}
					
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){
				$('#dg').datagrid('acceptChanges');
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './sortides/sortides_nou.php';
					nou_registre = 0;
				}
				else {
					url = './sortides/sortides_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idsortides'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['data_inici'],
						  $('#dg').datagrid('getRows')[editIndex]['data_fi'],
						  $('#dg').datagrid('getRows')[editIndex]['hora_inici'],
						  $('#dg').datagrid('getRows')[editIndex]['hora_fi'],
						  $('#dg').datagrid('getRows')[editIndex]['lloc'],
						  $('#dg').datagrid('getRows')[editIndex]['descripcio']);
				
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
		}
				
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_fh').dialog('close');
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