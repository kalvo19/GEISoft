<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
    
  if ( isset($_REQUEST['idprofessor']) && ($_REQUEST['idprofessor']==0) ) {
  	$idprofessor = 0;
  }
  else if ( isset($_REQUEST['idprofessor']) ) {
    $idprofessor = $_REQUEST['idprofessor'];
  }
  if (! isset($idprofessor)) {
    $idprofessor = 0;
  }
?>

    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Unitats formatives per grups" style="height:auto;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './uf_class/uf_class_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="nom_uf" width="470" sortable="true">Unitat formativa</th>
                <th field="hores" width="60" sortable="true">Hores</th>
                <th data-options="field:'uf_data_inici',width:90,align:'left',styler:cellStyler,editor:{options:{formatter:myformatter,parser:myparser}}">Data inici UF</th>
                <th data-options="field:'data_inici',width:130,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data inici UF Grup</th>
                
                <th data-options="field:'uf_data_fi',width:90,align:'left',styler:cellStyler,editor:{options:{formatter:myformatter,parser:myparser}}">Data fi UF</th>
                <th data-options="field:'data_fi',width:110,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data fi UF Grup</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="height:auto; padding-top:7px; padding-bottom:7px;">
    &nbsp;M&ograve;dul<br />&nbsp;
    <select id="moduls" name="moduls" class="easyui-combogrid" style="width:610px" data-options="
            panelWidth: 610,
            idField: 'idmoduls',
            textField: 'modul',
            url: url,
            method: 'get',
            columns: [[
                {field:'modul',title:'M&ograve;dul',width:440}
            ]],
            fitColumns: true
    ">
    </select>
    <br />
    &nbsp;Grup<br />&nbsp; 
    <input id="grups" name="grups" class="easyui-combobox" style="width:250px" data-options="
                	required: false,
                    panelWidth: 250
    ">
    &nbsp;
    <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
    <img src="./images/line.png" height="1" width="100%" align="absmiddle" /> 
    &nbsp;
    <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs(2)">Actualitzar dates de les UF's</a>
    &nbsp;&nbsp;
    <!--<a id="del_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-remove',plain:true" onclick="gestioUFs(0)">Treure UF's</a>-->
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
				
		$('#moduls').combogrid({
			url: './uf_class/moduls_getdata.php?idprofessors=<?=$idprofessor?>',	
		});
		
		$('#grups').combobox({
			url:'./inf_assist/grup_prof_getdata.php?idprofessors=<?=$idprofessor?>',
			valueField:'idgrups',
			textField:'nom',
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
			var idmoduls = $('#moduls').combogrid('getValue');
		    var idgrups  = $('#grups').combogrid('getValue');
			url = './uf_class/uf_class_see.php?idmoduls='+idmoduls+'&idgrups='+idgrups;
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
    