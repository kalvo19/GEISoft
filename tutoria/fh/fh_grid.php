   <script type="text/javascript">
		$.extend($.fn.datagrid.defaults.editors, {
			timespinner: {
				init: function(container, options){
					var input = $('<input class="easyui-timespinner">').appendTo(container); 
					return input;
				},
				destroy: function(target){
					$(target).timespinner('destroy');
				},
				getValue: function(target){
					return $(target).timespinner('getValue');
				},
				setValue: function(target, value){
					$(target).timespinner('setValue', value);
				},
				resize: function(target, width){
					$(target).timespinner('resize',width);
				}
			}
		}); 
	</script>
    
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Franjes Hor&agrave;ries" style="height:540px;"
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
                <!--<th field="idfranges_horaries" width="70" sortable="true">ID</th>-->
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
                        
                <th data-options="field:'hora_inici',width:100,
                 editor:{type:'validatebox',options:{required:true}}
                ">Hora inici</th>
                
                <th data-options="field:'hora_fi',width:100,
                 editor:{type:'validatebox',options:{required:true}}
                ">Hora fi</th>
                
                <th data-options="field:'esbarjo',width:60,align:'center',
                formatter:function(value,row){
                             if (value==0) {
                                valor = '';
                             }
                             else {
                                valor = 'S';
                             }
                             return valor;
                       }, 
                editor:{type:'checkbox',options:{on:'S',off:''}}
                ">Esbarjo</th>
                
                <th data-options="field:'activada',width:60,align:'center',
                formatter:function(value,row){
                             if (value==0) {
                                valor = '';
                             }
                             else {
                                valor = 'S';
                             }
                             return valor;
                       },
                editor:{type:'checkbox',options:{on:'S',off:''}}
                ">Activada</th>
               
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar" style="height:60px;">
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="append_fh()">Nou</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:destroyItem()">Esborrar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>
    <br />
    &nbsp;Torn:&nbsp;
        <select id="s_torn" class="easyui-combobox" data-options="
					width:250,
                    url:'./fh/torn_getdata.php',
					idField:'idtorn',
                    valueField:'idtorn',
					textField:'nom_torn',
					panelHeight:'auto'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">Cercar</a>
        
    <a id="dies_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" disabled="true" onclick="gestionDias()">Assignar Dies</a>   
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:450px;height:350px;padding:5px 5px" modal="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Dies setmana" style="width:425px;height:305px"
                data-options="
                    singleSelect: true,
                    url:'./fh/fh_getdetail.php',
                    pagination: false,
                    rownumbers: true,
                    toolbar: '#tb',
                    onClickRow: onClickRow_mat
                ">
            <thead>
                <tr>
                    <!--<th data-options="field:'id_dies_franges',width:50">ID</th>-->
                    <th data-options="field:'iddies_setmana',width:250,
                            formatter:function(value,row){
								return row.dies_setmana;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'iddies_setmana',
									textField:'dies_setmana',
									url:'./fh/dies_getdata.php',
									required:false
								}
                            }">Dia</th>
                </tr>
            </thead>
        </table>
    </div> 
    
    <div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    
    <script type="text/javascript">  
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
		$(function(){  
            $('#dg').edatagrid({  
                url: './fh/fh_getdata.php',  
                saveUrl: './fh/fh_nou.php',  
                updateUrl: './fh/fh_edita.php',  
                destroyUrl: './fh/fh_esborra.php'  
            });  
		
        });
				 		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},  
			onExpandRow: function(index,row){  
				$('#ddv-'+index).datagrid({  
					url:'./fh/fh_getdetail.php?id='+row.idfranges_horaries,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'dies...',  
					height:'auto',
					columns:[[  
						{field:'dies_setmana',title:'Dia',width:200},  
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
   		
		function doSearch(){
			$('#dg').datagrid('load',{  
				s_torn: $('#s_torn').combobox('getValue') 
			});
		}
		
		function gestionDias(){  
			var row = $('#dg').datagrid('getSelected');
			
            if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.hora_inici+"-"+row.hora_fi);
				$('#dg_mat').datagrid('load',{ 
					id: row.idfranges_horaries  
     			});
            }  
        }		
    	
		function onAfterEdit(rowIndex, rowData, changes){
			$('#dg').datagrid('reload');
		}
		
		function onClickRow(index){
			$('#dies_button').linkbutton('enable');	
		}
		
		function onClickRow_mat(index){
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
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'iddies_setmana'});
				var dies_setmana = $(ed.target).combobox('getText');				
				$('#dg_mat').datagrid('getRows')[editIndex]['dies_setmana'] = dies_setmana;
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './fh/df_nou.php';
					nou_registre = 0;
				}
				else {
					url = './fh/df_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['id_dies_franges'];
				}
				
				afterEdit(url,
						  row_p.idfranges_horaries,
						  $('#dg_mat').datagrid('getRows')[editIndex]['iddies_setmana']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function append_fh(){
			javascript:$('#dg').edatagrid('addRow');
			$('#dies_button').linkbutton('disable');
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
					url = './fh/df_nou.php';
					nou_registre = 0;
				}
				else {
					url = './fh/df_edita.php?id='+row_a.id_dies_franges;
				}
				saveItem(url,row_a,row_p);
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
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta franja hor&agrave;ria?',function(r){  
                    if (r){  
                        $.post('./fh/df_esborra.php',{id:row.idfranges_horaries},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');    // reload the user data  
								//$('#dg').datagrid('reload');
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
		
		function saveItem(url,row_a,row_p){ 			
	
			$.post(url,{idfranges_horaries:row_p.idfranges_horaries,iddies_setmana:row_a.iddies_setmana},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');    // reload the user data 
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
		  
        }
	
	</script>