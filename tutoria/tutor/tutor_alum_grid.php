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
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid"  
        url="./tutor/tutor_alum_getdata.php?idgrups=<?=$idgrups?>"  
        title="Llistat d'alumnes" toolbar="#toolbar" fitColumns="false"
        rownumbers="true" pagination="false" sortName="ca.Valor" sortOrder="asc" singleSelect="true">  
        <thead>  
            <tr>
                <th data-options="field:'acces_alumne',width:20,styler:cellStyler_alumne,
                	formatter:function(value,row){
								return '';
					}
                "></th>
                <th data-options="field:'acces_familia',width:20,styler:cellStyler_familia,
                	formatter:function(value,row){
								return '';
					}
                "></th>
                <th field="Valor" width="850" sortable="true">Nom</th>                
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
    <div style="margin-bottom:5px">
        Cognoms: <input id="cognoms" class="easyui-validatebox" style="width:180px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <br />
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionMaterias()">Gesti&oacute; Mat&egrave;ries</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="verHorario()">Veure horari alumne</a>
        
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="enviarSMS(<?=$idgrups?>,'<?=getGrup($idgrups)->nom?>')">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>

        <img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="pujarFoto(<?=$idgrups?>)">Pujar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="esborrarFoto()">Esborrar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" disabled="true" onclick="canviContrasenya()">Contrasenya alumne</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" disabled="true" onclick="canviContrasenyaFamilia()">Dades acc&egrave;s families</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="cartesContrasenyesFamilies(<?=$idgrups?>)">Cartes contrasenyes families</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="llistatContrasenyesFamilies(<?=$idgrups?>)">Llistat contrasenyes families</a>
        
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
        <img src="./images/block_yellow.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Acc&eacute;s alumne&nbsp;
        <img src="./images/block_red.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Acc&eacute;s familia
        &nbsp;
        <a id="desactiva_a_alumne" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="treure_acces('alumne')">Treure acc&eacute;s alumne</a>
        <a id="desactiva_a_familia" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="treure_acces('familia')">Treure acc&eacute;s familia</a>
        
    </div>  
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" maximized="true" maximizable="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Mat&egrave;ries" style="width:825px;height:555px"
                data-options="
                    singleSelect: true,
                    url:'./tutor/tutor_alum_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#dlg-toolbar',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <th data-options="field:'idgrups_materies',width:780,
                            formatter:function(value,row){
								return row.matgrup;
							},
							editor:{
								type:'combogrid',
								options:{
									idField: 'idgrups_materies', 
                                    valueField:'idgrups_materies',
									textField:'matgrup',
                                    mode:'remote',
									url:'./almat/pe_mat_getdata.php',
									required:false,
                                    columns:[[
                                        {field:'materia',title:'Materia',width:570},
                                        {field:'nom',title:'Grup',width:180}
                                    ]]
								}
                            }">Mat&egrave;ria/Grup</th>
                </tr>
            </thead>
        </table>
    </div> 
    
    <div id="dlg-toolbar" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    
    <div id="dlg_upload" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" resizable="true" modal="true" maximized="true" maximizable="true" toolbar="#dlg_upload-toolbar">  
    </div>
        
    <div id="dlg_upload-toolbar">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_upload').dialog('refresh')">Recarregar</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:tancarFoto()">Tancar</a>  
                </td>
            </tr>  
        </table>  
    </div>
      
    <div id="dlg_hor" class="easyui-dialog" style="width:1200px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
    </div>
    
    <div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirHorario()">Imprimir</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
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
   
    <div id="dlg_contrasenya" class="easyui-dialog" style="width:450px;height:200px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_contrasenya-buttons">
            <div class="ftitle">Canvi contrasenya</div>
        	<form id="fm" method="post" novalidate>
            <div class="fitem">
                <label style="width:150px;">Nova contrasenya:</label>
                <input id="contrasenya_1" name="contrasenya_1" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'">
            </div>
            <div class="fitem">
                <label style="width:150px;">Repeteixi contrasenya:</label>
                <input id="contrasenya_2" name="contrasenya_2" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'">
            </div>
        	</form>
    </div>
        
    <div id="dlg_contrasenya-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveContrasenya()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_contrasenya').dialog('close')">Cancel.lar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_contrasenya_familia" class="easyui-dialog" style="width:450px;height:260px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_contrasenya_familia-buttons">
            <br /><br />
        	<form id="fm_familia" method="post" novalidate>
            <div class="fitem" style="padding-left:10px;">
                <label style="width:150px;">Login familia:</label>
                <input id="login_familia" name="login_familia" class="easyui-validatebox" type="text" data-options="required:true,validType:'length[3,50]'">
            </div>
            <br />
            <div class="fitem" style="padding-left:10px;">
                <label style="width:150px;">Nova contrasenya:</label>
                <input id="contrasenya_1_familia" name="contrasenya_1_familia" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'">
            </div>
            <div class="fitem" style="padding-left:10px;">
                <label style="width:150px;">Repeteixi contrasenya:</label>
                <input id="contrasenya_2_familia" name="contrasenya_2_familia" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'">
            </div>
        	</form>
    </div>
        
    <div id="dlg_contrasenya_familia-buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveContrasenyaFamilia()">Acceptar</a>
        			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_contrasenya_familia').dialog('close')">Cancel.lar</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_cf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_cf-toolbar">  
    </div>
    
    <div id="dlg_cf-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_cf').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirContrasenyesFamilies(<?=$idgrups?>)">Imprimir</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_cf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
     
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
      
    <script type="text/javascript">  
		var url;
		var editIndex = undefined;
		var nou_registre = 0;
		
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
		
		$('#dg').datagrid({
				view: detailview,
				detailFormatter:function(index,row){
					return '<div class="ddv"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
					border:false,
					cache:true,
					href:'./alum/alum_contacte.php?index='+index+'&idalumnes='+row.idalumnes,
					onLoad:function(){
					$('#dg').datagrid('fixDetailRowHeight',index);
					$('#dg').datagrid('selectRow',index);
					$('#dg').datagrid('getRowDetail',index).find('form').form('load',row);
				}
				});
					$('#dg').datagrid('fixDetailRowHeight',index);
				}
		});
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.activat=='N'){
						return 'background-color:whitesmoke;color:#CCC;';
					}
				}  
            });  
        });
		
		function cellStyler_alumne(value,row,index){
            if (value == 'S'){
                return 'background-color:#ffcb00;color:white;';
            }
        }
		
		function cellStyler_familia(value,row,index){
            if (value == 'S'){
                return 'background-color:#a70e11;color:white;';
            }
        }
   
		function gestionMaterias(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_mat').datagrid('load',{ 
					idalumnes: row.idalumnes  
     			});
            }  
        }
		
        function doSearch(){  
			$('#dg').datagrid('load',{  
				cognoms: $('#cognoms').val()  
			});  
		}
		
		function pujarFoto(grup){ 
		    var row = $('#dg').datagrid('getSelected');
            if (row){
				url = './tutor/alum_upload_photo.php?idalumnes='+row.idalumnes+'&grup='+grup;
				$('#dlg_upload').dialog('open').dialog('setTitle','Pujar foto');
				$('#dlg_upload').dialog('refresh', url);
			}
		}
		
		function esborrarFoto(){ 
		    $.messager.confirm('Confirmar','Esborrem aquesta foto?',function(r){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					url = './tutor/esborra_foto.php?id='+row.idalumnes;
					$.post(url,{},function(result){  
					if (result.success){ 
						$.messager.alert('Informaci&oacute;','Foto esborrada correctament!','info');
						$('#dg').datagrid('reload');
					} else { 
						$.messager.show({     
						title: 'Error',  
						msg: result.msg  
						});  
						}  
					 },'json');
				}
			});
		}
		
		function tancarFoto(){ 
			$('#dlg_upload').dialog('close');
			open1('./tutor/tutor_alum_grid.php?grup=<?= $idgrups ?>',this);
		}
		
		function canviContrasenya(){
            var row = $('#dg').datagrid('getSelected');
			$('#dlg_contrasenya').dialog('open').dialog('setTitle','Dades usuari');
            $('#fm').form('clear');
            url = './alum/alum_update_passwd.php?id='+row.idalumnes;
        }
		
		function saveContrasenya(){
            var contrasenya_1 = $('#contrasenya_1').val();
			var contrasenya_2 = $('#contrasenya_2').val();
			if (contrasenya_1!=contrasenya_2) {
				 $.messager.alert('Error','Les contrasenyes no coincideixen! Sisplau, revisa-les.','error');
				return false;
			}
			
			$('#fm').form('submit',{
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
					    $.messager.alert('Informaci&oacute;','Contrasenya actualitzada correctament!','info');
                        $('#dlg_contrasenya').dialog('close');
						$('#dg').datagrid('reload');
                    }
                }
            });
        }
		
		function canviContrasenyaFamilia(){
            var row = $('#dg').datagrid('getSelected');
			if (row) {
				url = './alum/familia_load.php?id='+row.idalumnes;
				$('#fm_familia').form('clear');
				$('#fm_familia').form('load',url);
				$('#dlg_contrasenya_familia').dialog('open').dialog('setTitle','Dades connexi&oacute; familia');
				url = './alum/familia_update_passwd.php?id='+row.idalumnes;
			}
        }
		
		function saveContrasenyaFamilia(){
            var contrasenya_1 = $('#contrasenya_1_familia').val();
			var contrasenya_2 = $('#contrasenya_2_familia').val();
			if (contrasenya_1!=contrasenya_2) {
				 $.messager.alert('Error','Les contrasenyes no coincideixen! Sisplau, revisa-les.','error');
				return false;
			}
			
			$('#fm_familia').form('submit',{
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
					    $.messager.alert('Informaci&oacute;','Dades de connexi&oacute; actualitzades correctament!','info');
                        $('#dlg_contrasenya_familia').dialog('close');
						$('#dg').datagrid('reload');
                    }
                }
            });
        }
		
		function treure_acces(element){ 
		    var row = $('#dg').datagrid('getSelected');
            if (row){
				$.messager.confirm('Confirmar','Est&aacute;s segur de que vols treure l\'acc&eacute;s?',function(r){  
				if (r){
					if (element=='alumne') {
						url = './alum/alum_t_ac_alum.php?idalumnes='+row.idalumnes;
					}
					if (element=='familia') {
						url = './alum/alum_t_ac_fami.php?idalumnes='+row.idalumnes;
					}
					$.post(url,{},function(result){  
					if (result.success){ 
						$.messager.alert('Informaci&oacute;','Acc&eacute;s tret correctament!','info');
						$('#dg').datagrid('reload');
					} else { 
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
		
		
		function imatgeAlumne(val,row){
			return '<img src="./images/alumnes/'+val+'.jpg<?= $strNoCache ?>" width=51 height=70 />';
		}
		
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){
				url = './almat/almat_see.php?idalumnes='+row.idalumnes+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.Valor);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');		
		    url = './almat/almat_print.php?idalumnes='+row.idalumnes+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
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
			open1('./tutor/tutor_alum_grid.php?grup='+grup,this);
			//$('#dg').datagrid('reload');
		}
		
		function llistatContrasenyesFamilies(idgrups){  
			url = './tutor/contrasenyes_families_see.php?idgrups='+idgrups;
			$('#dlg_cf').dialog('open').dialog('setTitle','Dades acc&eacute;s families');
			$('#dlg_cf').dialog('refresh', url);
        }
		
		function imprimirContrasenyesFamilies(idgrups){
		    url = './tutor/contrasenyes_families_print.php?idgrups='+idgrups;
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function cartesContrasenyesFamilies(idgrups){
		    url = './tutor/cartes_families_print.php?idgrups='+idgrups;
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg_mat').datagrid('selectRow', index)
							    .datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('dg_mat').datagrid('selectRow', editIndex);
				}
			}
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg_mat').datagrid('validateRow', editIndex)){
				var row_p = $('#dg').datagrid('getSelected');
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idgrups_materies'});				
				
				var val = $(ed.target).combogrid('getValue');
				var matgrup = $(ed.target).combogrid('getText');
												
				$('#dg_mat').datagrid('getRows')[editIndex]['matgrup'] = matgrup;
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './tutor/tutor_alum_nou.php';
					nou_registre = 0;
				}
				else {
					url = './tutor/tutor_alum_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idalumnes_grup_materia'];
				}
				afterEdit(url,
						  row_p.idalumnes,
						  $('#dg_mat').datagrid('getRows')[editIndex]['idgrups_materies']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
			
		function append(){
			if (endEditing()){
				$('#dg_mat').datagrid('appendRow',{});
				nou_registre = 1;
				
				editIndex = $('#dg_mat').datagrid('getRows').length-1;
				$('#dg_mat').datagrid('selectRow', editIndex)
						    .datagrid('beginEdit', editIndex);
			}
		}
			
		function accept(){			
			if (endEditing()){
				$('#dg_mat').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './tutor/tutor_alum_nou.php';
					nou_registre = 0;
				}
				else {
					url = './tutor/tutor_alum_edita.php?id='+row_a.idalumnes_grup_materia;
				}

				saveItem(url,row_p,row_a);
			}
		}
		
		function reject(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function getChanges(){
			var rows = $('#dg_mat').datagrid('getChanges');
			alert(rows.length+' rows are changed!');
		}
		
		function destroyItem(){  
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta mat&egrave;ria?',function(r){  
                    if (r){  
                        $.post('./tutor/tutor_alum_esborra.php',{id:row_a.idalumnes_grup_materia},function(result){  
                            if (result.success){  
                                $('#dg_mat').datagrid('reload');
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
				
		/*function saveItem(url,row_p,row_a){ 			
	
			$.post(url,{idalumnes:row_p.idalumnes,idgrups_materies:row_a.idgrups_materies},function(result){  
            if (result.success){  
			  
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }*/
		
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			var url = row.isNewRecord ? './alum/alum_nou.php' : './alum/alum_edita.php?id='+row.idalumnes;
			
			$('#dg').datagrid('getRowDetail',index).find('form').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(data){
					data = eval('('+data+')');
					data.isNewRecord = false;
					$('#dg').datagrid('collapseRow',index);
					$('#dg').datagrid('updateRow',{
						index: index,
						row: data
					});
				}
			});
			
			if (url=='./alum/alum_nou.php') {
				cognoms = $('#cognoms').val();
				open1('./alum/alum_grid.php?cognoms='+cognoms,this);
				//doSearch();
			}
		}
		
		function afterEdit(url,field1,field2){
			$.post(url,{idalumnes:field1,idgrups_materies:field2},function(result){  
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
		
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
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
            margin-bottom:1px;  
        }  
        .fitem label{  
            display: inline-table;
            width:120px;  
        }  
    </style>