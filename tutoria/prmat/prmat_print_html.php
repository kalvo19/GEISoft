<?php
  session_start();
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  
  $idprofessors  = intval($_REQUEST['idprofessors']);
  $nom_professor = getProfessor($idprofessors,TIPUS_nom_complet);
  $cursactual    = $_SESSION['curs_escolar'];
  $idTutorCarrec = getIdTutor()->idcarrecs;
  
  $rsDies     = mysql_query("select * from dies_setmana where laborable='S'");
  $rsHores    = mysql_query("select * from franges_horaries order by hora_inici");   
?>

	<link rel="shortcut icon" type="image/x-icon" href="../images/icons/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" href="../css/cupertino/easyui.css">
    <link rel="stylesheet" type="text/css" href="../css/icon.css">  
    <link rel="stylesheet" type="text/css" href="../css/demo.css">
    
	<style type="text/css">
		.left{
			width:20px;
			float:left;
		}
		.left table{
			background:#E0ECFF;
		}
		.left td{
			background:#eee;
		}
		.right{
			float:right;
			width:950px;
		}
		.right table{
			background:#E0ECFF;
			width:100%;
		}
		.right td{
			background:#fafafa;
			text-align:center;
			padding:2px;
		}
		.right td{
			background:#E0ECFF;
		}
		.right td.drop{
			background:#fafafa;
			width:155px;
		}
		.right td.over{
			background:#FBEC88;
		}
		.item{
			text-align:center;
			border:1px solid #499B33;
			background:#fafafa;
			/*width:100px;*/
		}
		.assigned{
			border:1px solid #BC2A4D;
		}
		
	</style>

<div style="width:1000px;">
 <h5 style="margin-bottom:0px">
  &nbsp;&nbsp;
  Curs escolar <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $_SESSION['curs_escolar_literal'] ?></a>&nbsp;&nbsp;
  Horari de <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $nom_professor ?></a>&nbsp;&nbsp;
 </h5>
 <br />
	<div class="left">
		&nbsp;
	</div>
	<div class="right">
		<table>
			<tr>
				<td class="blank" width="50"></td>
                <?php
				   while($row = mysql_fetch_object($rsDies)){
				      echo "<td class='title'>";
					  echo $row->dies_setmana;
					  echo "</td>";
				   }
				?>
			</tr>
			
                <?php
				   while($row = mysql_fetch_object($rsHores)){
				      $franjahoraria = $row->idfranges_horaries;
					  if ($row->esbarjo=='S') {
					    echo "<tr height='20'>";
						echo "<td width='30' class='time' valign='top'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						echo "<td class='lunch' colspan='5'>ESBARJO</td>";
						echo "</tr>";
					  }
					  else {
						  echo "<tr>";
						  echo "<td valign='top' class='time'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						  for ($dia=1; $dia<=5; $dia++) {
							 echo "<td valign='top' class='drop'>";
							 $rsMateries = getMateriesDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsMateries)) {
							    echo "<div style='border:2px solid #162b48;margin-bottom:3px;'>";
								echo "<div style='color:#cc092f;border-bottom:1px solid #cc092f;'>".$row['materia']."</div>";
								echo "<div style='color:#39892f;border-bottom:1px solid #39892f;'>".$row['grup']."</div>";
								echo "<div style='color:#162b48;border-bottom:1px solid #162b48;'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsGuardies = getGuardiaDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsGuardies)) {
							    echo "<div style='border:2px dashed #ffcb00;margin-bottom:3px;'>";
								echo "<div style='color:#988600;border-bottom:1px solid #988600;'>GUARDIA</div>";
								echo "<div style='color:#b26f00'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 echo "</td>";
						  }
						  echo "</tr>";
					  }
				   }
				?>          
		</table>
	</div>
</div>

<?php
mysql_free_result($rsDies);
mysql_free_result($rsHores);
mysql_close();
?>