	<table id="dg" class="easyui-datagrid" title="Unitats Formatives" style="width:auto;height:650px"
			data-options="
				iconCls: 'icon-tip',
				singleSelect: true,
                pagination: true,
				toolbar: '#tb',
				url: './ufs/ufs_getdata.php',
				onClickRow: onClickRow
			">
		<thead>
			<tr>
				<th data-options="field:'idunitats_formatives',width:80">ID</th>
				<th data-options="field:'idplans_estudis',width:100,
						formatter:function(value,row){
							return row.Acronim_pla_estudis;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idplans_estudis',
                                textField:'Acronim_pla_estudis',
                                url:'./ufs/pe_ufs_getdata.php',
								required:true
							}
						}">Pla estudis</th>
				<th data-options="field:'idmoduls',width:300,
						formatter:function(value,row){
							return row.Acronim_pla_estudis;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idmoduls',
                                textField:'nom_modul',
                                url:'./ufs/pe_mod_getdata.php',
								required:false
							}
						}">Modul professional</th>
                <th data-options="field:'nom_uf',width:450,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom UF</th>
                <th data-options="field:'hores_finals',width:90,align:'left',editor:{type:'numberbox',options:{precision:0}}">Hores Totals</th>
                <th data-options="field:'hores',width:90,align:'left',editor:{type:'numberbox',options:{precision:0}}">Hores UF</th>
			</tr>
		</thead>
	</table>

	<div id="tb" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
		<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">Obtenir Canvis</a>
        <br />
        &nbsp;&nbsp;-->
        Pla d'estudis:&nbsp;
        <select id="s_pe" class="easyui-combobox" data-options="
					width:70,
                    url:'./ufs/pe_ufs_getdata.php',
					idField:'idplans_estudis',
                    valueField:'idplans_estudis',
					textField:'Acronim_pla_estudis',
					panelHeight:'auto'
		">
        </select>
        Modul professional:&nbsp;
        <select id="s_mp" class="easyui-combobox" data-options="
					width:300,
                    url:'./ufs/pe_mod_getdata.php',
					idField:'idmoduls',
                    valueField:'idmoduls',
					textField:'nom_modul',
					panelHeight:'auto'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">Cercar</a>
	</div>
	
	<script type="text/javascript">
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
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
				var nom_modul           = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['Acronim_pla_estudis'] = Acronim_pla_estudis;
				$('#dg').datagrid('getRows')[editIndex]['nom_modul'] = nom_modul;				
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './ufs/ufs_nou.php';
					nou_registre = 0;
				}
				else {
					url = './ufs/ufs_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idunitats_formatives'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['idplans_estudis'],
						  $('#dg').datagrid('getRows')[editIndex]['idmoduls'],
						  $('#dg').datagrid('getRows')[editIndex]['nom_uf'],
						  $('#dg').datagrid('getRows')[editIndex]['hores_finals'],
						  $('#dg').datagrid('getRows')[editIndex]['hores']);
				
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
					url = './ufs/ufs_nou.php';
					nou_registre = 0;
				}
				else {
					url = './ufs/ufs_edita.php?id='+row.idmoduls;
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
                $.messager.confirm('Confirmar','Est&aacute;s seguro de que quieres eliminar esta unidad formativa?',function(r){  
                    if (r){  
                        $.post('./ufs/ufs_esborra.php',{id:row.idmoduls},function(result){  
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
                });  
            }  
        } 
		
		function saveItem(url,row){ 			
	
			$.post(url,{idplans_estudis:row.idplans_estudis,idmoduls:row.idmoduls,nom_uf:row.nom_uf,hores_finals:row.hores_finals,hores:row.hores},function(result){  
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
		
		function afterEdit(url,field1,field2,field3,field4,field5){		
	
			$.post(url,{idplans_estudis:field1,idmoduls:field2,nom_uf:field3,hores_finals:field4,hores:field5},function(result){  
            if (result.success){  
               //$('#dg').datagrid('reload');    // reload the user data  
            } else {  
               $.messager.show({   // show error message  
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }	
	
	</script>
