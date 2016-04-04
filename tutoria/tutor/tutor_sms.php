<?php
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   $idgrups = $_REQUEST['idgrups'];
?>        
    <div id="alumnesDiv" style="float:left">    
    <table id="dg_sms" class="easyui-datagrid" title="Alumnes de <?=getGrup($idgrups)->nom?>" style=" margin-right:5px;width:500px;height:530px"
			data-options="
				singleSelect: true,
                pagination: false,
                rownumbers: true,
				toolbar: '#tb',
				url: './tutor/tutor_alum_getdata.php?idgrups=<?=$idgrups?>'	
            ">    
        <thead>  
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th field="Valor" width="400" sortable="true">Alumne</th>
            </tr>  
        </thead>  
    </table>
    </div>
    
    <?php include_once('../tutor/tutor_form_sms.php'); ?>
    
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
			  ssm = ssm + (row.Valor+'\n');
			}
			 
			url = './tutor/tutor_set_target.php?idalumnes='+ss;
			
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
			 $.messager.alert('Informaci&oacute;','Destinataris inclosos correctament.Ja pots redactar el missatge o b&egrave; triar-lo predefinit i enviar.');
			 
			 $('#frm_sms').form('load',{
				destinataris:ssm,
			 });
			
		}

		function doReloadSMS(idgrups,nomgrup){
			url = './tutor/tutor_sms.php?idgrups='+idgrups;
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
				url = './tutor/tutor_form_sms.php?idalumnes='+ss;
				$('#dlg_fm_sms').dialog('open').dialog('setTitle','Enviar SMS grup '+nomgrup);
				$('#dlg_fm_sms').dialog('refresh', url);
			}
		}

	</script>