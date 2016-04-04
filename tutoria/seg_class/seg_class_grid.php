<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
    
  if ( isset($_REQUEST['idprofessor']) && ($_REQUEST['idprofessor']==0) ) {
  	$idprofessor = 0;
  }
  else if ( isset($_REQUEST['idprofessor']) ) {
    $idprofessor = $_REQUEST['idprofessor'];
  }
  if (! isset($idprofessor)) {
    $idprofessor = 0;
  }
?>

    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">
    <table id="dg" class="easyui-datagrid" title="Seguiment de les classes" style="height:540px;"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
                fixed: true,
				url: './seg_class/seg_class_getdata.php',
				onClickRow: onClickRow
			">    
        <thead>  
            <tr> 
            	<th data-options="field:'data',width:90">Data</th>
                <th data-options="field:'dia',width:90">Dia</th>
                <th data-options="field:'franja_horaria',width:110">Franja hor&agrave;ria</th>
                <th data-options="field:'lectiva',width:60,align:'center',
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
                ">Lectiva</th>
                
                <th data-options="field:'seguiment',width:320,editor:{type:'textarea',options:{required:true}}
                ">Seguiment</th>                
            </tr>  
        </thead>  
    </table>
  
    <div id="toolbar" style="height:auto; padding-top:7px; padding-bottom:7px;">
    &nbsp;Grup / Mat&egrave;ria<br />&nbsp;
    <select id="grups_materies" name="grups_materies" class="easyui-combogrid" style="width:610px" data-options="
            panelWidth: 610,
            idField: 'idagrups_materies',
            textField: 'materia',
            url: url,
            method: 'get',
            columns: [[
                {field:'grup',title:'Grup',width:170},
                {field:'materia',title:'Mat&egrave;ria',width:440}
            ]],
            fitColumns: true
    ">
    </select>
    <br /><br />
    &nbsp;Desde <input id="data_inici" name="data_inici" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
    Fins a <input id="data_fi" name="data_fi" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
    &nbsp;
    <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()">Cercar classes</a>
	<img src="./images/line.png" height="1" width="100%" align="absmiddle" />
    <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="accept()">Guardar seguiment</a>  
    &nbsp;&nbsp;&nbsp;
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="informeAssistencia()">Informe Assist&egrave;ncia</a>
    <img src="./images/line.png" height="1" width="100%" align="absmiddle" />
    <br /><i>&nbsp;NOTA: Per CCFF sortirà el periode comprés entre la data d'inici i fi de la UF</i>
    </div>   
    </div>
    
    
    <div id="dlg_inf" class="easyui-dialog" style="width:900px;height:600px;"   
            closed="true" collapsible="true" maximized="true" maximizable="true" resizable="true" modal="true" toolbar="#dlg_inf-toolbar">
	</div>
    
    <div id="dlg_inf-toolbar">
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <form method="post">
                Desde: <input id="data_inici_inf" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
        		Fins a: <input id="data_fi_inf" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>&nbsp;
                Percentatge&nbsp;<input id="percentatge" class="easyui-numberbox" value="80" size="4" data-options="precision:0,required:true,min:0,max:100">&nbsp;%&nbsp;
                <a href="#" onclick="imprimirPDF()"><img src="./images/icons/icon_pdf.png" height="32"/></a>
                <a href="#" onclick="imprimirWord()"><img src="./images/icons/icon_word.png" height="32"/></a>
                <a href="#" onclick="imprimirExcel()"><img src="./images/icons/icon_excel.png" height="32"/></a>
                </form>
                
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="doReload()">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_inf').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
    </div>
    
    <iframe id="fitxer_pdf" scrolling="yes" frameborder="0" style="width:10px;height:10px; visibility:hidden" src=""></iframe>
    
    <script type="text/javascript">  
		var editIndex = undefined;
		var url;
		var nou_registre = 0;
		
		$('#grups_materies').combogrid({
			url: 'nodata.php',
		});
						
		$('#grups_materies').combogrid({
			url: './seg_class/grup_materia_getdata.php?idprofessors=<?=$idprofessor?>',	
		});
		
		$(function(){  
            $('#dg').datagrid({             
				rowStyler:function(index,row){
					if (row.id_seguiment!=0){
						//return 'background-color:#c7d5ec;color:navy;';
						return 'background-color:#a1d88b;color:#009a49;font-weight:bold;';
					}
				}  
            });  
        });
		
		function doSearch(){ 
			editIndex = undefined;
			d_inici   = $('#data_inici').datebox('getValue');
			d_fi      = $('#data_fi').datebox('getValue');

			$('#dg').datagrid('load',{  
				idgrups_materies : $('#grups_materies').combobox('getValue'),
				data_inici       : $('#data_inici').datebox('getValue'),
				data_fi          : $('#data_fi').datebox('getValue')
			});
		}
		
		function informeAssistencia(){
			grup_materia = $('#grups_materies').combobox('getValue');
			url = './assist/assist_see.php?grup_materia='+grup_materia;
			$('#dlg_inf').dialog('open').dialog('setTitle','Informe assist&egrave;ncia');
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function imprimirPDF(){  
			grup_materia = $('#grups_materies').combobox('getValue');
			d_inici      = $('#data_inici_inf').datebox('getValue');
			d_fi         = $('#data_fi_inf').datebox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&percent='+percent+'&c_alumne=0';
			
			$('#fitxer_pdf').attr('src', url);		
		}
		
		function imprimirWord(){  
			grup_materia = $('#grups_materies').combobox('getValue');
			d_inici      = $('#data_inici_inf').datebox('getValue');
			d_fi         = $('#data_fi_inf').datebox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print_word.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&percent='+percent+'&c_alumne=0';
			
			$('#fitxer_pdf').attr('src', url);		
		}
		
		function imprimirExcel(){  
			grup_materia = $('#grups_materies').combobox('getValue');
			d_inici      = $('#data_inici_inf').datebox('getValue');
			d_fi         = $('#data_fi_inf').datebox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_print_excel.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&percent='+percent+'&c_alumne=0';
			
			$('#fitxer_pdf').attr('src', url);		
		}		
		
		function doReload(){
			grup_materia = $('#grups_materies').combobox('getValue');
			d_inici      = $('#data_inici_inf').datebox('getValue');
			d_fi         = $('#data_fi_inf').datebox('getValue');
			percent      = $('#percentatge').val();
			
			url = './assist/assist_see.php?grup_materia='+grup_materia+'&data_inici='+d_inici+'&data_fi='+d_fi+'&percent='+percent;
			
			$('#dlg_inf').dialog('refresh', url);
		}
		
		function onAfterEdit(rowIndex, rowData, changes){
			$('#dg').datagrid('reload');
			editIndex = undefined;
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
				var ed  = $('#dg').datagrid('getEditor', {index:editIndex,field:'id_seguiment'});
				$('#dg').datagrid('endEdit', editIndex);
				
				if (nou_registre) { 
					url = './seg_class/seg_class_nou.php';
					nou_registre = 0;
				}
				else {
					url = './seg_class/seg_class_edita.php?id='+$('#dg').datagrid('getRows')[editIndex]['id_seguiment'];
				}
				
				/*afterEdit(url,
						  row.id_dia_franja,
						  row.id_grup_materia,
						  row.lectiva,
						  row.seguiment,
						  row.data);*/
				
				editIndex = undefined;
				return true;
			} else {
				return false;
			}
		}
		
		function accept(){		
			if (endEditing()){				
				$('#dg').datagrid('acceptChanges');
				var row = $('#dg').datagrid('getSelected');
										
				if (row.id_seguiment==0) { 
					url = './seg_class/seg_class_nou.php';
					nou_registre = 0;
				}
				else {
					url = './seg_class/seg_class_edita.php?id='+row.id_seguiment;
				}
				saveItem(url,row);
				$('#dg').datagrid({             
					rowStyler:function(index,row){
						if (row.id_seguiment!=0){
							return 'background-color:#a1d88b;color:#009a49;font-weight:bold;';
						}
					}  
				}); 
			}
		}
		
		function saveItem(url,row){ 			
	
			$.post(url,{id_dia_franja:row.id_dia_franja,
						id_grup_materia:row.id_grup_materia,
						lectiva:row.lectiva,
						seguiment:row.seguiment,
						data:row.data},function(result){
							
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
		
		function afterEdit(url,field1,field2,field3,field4,field5){		
	
			$.post(url,{id_dia_franja:field1,id_grup_materia:field2,lectiva:field3,
						seguiment:field4,data:field5},function(result){  
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