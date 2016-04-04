<h2>Manteniment de cursos escolars</h2>  
<div class="demo-info" style="margin-bottom:10px">  
        <div class="demo-tip icon-tip">&nbsp;</div>  
        <div>Doble clic per editar una fila.</div>  
</div>
    
    <table id="dg" title="Cursos escolars" style="width:450px;height:450px"  
            toolbar="#toolbar" pagination="true" idField="idcursos_escolars"  
            rownumbers="true" fitColumns="true" singleSelect="true">  
        <thead>  
            <tr>  
                <th data-options="field:'curs',width:100" editor="{type:'validatebox',options:{required:true}}">Curs</th>
                <th data-options="field:'actual',width:60,align:'center',
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
                ">Actual</th>
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar">  
    <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">New</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Destroy</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>  
    <a href="#" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>  
</div>   
      
   <script type="text/javascript">  
        $(function(){  
            $('#dg').edatagrid({  
                url: './ce/ce_getdata.php',  
                saveUrl: './ce/ce_nou.php',  
                updateUrl: './ce/ce_edita.php',  
                destroyUrl: './ce/ce_esborra.php'  
            });  
			
			$('#dg').datagrid({
				rowStyler:function(index,row){
					if (row.actual>0){
					    
						return 'background-color:pink;color:blue;font-weight:bold;';
					}
					if (row.actual==0){
					    row.actual = '';
						return '';
					}
				}
			});
        });  
    </script>