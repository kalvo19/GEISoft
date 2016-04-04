<?php
  session_start();
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" style="height:540px;" 
        url="./prper/prper_getdata.php"  
        title="Hores de perman&egrave;ncies professors" toolbar="#toolbar" fitColumns="false"
        rownumbers="true" pagination="true" sortName="cp.Valor" sortOrder="asc" singleSelect="true">  
        <thead>  
            <tr>
                <th data-options="field:'codi_professor',width:90">ID</th>
                <th field="Valor" width="450" sortable="true">Nom</th>
            </tr>  
        </thead>  
    </table>
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="afegirHora()">Afegir hora</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="verHorario()">Veure horari</a> 
        &nbsp;&nbsp;
        Cognoms: <input id="cognoms" class="easyui-validatebox" style="width:180px">
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
    </div>
    
    <div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:720px;height:550px;padding:5px 5px" modal="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Hores" style="width:695px;height:495px"
                data-options="
                    singleSelect: true,
                    url:'./prper/prper_getdetail.php',
                    pagination: false,
                    rownumbers: true,
                    toolbar: '#tb',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <th data-options="field:'id_dies_franges',width:340,
                            formatter:function(value,row){
								return row.dia_hora;
							},
							editor:{
								type:'combobox',
								options:{
									idField: 'id_dies_franges', 
                                    valueField:'id_dies_franges',
									textField:'dia_hora',
									url:'./prper/df_getdata.php',
									required:true
								}
                            }">Dia/Hora</th>
                    <th data-options="field:'idespais_centre',width:240,
                            formatter:function(value,row){
								return row.descripcio;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'idespais_centre',
									textField:'descripcio',
									url:'./prper/ec_getdata.php',
									required:true
								}
                            }
                           ">Espai centre</th> 
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
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
       
    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
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
					url:'./prper/prper_getdetail.php?idprofessors='+row.id_professor,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'hores permanencies del professor...',  
					height:'auto',
					columns:[[  
						{field:'dia_hora',title:'Dia/Hora',width:340}, 
						{field:'descripcio',title:'Espai Centre',width:140}, 
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
   
		function afegirHora(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.Valor);
				$('#dg_mat').datagrid('load',{ 
					idprofessors: row.id_professor  
     			});
            }  
        }
		
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){ 
				url = './prmat/prmat_see.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
				$('#dlg_hor').dialog('refresh', url);
			}
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){
		    	url = './prmat/prmat_print.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    	$('#fitxer_pdf').attr('src', url);
			}
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
				
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'id_dies_franges'});
				var dia_hora = $(ed.target).combobox('getText');
				
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idespais_centre'});
				var descripcio = $(ed.target).combobox('getText');
				
				$('#dg_mat').datagrid('getRows')[editIndex]['dia_hora'] = dia_hora;	
				$('#dg_mat').datagrid('getRows')[editIndex]['descripcio']  = descripcio;
												
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './prper/prper_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prper/prper_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idprofpermanencia'];
				}
				afterEdit(url,
						  row_p.id_professor,
						  $('#dg_mat').datagrid('getRows')[editIndex]['id_dies_franges'],
						  $('#dg_mat').datagrid('getRows')[editIndex]['idespais_centre']);
				
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
					url = './prper/prper_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prper/prper_edita.php?id='+row_a.idprofpermanencia;
				}

				saveItem(url,row_a,row_p);
			}
		}
		
		function reject(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
			
		function destroyItem(){  
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar?',function(r){  
                    if (r){  
                        $.post('./prper/prper_esborra.php',{id:row_a.idprofpermanencia},function(result){  
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
		
		function saveItem(url,row_a,row_p){ 			
	
			$.post(url,{
					idprofessors:row_a.id_professor,
					idespais_centre:row_a.idespais_centre,
					id_dies_franges:row_a.id_dies_franges},function(result){  
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
		
		function afterEdit(url,field1,field2,field3){		
	
			$.post(url,{idprofessors:field1,id_dies_franges:field2,idespais_centre:field3},function(result){  
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