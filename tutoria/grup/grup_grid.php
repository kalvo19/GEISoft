	<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Grups" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers:true,
				toolbar: '#tb',
				url: './grup/grup_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
			<tr>
				<!--<th data-options="field:'idgrups',width:80">ID</th>-->
				<th data-options="field:'nom',width:350,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom</th>
                <th data-options="field:'idtorn',width:200,
						formatter:function(value,row){
							return row.nom_torn;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idtorn',
                                textField:'nom_torn',
                                url:'./fh/torn_getdata.php',
								required:true
							}
						}
                ">Torn</th>
                <th data-options="field:'Descripcio',width:450,align:'left',editor:{type:'validatebox',options:{required:false}}">Descripcio</th>
			</tr>
		</thead>
	</table>

	<div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
        <br />
        &nbsp;Torn:&nbsp;
        <select id="s_torn" class="easyui-combobox" data-options="
					width:320,
                    url:'./fh/torn_getdata.php',
					idField:'idtorn',
                    valueField:'idtorn',
					textField:'nom_torn',
					panelHeight:'auto'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
	</div>
	</div>
    
	<script type="text/javascript">
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
		function doSearch(){
			$('#dg').datagrid('load',{  
				s_torn: $('#s_torn').combobox('getValue') 
			});
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){		
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './grup/grup_nou.php';
					nou_registre = 0;
				}
				else {
					url = './grup/grup_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idgrups'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['idtorn'],
						  $('#dg').datagrid('getRows')[editIndex]['nom'],
						  $('#dg').datagrid('getRows')[editIndex]['Descripcio']);
				
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
		}
		
		
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg').datagrid('selectRow', index)
							.datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('#dg').datagrid('selectRow', editIndex);
				}
			}
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
		
		function accept(){
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				
				var row = $('#dg').datagrid('getSelected');
				
				if (nou_registre) { 
					url = './grup/grup_nou.php';
					nou_registre = 0;
				}
				else {
					url = './grup/grup_edita.php?id='+row.idgrups;
				}
				saveItem(url,row);
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		function getChanges(){
			var rows = $('#dg').datagrid('getChanges');
			alert(rows.length+' rows are changed!');
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s seguro de que quieres eliminar este grupo?',function(r){  
                    if (r){  
                        $.post('./grup/grup_esborra.php',{id:row.idgrups},function(result){  
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
		
		function saveItem(url,row){ 			
	
			$.post(url,{idtorn:row.idtorn,nom:row.nom,Descripcio:row.Descripcio},function(result){  
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
		
		function afterEdit(url,field1,field2,field3){		
	
			$.post(url,{idtorn:field1,nom:field2,Descripcio:field3},function(result){  
            if (result.success){  
               //$('#dg').datagrid('reload');    // reload the user data  
            } else {  
               $.messager.show({   // show error message  
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }	
		
	</script>
