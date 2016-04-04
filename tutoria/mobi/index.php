<?php
	session_start();
	include_once('../bbdd/connect.php');
	include_once('../func/constants.php');
	include_once('../func/generic.php');	
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Tutoria|Asistencia|Faltas</title>
    <meta name="Description" content="Gestión de faltas de assistencia">
    <meta name="Keywords" content="Tutoria,assitencia,aplicatiu,aplicatiu de tutoria,gestion faltas de asistencia,gestion horarios,gestion guardias,asistencia alumnos">
    <meta name="robots" content="index, follow" />
    <link rel="shortcut icon" type="image/x-icon" href="../images/icons/favicon.ico">  
    <link rel="stylesheet" type="text/css" href="../css/bootstrap/easyui.css">  
    <link rel="stylesheet" type="text/css" href="../css/icon.css">  
    <link rel="stylesheet" type="text/css" href="../css/demo.css"> 
    <script type="text/javascript" src="../js/jquery-1.8.0.min.js"></script>  
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    
    <script type="text/javascript">  
		function open1(url,a){
			currPageItem = $(a).text();
			$('body>div.menu-top').menu('destroy');
			$('body>div.window>div.window-body').window('destroy');
			$('#content').panel('refresh',url);
		}
	</script>
</head>
<body>

        <form id="ff" action="../mobi/login.php" method="post">
        <div style="text-align:center;margin:0px;overflow:hidden">
            <img src="../images/left_rib2.png" style="margin:0"> 
        </div>
        <div style="padding:0 5px">
            <div class="m-item" style="border-bottom:1px solid #eee">
                <div class="m-label">Usuari</div>
                <div>
                	<input class="m-input easyui-validatebox" name="login" placeholder="usuari">
                </div>
            </div>
            <div class="m-item">
                <div class="m-label">Contrasenya</div>
                <div><input class="m-input easyui-validatebox" name="passwd" type="password" placeholder="contrasenya"></div>
            </div>
            <div style="text-align:center;margin-top:10px">
                <a href="javascript:void(0)" class="easyui-linkbutton" style="width:50%" onClick="submitForm()"><span style="font-size:16px">Entrar-hi</span></a>  
            </div>
        </div>
        <div style="text-align:center;margin:6px;overflow:hidden">
            <?php
				$img_logo = '../images/logo.jpg';
                if (file_exists($img_logo)) {
                	echo "<img src='".$img_logo."'>";
				}
			?>
        </div>            
        </form>

    <style scoped>
        body {
			padding-left:5px;
			padding-top:5px;
			background:url(../images/logo_mobil.png) no-repeat;
		}
		.panel-title{
            text-align:center;
            font-size:20px;
            font-weight:bold;
            text-shadow:0 -1px rgba(0,0,0,0.3);
        }
        .m-item{
            height:30px;
            line-height:30px;
            padding:5px;
            background:#fff;
            color:#000;
			filter:alpha(opacity=70);-moz-opacity:.70;opacity:.70;
        }
        .m-label{
            float:left;
            width:100px;
            font-size:16px;
			font-weight:bolder;
			font-family: Geneva, Arial, Helvetica, sans-serif;
        }
        .m-input{
            height:30px;
            line-height:30px;
            font-size:16px;
            border:0;
            width:150px;
        }
    </style>
    
    <script>
        function submitForm(){  
			$('#ff').form('submit',{
				onSubmit: function(){
					return $(ff).form('validate');
				},
				success:function(data){
					//alert(data);
					
					var data = eval('(' + data + ')');
					
					if (data.error){
						$.messager.alert('Geisoft',data.message,'error');
					}
					
					if (data.login){
						top.location = '../mobi/home.php'
					}
				}
			});
			 
        }  
        function clearForm(){  
            $('#ff').form('clear');  
        }  
    </script>
    
</body>    
</html>