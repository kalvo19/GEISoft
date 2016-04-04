<?php
	session_start();	 
	include_once('../bbdd/connect.php');
	include_once('../func/constants.php');
	include_once('../func/generic.php');
	mysql_query("SET NAMES 'utf8'");
	
	$url_horari = "";
	$url_passwd = "";
	$width_right_menu = 0;
					
	if ( !isset($_SESSION['usuari']) ) {
		header('Location: index.php');
		$idprofessors         = 0;
		$curs_escolar         = '';
		$curs_escolar_literal = '';
	}
	else {
		$idprofessors         = isset($_SESSION['professor']) ? $_SESSION['professor'] : 0;
		$idalumnes            = isset($_SESSION['alumne'])    ? $_SESSION['alumne']    : 0;
		$curs_escolar         = $_SESSION['curs_escolar'];
		$curs_escolar_literal = $_SESSION['curs_escolar_literal'];
	}
?>
<!DOCTYPE html>
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
		var registre_entrada = 'NOT_INIT';
		
		function open1(url,a){
			currPageItem = $(a).text();
			$('body>div.menu-top').menu('destroy');
			$('body>div.window>div.window-body').window('destroy');
			$('#content').panel('refresh',url);
		}
		
		function registreEntrada(op,idprofessors) {
			var url   = '';
			var texte = '';
			var operacio;
			
			if (registre_entrada == 'NOT_INIT') {
				operacio = op;
			}
			else {
				operacio = registre_entrada;
			}


			if (operacio) {
				url              = '../ctrl_prof/ctrl_prof_reg_in.php';
				texte            = 'Registre Sortida';
				txt_confirm      = 'Registrem l\'entrada?';
				icono			 = 'icon-undo';
				registre_entrada = 0;
			}
			else {
				url              = '../ctrl_prof/ctrl_prof_reg_out.php';
				texte            = 'Registre Entrada';
				txt_confirm      = 'Registrem la sortida?';
				icono			 ='icon-redo';
				registre_entrada = 1;
			}
			$.messager.confirm('Confirmar',txt_confirm,function(r){  
                    if (r){  
                        $.post(url,{idprofessors:idprofessors},function(result){  
                            if (result.success){  
                                $('#registre').linkbutton({				
									text: texte,
									iconCls: icono,
									plain: true,
								});
                            } else { 
							    $.messager.alert('Error','Registre d\'entrada erroni!','error');
                            }  
                        },'json'); 
                    }  
            });
		}
		
		function sortir(idprofessors) {
			var url = '../ctrl_prof/ctrl_prof_reg_out_home.php';
			
			if (idprofessors == 0) {
				location.href = '../logout.php'; 
			}
			else {
				$.messager.confirm('Confirmar','VOLS SORTIR DEL SISTEMA?',function(r){  
				if (r){
					location.href = '../logout.php';
				}
				});

			}
       }
	</script>
</head>
<body class="easyui-layout">
    <div data-options="region:'north',border:true" style="overflow:hidden">
        <table width="100%" height="45" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="155"><a href="home.php"><img src="../images/left_ribbon.png" border="0"></a></td>
            <td valign="top" width="60">
                <?php
				$img_prof_path = '../images/prof/'.$idprofessors.'.jpg';
                if (file_exists($img_prof_path)) {
                	echo "<img src='".$img_prof_path."' height='45'>";
				}
				?>
            </td>
            
            <td valign="top">
                <?php 
				if (isset($_SESSION['professor'])) {
					echo "Hola&nbsp;<strong>".getProfessor($idprofessors,TIPUS_nom_complet)."</strong>";
				}
				else if (isset($_SESSION['alumne'])){
					echo "Hola&nbsp;<strong>".getAlumne($idalumnes,TIPUS_nom_complet)."</strong>";
				}
				?>
                
                <br>
                Curs&nbsp;<strong><?= $curs_escolar_literal ?></strong>
            </td>
            <td valign="top">
            	<?php 
				if (isset($_SESSION['professor'])) {
            		$hora_entra_centre = strtotime(getLastLogProfessor($idprofessors,date("y-m-d"),TIPUS_ACCIO_ENTROALCENTRE));
				    $hora_surt_centre  = strtotime(getLastLogProfessor($idprofessors,date("y-m-d"),TIPUS_ACCIO_SURTODELCENTRE));
					
					if ($hora_entra_centre > $hora_surt_centre) {
						//echo "<a id='registre' href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-undo' plain='true' onClick='registreEntrada(0,".$idprofessors.")'>Registre Sortida</a>";
					}
					else {
						//echo "<a id='registre' href='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-redo' plain='true' onClick='registreEntrada(1,".$idprofessors.")'>Registre Entrada</a>";
					}
				}
				?>
            </td>
            <td valign="top" align="right">
               <a href="javascript:void(0)" title="Sortir del sistema" onClick="sortir(<?=$idprofessors?>)" class="easyui-tooltip">
               <img src="../images/logout.png" height="25" border="0"></a>
            </td>
          </tr>
        </table>
    </div>
    
    <div data-options="region:'south',border:false" style="overflow:hidden">
        <div class="panel-header" style="padding:0 0 0 5px;border-width:1px 0;">
            <span class="panel-title" style="line-height:30px">
            	<strong><?=getDiaSetmana(date('w'))?>,&nbsp;<?=date('d')?></strong>
                &nbsp;de&nbsp;<strong><?=getMes(date('m'))?>&nbsp;</strong>del&nbsp;<strong><?=date('Y')?>.</strong>
                <strong><?= date("H:i")." h"?>.&nbsp;</strong>
            </span>
            <div style="clear:both"></div>
        </div>
    </div>
    
    <div data-options="region:'center',border:false">
    <h2>Les teves classes d'avui</h2>
    <table width="100%" class="taula">
    <?php  
      $rsFranges    = getFrangesHoraries();				
      while ($row = mysql_fetch_assoc($rsFranges)) {
	  	$aquesta_classe = 0;
		$rsFrangesTorns = comprovarHoraDiaTorn(date('H:i'));
				  
		while ($row_torn = mysql_fetch_assoc($rsFrangesTorns)) {						
			if ($row['idfranges_horaries'] == $row_torn['idfranges_horaries']) {
				$aquesta_classe = 1;
			}
		}
				  
	  $rsGuardia = getGuardiaDiaHoraProfessor(date('w'),$row['idfranges_horaries'],$curs_escolar,$idprofessors);
	  while ($row_g = mysql_fetch_assoc($rsGuardia)) {
		$link    = "<a style='color:#333; text-decoration:none;' href='guard_classes.php?act=1&idprofessors=".$idprofessors.'&idfranges_horaries='.$row['idfranges_horaries'].'&hora='.$row['hora_fi']."'>";
		$fi_link = "</a>";
		if ($aquesta_classe) {
			echo "<tr style='background:url(../images/fons_quadre_classe_actual.png)'>";
		}
		else {
			echo "<tr style='background:url(../images/fons_quadre_guardia.png)'>";
		}
		
		echo "<td style='border:1px solid #ccc'><div style='font-size:16px;font-weight:bold'>$link".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."$fi_link</div>";
		echo "<div style='font-size:14px;line-height:18px'>".$link."GU&Agrave;RDIA".$fi_link."</div>";
		echo "<div style='font-size:14px;line-height:18px'>$link".$row_g['espaicentre']."$fi_link</div>";
		echo "</tr>";
	  }
				  
	  $rsMateries = getMateriesDiaHoraProfessor(date('w'),$row['idfranges_horaries'],$curs_escolar,$idprofessors);
	  while ($rowm = mysql_fetch_assoc($rsMateries)) {
	    $fi_link = "</a>";
						
		if ($aquesta_classe) {					  
		    $link      = "<a style='color:#162b48; text-decoration:none;' href='grid.php?act=1&idprofessors=".$idprofessors."&idgrups=".$rowm['idgrups']."&idmateria=".$rowm['idmateria']."&idfranges_horaries=".$row['idfranges_horaries']."&idespais_centre=".$rowm['idespais_centre']."'>";
						
			echo "<tr style='background:url(../images/fons_quadre_classe_actual.png)'>";
			echo "<td style='border:1px solid #ccc'><div style='font-size:16px;font-weight:bold'>$link".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['materia']."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['grup']."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['espaicentre']."$fi_link</div></td>";
			echo "</tr>";
		}
		else {
			$link      = "<a style='color:#162b48; text-decoration:none;' href='grid.php?act=0&idprofessors=".$idprofessors."&idgrups=".$rowm['idgrups']."&idmateria=".$rowm['idmateria']."&idfranges_horaries=".$row['idfranges_horaries']."&idespais_centre=".$rowm['idespais_centre']."'>";
			
			echo "<tr style='background:#F5F5F5'>";
			echo "<td style='background:#F0F8FF;border:1px solid #ccc'><div style='font-size:16px;font-weight:bold'>$link".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['materia']."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['grup']."$fi_link</div>";
			echo "<div style='font-size:14px;line-height:18px'>$link".$rowm['espaicentre']."$fi_link</div></td>";
			echo "</tr>";
		}
		
	 }
	}
	
	if (isset($rsFranges)) {
		mysql_free_result($rsFranges);
	}
	if (isset($rsFrangesTorns)) {
		mysql_free_result($rsFrangesTorns);
	}
	if (isset($rsGuardia)) {
		mysql_free_result($rsGuardia);
	}
	if (isset($rsMateries)) {
		mysql_free_result($rsMateries);
	}
	?>
    </table>
    <br /><br />      
    </div>
    
    <style scoped>
        .panel-title{
            text-align:center;
            font-size:14px;
            font-weight:bold;
            text-shadow:0 -1px rgba(0,0,0,0.3);
        }
        .datagrid-row{
            height:55px;
            background-color:#fff;
            color:#666;
        }
        .datagrid-row td{  
            border-width:0 0 1px 1px;
            border-style:solid;
        }  
        .datagrid-row td:last-child{  
            border-width:0 1px 1px 0;
        }
        .arrow{	
            width:6px;
            height:6px;
            border:2px solid #888;
            border-width:2px 2px 0 0;
            -webkit-transform:rotate(5deg);
        }  
		a {
			text-decoration:none;
			color:#333333;
		}
    </style>
</body>
</html>

<?php
mysql_free_result($rsFranges);
mysql_free_result($rsGuardia);
mysql_free_result($rsMateries);
mysql_close();
?>