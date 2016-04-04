	<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Materies per grups" style="height:540px"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers:true,
				toolbar: '#toolbar',
				url: './grma/grma_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
			<tr>
				<!--<th data-options="field:'idgrups',width:80">ID</th>-->
				<th data-options="field:'nom',width:350,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom</th>
                <th data-options="field:'Descripcio',width:550,align:'left',editor:{type:'validatebox',options:{required:false}}">Descripcio</th>
			</tr>
		</thead>
	</table>

	<div id="toolbar" style="padding:5px;height:auto">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionMaterias()">Assignar materies</a>  
        <!--&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionPlantilla()">Definir plantilla</a>-->
        &nbsp;&nbsp;
        Grup:&nbsp;
        <select id="g_pe" class="easyui-combobox" data-options="
					width:350,
                    url:'./grma/grup_getdata.php',
					idField:'idgrups',
                    valueField:'idgrups',
					textField:'nom'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" closed="true" maximized="true" maximizable="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Mat&egrave;ries" 
                data-options="
                    singleSelect: true,
                    rownumbers:true,
                    url:'./grma/grma_getdetail.php',
                    pagination: false,
                    toolbar: '#tb',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                 <th data-options="field:'id_mat_uf_pla',width:750,
						formatter:function(value,row){
							return row.nom;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'id',
                                textField:'nom',
                                url:'./grma/mat_getdata.php',
								required:false
							}
						}">Materia/M&ograve;dul</th>
                 <th data-options="field:'data_inici',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data inici</th>
                 <th data-options="field:'data_fi',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data fi</th>                  
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
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		
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
		
        function doSearch(){  
			$('#dg').datagrid('load',{  
				g_pe: $('#g_pe').combobox('getValue'),
			});  
		} 
		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},  
			onExpandRow: function(index,row){  
				$('#ddv-'+index).datagrid({  
					url:'./grma/grma_getdetail.php?id_grups='+row.idgrups,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'materies del grup...',  
					height:'auto',
					columns:[[  
						{field:'nom',title:'Materia/M&ograve;dul',width:630},
						{field:'data_inici',title:'Inici',width:90},
						{field:'data_fi',title:'Fi',width:90}
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
   
		function gestionMaterias(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.nom);
				$('#dg_mat').datagrid('load',{ 
					id_grups: row.idgrups  
     			});
            }  
        }
		
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){  
				url = './hor/hor_see.php?idgrups='+row.idgrups;
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.nom);
				$('#dlg_hor').dialog('refresh', url);
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
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'id_mat_uf_pla'});
				var nom = $(ed.target).combobox('getText');				
				$('#dg_mat').datagrid('getRows')[editIndex]['nom'] = nom;
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './grma/grma_nou.php';
					nou_registre = 0;
				}
				else {
					url = './grma/grma_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idgrups_materies'];
				}
				afterEdit(url,
						  row_p.idgrups,
						  $('#dg_mat').datagrid('getRows')[editIndex]['id_mat_uf_pla'],
						  $('#dg_mat').datagrid('getRows')[editIndex]['data_inici'],
						  $('#dg_mat').datagrid('getRows')[editIndex]['data_fi']);
				
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
				var row_a = $('#dg_mat').datagrid('getSelected');
				var row_p = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './grma/grma_nou.php';
					nou_registre = 0;
				}
				else {
					url = './grma/grma_edita.php?id='+row_a.idgrups_materies;
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
                $.messager.confirm('Confirmar','Est&aacute;s seguro de que quieres eliminar esta materia?',function(r){  
                    if (r){  
                        $.post('./grma/grma_esborra.php',{id:row_a.idgrups_materies},function(result){  
                            if (result.success){  
                                $('#dg_mat').datagrid('reload');    // reload the user data  
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
	
			$.post(url,{
					id_grups:row_p.idgrups,
					id_mat_uf_pla:row_a.id_mat_uf_pla,
					data_inici:row_a.data_inici,
					data_fi:row_a.data_fi},function(result){  
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
		
		function afterEdit(url,field1,field2,field3,field4){		
	
			$.post(url,{id_grups:field1,id_mat_uf_pla:field2,data_inici:field3,data_fi:field4},function(result){  
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