	<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Mat&egrave;ries" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
				toolbar: '#tb',
				url: './mat/mat_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
			<tr>
				<!--<th data-options="field:'idmateria',width:80">ID</th>-->
				<th data-options="field:'idplans_estudis',width:180,
						formatter:function(value,row){
							return row.Acronim_pla_estudis;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idplans_estudis',
                                textField:'Acronim_pla_estudis',
                                url:'./mat/pe_mat_getdata.php',
								required:true
							}
						}">Pla estudis</th>
				<th sortable="true" data-options="field:'nom_materia',width:450,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom materia</th>
                <th data-options="field:'hores_finals',width:100,align:'left',editor:{type:'numberbox',options:{precision:0}}">Hores finals</th>
                <th data-options="field:'automatricula',width:100,align:'center',
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
                ">Automatr&iacute;cula</th>
			</tr>
		</thead>
	</table>

	<div id="tb" style="height:60px;">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
		<br />
        &nbsp;Pla d'estudis:&nbsp;
        <select id="s_pe" class="easyui-combobox" data-options="
					width:450,
                    url:'./mat/pe_mat_getdata.php',
					idField:'idplans_estudis',
                    valueField:'idplans_estudis',
					textField:'Nom_plan_estudis',
					panelHeight:'auto'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="grupsMatriculats()">
        <img src="./images/group.png" height="16" align="absmiddle" />&nbsp;Matricular alumnes d'un grup</a>
	</div>
    </div>
    
    <div id="dlg_gr" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" closed="true">
        <table id="dg_gr" class="easyui-datagrid" title="" style="width:875px;height:555px"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'./mat/gr_mat_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_gr_toolbar'
                ">
            <thead>
                <tr>
                    <th data-options="field:'ck1',checkbox:true"></th>
                    <th field="nom" width="500">Grup</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_gr_toolbar" style="height:auto">
	  <input id="idgrup" name="idgrup" class="easyui-combogrid" style="width:400px" data-options="
                	required: true,
                    panelWidth: 400,
                    idField: 'idgrups',
                    textField: 'nom',
                    url: './grma/grup_getdata.php',
                    method: 'get',
                    columns: [[
                        {field:'nom',title:'Grup',width:400}
                    ]],
                    fitColumns: true
      ">&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="afegirGrup()">Afegir alumnes grup</a>
        &nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="treureGrup()">Treure alumnes grup</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="tancarGrup()">Tancar</a>
    </div>
	
	<script type="text/javascript">
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
		$('#dg_gr').datagrid({singleSelect:(this.value==1)})
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.automatricula=='S'){
						return 'background-color:whitesmoke;color:blue;font-weight:bold;';
					}
				}  
            });  
        });
		
		function doSearch(){
			$('#dg').datagrid('load',{  
				s_pe: $('#s_pe').combobox('getValue') 
			});
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'idplans_estudis'});
				var Acronim_pla_estudis = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['Acronim_pla_estudis'] = Acronim_pla_estudis;				
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './mat/mat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './mat/mat_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idmateria'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['idplans_estudis'],
						  $('#dg').datagrid('getRows')[editIndex]['nom_materia'],
						  $('#dg').datagrid('getRows')[editIndex]['hores_finals'],
						  $('#dg').datagrid('getRows')[editIndex]['automatricula']);
				
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
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
		function append(){
			if (endEditing()){
				$('#dg').datagrid('appendRow',{});
				nou_registre = 1;
				
				editIndex = $('#dg').datagrid('getRows').length-1;
				$('#dg').datagrid('selectRow', editIndex)
						.datagrid('beginEdit', editIndex);
			}
		}
		
		function accept(){
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				
				var row = $('#dg').datagrid('getSelected');
				
				if (nou_registre) { 
					url = './mat/mat_nou.php';
					nou_registre = 0;
				}
				else {
					url = './mat/mat_edita.php?id='+row.idmateria;
				}
				saveItem(url,row);
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		function getChanges(){
			var rows = $('#dg').datagrid('getChanges');
			alert(rows.length+' rows are changed!');
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Desitjas esborrar aquesta mat&egrave;ria?',function(r){  
                    if (r){  
                        $.post('./mat/mat_esborra.php',{id:row.idmateria},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload'); 
								editIndex = undefined; 
                            } else {  
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
		
		function saveItem(url,row){ 			
	
			$.post(url,{
				idplans_estudis:row.idplans_estudis,nom_materia:row.nom_materia,
				hores_finals:row.hores_finals,automatricula:row.automatricula
			},function(result){  
			
            if (result.success){  
               $('#dg').datagrid('reload');    // reload the user data  
            } else {  
               $.messager.show({   // show error message  
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2,field3,field4){		
	
			$.post(url,{
				idplans_estudis:field1,nom_materia:field2,
				hores_finals:field3,automatricula:field4
			},function(result){  
			
            if (result.success){  
               //$('#dg').datagrid('reload');    // reload the user data  
            } else {  
               $.messager.show({   // show error message  
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function grupsMatriculats(){		    
			var row = $('#dg').datagrid('getSelected');

			if (row) {
				$('#dg_gr').datagrid('load',{  
					idmateria : row.id_mat_uf_pla
				});
				$('#dlg_gr').dialog('open').dialog('setTitle','Grups on es cursa');
			}
        }
		
		function afegirGrup(){		    
			var idgrup    = $('#idgrup').combogrid('getValue');
			var row       = $('#dg').datagrid('getSelected');
			url = './mat/gr_mat_nou.php';
			
			if (row) {
				$.messager.confirm('Confirmar','Introdu&iuml;m aquests alumnes?',function(r){  
						$.post(url,{
								idmateria:row.id_mat_uf_pla,
								idgrup:idgrup},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Alumnes del grup introdu&iuml;ts correctament!','info');
								$('#dg_gr').datagrid('reload');
                            } else { 
							    $.messager.alert('Error','Alumnes del grup introdu&iuml;ts erroniament!','error');
								 
                                $.messager.show({  
                                    title: 'Error',  
                                    msg: result.msg  
                                });  
                            }  
                        },'json');
				});
			}
        }
		
		function treureGrup(){ 
		  var row_main = $('#dg').datagrid('getSelected');
		  var rows_gr  = $('#dg_gr').datagrid('getSelections');
		  
		  if (rows_gr && row_main){ 
			   var ss_gr = [];
			   for(var i=0; i<rows_gr.length; i++){
					var row = rows_gr[i];
					ss_gr.push(row.id_grups);
			   }
			      
			   url = './mat/gr_mat_esborra.php';
			   
			   $.messager.confirm('Confirmar','Esborrem aquests alumnes?',function(r){  
                    if (r){  
                        $.post(url,{
								idmateria:row_main.id_mat_uf_pla,
								idgrups:ss_gr},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Dades actualitzades correctament!','info');
								$('#dg_gr').datagrid('reload');
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
		
		function tancarGrup() {
			javascript:$('#dlg_gr').dialog('close');
		}	
		
		
	</script>
