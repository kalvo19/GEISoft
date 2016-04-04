<h2>Manteniment de materies/moduls</h2>  
<div class="demo-info" style="margin-bottom:10px">  
        <div class="demo-tip icon-tip">&nbsp;</div>  
        <div>Doble clic per editar una fila.</div>  
</div>
    
    <table id="dg" title="Materies/Moduls" style="width:550px;height:550px"  
            toolbar="#toolbar" pagination="true" idField="idunitats_formatives"  
            rownumbers="true" fitColumns="true" singleSelect="true">  
        <thead>  
            <tr>  
                <th data-options="field:'nom_uf',width:300" editor="{type:'validatebox',options:{required:true}}">Nom UF</th>
                <th data-options="field:'hores',width:100" editor="{type:'numberbox',options:{required:true}}">Hores</th>
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar">  
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Nou</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Esborrar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>  
    </div>   
      
   <script type="text/javascript">  
        $(function(){  
            $('#dg').edatagrid({  
                url: './uf/uf_getdata.php',  
                saveUrl: './uf/uf_nou.php',  
                updateUrl: './uf/uf_edita.php',  
                destroyUrl: './uf/uf_esborra.php'  
            });  
			
			$('#dg').datagrid({
				rowStyler:function(index,row){
					if (row.activada>0){
						return 'color:blue;font-weight:bold;';
					}
					if (row.activada==0){
					    row.activada = '';
						return '';
					}
					if (row.esbarjo==1){
						return 'background-color:pink';
					}
				}
			});
        });  
    </script>