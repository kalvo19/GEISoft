<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $idgrups         = intval($_REQUEST['idgrups']);
  $idtorn          = getGrup($idgrups)->idtorn;
  $cursactual      = isset($_REQUEST['curs'])        ? $_REQUEST['curs']        : 0;
  $cursliteral     = isset($_REQUEST['cursliteral']) ? $_REQUEST['cursliteral'] : '';
  $idTutorCarrec = getIdTutor()->idcarrecs;
  
  if(!is_object(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups))) {
	$idprofessors = 0;
	$nom_tutor    = "";
  }
  else {
	$idprofessors = getIdProfessorByCarrecGrup($idTutorCarrec,$idgrups)->idprofessors;
	$nom_tutor    = getProfessor($idprofessors,TIPUS_nom_complet);
  }
  
  $rsDies     = mysql_query("select * from dies_setmana where laborable='S'");
  $rsHores    = mysql_query("select * from franges_horaries where idtorn=".$idtorn." order by hora_inici");
   
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
		.esquerra{
			width:5px;
			float:left;
		}
		.esquerra table{
			background:#E0ECFF;
		}
		.esquerra td{
			background:#eee;
		}
		.dreta{
			float:right;
			width:1000px;
		}
		.dreta table{
			background:#E0ECFF;
			width:100%;
		}
		.dreta td{
			background:#fafafa;
			text-align:center;
			padding:2px;
		}
		.dreta td{
			background:#E0ECFF;
		}
		.dreta td.drop{
			background:#fafafa;
			width:95px;
		}
		.dreta td.over{
			background:#FBEC88;
		}
		.item_horari{
			text-align:center;
			border:1px solid #499B33;
			background:#fafafa;
			/*width:100px;*/
		}
		.assigned_horari{
			border:1px solid #BC2A4D;
		}
		
	</style>

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

<div style="width:1000px;">
 <h5 style="margin-bottom:0px">
  &nbsp;&nbsp;
  Curs escolar <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $cursliteral ?></a>&nbsp;&nbsp;
  Horari de <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= getGrup($idgrups)->nom ?></a>&nbsp;&nbsp;
  Tutor/a  <a style=" color: #000066; border:1px dashed #CCCCCC; padding:3px 3px 3px 3px ">
  <?= $nom_tutor ?></a>
 </h5>       
	<div class="esquerra">
		&nbsp;
	</div>
	<div class="dreta">
		<table>
			<tr>
				<td class="blank" width="5"></td>
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
						echo "<td class='time' valign='top'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						echo "<td class='lunch' colspan='5'>ESBARJO</td>";
						echo "</tr>";
					  }
					  else {
						  echo "<tr>";
						  echo "<td valign='top' class='time'>".substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)."</td>";
						  for ($dia=1; $dia<=5; $dia++) {
						     $rsMateries = getMateriesDiaHoraGrup($dia,$franjahoraria,$cursactual,$idgrups);
							 echo "<td valign='top' class='drop'>";
							
							 while ($row = mysql_fetch_assoc($rsMateries)) {
							    echo "<div style='border:2px solid #162b48;margin-bottom:3px;'>";
								echo "<div style='color:#b26f00;border-bottom:1px solid #b26f00;'>".$row['materia']."</div>";
								echo "<div style='color:#162b48;border-bottom:1px solid #162b48;'>".$row['espaicentre']."</div>";
								if(!is_object(getProfessorByGrupMateria($row['idgrups_materies']))) {
								  $nom_professor = " ";
								}
								else {
								  $nom_professor = getProfessor(getProfessorByGrupMateria($row['idgrups_materies'])->idprofessors,TIPUS_nom_complet);
								}
								
								echo "<div style='color:#39892f'>".$nom_professor."</div>";
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

<script type="text/javascript">
	$('#header').css('visibility', 'hidden');
	$('#footer').css('visibility', 'hidden');
</script>

<?php
mysql_free_result($rsDies);
mysql_free_result($rsHores);
mysql_close();
?>