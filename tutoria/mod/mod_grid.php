	<div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="M&ograve;duls Professionals" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
				toolbar: '#toolbar',
				url: './mod/mod_getdata.php',
				onClickRow: onClickRow,
                onAfterEdit: onAfterEdit 
			">
		<thead>
			<tr>
				<!--<th data-options="field:'idmoduls',width:80">ID</th>-->
				<th data-options="field:'idplans_estudis',width:100,
						formatter:function(value,row){
							return row.Acronim_pla_estudis;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idplans_estudis',
                                textField:'Acronim_pla_estudis',
                                url:'./mod/pe_mod_getdata.php',
								required:true
							}
						}">Pla estudis</th>
				<th data-options="field:'nom_modul',width:600,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom modul</th>
                <th data-options="field:'hores_finals',width:90,align:'left',editor:{type:'numberbox',options:{precision:0}}">Hores finals</th>
                <th data-options="field:'horeslliuredisposicio',width:130,align:'left',editor:{type:'numberbox',options:{precision:0}}">H. Lliure disposici&oacute;</th>
			</tr>
		</thead>
	</table>

	<div id="toolbar" style="height:auto">
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
		<!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true" onclick="getChanges()">Obtenir Canvis</a>
        <br />
        &nbsp;&nbsp;-->
        <br />
        &nbsp;Pla d'estudis:&nbsp;
        <select id="s_pe" class="easyui-combobox" data-options="
					width:450,
                    url:'./mod/pe_mod_getdata.php',
					idField:'idplans_estudis',
                    valueField:'idplans_estudis',
					textField:'Nom_plan_estudis',
					panelHeight:'auto'
		">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;&nbsp;
        <a id="ufs_button" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" disabled="true" plain="true" onclick="gestionUFs()">Assignar UF's</a>
	</div>
    </div>
    
    <div id="dlg" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" maximized="true" maximizable="true" closed="true" buttons="#dlg-buttons">
        <table id="dg_mat" class="easyui-datagrid" title="Unitats Formatives" style="width:995px;height:550px" 
                data-options="
                    singleSelect: true,
                    rownumbers:true,
                    url:'./mod/mod_getdetail.php',
                    pagination: false,
                    toolbar: '#tb',
                    onClickRow: onClickRowUF
                ">
            <thead>
                <tr>
                 <th data-options="field:'nom_uf',width:550,align:'left',editor:{type:'validatebox',options:{required:true}}">Nom UF</th>
                 <th data-options="field:'hores',width:100,align:'left',editor:{type:'numberbox',options:{precision:0}}">Hores</th>
                 <th data-options="field:'data_inici',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data inici</th>
                 <th data-options="field:'data_fi',width:90,align:'left',editor:{type:'datebox',options:{formatter:myformatter,parser:myparser}}">Data fi</th>
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
    </div>
    
    <div id="tb" style="height:auto;">
    	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" onclick="grupsMatriculats()">
        <img src="./images/group.png" height="16" align="absmiddle" />&nbsp;Matricular alumnes d'un grup</a>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a id="btnAppend" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendUF()">Nou</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItemUF()">Esborrar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="acceptUF()">Acceptar</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="rejectUF()">Tancar</a>
    </div>
    
    <div id="dlg_gr" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" modal="true" closed="true">
        <table id="dg_gr" class="easyui-datagrid" title="" style="width:875px;height:555px"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'./mod/gr_uf_getdetail.php',
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
		var nou_registre    = 0;
		var nou_registre_uf = 0;
		
		$('#dg_gr').datagrid({singleSelect:(this.value==1)})
		
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
		
		function doSearch(){
			$('#dg').datagrid('load',{  
				s_pe: $('#s_pe').combobox('getValue') 
			});
		}
		
		$('#dg').datagrid({  
			view: detailview,  
			detailFormatter:function(index,row){  
				return '<div style="padding:2px"><table id="ddv-' + index + '"></table></div>';  
			},  
			onExpandRow: function(index,row){  
				$('#ddv-'+index).datagrid({  
					url:'./mod/mod_getdetail.php?id_moduls='+row.idmoduls,  
					fitColumns:false,  
					rownumbers:true,  
					loadMsg:'unitats formatives del m&ograve;dul...',  
					height:'auto',
					columns:[[  
						{field:'nom_uf',title:'Unitat Formativa',width:500},
						{field:'hores',title:'Hores',width:70},
						{field:'data_inici',title:'Inici',width:100},
						{field:'data_fi',title:'F&iacute;',width:100}
					]],
					onResize:function(){  
						$('#dg').datagrid('fixDetailRowHeight',index);  
					},  
					onLoadSuccess:function(){  
						setTimeout(function(){  
							$('#dg').datagrid('fixDetailRowHeight',index);  
						},0);  
					}  
				});  
				$('#dg').datagrid('fixDetailRowHeight',index);  
			}  
		});
   		
		$(function(){  
            $('#dg_mat').datagrid({             
				rowStyler:function(index,row){
					if (row.automatricula=='S'){
						return 'background-color:whitesmoke;color:blue;font-weight:bold;';
					}
				}  
            });  
        });
				
		function gestionUFs(){ 
            var row = $('#dg').datagrid('getSelected');
			editIndex = undefined;
			
			if (row){  
				$('#dlg').dialog('open').dialog('setTitle',row.nom_modul);
				$('#dg_mat').datagrid('load',{ 
					id_moduls: row.idmoduls  
     			});
            }  
        }
		
		function grupsMatriculats(){		    
			var row = $('#dg_mat').datagrid('getSelected');

			if (row) {
				$('#dg_gr').datagrid('load',{  
					idunitats_formatives : row.idunitats_formatives
				});
				$('#dlg_gr').dialog('open').dialog('setTitle','Grups on es cursa');
			}
        }
		
		function afegirGrup(){		    
			var idgrup    = $('#idgrup').combogrid('getValue');
			var row       = $('#dg_mat').datagrid('getSelected');
			url = './mat/gr_mat_nou.php';
			
			if (row) {
				$.messager.confirm('Confirmar','Introdu&iuml;m aquests alumnes?',function(r){  
						$.post(url,{
								idmateria:row.idunitats_formatives,
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
		  var row_main = $('#dg_mat').datagrid('getSelected');
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
								idmateria:row_main.idunitats_formatives,
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
		
		function endEditing(){
			if (editIndex == undefined){return true}
			if ($('#dg').datagrid('validateRow', editIndex)){
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'idplans_estudis'});
				var Acronim_pla_estudis = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['Acronim_pla_estudis'] = Acronim_pla_estudis;				
				$('#dg').datagrid('acceptChanges');
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './mod/mod_nou.php';
					nou_registre = 0;
				}
				else {
					url = './mod/mod_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idmoduls'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['idplans_estudis'],
						  $('#dg').datagrid('getRows')[editIndex]['nom_modul'],
						  $('#dg').datagrid('getRows')[editIndex]['hores_finals'],
						  $('#dg').datagrid('getRows')[editIndex]['horeslliuredisposicio']);
				
				editIndex = undefined;
				return true;
			} else {				
				return false;
			}
		}
		
		function endEditingUF(){
			if (editIndex == undefined){return true}			
			if ($('#dg_mat').datagrid('validateRow', editIndex)){
			    var row_p = $('#dg').datagrid('getSelected');
				$('#dg_mat').datagrid('endEdit', editIndex);
				
				if (nou_registre_uf) { 
					url = './mod/mod_nou_uf.php';
					nou_registre_uf = 0;
				}
				else {
					url = './mod/mod_edita_uf.php?id='+$('#dg_mat').datagrid('getRows')[editIndex]['idunitats_formatives'];
				}
				afterEditUF(url,
						    row_p.idmoduls,
							row_p.idplans_estudis,
						    $('#dg_mat').datagrid('getRows')[editIndex]['nom_uf'],
						    $('#dg_mat').datagrid('getRows')[editIndex]['hores'],
							$('#dg_mat').datagrid('getRows')[editIndex]['data_inici'],
							$('#dg_mat').datagrid('getRows')[editIndex]['data_fi'],
							$('#dg_mat').datagrid('getRows')[editIndex]['automatricula']);
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function onAfterEdit(rowIndex, rowData, changes){
			$('#dg').datagrid('reload');
		}
		
		function onClickRow(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg').datagrid('selectRow', index)
							.datagrid('beginEdit', index);
					editIndex = index;
					$('#ufs_button').linkbutton('enable');	
				} else {
					$('#dg').datagrid('selectRow', editIndex);
				}
			}
		}
		 
		function onClickRowUF(index){
			if (editIndex != index){
				if (endEditing()){
					$('#dg_mat').datagrid('selectRow', index)
							.datagrid('beginEdit', index);
					editIndex = index;
				} else {
					$('#dg_mat').datagrid('selectRow', editIndex);
				}
			}
		}
		
		function append(){
			if (endEditing()){
				$('#dg').datagrid('appendRow',{});
				nou_registre = 1;
				$('#ufs_button').linkbutton('disable');
				editIndex = $('#dg').datagrid('getRows').length-1;
				$('#dg').datagrid('selectRow', editIndex)
						.datagrid('beginEdit', editIndex);
			}
		}
		
		function appendUF(){
			if (endEditingUF()){
				$('#dg_mat').datagrid('appendRow',{});
				nou_registre_uf = 1;
				
				editIndex = $('#dg_mat').datagrid('getRows').length-1;
				$('#dg_mat').datagrid('selectRow', editIndex)
						    .datagrid('beginEdit', editIndex);
			}
			
		}
		
		function accept(){
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				
				var row = $('#dg').datagrid('getSelected');
				
				if (nou_registre) { 
					url = './mod/mod_nou.php';
					nou_registre = 0;
				}
				else {
					url = './mod/mod_edita.php?id='+row.idmoduls;
				}
				saveItem(url,row);
			}
		}
		
		function acceptUF(){			
			if (endEditingUF()){
				$('#dg_mat').datagrid('acceptChanges');
				var row_p = $('#dg').datagrid('getSelected');
				var row_a = $('#dg_mat').datagrid('getSelected');
										
				if (nou_registre_uf) { 
					url = './mod/mod_nou_uf.php';
					nou_registre_uf = 0;
				}
				else {
					url = './mod/mod_edita_uf.php?id='+row_a.idunitats_formatives;
				}

				saveItemUF(url,row_a,row_p);
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		
		function rejectUF(){
			$('#dg_mat').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected');  
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquest m&ograve;dul?',function(r){  
                    if (r){  
                        $.post('./mod/mod_esborra.php',{id:row.idmoduls},function(result){  
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
		
		function destroyItemUF(){  
            var row_a = $('#dg_mat').datagrid('getSelected'); 
            if (row_a){  
                $.messager.confirm('Confirmar','Est&aacute;s segur de que vols esborrar aquesta UF?',function(r){  
                    if (r){  
                        $.post('./mod/mod_esborra_uf.php',{id:row_a.idunitats_formatives},function(result){  
                            if (result.success){  
                                $('#dg_mat').datagrid('reload');    // reload the user data  
								editIndex = undefined;
								//$('#dg').datagrid('reload');
                            } else {  
                                $.messager.show({   // show error message  
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
	
			$.post(url,{idplans_estudis:row.idplans_estudis,nom_modul:row.nom_modul,hores_finals:row.hores_finals,horeslliuredisposicio:row.horeslliuredisposicio},function(result){  
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
		
		function saveItemUF(url,row_a,row_p){ 			
	
			$.post(url,{
				id_moduls:row_p.id_moduls,idplans_estudis:row_p.idplans_estudis,
				nom_uf:row_a.nom_uf,hores:row_a.hores,data_inici:row_a.data_inici,
				data_fi:row_a.data_fi,automatricula:row_a.automatricula
			},function(result){  
			
            if (result.success){  
               //$('#dg_mat').datagrid('reload');    // reload the user data 
			   //$('#dg').datagrid('reload'); 
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2,field3,field4){		
	
			$.post(url,{idplans_estudis:field1,nom_modul:field2,hores_finals:field3,horeslliuredisposicio:field4},function(result){  
            if (result.success){  
               
			   //$('#dg').datagrid('reload');  
			   editIndex = undefined;
			    
            } else {  
               $.messager.show({  
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEditUF(url,field1,field2,field3,field4,field5,field6,field7){		
	
			$.post(url,{
				id_moduls:field1,idplans_estudis:field2,nom_uf:field3,
						hores:field4,data_inici:field5,data_fi:field6,
						automatricula:field7
			},function(result){  
			
            if (result.success){  
               //$('#dg_mat').datagrid('reload');
			   //$('#dg').datagrid('reload');    
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }

	</script>