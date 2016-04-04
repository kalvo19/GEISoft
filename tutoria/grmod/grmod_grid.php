<?php
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");   
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">    
    <table id="dg" class="easyui-datagrid" title="Unitats formatives per grups" style="height:auto;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './grmod/grmod_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="nom_uf" width="470" sortable="true">Unitat formativa</th>
                <th field="hores" width="60" sortable="true">Hores</th>
                <th data-options="field:'uf_data_inici',width:90,align:'left',styler:cellStyler,editor:{options:{formatter:myformatter,parser:myparser}}">Data inici UF</th>
                <th data-options="field:'data_inici',width:100,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data inici Grup</th>
                
                <th data-options="field:'uf_data_fi',width:80,align:'left',styler:cellStyler,editor:{options:{formatter:myformatter,parser:myparser}}">Data fi UF</th>
                <th data-options="field:'data_fi',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data fi Grup</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Pla d'estudis&nbsp;
        <select id="pla_estudis" class="easyui-combogrid" style="width:620px" data-options="
            panelWidth: 620,
            idField: 'idplans_estudis',
            textField: 'Nom_plan_estudis',
            method: 'get',
            columns: [[
            	{field:'Acronim_pla_estudis',title:'',width:170},
                {field:'Nom_plan_estudis',title:'Nom',width:450}                
            ]],
            fitColumns: true
        ">
        </select>
        <br />
        M&ograve;dul&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select id="moduls" class="easyui-combogrid" style="width:620px" data-options="
            panelWidth: 620,
            idField: 'idmoduls',
            textField: 'nom_modul',
            method: 'get',
            columns: [[
                {field:'nom_modul',title:'M&ograve;dul',width:620}
            ]],
            fitColumns: true
        ">
        </select>
        &nbsp;<br />
        Grup&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <select id="grups" class="easyui-combogrid" style="width:350px" data-options="
            panelWidth: 350,
            idField: 'idgrups',
            textField: 'nom',
            url: url,
            method: 'get',
            columns: [[
                {field:'nom',title:'Grup',width:300}
            ]],
            fitColumns: true
        ">
        </select>
        &nbsp;
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <br />
        <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs(1)">Assignar UF's al grup</a>
        &nbsp;&nbsp;
        <a id="del_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-remove',plain:true" onclick="gestioUFs(0)">Treure UF's</a>
        &nbsp;&nbsp;&nbsp;
        <a id="ufs_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-tip',plain:true,disabled:false" onclick="verUFs()">Veure assignacions grup</a>
    </div>
	</div>
    
	<div id="dlg_ver" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_ver-toolbar">  
	</div>
        
	<div id="dlg_ver-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar()">Tancar</a>  
	</div>
	
    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		
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
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
				
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
			onSelect: function(){
				$('#ufs_button').linkbutton('enable');
				$('#add_button').linkbutton('enable');
				$('#del_button').linkbutton('enable');
			}	
		});
		
		function cellStyler(value,row,index){
                return 'background-color:whitesmoke;color:#CCC;';
        }
						
        function doSearch(){ 		   
			$('#dg').datagrid('load',{  
				idmoduls : $('#moduls').combogrid('getValue'),
				idgrups  : $('#grups').combogrid('getValue')
			});
			
			var rows = $('#dg').datagrid('getRows');
			for(var i=0; i<rows.length; i++){
				var row = rows[i];
				//alert(row.uf_data_inici);
				if (row.data_inici == '') {
					//alert(row.data_inici);
					//row.data_inici = row.uf_data_inici;
				}
				if (row.data_fi == '') {
					//row.data_fi = row.uf_data_fi;
				}
			}
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
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
			    var row = $('#dg').datagrid('getSelected');
				$('#dg').datagrid('endEdit', editIndex);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function gestioUFs(afegir){ 
		  endEditing();
		  
		  var rows_uf    = $('#dg').datagrid('getSelections');
		  var rows_di    = $('#dg').datagrid('getSelections');
		  var rows_df    = $('#dg').datagrid('getSelections');
		  
          var id_grups   = $('#grups').combogrid('getValue');
		  
		  if (rows_uf){ 
			   var ss_uf = [];
			   var ss_di = [];
			   var ss_df = [];
			   for(var i=0; i<rows_uf.length; i++){
					var row = rows_uf[i];
					ss_uf.push(row.idunitats_formatives);
					ss_di.push(row.data_inici);
					ss_df.push(row.data_fi);
			   }
			   
			   if (afegir==0) {
				url = './grmod/grmod_esborra.php';   
			   }
			   else {
			   	url = './grmod/grmod_edita.php';
			   }
			   
			   $.messager.confirm('Confirmar','Actualitzem dades?',function(r){  
                    if (r){  
                        $.post(url,{
								id_grups:id_grups,
								afegir:afegir,
								data_inici:ss_di,
								data_fi:ss_df,
								idunitats_formatives:ss_uf},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Introducci&oacute; de Unitats Formatives efectuada correctament!','info');
                            } else { 
							    $.messager.alert('Error','Introducci&oacute; de Unitats Formatives efectuada erroniament!','error');
								 
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
		
		function verUFs(){
		    var id_grups = $('#grups').combogrid('getValue');
			url = './grmod/grmod_see.php?id_grups='+id_grups;
			$('#dlg_ver').dialog('open').dialog('setTitle','Mat&egrave;ries');
			$('#dlg_ver').dialog('refresh', url);
        }
		
		function tancar() {
		    javascript:$('#dlg_ver').dialog('close');
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