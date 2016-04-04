<?php
  session_start();
?> 
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px;" 
        url="./algr/algr_getdata.php"  
        title="Alumnes per grups" toolbar="#toolbar" fitColumns="false"
        rownumbers="true" pagination="true" sortName="ca.Valor" sortOrder="asc" singleSelect="true">  
        <thead>  
            <tr>
                <th data-options="field:'codi_alumnes_saga',width:120">ID</th>
                <th field="Valor" width="650" sortable="true">Nom</th>
                <!--<th field="grup" width="200" sortable="true">Grup</th>-->
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionGrups()">Gesti&oacute; Grups</a>  
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionMaterias()">Gesti&oacute; Mat&egrave;ries</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="verHorario()">Veure horari</a>  
        &nbsp;<br />&nbsp; 
        Cognoms: <input id="cognoms" class="easyui-validatebox" style="width:180px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
    </div>
    </div>
    
    <div id="dlg_grup" class="easyui-dialog" style="width:650px;height:600px;padding:5px 5px" modal="true" closed="true">
        <table id="dg_grup" class="easyui-datagrid" title="Grups" style="width:625px;height:555px"
                data-options="
                    singleSelect: true,
                    url:'./algr/algr_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_grup_toolbar'
                ">
            <thead>
                <tr>
                    <th data-options="field:'idgrups',width:570,
                            formatter:function(value,row){
								return row.nom;
							},
							editor:{
								type:'combogrid',
								options:{
									idField: 'idgrups', 
                                    valueField:'idgrups',
									textField:'nom',
                                    mode:'remote',
									url:'./algr/gr_getdata.php',
									required:false,
                                    columns:[[
                                        {field:'nom',title:'Grup',width:210},
                                        {field:'Descripcio',title:'Descripci&oacute;',width:300}
                                    ]]
								}
                            }">Grup</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_grup_toolbar" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    
    
    <div id="dlg_mat" class="easyui-dialog" style="width:850px;height:600px;padding:5px 5px" maximizable="true" modal="true" closed="true">
        <table id="dg_mat" class="easyui-datagrid" title="Mat&egrave;ries" style="width:825px;height:555px"
                data-options="
                    singleSelect: true,
                    url:'./almat/almat_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_mat_toolbar',
                    onClickRow: onClickRow_mat
                ">
            <thead>
                <tr>
                    <!--<th data-options="field:'idalumnes_grup_materia',width:50">ID</th>-->
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
                                        {field:'materia',title:'Materia/UF',width:550},
                                        {field:'nom',title:'Grup',width:180}
                                    ]]
								}
                            }">Mat&egrave;ria/Grup</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_mat_toolbar" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append_mat()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem_mat()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept_mat()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject_mat()">Tancar</a>
    </div>
    
    <div id="dlg_hor" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
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
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.activat=='N'){
						return 'background-color:whitesmoke;color:#CCC;';
					}
				}  
            });  
        });
		 
        function doSearch(){  
			$('#dg').datagrid('load',{  
				cognoms: $('#cognoms').val()  
			});  
		} 
		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},  
			onExpandRow: function(index,row){  
				$('#ddv-'+index).datagrid({  
					url:'./algr/algr_getdetail.php?idalumnes='+row.id_alumne,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'grup(s) de l\'alumne...',  
					height:'auto',
					columns:[[  
						{field:'nom',title:'Grup',width:400},  
					]],
					onResize:function(){  
						$('#dg').datagrid('fixDetailRowHeight',index);  
					},  
					onLoadSuccess:function(){  
						setTimeout(function(){  
							$('#dg').datagrid('fixDetailRowHeight',index);  
						},0);  
					}  
				});  
				$('#dg').datagrid('fixDetailRowHeight',index);  
			}  
		});
   
		function gestionGrups(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){  		
				$('#dlg_grup').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_grup').datagrid('load',{ 
					idalumnes: row.id_alumne  
     			});
            }  
        }
		
		function gestionMaterias(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){  
				$('#dlg_mat').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_mat').datagrid('load',{ 
					idalumnes: row.id_alumne  
     			});
            }  
        }		
        
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){
				url = './algr/algr_see.php?idalumnes='+row.id_alumne+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.Valor);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');		
		    url = './algr/algr_print.php?idalumnes='+row.id_alumne+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
        }
		
		function onClickRow_mat(index){
			if (editIndex != index){
				if (endEditing_mat()){
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
			if ($('#dg_grup').datagrid('validateRow', editIndex)){
			    var row_p = $('#dg').datagrid('getSelected');
				var ed = $('#dg_grup').datagrid('getEditor', {index:editIndex,field:'idgrups'});				
				
				var val = $(ed.target).combogrid('getValue');
				var nom = $(ed.target).combogrid('getText');
												
				$('#dg_grup').datagrid('getRows')[editIndex]['nom'] = nom;
				$('#dg_grup').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './algr/algr_nou.php';
					nou_registre = 0;
				}
				else {
					url = './algr/algr_edita.php?id='+$('#dg_grup').datagrid('getRows')[editIndex]['idgrups'];
				}
				afterEdit(url,
						  row_p.id_alumne,
						  $('#dg_grup').datagrid('getRows')[editIndex]['idgrups']);
			    nou_registre = 0;
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function endEditing_mat(){
			if (editIndex == undefined){return true}			
			if ($('#dg_mat').datagrid('validateRow', editIndex)){
			    var row_p = $('#dg').datagrid('getSelected');
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idgrups_materies'});				
				
				var val = $(ed.target).combogrid('getValue');
				var matgrup = $(ed.target).combogrid('getText');
												
				$('#dg_mat').datagrid('getRows')[editIndex]['matgrup'] = matgrup;
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './almat/almat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './almat/almat_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idalumnes_grup_materia'];
				}
				afterEdit_mat(url,
						      row_p.id_alumne,
						      $('#dg_mat').datagrid('getRows')[editIndex]['idgrups_materies']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function append(){
			if (endEditing()){
				$('#dg_grup').datagrid('appendRow',{});
				nou_registre = 1;
				
				editIndex = $('#dg_grup').datagrid('getRows').length-1;
				$('#dg_grup').datagrid('selectRow', editIndex)
						     .datagrid('beginEdit', editIndex);
			}
		}
		
		function append_mat(){
			if (endEditing_mat()){
				$('#dg_mat').datagrid('appendRow',{});
				nou_registre = 1;
				
				editIndex = $('#dg_mat').datagrid('getRows').length-1;
				$('#dg_mat').datagrid('selectRow', editIndex)
						    .datagrid('beginEdit', editIndex);
			}
			
		}
		
		function accept(){			
			if (endEditing()){
				$('#dg_grup').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_grup').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './algr/algr_nou.php';
					nou_registre = 0;
				}
				else {
					url = './algr/algr_edita.php?id='+row_a.idgrups;
				}

				saveItem(url,row_p,row_a);
			}
		}
		
		function accept_mat(){			
			if (endEditing_mat()){
				$('#dg_mat').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './algr/algr_nou.php';
					nou_registre = 0;
				}
				else {
					url = './algr/algr_edita.php?id='+row_a.idgrups;
				}

				saveItem_mat(url,row_p,row_a);
			}
		}
		
		function reject(){
			$('#dg_grup').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_grup').dialog('close');
		}
		
		function reject_mat(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_mat').dialog('close');
		}
				
		function destroyItem(){  
		    var row   = $('#dg').datagrid('getSelected');
            var row_a = $('#dg_grup').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest grup?',function(r){  
                    if (r){  
                        $.post('./algr/algr_esborra.php',{idalumnes:row.id_alumne,idgrups:row_a.idgrups},function(result){  
						    if (result.success){ 
								$('#dg_grup').datagrid('reload');
								editIndex = undefined;  
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
		
		function destroyItem_mat(){  
		    var row   = $('#dg').datagrid('getSelected');
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta mat&egrave;ria?',function(r){  
                    if (r){  
						$.post('./almat/almat_esborra.php',{id:row_a.idalumnes_grup_materia},function(result){  
                            if (result.success){  
                                $('#dg_mat').datagrid('reload'); 
								editIndex = undefined;     
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
		
		function saveItem(url,row_p,row_a){
			$.post(url,{idalumnes:row_p.id_alumne,idgrups:row_a.idgrups},function(result){  
            if (result.success){  
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
        }
		
		function saveItem_mat(url,row_a,row_p){
			$.post(url,{idalumnes:row_p.id_alumne,idgrups_materies:row_a.idgrups_materies},function(result){  
            if (result.success){  
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
        }
		
		function afterEdit(url,field1,field2){
			$.post(url,{idalumnes:field1,idgrups:field2},function(result){  
            if (result.success){  
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
        }
		
		function afterEdit_mat(url,field1,field2){
			$.post(url,{idalumnes:field1,idgrups_materies:field2},function(result){  
            if (result.success){  
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
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