<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $idprofessors  = intval(1);
  $rsGrups       = getGrups(4);
  
  $cursactual    = isset($_REQUEST['curs'])        ? $_REQUEST['curs']        : 0;
  $cursliteral   = isset($_REQUEST['cursliteral']) ? $_REQUEST['cursliteral'] : '';
  
 /* if(!is_object(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups))) {
	$idprofessors = 0;
	$nom_tutor    = "";
  }
  else {
	$idprofessors = getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups)->idprofessors;
	$nom_professor    = getProfessor($idprofessors)->nom." ".getProfessor($idprofessors)->cognoms;
  }*/
  
  //$rsDies  = mysql_query("select * from dies_setmana where laborable='S'");
  $rsDies  = mysql_query("select * from dies_setmana where laborable='S'");
  $rsHores = mysql_query("select * from franges_horaries order by hora_inici");
?>

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
			width:1000px;
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
			width:95px;
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
  <?= $cursliteral ?></a>&nbsp;&nbsp;
  Taula global de gu&agrave;rdies&nbsp;&nbsp;
 </h5> 
 
 <?php
	$total_grups = mysql_num_rows($rsGrups);
	
	for($i=0; $i<$total_grups; $i++){	  
	  mysql_data_seek($rsGrups,$i);
	  $row_a         = mysql_fetch_assoc($rsGrups);
	  $idtorn_actual = $row_a['idtorn'];
	  
	  if ($i < $total_grups-1) {
		$j = $i+1;
	  }
	  else {
	    $j = $i;
	  }
	  //echo $i."--".$j."<br>";
	  
	  mysql_data_seek($rsGrups,$j);
	  $row_a          = mysql_fetch_assoc($rsGrups);
	  $idtorn_seguent = $row_a['idtorn'];
	  
	  //echo $idtorn_actual."--".$idtorn_seguent."<br>";
	  
	  if (($idtorn_actual != $idtorn_seguent) || (($i == $j) && ($idtorn_actual == $idtorn_seguent))) {
		  echo "<h5 style='margin-bottom:0px'>";
		  echo "&nbsp;&nbsp;&nbsp;Torn-->&nbsp;".getTorn($idtorn_actual)->nom_torn;
		  echo "</h5>"; 
		  $rsHores    = mysql_query("select * from franges_horaries where idtorn=".$idtorn_actual." order by hora_inici");  
 ?>      
	<div class="left">
		&nbsp;
	</div>
	<div class="right">
		<table>
			<tr>
				<td class="blank" width="50"></td>
                <?php
				   mysql_data_seek($rsDies,0);
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
							 
							 $rsGuardies = getGuardiaDiaHora($dia,$franjahoraria,$cursactual);
							 while ($row = mysql_fetch_assoc($rsGuardies)) {
							    /*echo "<div style='border:2px solid #87741e;margin-bottom:3px; background:#fff6ce;height:85px;'>";
								echo "<div style='color:#988600;border-bottom:1px solid #988600;height:50px;'>".getProfessor($row['idprofessors'],TIPUS_nom_complet)."</div>";
								echo "<div style='color:#c99900'>".$row['espaicentre']."</div>";
								echo "</div>";*/
								
								echo "<div style='border:2px solid #87741e;margin-bottom:3px;background:#f2b735;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>".getProfessor($row['idprofessors'],TIPUS_nom_complet)."</div>";
								echo "<div style='color:#87741e'>".$row['espaicentre']."</div>";
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

<?php
		}
	}
?> 

</div>
 
<?php
mysql_free_result($rsGrups);
mysql_free_result($rsDies);
mysql_free_result($rsHores);
mysql_close();
?>