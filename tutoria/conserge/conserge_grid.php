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
    <table id="dg" class="easyui-datagrid" title="Assist&egrave;ncia del dia" style="height:540px;" 
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#toolbar',
				url: './conserge/conserge_getdata.php'
			">    
        <thead>  
            <tr>
            	<!--<th field="idincidencia_alumne" width="100" sortable="true" align="center">ID</th>--> 
                <th field="dia_hora" width="85" sortable="true" align="center">Hora</th> 
                <th field="alumne" width="270" sortable="true">Alumne</th>
                <th field="grup" width="170" sortable="true" align="center">Grup</th>
                <th data-options="field:'tipus_falta',width:120" sortable="true" align="center">Tipus falta</th>
                <th sortable="true" align="left" data-options="field:'id_tipus_incident',width:280,
						formatter:function(value,row){
                            if (row.id_tipus_incidencia==<?=TIPUS_FALTA_ALUMNE_SEGUIMENT?>) {
                            	return row.tipus_incident;
                            }
                            else {
                            	return '';
                            }
						}">Tipus incident</th>
            </tr>  
        </thead>  
    </table> 
    
    <div id="toolbar" style="padding:5px;height:auto">  
        Dia&nbsp;<input id="data" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"></input>
        
        <a href="#" class="easyui-linkbutton" iconCls="icon-search" onclick="doSearch()"></a>
        &nbsp;
        <!--<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="accept()">Acceptar canvis</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Cancel.lar</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="destroyItem()">Esborrar entrada</a>-->
        &nbsp;&nbsp;
        <a id="horari_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-tip',plain:true" onclick="verHorario()">Veure horari grup</a>&nbsp;
        <a id="sms_button" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="enviarSMS()">
        <img src="./images/envelope.png" height="16" align="absbottom" />&nbsp;Enviar SMS</a>
    </div>
    </div>
    
    <div id="dlg_hor_c" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" maximized="true" maximizable="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
    </div>
    
    <div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor_c').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:$('#dlg_hor_c').dialog('close')">Tancar</a>  
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

    <script type="text/javascript">  
        var url;
		var editIndex = undefined;
		var nou_registre = 0;
		var idgrups;
		var nom_grup;
		var theDate;
		var theDay;
		
		$('#data').datebox({
			onSelect: function(date){
				theDate = new Date(date);
				theDay  = theDate.getDay();				
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
						href:'./tutor/tutor_getdetail.php?id='+row.idincidencia_alumne,
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
			$('#dg').datagrid('load',{  
				data : $('#data').datebox('getValue')
			});  
		} 
			
		function verHorario(){
		    var row = $('#dg').datagrid('getSelected');
            editIndex = undefined;
			
			if (row){ 			     
				url = './hor/hor_see.php?idgrups='+row.idgrups+'&curs=<?=$_SESSION['curs_escolar']?>&cursliteral=<?=$_SESSION['curs_escolar_literal']?>';
				$('#dlg_hor_c').dialog('open').dialog('setTitle','Horari de '+row.grup);
				$('#dlg_hor_c').dialog('refresh', url);
            }
        }
		
		function enviarSMS(){
			var data = $('#data').datebox('getValue');
			url = './conserge/conserge_sms.php?data='+data;
			$('#dlg_sms').dialog('open').dialog('setTitle','Enviar SMS');
			$('#dlg_sms').dialog('refresh', url);
		}
		
		function tancar() {
		    javascript:$('#dlg_sms').dialog('close');
			//location.href = './conserge/conserge_grid.php';
			open1('./conserge/conserge_grid.php');
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