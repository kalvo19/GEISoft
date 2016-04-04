    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" title="Plans d'estudis" style="height:540px;"  
            toolbar="#toolbar" pagination="true" idField="idplans_estudis"  
            rownumbers="true" fitColumns="false" singleSelect="true">  
        <thead>  
            <tr>  
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
                <th field="Nom_plan_estudis" width="450" editor="{type:'validatebox',options:{required:false}}">Nom plan estudis</th>
                <th field="Acronim_pla_estudis" width="200" editor="{type:'validatebox',options:{required:false}}">Acronim</th>
            </tr>  
        </thead>  
    </table>  
    <div id="toolbar">  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">Nou</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Esborrar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Guardar</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel.lar</a>  
    </div>
    </div>
     
    <script type="text/javascript">  
        $(function(){  
            $('#dg').edatagrid({  
                url: './pe/pe_getdata.php',  
                saveUrl: './pe/pe_nou.php',  
                updateUrl: './pe/pe_edita.php',  
                destroyUrl: './pe/pe_esborra.php'  
            });  
        });  
    </script>