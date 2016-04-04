<?php
	$cognoms = isset($_REQUEST['cognoms']) ? $_REQUEST['cognoms'] : '' ;
?>    
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px;" title="Manteniment d'alumnes"
        data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './alum/alum_getdata.php',
                sortName:'ca.Valor',
                sortOrder:'asc',
				onClickRow: onClickRow
			"> 
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
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
                <th data-options="field:'codi_alumnes_saga',width:120">ID</th>
                <th field="Valor" width="750" sortable="true">Nom</th>
                <!--<th field="grup" width="200" sortable="true">Grup</th>-->
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
    <div style="margin-bottom:5px">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">Nou</a>
        <a id="activa_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" disabled="true" onclick="activa('S')">Activa</a>  
        <a id="desactiva_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="activa('N')">Desactiva</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">Esborra</a>
        Cognoms: <input id="cognoms" name="cognoms" class="easyui-validatebox" style="width:180px" value="<?=$cognoms?>">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>

        <img src="./images/line.png" height="1" width="100%" align="absmiddle" />&nbsp; 
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="pujarFoto()">Pujar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="esborrarFoto()">Esborrar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="canviContrasenya()">Contrasenya alumne</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="canviContrasenyaFamilia()">Dades acc&egrave;s families</a>
        <br />
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="establirGermans()">
        <img src="./images/group.png" height="16" align="absmiddle" />&nbsp;Establir germans</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="habilitarFamilies()">
        <img src="./images/keys.png" height="16" align="absmiddle" />&nbsp;Habilitar accés totes les families</a>
        &nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="llistatContrasenyesFamilies()">Llistat contrasenyes families</a>
        
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
        <img src="./images/block_yellow.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Acc&eacute;s alumne&nbsp;
        <img src="./images/block_red.png" width="25" height="15" style="border:1px dashed #7da949" />&nbsp;Acc&eacute;s familia
        &nbsp;
        <a id="desactiva_a_alumne" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="treure_acces('alumne')">Treure acc&eacute;s alumne</a>
        <a id="desactiva_a_familia" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="treure_acces('familia')">Treure acc&eacute;s familia</a>
    </div>  
    </div>
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
    
    <div id="dlg_fitxa" class="easyui-dialog" style="width:900px;height:650px;"  
            closed="true" collapsible="true" resizable="true" modal="true" maximizable="true" buttons="#dlg_fitxa-toolbar">
    </div>
        
    <div id="dlg_fitxa-toolbar">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveItem(<?php echo $_REQUEST['index'];?>)">Guardar</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="$('#dlg_fitxa').dialog('close')">Tancar</a>  
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_upload" class="easyui-dialog" style="width:900px;height:550px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" maximizable="true" toolbar="#dlg_upload-toolbar">
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
    
    <div id="dlg_cf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" maximizable="true" toolbar="#dlg_cf-toolbar">  
    </div>
    
    <div id="dlg_cf-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_cf').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirContrasenyesFamilies()">Imprimir</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_cf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_acces_families" class="easyui-dialog" style="width:800px;height:600px;"  
            closed="true" collapsible="true" resizable="true" modal="true" maximizable="true" buttons="#dlg_acces_families-toolbar">  
    </div>
    
    <div id="dlg_acces_families-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_acces_families').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
      
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <script type="text/javascript">  
        var url;
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
		
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
					href:'./alum/alum_contacte.php?index='+index+'&idalumnes='+row.id_alumne,
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
		
		function establirGermans(){ 
		  var rows    = $('#dg').datagrid('getSelections');
		  
		  if (rows){ 
			   var ss_al = [];
			   for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss_al.push(row.id_alumne);
			   }

			   url = './alum/establir_germans.php';
			   
			   $.messager.confirm('Confirmar','Establim germans?',function(r){  
                    if (r){  
                        $.post(url,{
								idalumnes:ss_al},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Germans establerts correctament!','info');
								$('#dg').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Germans establerts erroniament!','error');
								 
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
		
		function habilitarFamilies(){ 
			   url = './alum/acces_totes_families.php';
			
			   $.messager.confirm('Confirmar','Habilitem l\'acc&eacute;s a totes les families?',function(r){  
                    if (r){ 
						$('#dlg_acces_families').dialog('open').dialog('setTitle','Acc&eacute;s families');
			  			$('#dlg_acces_families').dialog('refresh', url);
						
						$.post(url,function(result){ },'json');
                    }  
               });  
		}
				
		function llistatContrasenyesFamilies(){  
			url = './alum/contrasenyes_families_see.php';
			$('#dlg_cf').dialog('open').dialog('setTitle','Dades acc&eacute;s families');
			$('#dlg_cf').dialog('refresh', url);
        }
		
		function imprimirContrasenyesFamilies(){
		    url = './alum/contrasenyes_families_print.php';
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function onClickRow(index){ 
			var row = $('#dg').datagrid('getSelected');
			if (row.activat=='S'){
				$('#activa_button').linkbutton('disable');
				$('#desactiva_button').linkbutton('enable');
				$('#desactiva_a_alumne').linkbutton('enable');
				$('#desactiva_a_familia').linkbutton('enable');
			}
			if (row.activat=='N'){
				$('#activa_button').linkbutton('enable');
				$('#desactiva_button').linkbutton('disable');
				$('#desactiva_a_alumne').linkbutton('disable');
				$('#desactiva_a_familia').linkbutton('disable');
			}
		}
			
        function doSearch(){  
			$('#dg').datagrid('load',{  
				cognoms: $('#cognoms').val()  
			});  
		}
		
		function pujarFoto(){ 
		    var row = $('#dg').datagrid('getSelected');
            if (row){
				url = './alum/alum_upload_photo.php?idalumnes='+row.id_alumne;
				$('#dlg_upload').dialog('open').dialog('setTitle','Pujar foto');
				$('#dlg_upload').dialog('refresh', url);
			}
		}
		
		function esborrarFoto(){ 
		    $.messager.confirm('Confirmar','Esborrem aquesta foto?',function(r){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					url = './alum/esborra_foto.php?id='+row.id_alumne;
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
			open1('./alum/alum_grid.php',this);
		}
		
		function canviContrasenya(){
            var row = $('#dg').datagrid('getSelected');
			$('#dlg_contrasenya').dialog('open').dialog('setTitle','Dades usuari');
            $('#fm').form('clear');
            url = './alum/alum_update_passwd.php?id='+row.id_alumne;
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
				url = './alum/familia_load.php?id='+row.id_alumne;
				$('#fm_familia').form('clear');
				$('#fm_familia').form('load',url);
				$('#dlg_contrasenya_familia').dialog('open').dialog('setTitle','Dades connexi&oacute; familia');
				url = './alum/familia_update_passwd.php?id='+row.id_alumne;
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
						url = './alum/alum_t_ac_alum.php?idalumnes='+row.id_alumne;
					}
					if (element=='familia') {
						url = './alum/alum_t_ac_fami.php?idalumnes='+row.id_alumne;
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
		
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			var url = row.isNewRecord ? './alum/alum_nou.php' : './alum/alum_edita.php?id='+row.id_alumne;
			
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
		
		function cancelItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			if (row.isNewRecord){
				$('#dg').datagrid('deleteRow',index);
			} else {
				$('#dg').datagrid('collapseRow',index);
			}
		}
		
		function newItem(){
			$('#dg').datagrid('appendRow',{isNewRecord:true});
			var index = $('#dg').datagrid('getRows').length - 1;
			$('#dg').datagrid('expandRow', index);
			$('#dg').datagrid('selectRow', index);
		}
		
        function destroyUser(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Estás segur de que vols esborrar aquest alumne?',function(r){  
                    if (r){ 
                        $.post('./alum/alum_esborra.php',{id:row.id_alumne},function(result){  
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
		
		function activa(op){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Procedim?',function(r){  
                    if (r){  
                        $.post('./alum/alum_desactiva.php',{op:op,id:row.id_alumne},function(result){  
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