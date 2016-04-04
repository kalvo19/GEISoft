    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Tipus seguiments" style="height:540px"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
                onClickRow: onClickRow,
                onAfterEdit: onAfterEdit 
			">
        <thead>  
            <tr>  
                <th data-options="field:'tipus_incident',width:500,editor:{type:'validatebox',options:{required:true}}
                ">Nom</th>
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar">  
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="append_fh()">Nou</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" disabled="true" plain="true" onclick="javascript:destroyItem()">Esborrar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>
    </div>   
    </div>
    
    <script type="text/javascript">  
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
		$(function(){  
            $('#dg').edatagrid({  
                url: './incidents_tipus/incidents_tipus_getdata.php',  
                saveUrl: './incidents_tipus/incidents_tipus_nou.php',  
                updateUrl: './incidents_tipus/incidents_tipus_edita.php',  
                destroyUrl: './incidents_tipus/incidents_tipus_esborra.php'  
            });  
		
        });
				 		    	
		function onAfterEdit(rowIndex, rowData, changes){
			$('#dg').datagrid('reload');
		}
		
		function onClickRow(index){
		}
				
		function append(){
			if (endEditing()){
				$('#dg').datagrid('appendRow',{});
				nou_registre = 1;
				editIndex = $('#dg').datagrid('getRows').length-1;
				$('#dg').datagrid('selectRow', editIndex)
						.datagrid('beginEdit', editIndex);
			}
			
		}
		
		function append_fh(){
			javascript:$('#dg').edatagrid('addRow');
			$('#carrecs_button').linkbutton('disable');
		}
		
		function accept(){		
			if (endEditing()){				
				$('#dg').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './incidents_tipus/incidents_tipus_nou.php';
					nou_registre = 0;
				}
				else {
					url = './incidents_tipus/incidents_tipus_edita.php?id='+row_p.idtipus_incident;
				}
				saveItem(url,row_p);
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
				
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest tipus d\'incident?',function(r){  
                    if (r){  
                        $.post('./incidents_tipus/incidents_tipus_esborra.php',{id:row.idtipus_incident},function(result){  
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
		
		/*function saveItem(url,row_a,row_p){ 			
	
			$.post(url,{idfranges_horaries:row_p.idfranges_horaries,iddies_setmana:row_a.iddies_setmana},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');    
			   //$('#dg').datagrid('reload'); 
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2){		
	
			$.post(url,{idfranges_horaries:field1,iddies_setmana:field2},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');
			   //$('#dg').datagrid('reload');    
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }*/
	
	</script>