<?php

/*  ********************************************************************************************************
	festiu --> data es o no festiva
************************************************************************************************************ */
function festiu($data,$periode) {      
   $sql = "SELECT festiu FROM periodes_escolars_festius ";
   $sql.= "WHERE id_periode=$periode AND festiu='$data'";
   $rec = mysql_query($sql);

   $diasetmana = date_format(date_create($data), 'l');
   $count = 0;
   $result = "";
   while($row = mysql_fetch_object($rec)) {
	  	$count++;
		$result = $row;
   }
   mysql_free_result($rec);
   
   if ($count==1) {
	    return 1;
   }
   else if ($diasetmana=='Saturday' || $diasetmana=='Sunday') {
	   	return 1;
   }
   else {
	 	return 0;
   }
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	daysCatalan --> Torna el equivalent del dia de la setmana en català
************************************************************************************************************ */
function daysCatalan($diasetmana) {      
   $daysCatalan = array("Sunday" => "Diumenge","Monday" => "Dilluns","Tuesday" => "Dimarts","Wednesday" => "Dimecres","Thursday" => "Dijous","Friday" => "Divendres","Saturday" => "Dissabte",);
   
   return $daysCatalan["$diasetmana"];
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	daysSpanish --> Torna el equivalent del dia de la setmana en espanyol
************************************************************************************************************ */
function daysSpanish($diasetmana) {      
   $daysCatalan = array("Sunday" => "Domingo","Monday" => "Lunes","Tuesday" => "Martes","Wednesday" => "Mi&eacute;rcoles","Thursday" => "Jueves","Friday" => "Viernes","Saturday" => "S&aacute;bado",);
   
   return $daysCatalan["$diasetmana"];
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	extreuDies --> Dies no festius entre dues dates
************************************************************************************************************ */
function extreu_dies($start,$end,$periode) {
   $startdate = strtotime($start);
   $enddate   = strtotime($end);
   $dates     = array();
   
   if($startdate > $enddate){
        return false; 
   }
   
   while($startdate < $enddate){
   		if (!festiu($end,$periode)) {
			$diasetmana  = date_format(date_create($end), 'l');
			$datasortida = date_format(date_create($end), 'd-m-Y');
			array_push($dates,array("data" => "$datasortida","dia" => daysCatalan($diasetmana)));
		}
		$end = date("Y-m-d", strtotime("$end -1 day")); 
   		$startdate += 86400;
   }
   
   return $dates;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	dies_entre_dates --> Total dies entre dates per un periode escolar
************************************************************************************************************ */
function dies_entre_dates($start,$end,$periode) {
   $startdate   = strtotime($start);
   $enddate     = strtotime($end);
   $count_dates = 0;
   
   if($startdate > $enddate){
        return false; 
   }
   
   while($startdate < $enddate){
   		if (!festiu($end,$periode)) {
			$count_dates++;
		}
		$end = date("Y-m-d", strtotime("$end -1 day")); 
   		$startdate += 86400;
   }
   
   return $count_dates;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	classes_entre_dates --> Nombre de sessions d'un grup matèria entre dues dates
************************************************************************************************************ */

function classes_entre_dates($data_inici,$data_fi,$idgrups_materies,$periode) {
   $startdate     = strtotime($data_inici);
   $enddate       = strtotime($data_fi);
   $count_classes = 0;
   
   if($startdate > $enddate){
        return false; 
   }
   
   while($startdate < $enddate){
   	  if (!festiu($data_fi,$periode)) {
		$diasetmana         = date_format(date_create($data_fi), 'w');
			
		$sql="SELECT id_dies_franges,idfranges_horaries FROM dies_franges WHERE iddies_setmana=".$diasetmana." ORDER BY id_dies_franges DESC";
		$rec = mysql_query($sql);
		
		while($row = mysql_fetch_object($rec)) {
		   $result = $row;
		   $id_dies_franges    = $result->id_dies_franges;
		   
		   $sql_uc  = "SELECT * FROM unitats_classe WHERE idgrups_materies='".$idgrups_materies."' AND ";
           $sql_uc .= "id_dies_franges= ".$id_dies_franges ;
		   $rec_uc = mysql_query($sql_uc);
		   		   
		   while($row_uc = mysql_fetch_object($rec_uc)) {
			   $result_uc = $row_uc;
			   $count_classes++;
		   }
		   	   
		}	
	  }
	  $data_fi = date("Y-m-d", strtotime("$data_fi -1 day")); 
   	  $startdate += 86400;
   }
   
   if (isset($rec)) {
   		mysql_free_result($rec);
   }
   if (isset($rec_uc)) {
   		mysql_free_result($rec_uc);
   }

   return $count_classes;
}

/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	sessions_grup_materia --> Sessions d'un grup matèria entre dues dates
************************************************************************************************************ */
function sessions_grup_materia($data_inici,$data_fi,$idgrups_materies,$periode) {
   /*$data_inici  = $data_inici + " 00:01";
   $data_fi     = $data_fi + " 23:59";*/
   
   $startdate   = strtotime($data_inici);
   $enddate     = strtotime($data_fi);
   $dates       = array();
   $count_dates = 0;
   
   if($startdate > $enddate){
        return false; 
   }
   
   while($startdate < $enddate){
   	  if (!festiu($data_fi,$periode)) {
		$diasetmana         = date_format(date_create($data_fi), 'w');
		$literaldiasetmana  = date_format(date_create($data_fi), 'l');
		$datasortida        = date_format(date_create($data_fi), 'd-m-Y');
			
		$sql="SELECT id_dies_franges,idfranges_horaries FROM dies_franges WHERE iddies_setmana=".$diasetmana." ORDER BY id_dies_franges DESC";
		$rec = mysql_query($sql);

		
		while($row = mysql_fetch_object($rec)) {
		   $result = $row;
		   $id_dies_franges    = $result->id_dies_franges;
		   $idfranges_horaries = $result->idfranges_horaries;
		   $franja_horaria     = getLiteralFranjaHoraria($idfranges_horaries);
		   
		   $sql_uc  = "SELECT * FROM unitats_classe WHERE idgrups_materies='".$idgrups_materies."' AND ";
           $sql_uc .= "id_dies_franges= ".$id_dies_franges ;
		   $rec_uc = mysql_query($sql_uc);

		   while($row_uc = mysql_fetch_object($rec_uc)) {
			   $result_uc = $row_uc;
			   
			   $sql_qp  = "SELECT * FROM qp_seguiment WHERE data='".$data_fi."' ";
               $sql_qp .= "AND id_grup_materia='".$idgrups_materies."' AND id_dia_franja='".$id_dies_franges."' ORDER BY data DESC; ";
			   $rec_qp = mysql_query($sql_qp);
			   
			   $id_seguiment    = 0;
			   $lectiva         = 0;
			   $seguiment       = '';
			   
			   while($row_qp = mysql_fetch_object($rec_qp)) {
					$result_qp       = $row_qp;
					$id_seguiment    = $row_qp->id_seguiment;
					$lectiva         = $row_qp->lectiva;
					$seguiment       = $row_qp->seguiment;
			   }
			   
			   $count_dates++;
			   array_push( $dates,array(
			   				"id_seguiment"    => $id_seguiment,
							"data"			  => "$datasortida",
							"id_dia_franja"   => $id_dies_franges,
							"id_grup_materia" => $idgrups_materies,
			   				"dia"             => daysCatalan($literaldiasetmana),
							"franja_horaria"  => $franja_horaria,
							"lectiva" 		  => $lectiva,
							"seguiment" 	  => $seguiment));
		   }
		   	   
		}	
	  }
	  $data_fi = date("Y-m-d", strtotime("$data_fi -1 day")); 
   	  $startdate += 86400;
   }
   
   if (isset($rec)) {
   		mysql_free_result($rec);
   }
   if (isset($rec_uc)) {
   		mysql_free_result($rec_uc);
   }
   if (isset($rec_gp)) {
   		mysql_free_result($rec_gp);
   }
   
   $dates_total = array("total"=>$count_dates,"rows"=>$dates);
   return $dates_total;
}
/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	indicadors_profe --> Indicadors diversos de professor 
************************************************************************************************************ */
function indicadors_profe($data_inici,$data_fi,$idgrup,$idmateria,$periode) {
   $grup_materia  = existGrupMateria($idgrup,$idmateria);
   $escicleloe    = isMateria($idmateria) ? 0 : 1 ;
   if ($escicleloe) {
	   	$data_inici    = getGrupMateria($grup_materia)->data_inici;
   		$data_fi       = getGrupMateria($grup_materia)->data_fi;
   }
   $txt_inici  = substr($data_inici,8,2)."-".substr($data_inici,5,2)."-".substr($data_inici,0,4);
   $txt_fi     = substr($data_fi,8,2)."-".substr($data_fi,5,2)."-".substr($data_fi,0,4);
   $hores_classes_entre_dates    = classes_entre_dates($data_inici,$data_fi,$grup_materia,$periode);      
      
   // Comptabiltzem classes realitzades entre dates 
   $sql  = "SELECT COUNT(id_seguiment) AS total FROM qp_seguiment WHERE id_grup_materia='".$grup_materia."' AND ";
   $sql .= "data >='".$data_inici."' AND data <='".$data_fi."' AND lectiva=1";
   $rec = mysql_query($sql);
   $classes_entre_dates_realitzades = 0;
   $result = "";
   while($row = mysql_fetch_object($rec)) {
		$result                          = $row;
		$classes_entre_dates_realitzades = $result->total;
   }
   
   echo "<br>*********";
   if ($escicleloe)
      {
      echo "<h5>Hores nominals de la UF: ".getMateria($idmateria)->hores;
      echo "<br>Data inici/data fi de la UF: ".$txt_inici." / ".$txt_fi;
      echo "<br>Hores realitzables totals: ".$hores_classes_entre_dates."</h5>";
      }
   
   
   echo "<br>Dies lectius entre dates de la consulta: ".dies_entre_dates($data_inici,$data_fi,$periode);
   echo "<br>Hores lectives realitzables: ".$hores_classes_entre_dates;
   echo "<br>Hores de classe realitzades: ".$classes_entre_dates_realitzades;
   echo "<br>% dies lectius: ".($classes_entre_dates_realitzades/$hores_classes_entre_dates)*100;
   echo "<br>*********";
   
   if($escicleloe)
      {
      $percent_prog_efectives=($classes_entre_dates_realitzades/$hores_classes_entre_dates)*100;
      echo "<br>Percentatge hores programades/efectives: ".round($percent_prog_efectives,2)."%" ;

      $percent_efectives_efectuades=$classes_entre_dates_realitzades/$hores_classes_entre_dates*100;
      echo "<br>Percentatge hores efectives/efectuades: ".round($percent_efectives_efectuades,2)."%" ;

      $percent_prog_efectuades=$classes_entre_dates_realitzades/$hores_classes_entre_dates*100;
      echo "<br>Percentatge hores programades/efectuades: ".round($percent_prog_efectuades,2)."%" ;
      }
   }
/* ********************************************************************************************************* */


/*  ********************************************************************************************************
	getTotalSeguimientoGrupMateria --> Indicadors diversos de professor 
************************************************************************************************************ */
function getTotalSeguimientoGrupMateria($data_inici,$data_fi,$idgrup,$idmateria,$periode) {
   $grup_materia  = existGrupMateria($idgrup,$idmateria);

   $sql  = "SELECT COUNT(id_seguiment) AS total FROM qp_seguiment WHERE id_grup_materia='".$grup_materia."' AND ";
   $sql .= "data >='".$data_inici."' AND data <='".$data_fi."' AND lectiva=1";
   $rec = mysql_query($sql);
   $classes_entre_dates_realitzades = 0;
   $result = "";
   while($row = mysql_fetch_object($rec)) {
		$result                          = $row;
		$classes_entre_dates_realitzades = $result->total;
   }
   
   return $classes_entre_dates_realitzades;

}

/* ********************************************************************************************************* */

/*  ********************************************************************************************************
	assistencia_alumne --> Indicadors diversos de professor 
************************************************************************************************************ */
function assistencia_alumne($data_inici,$data_fi,$idalumne,$grup_materia,$idgrup,$idmateria,$periode) {
      
      $classes             = classes_entre_dates($data_inici,$data_fi,$grup_materia,$periode);
	  $classes_realitzades = getTotalSeguimientoGrupMateria($data_inici,$data_fi,$idgrup,$idmateria,$periode);
	  $absencies_alumne    = getTotalIncidenciasAlumne($idalumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi);
      
      echo "<br>Classes possibles entre dates: ".$classes;
      echo "<br>Classes amb seguiment entre dates: ".$classes_realitzades;
      echo "<br>Absències: ".$absencies_alumne;
      echo "<br>% Absències respecte a classes possibles: ".round(($classes-$absencies_alumne)*100/$classes,2)."%";
      if ($classes_realitzades > 0) {
	    echo "<br>% Absències respecte a classes amb seguiment: ".round(($classes_realitzades-$absencies_alumne)*100/$classes_realitzades,2)."%";
	  }
              
}
/* ********************************************************************************************************* */
	  
?>