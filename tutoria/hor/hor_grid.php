<?php
  session_start();
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Horaris per grups" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers:true,
				toolbar: '#toolbar',
				url: './hor/hor_getdata.php',
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
        <br />
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="gestionHorario()">Definir horari</a>  
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="verHorario()">Veure horari</a>      
    </div>
    
    <div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" closed="true" maximized="true" maximizable="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Horari" style="width:1025px;height:645px"
                data-options="
                    singleSelect: true,
                    rownumbers:true,
                    url:'./hor/hor_getdetail.php',
                    pagination: false,
                    toolbar: '#tb',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <!--<th data-options="field:'idunitats_classe',width:50">ID</th>-->
                    <th data-options="field:'id_dies_franges',width:390,
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
                            
                    <th data-options="field:'idgrups_materies',width:440,
                            formatter:function(value,row){
                                return row.nom_materia;
							},
							editor:{
								type:'combobox',
								options:{
                                	valueField:'idgrups_materies',
                                	textField:'nom_materia',
                                   	url:'./hor/mat_getdata.php',
									required:true
								},                        
                            }">Materia</th>

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
					url:'./hor/hor_getdetail.php?id_grups='+row.idgrups,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'horari del grup...',  
					height:'auto',
					columns:[[  
						{field:'dia_hora',title:'Dia/Hora',width:240},  
						{field:'nom_materia',title:'Materia/M&ograve;dul',width:540},
						{field:'descripcio',title:'Espai Centre',width:240},  
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
   
		function gestionHorario(){  
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
				url = './hor/hor_see.php?idgrups='+row.idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.nom);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');
		
		    url = './hor/hor_print.php?idgrups='+row.idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
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
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg_mat').datagrid('validateRow', editIndex)){
				
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'id_dies_franges'});
				var dia_hora = $(ed.target).combobox('getText');
				
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idgrups_materies'});
				var nom_materia = $(ed.target).combobox('getText');
				
				var ed = $('#dg_mat').datagrid('getEditor', {index:editIndex,field:'idespais_centre'});
				var descripcio = $(ed.target).combobox('getText');
				
				$('#dg_mat').datagrid('getRows')[editIndex]['dia_hora']    = dia_hora;
				$('#dg_mat').datagrid('getRows')[editIndex]['nom_materia'] = nom_materia;
				$('#dg_mat').datagrid('getRows')[editIndex]['descripcio']  = descripcio;
									
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre) {
					url = './hor/hor_nou.php';
					nou_registre = 0;
				}
				else {
					url = './hor/hor_edita.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idunitats_classe'];
				}
				afterEdit(url,
						  $('#dg_mat').datagrid('getRows')[editIndex]['id_dies_franges'],
						  $('#dg_mat').datagrid('getRows')[editIndex]['idgrups_materies'],
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
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './hor/hor_nou.php';
					nou_registre = 0;
				}
				else {
					url = './hor/hor_edita.php?id='+row_a.idunitats_classe;
				}

				saveItem(url,row_a);
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
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta asignacio horaria?',function(r){  
                    if (r){  
                        $.post('./hor/hor_esborra.php',{id:row_a.idunitats_classe},function(result){  
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
		
		function saveItem(url,row_a){ 			
	
			$.post(url,{
					id_dies_franges:row_a.id_dies_franges,
					idespais_centre:row_a.idespais_centre,
					idgrups_materies:row_a.idgrups_materies},function(result){  
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
	
			$.post(url,{id_dies_franges:field1,idgrups_materies:field2,idespais_centre:field3},function(result){  
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