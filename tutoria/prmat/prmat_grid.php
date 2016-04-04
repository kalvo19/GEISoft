<?php
	session_start();
	include_once('../bbdd/connect.php');
	include_once('../func/constants.php');
	include_once('../func/generic.php');
	mysql_query("SET NAMES 'utf8'");
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px" 
        url="./prmat/prmat_getdata.php"  
        title="Materies per professors" toolbar="#toolbar" fitColumns="false"
        rownumbers="true" pagination="true" sortName="cp.Valor" sortOrder="asc" singleSelect="true">  
        <thead>  
            <tr>
                <th data-options="field:'codi_professor',width:90">ID</th>
                <th field="Valor" width="650" sortable="true">Nom</th>
            </tr>  
        </thead>  
    </table>
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionMaterias()">Assignar Materies</a>  
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-help" plain="true" onclick="verHorario()">Veure horari</a>  
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="definirHorario()">Definir horari</a>  
        <br />
        &nbsp;Cognoms: <input id="cognoms" class="easyui-validatebox" style="width:180px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
    </div>
    
    <div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" maximized="true" maximizable="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Mat&egrave;ries" style="width:880px;height:545px"
                data-options="
                    singleSelect: true,
                    url:'./prmat/prmat_getdetail.php',
                    pagination: false,
                    rownumbers: true,
                    toolbar: '#tb',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <!--<th data-options="field:'idprof_grup_materia',width:50">ID</th>-->
                    <th data-options="field:'idagrups_materies',width:850,
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
									url:'./prmat/pe_mat_getdata.php',
									required:false,
                                    columns:[[
                                        {field:'nom_materia',title:'Materia',width:600},
                                        {field:'nom',title:'Grup',width:220}
                                    ]]
								}
                            }">Materia/Grup</th>
                </tr>
            </thead>
        </table>
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
    
    <div id="dlg_m_hor" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" closed="true" maximized="true" maximizable="true">
        <table id="dg_m_hor" class="easyui-datagrid" title="Horari" style="width:1025px;height:645px"
                data-options="
                    singleSelect: true,
                    rownumbers:true,
                    url:'./prmat/prmat_get_horari.php',
                    pagination: false,
                    toolbar: '#tb_m_hor',
                    onClickRow: onClickRow_m_hor
                ">
            <thead>
                <tr>
                    <!--<th data-options="field:'idunitats_classe',width:50">ID</th>-->
                    <th data-options="field:'id_dies_franges',width:280,
                            formatter:function(value,row){
								return row.dia_hora;
							},
							editor:{
								type:'combobox',
								options:{
									idField: 'id_dies_franges', 
                                    valueField:'id_dies_franges',
									textField:'dia_hora',
									url:'./hor/df_getdata.php',
									required:true
								}
                            }">Dia/Hora</th>
                            
                    <th data-options="field:'idgrups_materies',width:580,
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
									url:'./prmat/pe_mat_getdata.php',
									required:false,
                                    columns:[[
                                        {field:'nom_materia',title:'Materia',width:350},
                                        {field:'nom',title:'Grup',width:230}
                                    ]]
								}
                            }">Materia/Grup</th>
                    
                    <th data-options="field:'idespais_centre',width:120,
                            formatter:function(value,row){
								return row.descripcio;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'idespais_centre',
									textField:'descripcio',
									url:'./hor/ec_getdata.php',
									required:true
								}
                            }
                           ">Espai centre</th>                  
                </tr>
            </thead>
        </table>
    </div> 
    
    <div id="tb_m_hor" style="height:auto">
		<a id="btnAppend_m_hor" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append_m_hor()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem_m_hor()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept_m_hor()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject_m_hor()">Tancar</a>
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
       
    <script type="text/javascript">  
        var url;
		var editIndex       = undefined;
		var editIndex_m_hor = undefined;
		var url;
		var nou_registre = 0;
		var nou_registre_m_hor = 0;
		
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
					url:'./prmat/prmat_getdetail.php?idprofessors='+row.id_professor,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'materies del professor...',  
					height:'auto',
					columns:[[  
						{field:'matgrup',title:'Materia/Grup',width:600},  
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
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.activat=='N'){
						return 'background-color:whitesmoke;color:#CCC;';
					}
				}  
            });  
        });
   
		function gestionMaterias(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_mat').datagrid('load',{ 
					idprofessors: row.id_professor  
     			});
            }  
        }
		
		function definirHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				$('#dlg_m_hor').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_m_hor').datagrid('load',{ 
					idprofessors: row.id_professor 
     			});
            }  
        }
		
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				url = './prmat/prmat_see.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.Valor);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');
		
		    url = './prmat/prmat_print.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
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
		
		function onClickRow_m_hor(index){
			if (editIndex_m_hor != index){
				if (endEditing_m_hor()){
					$('#dg_m_hor').datagrid('selectRow', index)
							      .datagrid('beginEdit', index);
					editIndex_m_hor = index;
				} else {
					$('dg_m_hor').datagrid('selectRow', editIndex_m_hor);
				}
			}
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg_mat').datagrid('validateRow', editIndex)){
			    var row_p = $('#dg').datagrid('getSelected');
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idagrups_materies'});				
				
				var val = $(ed.target).combogrid('getValue');
				var matgrup = $(ed.target).combogrid('getText');
												
				$('#dg_mat').datagrid('getRows')[editIndex]['matgrup'] = matgrup;
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './prmat/prmat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prmat/prmat_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idprof_grup_materia'];
				}
				afterEdit(url,
						  row_p.id_professor,
						  $('#dg_mat').datagrid('getRows')[editIndex]['idagrups_materies']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function endEditing_m_hor(){
			if (editIndex_m_hor == undefined){return true}			
			if ($('#dg_m_hor').datagrid('validateRow', editIndex_m_hor)){
				var row_p = $('#dg').datagrid('getSelected');
				
				var ed = $('#dg_m_hor').datagrid('getEditor', {index:editIndex_m_hor,field:'id_dies_franges'});
				var dia_hora = $(ed.target).combobox('getText');
				
				var ed = $('#dg_m_hor').datagrid('getEditor', {index:editIndex_m_hor,field:'idgrups_materies'});
				var matgrup = $(ed.target).combogrid('getText');
				
				var ed = $('#dg_m_hor').datagrid('getEditor', {index:editIndex_m_hor,field:'idespais_centre'});
				var descripcio = $(ed.target).combobox('getText');
				
				$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['dia_hora']    = dia_hora;
				$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['matgrup']     = matgrup;
				$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['descripcio']  = descripcio;
									
				$('#dg_m_hor').datagrid('endEdit', editIndex_m_hor);
				
				if (nou_registre_m_hor) {
					url = './prmat/hor_nou.php';
					nou_registre_m_hor = 0;
				}
				else {
					url = './hor/hor_edita.php?id='+$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['idunitats_classe'];
				}
				afterEdit_m_hor(url,
								row_p.id_professor,
						  		$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['id_dies_franges'],
						  		$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['idgrups_materies'],
						  		$('#dg_m_hor').datagrid('getRows')[editIndex_m_hor]['idespais_centre']);
				
				editIndex_m_hor = undefined;
				
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
		
		function append_m_hor(){			
			if (endEditing_m_hor()){
				$('#dg_m_hor').datagrid('appendRow',{});
				nou_registre_m_hor = 1;
				editIndex_m_hor = $('#dg_m_hor').datagrid('getRows').length-1;
				$('#dg_m_hor').datagrid('selectRow', editIndex_m_hor)
						      .datagrid('beginEdit', editIndex_m_hor);
			}

		}
		
		function accept(){			
			if (endEditing()){
				$('#dg_mat').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './prmat/prmat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prmat/prmat_edita.php?id='+row_a.idprof_grup_materia;
				}

				saveItem(url,row_a,row_p);
			}
		}
		
		function accept_m_hor(){			
			if (endEditing_m_hor()){
				$('#dg_m_hor').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_m_hor').datagrid('getSelected');
										
				if (nou_registre_m_hor) { 
					url = './hor/hor_nou.php';
					nou_registre_m_hor = 0;
				}
				else {
					url = './hor/hor_edita.php?id='+row_a.idunitats_classe;
				}

				saveItem_m_hor(url,row_a,row_p);
			}
		}
		
		function reject(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function reject_m_hor(){
			$('#dg_m_hor').datagrid('rejectChanges');
			editIndex_m_hor = undefined;
			$('#dlg_m_hor').dialog('close');
		}
		
		function getChanges(){
			var rows = $('#dg_mat').datagrid('getChanges');
			alert(rows.length+' rows are changed!');
		}
		
		function destroyItem(){  
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s seguro de que quieres eliminar esta materia?',function(r){  
                    if (r){  
                        $.post('./prmat/prmat_esborra.php',{id:row_a.idprof_grup_materia},function(result){  
                            if (result.success){
                                $('#dg_mat').datagrid('deleteRow', editIndex);
                            } else {  
                                $.messager.show({   // show error message  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json');  
                    }  
                });  
            }  
        }
		
		function destroyItem_m_hor(){  
            var row_p = $('#dg').datagrid('getSelected');
			var row_a = $('#dg_m_hor').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta asignacio horaria?',function(r){  
                    if (r){  
                        $.post('./prmat/hor_esborra.php',
							{idprofessors:row_p.id_professor,idgrups_materies:row_a.idgrups_materies,id:row_a.idunitats_classe},function(result){  
                            if (result.success){ 
							    editIndex_m_hor = undefined; 
                                $('#dg_m_hor').datagrid('reload');
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
		
		function saveItem(url,row_a,row_p){ 			
	
			$.post(url,{idprofessors:row_p.id_professor,idagrups_materies:row_a.idagrups_materies},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');    // reload the user data 
			   //$('#dg').datagrid('reload'); 
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg
               });  
               }  
             },'json');
		  
        }
		
		function saveItem_m_hor(url,row_a,row_p){ 			
	
			$.post(url,{
					idprofessors:row_p.id_professor,
					id_dies_franges:row_a.id_dies_franges,
					idespais_centre:row_a.idespais_centre,
					idgrups_materies:row_a.idgrups_materies},function(result){  
            if (result.success){  
               //$('#dg_m_hor').datagrid('reload'); 
			   //$('#dg').datagrid('reload'); 
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2){		
	
			$.post(url,{idprofessors:field1,idagrups_materies:field2},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');
			   //$('#dg').datagrid('reload');    
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit_m_hor(url,field1,field2,field3,field4){		
	
			$.post(url,{idprofessors:field1,id_dies_franges:field2,idgrups_materies:field3,idespais_centre:field4},function(result){  
            if (result.success){  
               //$('#dg_m_hor').datagrid('reload');
			   //$('#dg').datagrid('reload');    
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