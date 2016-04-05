<?php
	//force redirect to secure page
    if($_SERVER['SERVER_PORT'] != '443') { 
		header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); 
		exit(); 
	}
	ini_set("session.cookie_lifetime","7200");
	ini_set("session.gc_maxlifetime","7200");
	session_start();
	include_once('./bbdd/connect.php');
	include_once('./func/constants.php');
	include_once('./func/generic.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Tutoria|Asistencia|Faltas</title>
    <meta name="Description" content="Gestión de faltas de assistencia">
    <meta name="Keywords" content="Tutoria,assitencia,aplicatiu,aplicatiu de tutoria,gestion faltas de asistencia,gestion horarios,gestion guardias,asistencia alumnos">
    <meta name="robots" content="index, follow" />
    <link rel="shortcut icon" type="image/x-icon" href="./images/icons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="./css/main.css" />
    <link rel="stylesheet" type="text/css" href="./css/cupertino/easyui.css">
    <link rel="stylesheet" type="text/css" href="./css/icon.css">  
    <link rel="stylesheet" type="text/css" href="./css/demo.css"> 
	
	<script type="text/javascript" src="./js/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="./js/jquery.easyui.min.js"></script>
    
    <script type="text/javascript">
	    var dispositivo = navigator.userAgent.toLowerCase();
	    if( dispositivo.search(/iphone|ipod|ipad|android|windows phone/) > -1 ) {
	      	window.location.href = './mobi/';  
		  }
		else {
		  }
	</script>

    <script type="text/javascript">  
		function loaddoc(doc){
			$.ajax({
			  url: doc,
			  cache: false
			}).done(function( html ) {
			  $("#content").html(html);
			});
		}
		function showcontent(doc){  
    		$('#content').html(doc);
		}
		
		function open1(url,a){
			currPageItem = $(a).text();
			$('body>div.menu-top').menu('destroy');
			$('body>div.window>div.window-body').window('destroy');
			$('#content').panel('refresh',url);
		}
		
		function open1area(url,a,area){
			currPageItem = $(a).text();
			$('body>div.menu-top').menu('destroy');
			$('body>div.window>div.window-body').window('destroy');
			$(area).panel('refresh',url);
		}
		
		function open2(plugin){
			if (plugin){
				currPlugin = plugin;
				currPageItem = '';
			}
			var href = '?plugin=' + currPlugin + '&theme=' + $('#cb-theme').combobox('getValue');
			href += '&dir=' + ($('#ck-rtl').is(':checked')?'rtl':'ltr');
			href += '&pitem=' + currPageItem;
			location.href = href;
		}
	</script>
<style type="text/css">
#capcha div {
    float: left;
} 
</style>
<!-- JavaScripts-->
<!-- <script type="text/javascript" src="./js/jquery.js"></script> -->
<script type="text/javascript" src="./js/s3Capcha.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#capcha').s3Capcha();
    });
</script>
    
</head>
<body class="easyui-layout">
	<div data-options="region:'north',border:false" style="height:46px;padding:1px;filter:alpha(opacity=80);-moz-opacity:.90;opacity:.90; overflow:hidden;) no-repeat top left; z-index:100;">
        <table width="100%" height="46" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><a href="index.php"><img src="images/left_ribbon.png" border="0"></a></td>
            <td  valign="top">
                <br><b></b>
            </td>
            <td align="right">&nbsp;
            
            </td>
          </tr>
        </table>  
    </div>
	<div data-options="region:'west',border:false,split:true,collapsed:false" style="width:100px;padding:1px;">
    </div> 
	
    <div data-options="region:'east',border:false,split:true,collapsed:false" style="width:100px;padding:10px;">  
    </div>
    
	<div data-options="region:'south',border:false" style="height:45px;;padding:10px;filter:alpha(opacity=70);-moz-opacity:.70;opacity:.70;">
    </div>
    
	<div id="content" data-options="region:'center',border:false" style="padding-left:85px;">
    	<h2>Identificació</h2>
        <div class="demo-info">
            <div class="demo-tip icon-tip"></div>
            <div>Introdueix el teu usuari i la teva contrasenya</div>
        </div>
        
        <?php
		  if (isset($_SESSION['errno']) && $_SESSION['errno']==1) {
		?>
        <div class="error-info">
            <div class="error-tip icon-no"></div>
            <div>Usuari i/o contrasenya erronis. Sisplau, torna a introduïr-los</div>
        </div>
		<?php
		  }
		?>
        <div class="easyui-panel" title="&nbsp;" style=" padding-left:30px;width:575px; filter:alpha(opacity=75);-moz-opacity:.75;opacity:.75;"> 
        <form id="ff" action="login.php" method="post">  
            <table>  
                <tr>  
                    <td align="right">Usuari</td>  
                    <td><input id="login" class="easyui-validatebox" type="text" size="50" name="login" data-options="required:false"></input></td>  
                </tr>  
                <tr>  
                    <td>Contrasenya</td>  
                    <td><input id="passwd" class="easyui-validatebox" type="password" size="50" name="passwd" data-options="required:false"></input></td>  
                </tr>  
                <tr>  
                    <td valign="top">&nbsp;</td>  
                    <td><div id="capcha"><div id="capcha"> <?php include("s3Capcha.php"); ?> </div><br /><br /><br /></td>  
                </tr>  
                <tr>
                    <td colspan="2">
                    <div style="padding-left:155px; padding-top:15px;"> 
                        <a href="javascript:void(0)" class="easyui-linkbutton" onClick="submitForm()">Entrar-hi</a>  
                        <a href="javascript:void(0)" class="easyui-linkbutton" onClick="clearForm()">Cancel.lar</a>  
                    </div>
                    </td>
                </tr>
             </table>
             <table>
                <tr>
            		<td>
                     <?php
                     $hosting        = 1; //0 per instal.lacions lliures
                     $modul_reg_prof = getModulsActius()->mod_reg_prof;
                     if (($hosting) AND ($modul_reg_prof))
                        {
                        print('<a id=\'registre\' title=\'Registre entrada\' href=\'javascript:void(0)\' onClick=\'registreProfessor(0)\'><img id=\'img_registre\' src=\'./images/icons/icon_login.png\' width=\'50\' border=\'0\'></a>&nbsp;&nbsp;&nbsp;&nbsp;');
						print('<a id=\'registre\' title=\'Registre sortida\' href=\'javascript:void(0)\' onClick=\'registreProfessor(1)\'><img id=\'img_registre\' src=\'./images/icons/icon_logout.png\' width=\'50\' border=\'0\'></a>');
                        }
                     else
                        {
                        print('<a id=\'registre\' title=\'Registre entrada\' href=\'javascript:void(0)\' ><img id=\'img_registre\' src=\'./images/icons/icon_login.png\' width=\'50\' border=\'0\'></a>&nbsp;&nbsp;&nbsp;&nbsp;');
						print('<a id=\'registre\' title=\'Registre sortida\' href=\'javascript:void(0)\' ><img id=\'img_registre\' src=\'./images/icons/icon_logout.png\' width=\'50\' border=\'0\'></a>');                        
                        }
                     ?>
                    </td>
        		</tr>
            </table>  
        </form>        
        </div>  
        
        <script>
        function submitForm(){  
			$('#ff').form('submit',{
				onSubmit: function(){
					return $(ff).form('validate');
				},
				success:function(data){
					var win = $.messager.progress({
						title:'Sisplau esperi un moment',
						msg:'Carregant dades...'
					});
					setTimeout(function(){
						$.messager.progress('close');
					},10000)

					top.location = 'home.php';
				}
			});
			 
        }  
        
		function clearForm(){  
            $('#ff').form('clear');  
        }
		
		function registreProfessor(op) {
			var url      = '';			
			
			if (($('#login').val()=='') || ($('#passwd').val()=='')) {
				return;
			}
			
			if (op==0) {
				url = './ctrl_prof/ctrl_prof_reg_in.php';
				
			}
			else {
				url = './ctrl_prof/ctrl_prof_reg_out.php';				
			}
			
			$('#ff').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    //alert(result);
					var result = eval('('+result+')');
					
                    if (result.login){
						$.messager.alert('Informaci&oacute;',result.msg,'info');
					}
					else if (result.error){
						$.messager.alert('Registre erroni',result.msg,'error');
                    }
                }
            });
		}
    </script>
    </div>
      
</body>
</html>
