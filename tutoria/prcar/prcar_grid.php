<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">   
    <table id="dg" class="easyui-datagrid" title="C&agrave;rrecs de professors" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: true,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './prcar/prcar_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th sortable="true" data-options="field:'idprofessors',width:330,sortable:true,
						formatter:function(value,row){
							return row.Valor;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'id_professor',
                                textField:'Valor',
                                url:'./prcar/prof_getdata.php',
								required:true
							}
						}">Professor</th>
                
                 <th data-options="field:'idcarrecs',width:200,sortable:true,
                            formatter:function(value,row){
								return row.nom_carrec;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'idcarrecs',
									textField:'nom_carrec',
									url:'./prcar/ca_getdata.php',
									required:true
								}
                         }">C&agrave;rrec</th>
                        
                  <th data-options="field:'idgrups',width:340,sortable:true,
                            formatter:function(value,row){
								return row.nom;
							},
							editor:{
								type:'combobox',
								options:{
                                    valueField:'idgrups',
									textField:'nom',
									url:'./prcar/gr_getdata.php',
									required:false
								}
                         }">Grup</th>
                         
                  <th data-options="field:'principal',width:60,align:'center',
                formatter:function(value,row){
                             if (value==0) {
                                valor = '';
                             }
                             else {
                                valor = 'S';
                             }
                             return valor;
                       }, 
                editor:{type:'checkbox',options:{on:'1',off:'0'}}
                ">Principal</th>
                
            </tr>  
        </thead>  
    </table>  
    
    <div id="toolbar" style="padding:5px;height:auto"> 
		<a id="nou_carrec" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Nou</a>
		<a id="esborra_carrec" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,disabled:true" onclick="destroyItem()">Esborrar</a>
		<a id="Acceptar_carrec"href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,disabled:true" onclick="accept()">Acceptar canvis</a>
		&nbsp;&nbsp;
        <a id="veure_horari" href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" disabled="true" onclick="verHorario()">Veure horari</a> 
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
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirHorario(<?=$idgrups?>)">Imprimir</a>  
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
						
		function verHorario(){  
            var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){ 
				url = './prmat/prmat_see.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
				$('#dlg_hor').dialog('refresh', url);
			}
        }
		
		function imprimirHorario(){
			var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){
		    	url = './prmat/prmat_print.php?idprofessors='+row.id_professor+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
		    	$('#fitxer_pdf').attr('src', url);
			}
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
			$('#esborra_carrec').linkbutton('enable');
			$('#Acceptar_carrec').linkbutton('enable');
			$('#veure_horari').linkbutton('enable');
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
				var row_p = $('#dg').datagrid('getSelected');
				
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'idprofessors'});
				var Valor = $(ed.target).combobox('getText');
				
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'idcarrecs'});
				var nom_carrec = $(ed.target).combobox('getText');
				
				var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'idgrups'});
				var nom = $(ed.target).combobox('getText');
				
				$('#dg').datagrid('getRows')[editIndex]['Valor']      = Valor;
				$('#dg').datagrid('getRows')[editIndex]['nom_carrec'] = nom_carrec;	
				$('#dg').datagrid('getRows')[editIndex]['nom']        = nom;
										
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './prcar/prcar_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prcar/prcar_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['idprofessor_carrec'];
				}
				afterEdit(url,
						  $('#dg').datagrid('getRows')[editIndex]['idprofessors'],
						  $('#dg').datagrid('getRows')[editIndex]['idcarrecs'],
						  $('#dg').datagrid('getRows')[editIndex]['idgrups'],
						  $('#dg').datagrid('getRows')[editIndex]['principal']);
				
				editIndex = undefined;
				
				return true;
			} else {
				return false;
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
			$('#Acceptar_carrec').linkbutton('enable');
		}
		
		function accept(){			
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				var row = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './prcar/prcar_nou.php';
					nou_registre = 0;
				}
				else {
					url = './prcar/prcar_edita.php?id='+row.idprofessor_carrec;
				}

				saveItem(url,row);
				
				$('#esborra_carrec').linkbutton('disable');
				$('#Acceptar_carrec').linkbutton('disable');
				$('#veure_horari').linkbutton('disable');
				
				$('#dg').datagrid('reload'); 
				editIndex = undefined;
			}
		}
		
		function reject(){
			$('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg').dialog('close');
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s seguo de que vols esborrar aquest c&agrave;rrec?',function(r){  
                    if (r){  
                        $.post('./prcar/prcar_esborra.php',{id:row.idprofessor_carrec},function(result){  
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
					idprofessors:row.idprofessors,
					idcarrecs:row.idcarrecs,
					idgrups:row.idgrups,
					principal:row.principal},function(result){  
            if (result.success){  
               $('#dg').datagrid('reload');
			   editIndex = undefined;
				$('#esborra_carrec').linkbutton('disable');
				$('#Acceptar_carrec').linkbutton('disable');
				$('#veure_horari').linkbutton('disable');
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.msg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2,field3,field4){		
	
			$.post(url,{idprofessors:field1,idcarrecs:field2,idgrups:field3,principal:field4},function(result){  
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