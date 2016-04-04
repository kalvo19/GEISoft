<?php
  session_start();
?>
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" title="Espais del centre" style="height:540px;"  
            toolbar="#toolbar" pagination="true" idField="idespais_centre"  
            rownumbers="true" fitColumns="false" singleSelect="true">  
        <thead>  
            <tr>  
                <th data-options="field:'descripcio',width:400" editor="{type:'validatebox',options:{required:true}}">Descripcio</th>
                <th data-options="field:'activat',width:60,align:'center',
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
                ">Activat</th>
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="verHorario()">Veure horari</a>  
    &nbsp;&nbsp;
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Nou</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Esborrar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>  
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
        function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){
				url = './ec/ec_see.php?idespais_centre='+row.idespais_centre+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari de '+row.descripcio);
				$('#dlg_hor').dialog('refresh', url);
            }
        }
		
		function imprimirHorario(){  
            var row = $('#dg').datagrid('getSelected');
			/*var strWindowFeatures = "menubar=no,location=no,resizable=no,scrollbars=no,status=yes,width=1020,height=600";
			window.open('./ec/ec_print.php?idespais_centre='+row.idespais_centre,'Imprimir',strWindowFeatures);	*/	
		
		    url = './ec/ec_print.php?idespais_centre='+row.idespais_centre+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    $('#fitxer_pdf').attr('src', url);
			
        }
		
		$(function(){  
            $('#dg').edatagrid({  
                url: './ec/ec_getdata.php',  
                saveUrl: './ec/ec_nou.php',  
                updateUrl: './ec/ec_edita.php',  
                destroyUrl: './ec/ec_esborra.php'  
            });  
			
			$('#dg').datagrid({
				rowStyler:function(index,row){
					if (row.activat>0){
						return 'color:blue;font-weight:bold;';
					}
					if (row.activat==0){
					    row.activat = '';
						return '';
					}
				}
			})
			
			
        });
	</script>