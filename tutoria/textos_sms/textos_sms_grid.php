    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Textos SMS plantilles" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers:true,
				toolbar: '#toolbar',
				url: './textos_sms/textos_sms_getdata.php',
				onClickRow: onClickRow
			">
        <thead>  
            <tr>
                <th data-options="field:'nom',width:170,editor:{type:'validatebox',validType:'length[0,45]'}">Nom</th>
                <th data-options="field:'descripcio',width:550,editor:{type:'textarea',validType:'length[0,160]'}">Descripci&oacute;</th>
            </tr>
        </thead>
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
    </div>  
    </div>
     
    <script type="text/javascript">  
        var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
	   function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) {
					url = './textos_sms/textos_sms_nou.php';
					nou_registre = 0;
				}
				else {
					url = './textos_sms/textos_sms_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idtextos'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['nom'],
						  $('#dg').datagrid('getRows')[editIndex]['descripcio']);
				
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
					url = './textos_sms/textos_sms_nou.php';
					nou_registre = 0;
				}
				else {
					url = './textos_sms/textos_sms_edita.php?id='+row.idtextos;
				}

				saveItem(url,row);
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest texte?',function(r){  
                    if (r){  
                        $.post('./textos_sms/textos_sms_esborra.php',{id:row.idtextos},function(result){  
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
	
			$.post(url,{
					nom:row.nom,
					descripcio:row.descripcio},function(result){  
            if (result.success){  
			   $('#dg').datagrid('reload'); 
			   editIndex = undefined;
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2){		
	
			$.post(url,{nom:field1,descripcio:field2},function(result){  
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
            margin-bottom:1px;  
        }  
        .fitem label{  
            display: inline-table;
            width:120px;  
        }  
    </style>