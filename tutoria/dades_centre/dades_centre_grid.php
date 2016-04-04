<?php
   session_start();
   include_once('../bbdd/connect.php');
   include_once('../func/generic.php');
   include_once('../func/constants.php');
   mysql_query("SET NAMES 'utf8'");
   
   if (isset($_SESSION['sortida'])) {
   		unset($_SESSION['sortida']);
   }
   
   $fechaSegundos = time();
   $strNoCache = "?nocache=$fechaSegundos";
?>        
    <div id="dlg_main" class="easyui-panel" style="width:auto;height:auto;">       
    <div class="easyui-panel" title="Dades centre" style="width:auto; height:auto;" data-options="buttons:'#dlg_buttons'"> 
        	<form id="fm_dades_centre" method="post" novalidate>
            <div>  
            	<label>Nom Centre</label><br />
                <input id="nom" name="nom" size="90" class="easyui-validatebox" data-options="required:true,validType:'length[3,300]'">
            </div>
            
            <div>  
            	<label>Adre&ccedil;a</label><br />
                <input id="adreca" name="adreca" size="90" class="easyui-validatebox" data-options="required:true,validType:'length[3,300]'">
            </div>
            
            <div>  
            	<label>Codi Postal</label><br />
                <input id="cp" name="cp" size="15" class="easyui-validatebox" data-options="required:true,validType:'length[5,8]'">
            </div>
            
            <div>  
            	<label>Poblaci&oacute;</label><br />
                <input id="poblacio" name="poblacio" size="90" class="easyui-validatebox" data-options="required:true,validType:'length[3,200]'">
            </div>
            
            <div>  
            	<label>Tlf</label><br />
                <input id="tlf" name="tlf" size="30" class="easyui-validatebox" data-options="required:true,validType:'length[3,40]'">
            </div>
            
            <div>  
            	<label>Fax</label><br />
                <input id="fax" name="fax" size="30" class="easyui-validatebox" data-options="required:true,validType:'length[3,40]'">
            </div>
            
            <div>  
            	<label>EMail</label><br />
                <input id="email" name="email" size="90" class="easyui-validatebox" data-options="required:true,validType:'length[3,100]'">
            </div>
            <br />
            <div>  
            	<label>Professors poden enviar SMS's?</label>&nbsp;
                <input id="prof_env_sms" name="prof_env_sms" type="radio" value="1">S&iacute;&nbsp;
                <input id="prof_env_sms" name="prof_env_sms" type="radio" value="0">No
            </div>
        	</form>
    </div>
    </div>
        
    <div id="dlg_buttons">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr height="30">  
                <td align="right">
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveDadesCentre()">Canviar dades centre</a>
                    &nbsp;
                    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="pujarLogo()">Pujar logo</a>
                </td>
            </tr>  
        </table>  
    </div>
    
    <div id="dlg_upload" class="easyui-dialog" style="width:900px;height:600px;"  
            closed="true" collapsible="true" resizable="true" modal="true" maximized="true" maximizable="true" toolbar="#dlg_upload-toolbar">
    </div>
        
    <div id="dlg_upload-toolbar">
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>  
                <td>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_upload').dialog('refresh')">Recarregar</a>
                    <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" onclick="javascript:tancarFoto()">Tancar</a>  
                </td>
            </tr>  
        </table>  
    </div>
    
    <script type="text/javascript">  
        var url ;		
		
		$('#fm_dades_centre').form('load','./dades_centre/dades_centre_load.php');
		
		function saveDadesCentre(){			
			url = './dades_centre/dades_centre_edita.php';
			
			$('#fm_dades_centre').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){					
					var result = eval('('+result+')');					
                    if (result.msg){
                        $.messager.show({
                            title: 'Error',
                            msg: result.msg
                        });
                    } else {
					    $.messager.alert('Informaci&oacute;','Dades centre actualitzades correctament!','info');
                    }
                }
            });
        }
		
		function pujarLogo(){ 
				url = './dades_centre/dades_centre_upload_photo.php';
				$('#dlg_upload').dialog('open').dialog('setTitle','Pujar logo');
				$('#dlg_upload').dialog('refresh', url);
		}
		
		function esborrarLogo(){ 
		    $.messager.confirm('Confirmar','Esborrem aquest logo?',function(r){
				var row = $('#dg').datagrid('getSelected');
				if (row){
					url = './dades_centre/esborra_logo.php';
					$.post(url,{},function(result){  
					if (result.success){ 
						$.messager.alert('Informaci&oacute;','Logo esborrat correctament!','info');
						$('#dg').datagrid('reload');
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
		
		function tancarFoto(){ 
			$('#dlg_upload').dialog('close');
			open1('./dades_centre/dades_centre_grid.php',this);
		}
		
	</script>
        
    <style type="text/css">  
        #fm_dades_centre{  
            margin:0;  
            padding:5px 30px;  
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