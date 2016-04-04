<?php
   include_once('./bbdd/connect.php');
   include_once('./func/generic.php');
   include_once('./func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">   
    <table id="dg" class="easyui-datagrid" title="Automatr&iacute;cula alumnes" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './alum_automat/alum_automat_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="grup" width="120" sortable="true">Grup</th>
                <th field="materia" width="450" sortable="true">Mat&egrave;ria</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Pla d'estudis&nbsp;<br />
        <select id="pla_estudis" class="easyui-combogrid" style="width:400px" data-options="
            panelWidth: 400,
            idField: 'idplans_estudis',
            textField: 'Nom_plan_estudis',
            method: 'get',
            columns: [[
            	{field:'Acronim_pla_estudis',title:'',width:60},
                {field:'Nom_plan_estudis',title:'Nom',width:140}                
            ]],
            fitColumns: true
        ">
        </select>
        
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;
        <a id="matricula_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,disabled:true"  plain="true" onclick="gestioMatricula(<?=$_SESSION['alumne']?>,1)">Matr&iacute;cular-se</a>  
        <a id="des_matricula_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,disabled:true"  plain="true" onclick="gestioMatricula(<?=$_SESSION['alumne']?>,0)">Esborrar-se</a>
        
        <br />
        M&ograve;dul&nbsp;<br />
        <select id="moduls" class="easyui-combogrid" style="width:620px" data-options="
            panelWidth: 620,
            idField: 'idmoduls',
            textField: 'nom_modul',
            method: 'get',
            columns: [[
                {field:'nom_modul',title:'M&ograve;dul',width:620}
            ]],
            fitColumns: true
        ">
        </select>
          
        
    </div>
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>

    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		
		//$('#dg').datagrid({singleSelect:(this.value==1)})
				
		$('#pla_estudis').combogrid({
			url: './grmod/pe_getdata.php',	
			
			onSelect: function(index,field,value){
				var p_e = $('#pla_estudis').combogrid('getValue');
				
				$('#moduls').combobox('clear');
					
				$('#moduls').combogrid({
					url:'./grmod/modul_getdata.php?idplans_estudis='+p_e,
				});
			}
			
		});
						
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.matriculado==1){
						return 'background-color:whitesmoke;color:#DD6F00;font-weight:bold;';
					}
				}  
            });  
        });
				
        function doSearch(){				   
			$('#dg').datagrid('load',{  
			    idplans_estudis : $('#pla_estudis').combogrid('getValue'),
				idmoduls        : $('#moduls').combogrid('getValue')
			});
			$('#matricula_button').linkbutton('enable');
			$('#des_matricula_button').linkbutton('enable');
		} 
		
		function onClickRow(index){ 
		}
	
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_fh').dialog('close');
		}
		
		function gestioMatricula(idalumnes,afegir){ 
		  var rows_mat    = $('#dg').datagrid('getSelections');
		  var row_p = $('#dg').datagrid('getSelected');
		  
		  if (rows_mat){ 
			   var ss_mat = [];
			   for(var i=0; i<rows_mat.length; i++){
					var row = rows_mat[i];
					ss_mat.push(row.idgrups_materies);
			   }
			   url = './alum_automat/alum_automat_edita.php';
			   
			   if (afegir) {		   
				   $.messager.prompt('Confirmar','Sisplau, introdueixi la contrasenya',function(r){ 
						if (r){
							if (row_p.contrasenya == r || row_p.contrasenya == '') {
								$.post(url,{
										afegir:afegir,
										idalumnes:idalumnes,
										idgrups_materies:ss_mat},function(result){  
									if (result.success){  
										$.messager.alert('Informaci&oacute;','Operaci&oacute; efectuada correctament!','info');
										$('#dg').datagrid('reload');
										$('#menu_lateral').panel('refresh','./alum_automat/alum_automat_panel.php');									
									} else { 
										$.messager.alert('Error','Operaci&oacute; efectuada erroniament!','error');
										 
										$.messager.show({  
											title: 'Error',  
											msg: result.msg  
										});  
									}  
								},'json'); 
							}
							else {
								$.messager.alert('Error','Contrasenya err&ograve;nia.','error');
							} 
						}
				   }); 
				}
				
				else {
					$.messager.confirm('Confirmar','Ens desmatriculem?',function(r){
						if (r){  
							$.post(url,{
									afegir:afegir,
									idalumnes:idalumnes,
									idgrups_materies:ss_mat},function(result){  
								if (result.success){  
									$.messager.alert('Informaci&oacute;','Operaci&oacute; efectuada correctament!','info');
									$('#dg').datagrid('reload');
									$('#menu_lateral').panel('refresh','./alum_automat/alum_automat_panel.php');
								} else { 
									$.messager.alert('Error','Operaci&oacute; efectuada erroniament!','error');
									 
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
		}
	
		function informeAssistencia(){  
		    var g = $('#grups_combo').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;
			
			$('#c_alumne').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});
			
			url = './assist_adm/assist_adm_see.php?idgrups='+idgrups;
			$('#dlg_inf').dialog('open').dialog('setTitle','Assistencia del grup '+nomgrup);
			$('#dlg_inf').dialog('refresh', url);	
		}
		
		function imprimirInforme(){  
			var g = $('#grups_combo').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			
			url = './assist/assist_print.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups;
			$('#fitxer_pdf').attr('src', url);
		}
				
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