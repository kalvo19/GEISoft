<?php
	session_start();	 
	//mysql_query("SET NAMES 'utf8'");
	
	$id    = intval($_REQUEST['id']);
	$tipus = $_REQUEST['tipus']
?>

<div class="easyui-panel" title="Canvi contrasenya" style="width:675px; height:200px; filter:alpha(opacity=75);-moz-opacity:.75;opacity:.75;"> 
            <form id="fm" method="post" novalidate>
            <table>  
                <tr>  
                    <td align="right"><label style="width:150px;">Nova contrasenya:</label></td>  
                    <td><input id="contrasenya_1" name="contrasenya_1" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'"></td>  
                </tr>  
                <tr>  
                    <td align="right"><label style="width:150px;">Repeteixi contrasenya:</label></td>  
                    <td><input id="contrasenya_2" name="contrasenya_2" class="easyui-validatebox" type="password" data-options="required:true,validType:'length[3,20]'"></td>  
                </tr>  
                
                <tr>
                    <td colspan="2">
                    <div style="padding-left:255px; padding-top:15px;"> 
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveContrasenya()">Acceptar</a> 
                        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#fm').form('clear')">Cancel.lar</a>  
                    </div>
                    </td>
                </tr>
                 
            </table>  
            </form>  
        </div>   
    
    <script type="text/javascript">  
        var url = './<?=$tipus?>/<?=$tipus?>_update_passwd.php?id=<?=$id?>';
	
		function saveContrasenya(){
            var contrasenya_1 = $('#contrasenya_1').val();
			var contrasenya_2 = $('#contrasenya_2').val();
			if (contrasenya_1!=contrasenya_2) {
				 $.messager.alert('Error','Les contrasenyes no coincideixen! Sisplau, revisa-les.','error');
				return false;
			}
			
			$('#fm').form('submit',{
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
					    $.messager.alert('Informaci&oacute;','Contrasenya actualitzada correctament!','info');
                        $('#dlg_contrasenya').dialog('close');
                    }
                }
            });
        }
		

    </script>
