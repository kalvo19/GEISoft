<?php
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");   
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">   
    <table id="dg" class="easyui-datagrid" title="Matriculacions mat&egrave;ries alumnes" style="width:auto;height:auto"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './hormod/hormod_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th field="nom" width="670" sortable="true">Mat&egrave;ria / Unitat formativa</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Grup&nbsp;
        <select id="grups" class="easyui-combogrid" style="width:400px" data-options="
            panelWidth: 400,
            idField: 'idgrups',
            textField: 'nom',
            url: url,
            method: 'get',
            columns: [[
                {field:'nom',title:'Grup',width:370}
            ]],
            fitColumns: true
        ">
        </select>
        &nbsp;
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;&nbsp;&nbsp;
        <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="alumnesMatriculats()">Alumnes matriculats amb aquesta mat&egrave;ria</a>
        &nbsp;&nbsp;&nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-tip',plain:true,disabled:false" onclick="verHorari()">Veure horari grup</a>
    </div>
	</div>
    
	<div id="dlg_ver" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" buttons="#dlg_ver-toolbar">  
	</div>
        
	<div id="dlg_ver-toolbar">
    	 <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_ver').dialog('refresh')">Recarregar</a>
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar()">Tancar</a>  
	</div>
	
    <div id="dlg_al" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" closed="true">
        <table id="dg_al" class="easyui-datagrid" title="" style="width:850px;height:555px"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'./almat_tree/almat_tree_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_al_toolbar',
                    onClickRow: onClickRow_al
                ">
            <thead>
                <tr>
                    <th data-options="field:'ck1',checkbox:true"></th>
                    <th field="Valor" width="500">Alumne</th>
                    <!--<th data-options="field:'idespais_centre',width:500,
                            formatter:function(value,row){
								return row.descripcio;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'idespais_centre',
									textField:'descripcio',
									url:'./hormod/ec_getdata.php',
									required:true
								}
                            }
                           ">Alumne</th>-->
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_al_toolbar" style="height:auto">
	  <input id="idalumne" name="idalumne" class="easyui-combogrid" style="width:500px" data-options="
                	required: true,
                    panelWidth: 500,
                    idField: 'id_alumne',
                    textField: 'alumne',
                    url: './almat_tree/alum_getdata.php',
                    method: 'get',
                    columns: [[
                        {field:'alumne',title:'Alumne',width:470}
                    ]],
                    fitColumns: true
      ">&nbsp;
      <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="afegirAlumne()">Afegir alumne</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="treureAlumnes()">Treure alumnes</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="tancarAlumnes()">Tancar</a>
    </div>
    
    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var editIndex_al = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		
		$('#dg_al').datagrid({singleSelect:(this.value==1)})
		
		$('#grups').combogrid({
			url: './grmod/grup_getdata.php',	
		});
						
        function doSearch(){ 
			$('#horari_button').linkbutton('enable');
			$('#add_button').linkbutton('enable');		
			   
			$('#dg').datagrid('load',{  
				id_grups : $('#grups').combogrid('getValue')
			});  
		} 
		
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg').datagrid('selectRow', index)
							    .datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('dg').datagrid('selectRow', editIndex);
				}
			}
		}
		
		function onClickRow_al(index){
			if (editIndex_al != index){
				if (endEditing()){
					$('#dg_al').datagrid('selectRow', index)
							   .datagrid('beginEdit', index);
					editIndex_al = index;
				} else {
					$('dg_al').datagrid('selectRow', editIndex_al);
				}
			}
		}
		
		function endEditing(){
			if (editIndex_al == undefined){return true}			
			if ($('#dg_al').datagrid('validateRow', editIndex_al)){
			    var row = $('#dg_al').datagrid('getSelected');
				var ed  = $('#dg_al').datagrid('getEditor', {index:editIndex_al,field:'idespais_centre'});
				var descripcio = $(ed.target).combobox('getText');
				$('#dg_al').datagrid('getRows')[editIndex_al]['descripcio']  = descripcio;
				$('#dg_al').datagrid('endEdit', editIndex_al);
				$('#dg_al').datagrid('acceptChanges');
				
				editIndex_al = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function afegirAlumne(){		    
			var id_alumne = $('#idalumne').combogrid('getValue');
			var row       = $('#dg').datagrid('getSelected');
			url = './almat_tree/almat_tree_nou.php';
			
			if (row) {
						$.post(url,{
								idgrups_materies:row.idgrups_materies,
								idalumnes:id_alumne},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Alumne introdu&iuml;t correctament!','info');
								$('#dg_al').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Alumne introdu&iuml;t erroniament!','error');
								 
                                $.messager.show({  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json');
			}
        }
		
		function treureAlumnes(){ 
		  var row_main = $('#dg').datagrid('getSelected');
		  var rows_al  = $('#dg_al').datagrid('getSelections');
		  //endEditing();
		  
		  if (rows_al && row_main){ 
			   var ss_al = [];
			   for(var i=0; i<rows_al.length; i++){
					var row = rows_al[i];
					ss_al.push(row.idalumnes);
			   }
			   			   
			   url = './almat_tree/almat_tree_edita.php';
			   
			   $.messager.confirm('Confirmar','Esborrem aquests alumnes?',function(r){  
                    if (r){  
                        $.post(url,{
								idgrups_materies:row_main.idgrups_materies,
								idalumnes:ss_al},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Dades actualitzades correctament!','info');
								$('#dg_al').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Dades actualitzades erroniament!','error');
								 
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
		
		function verHorari(){
		    var id_grups = $('#grups').combogrid('getValue');
			url = './hormod/hormod_see.php?idgrups='+id_grups;
			$('#dlg_ver').dialog('open').dialog('setTitle','Horari');
			$('#dlg_ver').dialog('refresh', url);
        }
		
		function alumnesMatriculats(){		    
			var row = $('#dg').datagrid('getSelected');
			
			if (row) {
				$('#dg_al').datagrid('load',{  
					idgrups_materies : row.idgrups_materies
				});
				$('#dlg_al').dialog('open').dialog('setTitle','Alumnes matriculats');
			}
        }
		
		function tancar() {
		    javascript:$('#dlg_ver').dialog('close');
		}	
		
		function tancarAlumnes() {
			javascript:$('#dlg_al').dialog('close');
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