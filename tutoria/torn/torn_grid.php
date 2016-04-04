<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $idprofessors  = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0;
   $fechaSegundos = time();
   $strNoCache    = "?nocache=$fechaSegundos";
?> 
	<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Torns" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './torn/torn_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
            <tr>
            	<!--<th field="idtorn" width="120" sortable="true">ID</th>-->
                <th data-options="field:'nom_torn',width:400" editor="{type:'validatebox',options:{required:true}}">Nom</th>
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar canvis</a>
    </div>
    </div>
    
    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
   		var nou_registre = 0;
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.activat=='S'){
						return 'background-color:whitesmoke;color:#009a49;font-weight:bold;';
					}
					if (row.automatricula=='S'){
						return 'background-color:#fff;color:#562e18;';
					}	
				}  
            });  
        });
				
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
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './torn/torn_nou.php';
					nou_registre = 0;
				}
				else {
					url = './torn/torn_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idtorn'];
				}
				
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['nom_torn']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
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
				var row_p = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './torn/torn_nou.php';
					nou_registre = 0;
				}
				else {
					url = './torn/torn_edita.php?id='+row_p.idtorn;
				}

				saveItem(url,row_p);
			}
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest torn?',function(r){  
                    if (r){  
                        $.post('./torn/torn_esborra.php',{id:row.idtorn},function(result){  
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
				
		function saveItem(url,row_p){ 			
	
			$.post(url,{nom_torn:row_p.nom_torn},function(result){  
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
		
		function afterEdit(url,field1){		
	
			$.post(url,{nom_torn:field1},function(result){  
            if (result.success){  
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