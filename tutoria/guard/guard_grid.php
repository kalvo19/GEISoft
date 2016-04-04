<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $hora = isset($_REQUEST['hora']) ? $_REQUEST['hora'] : '00:00';
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
        
    <table id="dg" class="easyui-datagrid" title="Gu&agrave;rdies :: Control assist&egrave;ncia " style="width:1100px;height:550px"
			data-options="
				iconCls: 'icon-tip',
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './guard/guard_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr>
                <th field="alumne" width="270" sortable="true">Alumne</th>
                <th sortable="true" data-options="field:'id_tipus_incidencia',width:100,
						formatter:function(value,row){
							return row.tipus_falta;
						},
						editor:{
							type:'combobox',
							options:{
								valueField:'idtipus_falta_alumne',
                                textField:'tipus_falta',
                                url:'./guard/guard_tf_getdata.php',
								required:true
							}
						}">Tipus falta</th>
                <th data-options="field:'comentari',width:650,align:'left',editor:{type:'textarea',options:{required:false}}">Comentari</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Grups&nbsp;
        <select id="classes" class="easyui-combogrid" style="width:830px" data-options="
            panelWidth: 830,
            idField: 'idagrups_materies',
            textField: 'grup',
            url: url,
            method: 'get',
            columns: [[
                {field:'hora',title:'Hora',width:110},
                {field:'grup',title:'Grup',width:150},
                {field:'materia',title:'Materia',width:440},
                {field:'espaicentre',title:'Espai centre',width:130}
            ]],
            fitColumns: true
        ">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">Cercar</a>
        &nbsp;<br />
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar canvis</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar entrada</a>
        &nbsp;&nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true,disabled:true" onclick="verHorario()">Veure horari grup</a>&nbsp;
        <a id="assist_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-print',plain:true,disabled:true" onclick="informeAssistencia()">Informe Assist&egrave;ncia</a>
        <!--<a id="sms_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,disabled:true" onclick="enviarSMS()">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>-->
    </div>
    
    <div id="dlg_hor" class="easyui-dialog" style="width:1200px;height:600px;"  
            closed="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
    </div>
    
    <div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_inf" class="easyui-dialog" style="width:1100px;height:600px;"  
            closed="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">  
	</div>
    
	<div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <form method="post">
                Desde: <input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a: <input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
                Per alumne: 
                
                <select id="c_alumne" class="easyui-combobox" name="c_alumne" style="width:400px;" data-options="valueField:'idalumnes',textField:'Valor'">
                </select>
                <!--<input id="c_alumne" name="c_alumne" style="width:400px;" value="Tots els alumnes ...">-->
                </form>
                
                
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload()">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="imprimirInforme()">Imprimir</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
	</div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
	<div id="dlg_sms" class="easyui-dialog" style="width:1075px;height:600px;"  
            closed="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
	</div>
        
	<div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="tancar()">Tancar</a>  
	</div>

    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		var theDate = new Date();
		var theDay  = theDate.getDay();
		
		url = './guard/classes_getdata.php?dia='+theDay+'&hora=<?=$hora?>';

		$('#classes').combogrid({
					url: url,
		});
						
		$('#classes').combogrid({
			onSelect: function(date){
				$('#c_alumne').combo({
					url:'./tutor/tutor_alum_getdata.php?idgrups=27',
					valueField:'idalumnes',
					textField:'Valor'
				});
				
				$('#horari_button').linkbutton('enable');
				$('#assist_button').linkbutton('enable');
				$('#sms_button').linkbutton('enable');
			}
		});
		
		$(function(){  
            $('#dg').datagrid({  
				view: detailview,  
                detailFormatter:function(index,row){  
                    return '<div id="ddv-' + index + '" style="padding:5px 0">'+
					        '<table><tr>' + 
							'<td style="border:0" valign=top>' + 
							'<img src="./images/alumnes/' + row.idalumnes + '.jpg<?= $strNoCache ?>" style="width:50px;height:70px;"></td>' +
							'<td width=10>&nbsp;</td>' +
							'</tr></table>' +
							'</div>';
                },			
                onExpandRow: function(index,row){  
                    $('#ddv-'+index).panel({  
						height:auto,  
                        border:false,  
                        cache:false,  
                        columns:[[  
							{field:'comentari',title:'Comentari',width:400}
						]],  
                        onLoad:function(){  
                            $('#dg').datagrid('fixDetailRowHeight',index);  
                        }  
                    });  
                    $('#dg').datagrid('fixDetailRowHeight',index);  
                },
				rowStyler:function(index,row){
				    if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_ABSENCIA?>){
						return 'background-color:whitesmoke;color:#be0f34;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_RETARD?>){
						return 'background-color:whitesmoke;color:#ada410;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_SEGUIMENT?>){
						return 'background-color:whitesmoke;color:#002596;font-weight:bold;';
					}
					if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_JUSTIFICADA?>){
						return 'background-color:#a1d88b;color:#009a49;font-weight:bold;';
					}
				}  
            });  
        });
		
        function doSearch(){ 		   
			var g = $('#classes').combogrid('grid');	
			var r = g.datagrid('getSelected');	
			
			if (r){ 
				$('#dg').datagrid('load',{  
					grup_materia       : $('#classes').combogrid('getValue'),
					idfranges_horaries : r.idfranges_horaries
				});
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
		}
		
		function endEditing(){
			if (editIndex == undefined){return true}			
			if ($('#dg').datagrid('validateRow', editIndex)){
			    var row = $('#dg').datagrid('getSelected');
				var ed  = $('#dg').datagrid('getEditor', {index:editIndex,field:'id_tipus_incidencia'});
				var tipus_falta = $(ed.target).combobox('getText');
				$('#dg').datagrid('getRows')[editIndex]['tipus_falta'] = tipus_falta;
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './guard/guard_nou.php';
					nou_registre = 0;
				}
				else {
					url = './guard/guard_edita.php';
				}
				afterEdit(url,
					$('#dg').datagrid('getRows')[editIndex]['idalumnes_grup_materia'],
					$('#dg').datagrid('getRows')[editIndex]['id_tipus_incidencia'],
					$('#dg').datagrid('getRows')[editIndex]['idprofessors'],
					$('#dg').datagrid('getRows')[editIndex]['data'],
					$('#dg').datagrid('getRows')[editIndex]['idfranges_horaries'],
					$('#dg').datagrid('getRows')[editIndex]['comentari']);
				
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
				$('#dg_mat').datagrid('selectRow', editIndex)
						    .datagrid('beginEdit', editIndex);
			}
			
		}
		
		function accept(){			
			if (endEditing()){
				$('#dg').datagrid('acceptChanges');
				var row = $('#dg').datagrid('getSelected');
										
				if (nou_registre) { 
					url = './guard/guard_nou.php';
					nou_registre = 0;
				}
				else {
					url = './guard/guard_edita.php';
				}

				saveItem(url,row);
			}
		}
		
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
		}
		
		function destroyItem(){  
            var row = $('#dg').datagrid('getSelected'); 
            if (row){  
                $.messager.confirm('Confirmar','Est&aacute;s seguro de que vols esborrar aquesta entrada?',function(r){  
                    if (r){  
                        $.post('./guard/guard_esborra.php',{
														idalumnes_grup_materia:row.idalumnes_grup_materia,
														id_tipus_incidencia:row.id_tipus_incidencia,
														idprofessors:row.idprofessors,
														data:row.data,
														idfranges_horaries:row.idfranges_horaries,
														comentari:row.comentari},function(result){  
                            if (result.success){  
                                $('#dg').datagrid('reload');
								editIndex = undefined; 
                            } else {  
                                $.messager.show({  
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
	
			$.post(url,{idalumnes_grup_materia:row.idalumnes_grup_materia,
						id_tipus_incidencia:row.id_tipus_incidencia,idprofessors:row.idprofessors,
						data:row.data,idfranges_horaries:row.idfranges_horaries,comentari:row.comentari},function(result){  
            if (result.success){  
               //$('#dg_mat').datagrid('reload');    // reload the user data 
			   $('#dg').datagrid('reload'); 
			   editIndex = undefined;
            } else {  
               $.messager.show({   
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
             },'json');
		  
        }
		
		function afterEdit(url,field1,field2,field3,field4,field5,field6){
			$.post(url,{idalumnes_grup_materia:field1,
						id_tipus_incidencia:field2,idprofessors:field3,
						data:field4,idfranges_horaries:field5,comentari:field6},function(result){  
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
		
		function verHorario(){  
            var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			url = './hor/hor_see.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function informeAssistencia(){  
		    var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;
			
			$('#c_alumne').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});

			url = './guard/guard_see.php?idgrups='+idgrups;
			$('#dlg_inf').dialog('open').dialog('setTitle','Assistencia del grup '+nomgrup);
			$('#dlg_inf').dialog('refresh', url);	
		}
		
		function imprimirInforme(){  
			var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
			
			url = './assist/assist_print.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function doReload(){
			var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;
			
			d_inici  = $('#data_inici').datebox('getValue');
			d_fi     = $('#data_fi').datebox('getValue');
			c_alumne = $('#c_alumne').combobox('getValue');
	
			url = './guard/guard_see.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne;
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function enviarSMS(){
		    var g = $('#classes').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
		}
		
		function tancar() {
		    javascript:$('#dlg_sms').dialog('close');
			open1('./guard/guard_grid.php');
			//$('#dg').datagrid('reload');
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