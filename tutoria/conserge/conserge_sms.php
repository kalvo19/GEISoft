<?php
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $data   = isset($_REQUEST['data']) ? substr($_REQUEST['data'],6,4)."-".substr($_REQUEST['data'],3,2)."-".substr($_REQUEST['data'],0,2) : date("Y-m-d");
   
?>        
    <div id="alumnesDiv" style="float:left">    
    <table id="dg_sms" class="easyui-datagrid" title="Alumnes" style=" margin-right:5px;width:500px;height:auto"
			data-options="
				iconCls: 'icon-tip',
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#tb',
				url: './conserge/conserge_alumnes_getdata.php?data=<?=$data?>'
			">    
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="alumne" width="400" sortable="true">Alumne</th>
            </tr>  
        </thead>  
    </table>  
    </div>
    
    <?php include_once('../conserge/conserge_form_sms.php'); ?>
    
    <div id="tb" style="padding:5px;height:auto">
        <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="getSelections()">Accepta destinataris</a>
    </div>
    
    <script type="text/javascript">  
        var url;
		
		$('#dg_sms').datagrid({singleSelect:(this.value==1)})
		
		$(function(){  
            $('#dg_sms').datagrid({  
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
		
		function getSelections(){
			var ss = []; var ssm = [];
			var rows = $('#dg_sms').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
			var row = rows[i];
			  ss.push(row.idalumnes);
			  ssm = ssm + (row.alumne+'\n');
			}
			 
			url = './conserge/conserge_set_target.php?idalumnes='+ss;
			
			$.post(url,{},function(result){  
            if (result.success){  
			   
            } else {  
               $.messager.show({     
               title: 'Error',  
               msg: result.errorMsg  
               });  
               }  
			   $.messager.alert('no va');
             },'json');
			 //$.messager.alert('Destinataris inclosos correctament.Ja pots redactar el missatge i enviar.',ssm);
			 $.messager.alert('Informaci&oacute;','Destinataris inclosos correctament.Ja pots redactar el missatge o b&egrave; triar-lo predefinit i enviar.');
			 
			 $('#frm_sms').form('load',{
				destinataris:ssm,
			 });
			
		}

		function doReloadSMS(idgrups,nomgrup){
			url = './conserge/conserge_sms.php?idgrups='+idgrups;
			$('#dlg_fm_sms').dialog('refresh', url);
		}
		
		function enviarSMS(idgrups,nomgrup){
		    var rows = $('#dg_sms').datagrid('getSelections');
			if (rows) {  
			    var ss = [];
			    for(var i=0; i<rows.length; i++){
					var row = rows[i];
					ss.push(row.idalumnes);
				}
				url = './conserge/conserge_form_sms.php?idalumnes='+ss;
				$('#dlg_fm_sms').dialog('open').dialog('setTitle','Enviar SMS grup '+nomgrup);
				$('#dlg_fm_sms').dialog('refresh', url);
			}
		}

	</script>