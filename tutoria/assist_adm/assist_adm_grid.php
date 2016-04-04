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
    <table id="dg" class="easyui-datagrid" title="Control assist&egrave;ncia general" 
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './assist_adm/assist_adm_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="Valor" width="270" sortable="true">Alumne</th>
            </tr>
        </thead>
    </table>
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Dia&nbsp;<input id="data" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        &nbsp;
        Grups&nbsp;
        <select id="grups_combo" class="easyui-combogrid" style="width:350px" data-options="
            panelWidth: 350,
            idField: 'idgrups',
            textField: 'grup',
            url: url,
            method: 'get',
            columns: [[
                {field:'grup',title:'Grup',width:400},
            ]],
            fitColumns: true
        ">
        </select>
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        <img src="./images/line.png" height="1" width="100%" align="absmiddle" />
        <a id="gestio_faltes" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true,disabled:true"  plain="true" onclick="gestioFaltes()">Gesti&oacute; Assist&egrave;ncia</a>  
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true,disabled:true" onclick="verHorario()">Veure horari grup</a>&nbsp;
        <a id="assist_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-print',plain:true,disabled:true" onclick="informeAssistencia()">Informe Assist&egrave;ncia</a>
        <a id="sms_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,disabled:true" onclick="enviarSMS()">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>
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
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">  
	</div>
    
	<div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:850" border="0">  
        <tr>  
            <td colspan="2">
                <form method="post">
                Desde&nbsp;<input id="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a&nbsp;<input id="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
             </td>
        </tr>     
        <tr>
           <td> 	
                Per alumne&nbsp;<br />
                <select id="c_alumne" class="easyui-combobox" name="c_alumne" style="width:400px;" data-options="valueField:'idalumnes',textField:'Valor'">
                </select>
           </td>
           <td>
                &nbsp;Per mat&egrave;ria&nbsp;<br />
                &nbsp;<select id="c_materia" class="easyui-combobox" name="c_materia" style="width:400px;" data-options="valueField:'idmateria',textField:'materia'">
                </select>
           </td>
        </tr>
        <tr>
           <td colspan="2">
                Percentatge&nbsp;<input id="percentatge" class="easyui-numberbox" value="20" size="5" data-options="precision:0,required:true,min:0,max:100">&nbsp;%&nbsp;
                <a href="#" onclick="imprimirPDF()">
                <img src="./images/icons/icon_pdf.png" height="32"/></a>
                <a href="#" onclick="imprimirWord()">
                <img src="./images/icons/icon_word.png" height="32"/></a>
                <a href="#" onclick="imprimirExcel()">
                <img src="./images/icons/icon_excel.png" height="32"/></a>
                </form>
 		   </td>
        </tr>
        <tr>
        	<td colspan="2">  
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload()">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
	</div>
    
	<div id="dlg_sms" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" buttons="#dlg_sms-toolbar">  
	</div>
        
	<div id="dlg_sms-toolbar">  
         <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="tancar()">Tancar</a>  
	</div>
    
    <div id="dlg_fh" class="easyui-dialog" style="width:900px;height:600px;padding:5px 5px" maximized="true" maximizable="true" modal="true" closed="true">
        <table id="dg_fh" class="easyui-datagrid" title="Classes del grup" style="width:875px;height:555px"
                data-options="
                    iconCls: 'icon-edit',
                    singleSelect: true,
                    url:'./assist_adm/assist_adm_getdetail.php',
                    pagination: false,
                    rownumbers: true, 
                    toolbar: '#tb_fh_toolbar',
                    onClickRow: onClickRow
                ">
            <thead>
                <tr>
                    <th data-options="field:'ck',checkbox:true"></th>
                    <th field="dia_hora" width="100">Franja hor&agrave;ria</th>
                    <th field="nom_materia" width="500">Mat&egrave;ria</th>
                    <th field="descripcio" width="180">Espai centre</th>
                </tr>
            </thead>
        </table>
    </div>
    
    <div id="tb_fh_toolbar" style="height:auto">
		<a id="btnFalta" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="gestioIncident(<?=TIPUS_FALTA_ALUMNE_ABSENCIA?>,1)">Falta</a>
        <a id="btnRetard" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="gestioIncident(<?=TIPUS_FALTA_ALUMNE_RETARD?>,1)">Retard</a>
        <a id="btnRetard" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" onclick="gestioIncident(<?=TIPUS_FALTA_ALUMNE_SEGUIMENT?>,1)">Seguiment</a>
        <a id="btnJustificar" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="gestioIncident(<?=TIPUS_FALTA_ALUMNE_JUSTIFICADA?>,1)">Justificar</a>
        &nbsp;&nbsp;
        <a id="btnTreure" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="gestioAssistencia(0,0)">Treure incid&egrave;ncies</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-redo',plain:true" onclick="reject()">Tancar</a>
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>

    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		
		$('#dg').datagrid({singleSelect:(this.value==1)})
		$('#dg_fh').datagrid({singleSelect:(this.value==1)})
		
		$('#grups_combo').combogrid({
			url: 'nodata.php',
		});
		
		$('#grups_combo').combogrid({
			onSelect: function(date){	
				$('#gestio_faltes').linkbutton('enable');
				$('#horari_button').linkbutton('enable');
				$('#assist_button').linkbutton('enable');
				$('#sms_button').linkbutton('enable');
			}
		});
		
		$('#data').datebox({
			onSelect: function(date){
				var theDate = new Date(date);
				var theDay  = theDate.getDay();
				url = './assist_adm/classes_getdata.php?dia='+theDay;
				
				$('#grups_combo').combogrid({
					url: url,
				});
				
			}
		});
		
		$(function(){  
            $('#dg').datagrid({  
				view: detailview,  
                detailFormatter:function(index,row){
					return '<div class="ddv" style="padding:5px 0"></div>';
				},
				onExpandRow: function(index,row){
					var ddv = $(this).datagrid('getRowDetail',index).find('div.ddv');
					ddv.panel({
						border:false,
						cache:false,
						href:'./assist_dant/assist_dant_getdetail.php?id='+row.idalumnes,
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
		
		function gestioFaltes(){  
            var row = $('#dg').datagrid('getSelected');
			
            if (row){  		
				$('#dlg_fh').dialog('open').dialog('setTitle','Control assist&egrave;ncia');
				$('#dg_fh').datagrid('load',{ 
					idgrups: $('#grups_combo').combogrid('getValue'),
					data   : $('#data').datebox('getValue')  
     			});
            }  
        }
		
        function doSearch(){ 		   
			$('#dg').datagrid('load',{  
				idgrups   : $('#grups_combo').combogrid('getValue')
			});  
		} 
		
		function onClickRow(index){
		}
	
		function reject(){
		    $('#dg').datagrid('rejectChanges');
			editIndex = undefined;
			$('#dlg_fh').dialog('close');
		}
		
		function gestioAssistencia(tipus_incidencia,afegir){ 
		  var rows_alum  = $('#dg').datagrid('getSelections');
		  var rows_mat   = $('#dg_fh').datagrid('getSelections');
		  var rows_fh    = $('#dg_fh').datagrid('getSelections');
          var data       = $('#data').datebox('getValue');
		  
		  if (rows_alum && rows_mat){ 
			   var ss_alum = [];
			   for(var i=0; i<rows_alum.length; i++){
					var row = rows_alum[i];
					ss_alum.push(row.idalumnes);
			   }
			   var ss_mat = [];
			   var ss_fh  = [];
			   for(var i=0; i<rows_mat.length; i++){
					var row = rows_mat[i];
					ss_mat.push(row.idgrups_materies);
					ss_fh.push(row.idfranges_horaries);
			   }
			   url = './assist_adm/assist_adm_edita.php';
			   
			   $.messager.confirm('Confirmar','Introdu&iuml;m aquestes dades?',function(r){  
                    if (r){  
                        $.post(url,{
								data:data,
								id_tipus_incidencia:tipus_incidencia,
								afegir:afegir,
								idalumnes:ss_alum,
								idfranges_horaries:ss_fh,
								idgrups_materies:ss_mat},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Introducci&oacute; de faltes efectuada correctament!','info');
                            } else { 
							    $.messager.alert('Error','Introducci&oacute; de faltes efectuada erroniament!','error');
								 
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
		
		function gestioIncident(tipus_incidencia,afegir){ 
		  var rows_alum  = $('#dg').datagrid('getSelections');
		  var rows_mat   = $('#dg_fh').datagrid('getSelections');
		  var rows_fh    = $('#dg_fh').datagrid('getSelections');
          var data       = $('#data').datebox('getValue');
		  
		  if (rows_alum && rows_mat){ 
			   var ss_alum = [];
			   for(var i=0; i<rows_alum.length; i++){
					var row = rows_alum[i];
					ss_alum.push(row.idalumnes);
			   }
			   var ss_mat = [];
			   var ss_fh  = [];
			   for(var i=0; i<rows_mat.length; i++){
					var row = rows_mat[i];
					ss_mat.push(row.idgrups_materies);
					ss_fh.push(row.idfranges_horaries);
			   }
			   url = './assist_adm/assist_adm_edita.php';
			   
			   $.messager.prompt('Gesti&oacute; incid&egrave;ncies', 'Sisplau, introdueixi el comentari per la incid&egrave;ncia', function(r){
                    if (r){  
                        $.post(url,{
								data:data,
								id_tipus_incidencia:tipus_incidencia,
								afegir:afegir,
								comentari:r,
								idalumnes:ss_alum,
								idfranges_horaries:ss_fh,
								idgrups_materies:ss_mat},function(result){  
                            if (result.success){  
                                $.messager.alert('Informaci&oacute;','Introducci&oacute; d\' incid&egrave;ncies efectuada correctament!','info');
                            } else { 
							    $.messager.alert('Error','Introducci&oacute; d\' incid&egrave;ncies efectuada erroniament!','error');
								 
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
	
		function verHorario(){  
            var g = $('#grups_combo').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			url = './hor/hor_see.php?idgrups='+idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
			
			$('#dlg_hor').dialog('open').dialog('setTitle','Horari');
			$('#dlg_hor').dialog('refresh', url);
        }
		
		function informeAssistencia(){  
		    var g   = $('#grups_combo').combogrid('grid');
			var r   = g.datagrid('getSelected');
			idgrups = r.idgrups;
			nomgrup = r.grup;
			
			$('#c_alumne').combobox({
				url:'./tutor/alum_getdata.php?idgrups='+idgrups,
				valueField:'idalumnes',
				textField:'Valor'
			});
			
			$('#c_materia').combobox({
				url:'./tutor/materies_getdata.php?idgrups='+idgrups,
				valueField:'idmateria',
				textField:'materia'
			});
			
			url = './assist_adm/assist_adm_see.php?idgrups='+idgrups;
			
			$('#dlg_inf').dialog('open').dialog('setTitle','Assistencia del grup '+nomgrup);
			$('#dlg_inf').dialog('refresh', url);	
		}
		
		function imprimirPDF(){  
			var g     = $('#grups_combo').combogrid('grid');
			var r     = g.datagrid('getSelected');
			idgrups   = r.idgrups;
			
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirWord(){  
			var g     = $('#grups_combo').combogrid('grid');
			var r     = g.datagrid('getSelected');
			idgrups   = r.idgrups;
			
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print_word.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function imprimirExcel(){  
			var g     = $('#grups_combo').combogrid('grid');
			var r     = g.datagrid('getSelected');
			idgrups   = r.idgrups;
			
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
			
			url = './assist_adm/assist_adm_print_excel.php?data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idgrups='+idgrups+'&idmateria='+c_materia+'&percent='+percent;
			$('#fitxer_pdf').attr('src', url);
		}
		
		function doReload(){
			var g     = $('#grups_combo').combogrid('grid');
			var r     = g.datagrid('getSelected');
			idgrups   = r.idgrups;
			nomgrup   = r.grup;
			
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');
			c_alumne  = $('#c_alumne').combobox('getValue');
			if ($('#c_materia').combobox('getValue') == '') {
				c_materia = 0;
			}
			else {
				c_materia = $('#c_materia').combobox('getValue');
			}
			percent   = $('#percentatge').val();
	
			url = './assist_adm/assist_adm_see.php?idgrups='+idgrups+'&data_inici='+d_inici+'&data_fi='+d_fi+'&c_alumne='+c_alumne+'&idmateria='+c_materia+'&percent='+percent;
			
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function enviarSMS(){
		    var g = $('#grups_combo').combogrid('grid');
			var r = g.datagrid('getSelected');
			idgrups = r.idgrups;
			//nomgrup = r.grup;
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
		}
		
		function tancar() {
		    javascript:$('#dlg_sms').dialog('close');
			open1('./assist_adm/assist_adm_grid.php');
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