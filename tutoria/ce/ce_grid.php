   <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
   <table id="dg" title="Cursos escolars" style="height:540px;"  
            idField="idperiodes_escolars"  
            fitColumns="false" 
            data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
                onClickRow: onClickRow
			">  
        <thead>  
            <tr>  
                <!--<th field="idperiodes_escolars" width="50">ID</th>-->
                <th data-options="field:'Nom',width:150" editor="{type:'validatebox',options:{required:true}}">Nom</th>
                <th data-options="field:'Descripcio',width:250" editor="{type:'validatebox',options:{required:true}}">Descripcio</th>
                <th data-options="field:'data_inici',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data inici</th>
                <th data-options="field:'data_fi',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data fi</th>
                <th data-options="field:'actual',width:60,align:'center',
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
                ">Actual</th>
            </tr>
        </thead> 
    </table>
  
    <div id="toolbar">  
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Nou</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Esborra</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>
    &nbsp;
    <a id="festius_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" disabled="true" onclick="gestionFestius()">Assignar Festius</a>
    </div>   
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:450px;height:350px;padding:5px 5px" modal="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Dies festius" style="width:425px;height:305px"
                data-options="
                    singleSelect: true,
                    url:'./ce/ce_getdetail.php',
                    pagination: false,
                    rownumbers: true,
                    toolbar: '#tb',
                    onClickRow: onClickRow_mat
                ">
            <thead>
                <tr>
                    <th data-options="field:'festiu',width:100,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data</th>
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
                url: './ce/ce_getdata.php',  
                saveUrl: './ce/ce_nou.php',  
                updateUrl: './ce/ce_edita.php',  
                destroyUrl: './ce/ce_esborra.php'  
            });  
			
			$('#dg').datagrid({
				rowStyler:function(index,row){
					if (row.actual=='S'){
						return 'background-color:whitesmoke;color:blue;font-weight:bold;';
					}
					if (row.actual==0){
					    row.actual = '';
						return '';
					}
				}
			});
        });
		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},  
			onExpandRow: function(index,row){  
				$('#ddv-'+index).datagrid({  
					url:'./ce/ce_getdetail.php?id='+row.idperiodes_escolars,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'periodes...',  
					height:'auto',
					columns:[[  
						{field:'festiu',title:'Festiu',width:200},  
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
		
		function gestionFestius(){  
			var row = $('#dg').datagrid('getSelected');
			
            if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.Nom);
				$('#dg_mat').datagrid('load',{ 
					id: row.idperiodes_escolars  
     			});
            }  
        }
				
		function onClickRow(index){
			$('#festius_button').linkbutton('enable');	
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
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'id_festiu'});
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './ce/festiu_nou.php';
					nou_registre = 0;
				}
				else {
					url = './ce/festiu_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['id_festiu'];
				}
				
				afterEdit(url,
						  row_p.idperiodes_escolars,
						  $('#dg_mat').datagrid('getRows')[editIndex]['festiu']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function accept(){		
			if (endEditing()){				
				$('#dg_mat').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './ce/festiu_nou.php';
					nou_registre = 0;
				}
				else {
					url = './ce/festiu_edita.php?id='+row_a.id_festiu;
				}
				saveItem(url,row_a,row_p);
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
		
		function reject(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function destroyItem(){  
            var row = $('#dg_mat').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest festiu?',function(r){  
                    if (r){  
                        $.post('./ce/festiu_esborra.php',{id:row.id_festiu},function(result){  
                            if (result.success){  
                                $('#dg_mat').datagrid('reload');      
								//$('#dg').datagrid('reload');
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
	
			$.post(url,{id_periode:row_p.idperiodes_escolars,festiu:row_a.festiu},function(result){  
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
	
			$.post(url,{id_periode:field1,festiu:field2},function(result){  
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
		
		function myformatter(date){  
            var y = date.getFullYear();  
            var m = date.getMonth()+1;  
            var d = date.getDate();  
            return (d<10?('0'+d):d)+'-'+(m<10?('0'+m):m)+'-'+y;
        }
		
        function myparser(s){  
            if (!s) return new Date();  
            var ss = (s.split('-'));  
            var y = parseInt(ss[0],10);  
            var m = parseInt(ss[1],10);  
            var d = parseInt(ss[2],10);  
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)){  
                return new Date(d,m-1,y);  
            } else {  
                return new Date();  
            }  
        }  

    </script>