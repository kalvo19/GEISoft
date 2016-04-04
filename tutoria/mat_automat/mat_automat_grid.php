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
	
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:550px;">
    <table id="dg" class="easyui-datagrid" title="Mat&egrave;ries amb automatr&iacute;cula" style="height:548px;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './mat_automat/mat_automat_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
            <tr>
                <th field="materia" width="350" sortable="true">Mat&egrave;ria</th>
                <th field="grup" width="150" sortable="true">Grup</th>
                <th data-options="field:'activat',width:70,align:'center',
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
                <th data-options="field:'contrasenya',width:180,align:'left',editor:{type:'validatebox',options:{required:false}}">Contrasenya automatr&iacute;cula</th>
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto">  
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" onclick="verHorario()">Veure horari grup</a>
    </div>
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
		var url;
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
				
		function verHorario(){  
			var row     = $('#dg').datagrid('getSelected');
			var idgrups = row.idgrups;
			
			url = './hor/hor_see.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function imprimirHorario(){
		    var row     = $('#dg').datagrid('getSelected');
			var idgrups = row.idgrups;
			
			url = './hor/hor_print.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
        }
				
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
				
				url = './mat_automat/mat_automat_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['id_mat_uf_pla'];
				
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['activat'],
						  $('#dg').datagrid('getRows')[editIndex]['contrasenya']);
				
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
										
				if (nou_registre) { 
					url = './mat_automat/mat_automat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './mat_automat/mat_automat_edita.php?id='+row_p.id_mat_uf_pla;
				}

				saveItem(url,row_p);
			}
		}
				
		function saveItem(url,row_p){ 			
	
			$.post(url,{activat:row_p.activat,contrasenya:row_p.contrasenya},function(result){  
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
		
		function afterEdit(url,field1,field2){		
	
			$.post(url,{activat:field1,contrasenya:field2},function(result){  
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