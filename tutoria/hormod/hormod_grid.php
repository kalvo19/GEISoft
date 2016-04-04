<?php
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");   
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">   
    <table id="dg" class="easyui-datagrid" title="Horaris unitats formatives per grups" style="width:auto;height:auto"
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
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="nom" width="670" sortable="true">Unitat formativa</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Grup&nbsp;
        <select id="grups" class="easyui-combogrid" style="width:350px" data-options="
            panelWidth: 350,
            idField: 'idgrups',
            textField: 'nom',
            url: url,
            method: 'get',
            columns: [[
                {field:'nom',title:'Grup',width:320}
            ]],
            fitColumns: true
        ">
        </select>
        &nbsp;
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;&nbsp;&nbsp;
        <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs(1)">Assignar hores a les UF's</a>
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
	
    <div id="dlg_fh" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" closed="true">
        <table id="dg_fh" class="easyui-datagrid" title="Classes del grup" style="width:875px;height:555px"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'./hormod/hormod_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_fh_toolbar',
                    onClickRow: onClickRow_fh
                ">
            <thead>
                <tr>
                    <th data-options="field:'ck1',checkbox:true"></th>
                    <th field="dia_hora" width="500">Franja hor&agrave;ria</th>
                    <th data-options="field:'idespais_centre',width:250,
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
                           ">Espai centre</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_fh_toolbar" style="height:auto">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="gestioHorari(1)">Afegir horari</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="gestioHorari(0)">Treure horari</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="tancarHorari()">Tancar</a>
    </div>
    
    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var editIndex_fh = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
		$('#dg_fh').datagrid({singleSelect:(this.value==1)})
		
		
		$('#pla_estudis').combogrid({
			url: './grmod/pe_getdata.php',	
			
			onSelect: function(index,field,value){
				var p_e = $('#pla_estudis').combogrid('getValue');
				
				$('#moduls').combogrid({
					url:'./grmod/modul_getdata.php?idplans_estudis='+p_e,
				});
			}
			
		});

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
		
		function onClickRow_fh(index){
			if (editIndex_fh != index){
				if (endEditing()){
					$('#dg_fh').datagrid('selectRow', index)
							   .datagrid('beginEdit', index);
					editIndex_fh = index;
				} else {
					$('dg_fh').datagrid('selectRow', editIndex_fh);
				}
			}
		}
		
		function endEditing(){
			if (editIndex_fh == undefined){return true}			
			if ($('#dg_fh').datagrid('validateRow', editIndex_fh)){
			    var row = $('#dg_fh').datagrid('getSelected');
				var ed  = $('#dg_fh').datagrid('getEditor', {index:editIndex_fh,field:'idespais_centre'});
				var descripcio = $(ed.target).combobox('getText');
				$('#dg_fh').datagrid('getRows')[editIndex_fh]['descripcio']  = descripcio;
				$('#dg_fh').datagrid('endEdit', editIndex_fh);
				$('#dg_fh').datagrid('acceptChanges');
				
				editIndex_fh = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function gestioHorari(afegir){ 
		  var rows_gm    = $('#dg').datagrid('getSelections');
		  var rows_fh    = $('#dg_fh').datagrid('getSelections');
          var id_grups   = $('#grups').combogrid('getValue');
		  endEditing();
		  
		  if (rows_gm && rows_fh){ 
			   var ss_gm = [];
			   for(var i=0; i<rows_gm.length; i++){
					var row = rows_gm[i];
					ss_gm.push(row.idgrups_materies);
			   }
			   var ss_fh = [];
			   var ss_ec = [];
			   for(var i=0; i<rows_fh.length; i++){
					var row = rows_fh[i];				
					ss_fh.push(row.id_dies_franges);
					ss_ec.push(row.idespais_centre);
			   }
			   			   
			   url = './hormod/hormod_edita.php';
			   
			   $.messager.confirm('Confirmar','Introdu&iuml;m aquestes dades?',function(r){  
                    if (r){  
                        $.post(url,{
								id_grups:id_grups,
								afegir:afegir,
								idgrups_materies:ss_gm,
								id_dies_franges:ss_fh,
								idespais_centre:ss_ec},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Introducci&oacute; d\' horaris efectuada correctament!','info');
								$('#dg_fh').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Introducci&oacute; d\' horaris efectuada erroniament!','error');
								 
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
		
		function gestioUFs(){		    
			$('#dg_fh').datagrid('load',{  
				id_grups : $('#grups').combogrid('getValue')
			});
			$('#dlg_fh').dialog('open').dialog('setTitle','Franges hor&agrave;ries');
        }
		
		function tancar() {
		    javascript:$('#dlg_ver').dialog('close');
		}	
		
		function tancarHorari() {
			javascript:$('#dlg_fh').dialog('close');
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