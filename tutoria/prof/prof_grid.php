<?php
	$cognoms = isset($_REQUEST['cognoms']) ? $_REQUEST['cognoms'] : '' ;
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px" title="Manteniment de Professors"  
        data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './prof/prof_getdata.php',
                sortName:'cp.Valor',
                sortOrder:'asc',
				onClickRow: onClickRow
			">
        <thead>  
            <tr>
                <th data-options="field:'codi_professor',width:120">ID</th>
                <th field="Valor" width="770" sortable="true">Nom</th> 
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
    <div style="margin-bottom:5px">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newItem()">Nou</a> 
        <a id="activa_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" disabled="true" onclick="activa('S')">Activa</a>  
        <a id="desactiva_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="activa('N')">Desactiva</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" disabled="true" onclick="destroyUser()">Esborra</a>
        Cognoms <input id="cognoms" class="easyui-validatebox" style="width:180px" value="<?=$cognoms?>">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="pujarFoto()">Pujar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="esborrarFoto()">Esborrar foto</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="canviContrasenya()">Canviar contrasenya</a>
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
    
    <div id="dlg_upload" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" maximizable="true" toolbar="#dlg_upload-toolbar">
    </div>
        
    <div id="dlg_upload-toolbar">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_upload').dialog('refresh')">Recarregar</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:tancarFoto()">Tancar</a>  
                </td>
            </tr>  
        </table>  
    </div>  
      
    <script type="text/javascript">  
        var url;
		
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
					href:'./prof/prof_contacte.php?index='+index+'&idprofessors='+row.id_professor,
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
		
		function onClickRow(index){ 
			var row = $('#dg').datagrid('getSelected');
			if (row.activat=='S'){
				$('#activa_button').linkbutton('disable');
				$('#desactiva_button').linkbutton('enable');
			}
			if (row.activat=='N'){
				$('#activa_button').linkbutton('enable');
				$('#desactiva_button').linkbutton('disable');
			}
		}
			
        function doSearch(){  
			$('#dg').datagrid('load',{  
				cognoms: $('#cognoms').val()  
			});  
		}
		
		function doSearch2(value){
            alert('You input: ' + value);
        }
		
		function pujarFoto(){ 
		    var row = $('#dg').datagrid('getSelected');
            if (row){
				url = './prof/prof_upload_photo.php?idprofessors='+row.id_professor;
				$('#dlg_upload').dialog('open').dialog('setTitle','Pujar foto');
				$('#dlg_upload').dialog('refresh', url);
			}
		}
		
		function esborrarFoto(){ 
		    $.messager.confirm('Confirmar','Esborrem aquesta foto?',function(r){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					url = './prof/esborra_foto.php?id='+row.id_professor;
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
			open1('./prof/prof_grid.php',this);
		}
		
		function canviContrasenya(){
            var row = $('#dg').datagrid('getSelected');
			$('#dlg_contrasenya').dialog('open').dialog('setTitle','Dades usuari');
            $('#fm').form('clear');
            url = './prof/prof_update_passwd.php?id='+row.id_professor;
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
                    }
                }
            });
        }
		
		function saveItem(index){
			var row = $('#dg').datagrid('getRows')[index];
			var url = row.isNewRecord ? './prof/prof_nou.php' : './prof/prof_edita.php?id='+row.id_professor;
			
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
			
			if (url=='./prof/prof_nou.php') {
				cognoms = $('#cognoms').val();
				open1('./prof/prof_grid.php?cognoms='+cognoms,this);
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
                $.messager.confirm('Confirmar','Estás segur de que vols esborrar aquest professor?',function(r){  
                    if (r){  
                        $.post('./prof/prof_esborra.php',{id:row.id_professor},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');    // reload the user data  
                            } else {  
                                $.messager.show({   // show error message  
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
                        $.post('./prof/prof_desactiva.php',{op:op,id:row.id_professor},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');    // reload the user data  
                            } else {  
                                $.messager.show({   // show error message  
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