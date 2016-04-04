<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $idprofessors  = intval($_REQUEST['idprofessors']);
  $rsGrupsProfessor = getGrupsProfessor($idprofessors);
  
  $nom_professor = getProfessor($idprofessors,TIPUS_nom_complet);
  $cursactual    = isset($_REQUEST['curs'])        ? $_REQUEST['curs']        : 0;
  $cursliteral   = isset($_REQUEST['cursliteral']) ? $_REQUEST['cursliteral'] : '';
  $idTutorCarrec = getIdTutor()->idcarrecs;
    
 /* if(!is_object(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups))) {
	$idprofessors = 0;
	$nom_tutor    = "";
  }
  else {
	$idprofessors = getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups)->idprofessors;
	$nom_professor    = getProfessor($idprofessors)->nom." ".getProfessor($idprofessors)->cognoms;
  }*/
  
  $rsDies        = mysql_query("select * from dies_setmana where laborable='S'");
  $rsHores       = mysql_query("select * from franges_horaries order by hora_inici");
  $mode_impresio = isset($_REQUEST['mode_impresio']) ? $_REQUEST['mode_impresio'] : 0;
?>

<style type="text/css">

#header,
#footer {
  position: fixed;
  left: 0;
  right: 0;
  color: #aaa;
  font-size: 0.9em;
}

#header {
  top: 0;
  border-bottom: 0.1pt solid #aaa;
  margin-bottom:15px;
}

#footer {
  bottom: 0;
  border-top: 0.1pt solid #aaa;
}

#header table,
#footer table {
  width: 100%;
  border-collapse: collapse;
  border: none;
}

#header td,
#footer td {
  padding: 0;
  width: 50%;
}

.page-number {
  text-align: right;
}

.page-number:before {
  content: " " counter(page);
}

hr {
  page-break-after: always;
  border: 0;
}

</style>

<style type="text/css">
		.left{
			width:5px;
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

<?php
  	if ($mode_impresio) {
?>

<div id="header">
  <table>
    <tr>
      <td>
	  <b><?= getDadesCentre()->nom ?></b><br />
      <?= getDadesCentre()->adreca ?>&nbsp;&nbsp;
      <?= getDadesCentre()->cp ?>&nbsp;<?= getDadesCentre()->poblacio ?>
      </td>
      <td style="text-align: right;">
      		<?php
				$img_logo = '../images/logo.jpg';
                if (file_exists($img_logo)) {
                	echo "<img src='".$img_logo."'>";
				}
			?>
      </td>
    </tr>
  </table>
</div>

<div id="footer">
  <table>
    <tr>
      <td>
        <?= getDadesCentre()->tlf ?>&nbsp;&nbsp;<?= getDadesCentre()->email ?>
      </td>
      <td align="right">
  		<div class="page-number"></div>
      </td>
    </tr>
  </table>
</div>

<?php
  	}
?>

<div style="width:1000px;">
 <br />
 <h5 style="margin-bottom:0px">
  &nbsp;&nbsp;
  Curs escolar <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $cursliteral ?></a>&nbsp;&nbsp;
  Horari de <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $nom_professor ?></a>&nbsp;&nbsp;
 </h5> 
 
 <?php
	$total_grups = mysql_num_rows($rsGrupsProfessor);
	
	for($i=0; $i<$total_grups; $i++){	  
	  mysql_data_seek($rsGrupsProfessor,$i);
	  $row_a         = mysql_fetch_assoc($rsGrupsProfessor);
	  $idtorn_actual = $row_a['idtorn'];
	  
	  if ($i < $total_grups-1) {
		$j = $i+1;
	  }
	  else {
	    $j = $i;
	  }
	  //echo $i."--".$j."<br>";
	  
	  mysql_data_seek($rsGrupsProfessor,$j);
	  $row_a          = mysql_fetch_assoc($rsGrupsProfessor);
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
		<table border=0>
			<tr>
				<td class="blank" width="50"></td>
                <?php
				   mysql_data_seek($rsDies,0);
				   while($row = mysql_fetch_object($rsDies)){
				      echo "<td class='title' width=160>";
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
						echo "<td width='10' valign='top'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						echo "<td class='lunch' colspan='5'>ESBARJO</td>";
						echo "</tr>";
					  }
					  else {
						  echo "<tr>";
						  echo "<td valign='top'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
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
							    /*echo "<div style='border:2px dashed #ffcb00;margin-bottom:3px;'>";
								echo "<div style='color:#988600;border-bottom:1px solid #988600;'>GUARDIA</div>";
								echo "<div style='color:#b26f00'>".$row['espaicentre']."</div>";
								echo "</div>";*/
								
								echo "<div style='background:#f2b735;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>GUARDIA</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsDireccio = getDireccioDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsDireccio)) {
							    //echo "<div style='border:2px dashed #e76000;margin-bottom:3px;'>";
								//echo "<div style='color:#988600;border-bottom:1px solid #e76000;'>DIRECCIO</div>";
								//echo "<div style='color:#b26f00'>".$row['espaicentre']."</div>";
								
								echo "<div style=background:#b43624;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>DIRECCIO</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsCoordinacio = getCoordinacioDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsCoordinacio)) {
								echo "<div style='background:#0074c5;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>COORDINACIO</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsAtencio = getAtencionsDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsAtencio)) {
								echo "<div style='background:#359444;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>ATENCIONS</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsPermanencia = getPermanenciesDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsPermanencia)) {
								echo "<div style='background:#8b5632;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>PERMANENCIA</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
								echo "</div>";
							 }
							 
							 $rsReunio = getReunionsDiaHoraProfessor($dia,$franjahoraria,$cursactual,$idprofessors);
							 while ($row = mysql_fetch_assoc($rsReunio)) {
								echo "<div style='background:#562b9a;margin-bottom:3px;'>";
								echo "<div style='color:#fff;border-bottom:1px solid #fff;'>REUNIO</div>";
								echo "<div style='color:#fff'>".$row['espaicentre']."</div>";
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

<script type="text/javascript">
	$('#header').css('visibility', 'hidden');
	$('#footer').css('visibility', 'hidden');
</script>

<?php
if (isset($rsGrupsProfessor)) {
	mysql_free_result($rsGrupsProfessor);
}
if (isset($rsDies)) {
	mysql_free_result($rsDies);
}
if (isset($rsHores)) {
	mysql_free_result($rsHores);
}
if (isset($rsMateries)) {
	mysql_free_result($rsMateries);
}
if (isset($rsGuardies)) {
	mysql_free_result($rsGuardies);
}
if (isset($rsDireccio)) {
	mysql_free_result($rsDireccio);
}
if (isset($rsCoordinacio)) {
	mysql_free_result($rsCoordinacio);
}
if (isset($rsAtencio)) {
	mysql_free_result($rsAtencio);
}
if (isset($rsPermanencia)) {
	mysql_free_result($rsPermanencia);
}
if (isset($rsReunio)) {
	mysql_free_result($rsReunio);
}

mysql_close();
?>