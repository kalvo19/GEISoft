<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");   
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">   
    <table id="dg" class="easyui-datagrid" title="Unitats formatives per professors" style="width:auto;height:auto"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './prmod/prmod_getdata.php',
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
        Professor&nbsp;
        <select id="professors" class="easyui-combogrid" style="width:400px" data-options="
            panelWidth: 400,
            idField: 'id_professor',
            textField: 'Valor',
            url: url,
            method: 'get',
            columns: [[
                {field:'Valor',title:'Professor',width:400}
            ]],
            fitColumns: true
        ">
        </select>
        <br />
        Grup&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
        &nbsp;<br />
        <a id="add_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-add',plain:true" onclick="gestioUFs(1)">Assignar UF's al professor</a>
        &nbsp;&nbsp;
        <a id="del_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-remove',plain:true" onclick="gestioUFs(0)">Treure UF's</a>
        &nbsp;&nbsp;&nbsp;
        <a id="ufs_button" href="javascript:void(0)" class="easyui-linkbutton" disabled="true" data-options="iconCls:'icon-tip',plain:true,disabled:false" onclick="verUFs()">Veure assignacions</a>
        &nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" disabled="true" plain="true" onclick="verHorario()">Veure horari professor</a>
    </div>
    </div>
    
	<div id="dlg_ver" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_ver-toolbar">  
	</div>
        
	<div id="dlg_ver-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar()">Tancar</a>  
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
		var idgrups;
		var nom_grup;
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
				
		$('#grups').combogrid({
			url: './prmod/grup_getdata.php',	
		});
		
		$('#professors').combogrid({
			url: './prmod/prof_getdata.php',
			onSelect: function(){
				$('#horari_button').linkbutton('enable');
				$('#ufs_button').linkbutton('enable');
			}
		});
								
        function doSearch(){ 
		    $('#add_button').linkbutton('enable');
			$('#del_button').linkbutton('enable');
					   
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
		  var rows_gr      = $('#dg').datagrid('getSelections');
          var id_professor = $('#professors').combogrid('getValue');
		  
		  if (rows_gr){ 
			   var ss_gr = [];
			   for(var i=0; i<rows_gr.length; i++){
					var row = rows_gr[i];
					ss_gr.push(row.idgrups_materies);
			   }
			   
			   url = './prmod/prmod_edita.php';
			   
			   $.messager.confirm('Confirmar','Introdu&iuml;m aquestes dades?',function(r){  
                    if (r){  
                        $.post(url,{
								id_professor:id_professor,
								afegir:afegir,
								idgrups_materies:ss_gr},function(result){  
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
		    var id_professor = $('#professors').combogrid('getValue');
			url = './prmod/prmod_see.php?id_professor='+id_professor;
			$('#dlg_ver').dialog('open').dialog('setTitle','Professors');
			$('#dlg_ver').dialog('refresh', url);
        }
				
		function verHorario(){  
            var id_professor = $('#professors').combogrid('getValue');
			
			url = './prmat/prmat_see.php?idprofessors='+id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function imprimirHorario(){
			var id_professor = $('#professors').combogrid('getValue');
			
		    url = './prmat/prmat_print.php?idprofessors='+id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
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