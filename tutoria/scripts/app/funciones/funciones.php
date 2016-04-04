<?php
	if(!isset($_SESSION)) {
		session_start();
	}
	$_SESSION['errores'] = array();
	define("TOTAL_TIPUS_CONTACTE", countallTipusContacte());
	for ($i=1;$i<=TOTAL_TIPUS_CONTACTE;$i++) {
		$nom_info  = getNomInfoTipusContacte($i);
		define("TIPUS_".$nom_info, $i);
	}
	
	function countallTipusContacte() {
		 $sql  = "SELECT COUNT(*) AS total FROM tipus_contacte";
		 $rec  = mysql_query($sql); 
		 
		 $count = 0;
		 $result = "";
		 while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		 }
		 mysql_free_result($rec);
		 return $result->total;
	}

	function getNomInfoTipusContacte($idtipus_contacte) {
		$sql  = "SELECT Nom_info_contacte FROM tipus_contacte ";
		$sql .= "WHERE idtipus_contacte = $idtipus_contacte";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		if ($count == 0) {
			return 0;
		}
		else {  
			return $result->Nom_info_contacte;
		}
	}
	
	function validaFamilia($login,$contrasenya,$contacte_login,$contacte_contrasenya) {
		$sql  = "SELECT cf.id_families FROM contacte_families cf ";
		$sql .= "INNER JOIN families          f ON cf.id_families = f.idfamilies ";
		$sql .= "INNER JOIN alumnes_families af ON f.idfamilies   = af.idfamilies ";
		$sql .= "INNER JOIN alumnes           a ON af.idalumnes   = a.idalumnes ";
		$sql .= "WHERE a.acces_familia='S' AND f.activat='S' AND cf.id_tipus_contacte=$contacte_login AND cf.Valor='$login' ";
		$rec  = mysql_query($sql);
		$count = 0;
		$result = "";
		
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		if ($count == 0) {
			array_push($_SESSION['errores'],1);
			return 0;
		}else { 
			$id_familia = $result->id_families;
			$sql  = "SELECT cf.id_families FROM contacte_families cf ";
			$sql .= "INNER JOIN families f ON cf.id_families = f.idfamilies ";
			$sql .= "WHERE f.activat='S' AND cf.id_tipus_contacte=$contacte_contrasenya AND cf.id_families=$id_familia AND cf.Valor=MD5('$contrasenya')";
			$rec = mysql_query($sql);
			$count = 0;
			$result = ""; 
			while($row = mysql_fetch_object($rec)) {
					$count++;
					$result = $row;
			}
		}
		
		mysql_free_result($rec);
		if ($count == 0) {
			array_push($_SESSION['errores'],2);
			return 0;
		}
		else { 
			return $result->id_families;
		}
	}
	function getCursActual() {
		$sql = "SELECT * FROM periodes_escolars WHERE actual = 'S'";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getAlumnesFamilia($idfamilies) {
		if ($idfamilies == 'undefined') {
			$idfamilies = 0;
		}
		 $sql  = "SELECT * FROM alumnes_families ";
		 $sql .= "WHERE idfamilies=$idfamilies";
		 $rec  = mysql_query($sql); 
		 
		 return $rec;
	}
	
	function getGrupAlumne($alumne) {
		 $count  = 0;
		 $result = "";
		 
		 $sql  = "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
		 $sql .= "FROM alumnes_grup_materia agm ";
		 $sql .= "INNER JOIN grups_materies gm ON agm.idgrups_materies = gm.idgrups_materies ";
		 $sql .= "INNER JOIN materia        ma ON gm.id_mat_uf_pla     = ma.idmateria ";
		 $sql .= "INNER JOIN grups          gr ON gm.id_grups          = gr.idgrups ";
		 $sql .= "WHERE agm.idalumnes='".$alumne."' ";
		 $sql .= "UNION ";
		 $sql .= "SELECT DISTINCT(gr.idgrups), gr.nom, gr.Descripcio ";
		 $sql .= "FROM alumnes_grup_materia agm ";
		 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
		 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
		 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
		 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
		 $sql .= "INNER JOIN grups              gr ON gm.id_grups          = gr.idgrups ";
		 $sql .= "WHERE agm.idalumnes='".$alumne."' ";
		
		 $sql .= "ORDER BY 1 LIMIT 1";

		 $rec = mysql_query($sql);
		 
		 while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		 }
		 
		 //echo $sql."<br><br>";
		 
		 if ($count == 0) {
		   return 0;
		 }
		 else {
		   return $result;
		 }
	  }
	
	function getGrup($idgrups) {
		$sql = "SELECT * FROM grups WHERE idgrups = '$idgrups'";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getIdTutor() {
		$sql = "SELECT idcarrecs FROM carrecs WHERE nom_carrec = 'TUTOR'";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getAlumne($idalumnes,$tipusContacte) {
		$sql  = "SELECT ca.Valor FROM contacte_alumne ca ";
		$sql .= "WHERE ca.id_tipus_contacte=$tipusContacte AND ca.id_alumne=$idalumnes ";
			
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		if ($count == 0) {
			return "";
		}
		else {
			return $result->Valor;
		}
	}
	
	function getMateriesDiaHoraAlumne($dia,$franja,$curs,$alumne) {
		 $sql = "SELECT id_dies_franges FROM dies_franges WHERE iddies_setmana=$dia AND idfranges_horaries=$franja AND idperiode_escolar=$curs";
		 $rec = mysql_query($sql);
		 $count = 0;
		 $result = "";
		 while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		 }
		 
		 if ($count==0) {
			$diafranja = 0;
		 }
		 else {
			$diafranja = $result->id_dies_franges;
		 }
		 
		 $sql  = "SELECT agm.*,m.nom_materia AS materia,ec.descripcio AS espaicentre,g.nom as grup,gm.idgrups_materies,gm.id_mat_uf_pla ";
		 $sql .= "FROM alumnes_grup_materia agm ";
		 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
		 //$sql .= "INNER JOIN prof_agrupament    pa ON agm.idgrups_materies = pa.idagrups_materies ";
		 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
		 $sql .= "INNER JOIN materia             m ON  gm.id_mat_uf_pla    = m.idmateria ";
		 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
		 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
		 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
		 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne AND uc.id_dies_franges=$diafranja";
		 
		 $sql .= " UNION ";
		 
		 $sql .= "SELECT agm.*,CONCAT(m.nom_modul,'-',uf.nom_uf) AS materia,ec.descripcio AS espaicentre,g.nom as grup,gm.idgrups_materies,gm.id_mat_uf_pla ";
		 $sql .= "FROM alumnes_grup_materia agm ";
		 $sql .= "INNER JOIN grups_materies     gm ON agm.idgrups_materies = gm.idgrups_materies ";
		 //$sql .= "INNER JOIN prof_agrupament    pa ON agm.idgrups_materies = pa.idagrups_materies ";
		 $sql .= "INNER JOIN grups               g ON gm.id_grups          = g.idgrups ";
		 $sql .= "INNER JOIN unitats_formatives uf ON gm.id_mat_uf_pla     = uf.idunitats_formatives ";
		 $sql .= "INNER JOIN moduls_ufs         mu ON gm.id_mat_uf_pla     = mu.id_ufs ";
		 $sql .= "INNER JOIN moduls              m ON mu.id_moduls         = m.idmoduls ";
		 $sql .= "INNER JOIN unitats_classe     uc ON gm.idgrups_materies  = uc.idgrups_materies ";
		 $sql .= "INNER JOIN dies_franges       df ON uc.id_dies_franges   = df.id_dies_franges ";
		 $sql .= "INNER JOIN espais_centre      ec ON uc.idespais_centre   = ec.idespais_centre "; 
		 $sql .= "WHERE df.idperiode_escolar=$curs AND agm.idalumnes=$alumne AND uc.id_dies_franges=$diafranja";
		 $sql .= " AND gm.data_inici<='".date("y-m-d")."' AND gm.data_fi>='".date("y-m-d")."'";
		 
		 $rec = mysql_query($sql);
		 
		 //echo $sql."<br><br>";	 
		 return $rec;
	  }
	
	function getProfessorByGrupMateria($idgrups_materies) {
		$sql  = "SELECT * FROM prof_agrupament pa ";
		$sql .= "INNER JOIN professors p ON pa.idprofessors = p.idprofessors ";
		$sql .= "WHERE pa.idagrups_materies = '$idgrups_materies' LIMIT 0,1 ";
		$rec = mysql_query($sql);
		
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getProfessor($idprofessors,$tipusContacte) {
		$sql  = "SELECT cp.Valor FROM contacte_professor cp ";
		$sql .= "WHERE cp.id_tipus_contacte=$tipusContacte AND cp.id_professor=$idprofessors ";
		
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		if ($count == 0) {
			return "";
		}
		else {
			return $result->Valor;
		}
	}
	
	function getHorariAlumne($idAlumne,$curs,$cursLiteral){
		$idGrup = getGrupAlumne($idAlumne)->idgrups;
		$idtorn = getGrup($idGrup)->idtorn;
		$nom_alumne = getAlumne($idAlumne,TIPUS_nom_complet);
		$idTutorCarrec = getIdTutor()->idcarrecs;
		$rsDies = mysql_query("select * from dies_setmana where laborable='S'");
		
		
		$retorno = array();
		for($dia=1; $dia<=5; $dia++){
			$retorno[$dia] = array();
			$rsHores = mysql_query("select * from franges_horaries where idtorn=".$idtorn." order by hora_inici");
			while($row = mysql_fetch_object($rsHores)){
				$franjahoraria = $row->idfranges_horaries;
				$retorno[$dia][$franjahoraria] = array();
				$retorno[$dia][$franjahoraria]['materias'] = array();
				array_push($retorno[$dia][$franjahoraria],array('rango'=>substr($row->hora_inici,0,5)."-".substr($row->hora_fi,0,5)));
				if ($row->esbarjo=='S') {
					$materias = array();
					$materias['materia'] = utf8_encode('Esbarjo');
					array_push($retorno[$dia][$franjahoraria]['materias'],$materias);
				}else{
					$rsMateries = getMateriesDiaHoraAlumne($dia,$franjahoraria,$curs,$idAlumne);
					while ($row = mysql_fetch_assoc($rsMateries)) {
						$materias = array();
						$materias['materia'] = utf8_encode($row['materia']);
						$materias['grup']=utf8_encode($row['grup']);
						$materias['espaicentre']=utf8_encode($row['espaicentre']);
						
						if(!is_object(getProfessorByGrupMateria($row['idgrups_materies']))) {
							$nom_professor = " ";
						}else {
							$nom_professor = utf8_encode(getProfessor(getProfessorByGrupMateria($row['idgrups_materies'])->idprofessors,TIPUS_nom_complet));
						}
						
						$materias['professor']=$nom_professor;
						array_push($retorno[$dia][$franjahoraria]['materias'],$materias);
					}
				}
			}
		}
		return $retorno;
	}
	
	function getValorTipusContacteAlumne($id_alumne,$idtipus_contacte) {
		 if ($id_alumne == 'undefined') {
		   $id_alumne = 0;
		 }
		 $sql  = "SELECT * FROM contacte_alumne WHERE id_alumne=$id_alumne AND id_tipus_contacte=$idtipus_contacte";
		 $rec  = mysql_query($sql); 
		 $count = 0;
		 $result = "";
		 while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		 }
		 mysql_free_result($rec);
		 if ($count == 0) {
		   return "";
		 }
		 else {
		   return $result->Valor;
		 }
	}
	
	function getIncidenciasAlumne($idalumnes,$id_tipus_incidencia,$data_inici,$data_fi) {
		 $sql  = "SELECT ia.* ";
		 $sql .= "FROM incidencia_alumne ia ";
		 $sql .= "WHERE ia.idalumnes = ".$idalumnes." AND ia.id_tipus_incidencia=".$id_tipus_incidencia;
		 $sql .= " AND ia.data BETWEEN '".$data_inici."' AND '".$data_fi."'";
		 $sql .= " ORDER BY ia.data,ia.id_tipus_incidencia,ia.id_tipus_incident ";
		 $rec = mysql_query($sql);
		 return $rec;
		
	}
	
	function getMateria($idmateria) {
		$sql = "SELECT * FROM materia WHERE idmateria = '$idmateria'";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		
		if ($count == 0) {
			$sql  = "SELECT uf.idunitats_formatives,CONCAT(m.nom_modul,'-',uf.nom_uf) AS nom_materia FROM unitats_formatives uf ";
			$sql .= "INNER JOIN moduls_ufs         mu ON uf.idunitats_formatives = mu.id_ufs ";
			$sql .= "INNER JOIN moduls              m ON mu.id_moduls            = m.idmoduls ";
			$sql .= "WHERE uf.idunitats_formatives = '$idmateria' ";
			
			$rec = mysql_query($sql);
			$count = 0;
			$result = "";
			while($row = mysql_fetch_object($rec)) {
					$count++;
					$result = $row;
			}	
		}
		
		mysql_free_result($rec);
		return $result;
	}
	
	function getLiteralTipusIncident($idtipus_incident) {
		$sql = "SELECT * FROM tipus_incidents WHERE idtipus_incident=".$idtipus_incident;
		$rec = mysql_query($sql);
		
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getCCCAlumne($idalumne,$data_inici,$data_fi) {
		$sql  = "SELECT ccc_tp.* ";
		$sql .= "FROM ccc_taula_principal ccc_tp ";
		$sql .= "WHERE ccc_tp.idalumne = ".$idalumne;
		$sql .= " AND ccc_tp.data BETWEEN '".$data_inici."' AND '".$data_fi."' ";
		$sql .= "ORDER BY ccc_tp.data DESC ";
		$rec  = mysql_query($sql); 
		
		return $rec;
	}
	
	function getLiteralTipusCCC($idccc_tipus) {
		$sql = "SELECT * FROM ccc_tipus WHERE idccc_tipus=".$idccc_tipus;
		$rec = mysql_query($sql);
		
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getAssistencia($idAlumne){
		$retorno = array();
		$data_inici = '1989-1-1';
		$data_fi = '2189-1-1';
		$rsIncidencias = getIncidenciasAlumne($idAlumne,TIPUS_FALTA_ALUMNE_ABSENCIA,$data_inici,$data_fi);
		//error_log($idAlumne.":".mysql_num_rows($rsIncidencias));
		if(mysql_num_rows($rsIncidencias) > 0){
			$retorno['faltes'] = array();
			$linea = 0;
			while($row = mysql_fetch_object($rsIncidencias)){
				$retorno['faltes'][$linea] = array();
				$retorno['faltes'][$linea]['dia'] = $row->data;
				$retorno['faltes'][$linea]['professor'] = utf8_encode(getProfessor($row->idprofessors,TIPUS_nom_complet));
				$retorno['faltes'][$linea]['materia'] = utf8_encode(getMateria($row->id_mat_uf_pla)->nom_materia);
				$linea++;
			}
		}else{
			$retorno['faltes'] = "";
		}
		
		$rsIncidencias = getIncidenciasAlumne($idAlumne,TIPUS_FALTA_ALUMNE_RETARD,$data_inici,$data_fi);
		//error_log($idAlumne.":".mysql_num_rows($rsIncidencias));
		if(mysql_num_rows($rsIncidencias) > 0){
			$retorno['retards'] = array();
			$linea = 0;
			while($row = mysql_fetch_object($rsIncidencias)){
				$retorno['retards'][$linea] = array();
				$retorno['retards'][$linea]['dia'] = $row->data;
				$retorno['retards'][$linea]['professor'] = utf8_encode(getProfessor($row->idprofessors,TIPUS_nom_complet));
				$retorno['retards'][$linea]['materia'] = utf8_encode(getMateria($row->id_mat_uf_pla)->nom_materia);
				$linea++;
			}
		}else{
			$retorno['retards'] = "";
		}
		
		$rsIncidencias = getIncidenciasAlumne($idAlumne,TIPUS_FALTA_ALUMNE_JUSTIFICADA,$data_inici,$data_fi);
		//error_log($idAlumne.":".mysql_num_rows($rsIncidencias));
		if(mysql_num_rows($rsIncidencias) > 0){
			$retorno['justificacions'] = array();
			$linea = 0;
			while($row = mysql_fetch_object($rsIncidencias)){
				$retorno['justificacions'][$linea] = array();
				$retorno['justificacions'][$linea]['dia'] = $row->data;
				$retorno['justificacions'][$linea]['professor'] = utf8_encode(getProfessor($row->idprofessors,TIPUS_nom_complet));
				$retorno['justificacions'][$linea]['materia'] = utf8_encode(getMateria($row->id_mat_uf_pla)->nom_materia);
				$retorno['justificacions'][$linea]['comentari'] = utf8_encode($row->comentari);
				$linea++;
			}
		}else{
			$retorno['justificacions'] = "";
		}
		
		$rsIncidencias = getIncidenciasAlumne($idAlumne,TIPUS_FALTA_ALUMNE_INCIDENT,$data_inici,$data_fi);
		//error_log($idAlumne.":".mysql_num_rows($rsIncidencias));
		if(mysql_num_rows($rsIncidencias) > 0){
			$retorno['incidencies'] = array();
			$linea = 0;
			while($row = mysql_fetch_object($rsIncidencias)){
				$retorno['incidencies'][$linea] = array();
				$retorno['incidencies'][$linea]['dia'] = $row->data;
				$retorno['incidencies'][$linea]['tipus'] = utf8_encode(getLiteralTipusIncident($row->id_tipus_incident)->tipus_incident);
				$retorno['incidencies'][$linea]['professor'] = utf8_encode(getProfessor($row->idprofessors,TIPUS_nom_complet));
				$retorno['incidencies'][$linea]['materia'] = utf8_encode(getMateria($row->id_mat_uf_pla)->nom_materia);
				$retorno['incidencies'][$linea]['comentari'] = utf8_encode($row->comentari);
				$linea++;
			}
		}else{
			$retorno['incidencies'] = "";
		}
		
		$rsIncidencias = getCCCAlumne($idAlumne,$data_inici,$data_fi);
		//error_log($idAlumne.":".mysql_num_rows($rsIncidencias));
		if(mysql_num_rows($rsIncidencias) > 0){
			$retorno['ccc'] = array();
			$linea = 0;
			while($row = mysql_fetch_object($rsIncidencias)){
				$retorno['ccc'][$linea] = array();
				$retorno['ccc'][$linea]['dia'] = $row->data;
				$retorno['ccc'][$linea]['tipus'] = utf8_encode(getLiteralTipusCCC($row->id_falta)->nom_falta);
				$retorno['ccc'][$linea]['expulsio'] = utf8_encode($row->expulsio);
				$retorno['ccc'][$linea]['professor'] = utf8_encode(getProfessor($row->idprofessors,TIPUS_nom_complet));
				$retorno['ccc'][$linea]['materia'] = utf8_encode((intval($row->idmateria!=0) ? getMateria($row->idmateria)->nom_materia : ''));
				$retorno['ccc'][$linea]['comentari'] = utf8_encode($row->descripcio_detallada);
				$linea++;
			}
		}else{
			$retorno['ccc'] = "";
		}
		return $retorno;
	}
	function getIdProfessorByCarrecGrup($idcarrecs,$idgrups) {
		$sql = "SELECT idprofessors FROM professor_carrec WHERE idcarrecs = '$idcarrecs' AND idgrups = '$idgrups'";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getMensajeria($idAlumne){
		$retorno = array();
		$idgrup = getGrupAlumne($idAlumne)->idgrups;
		$idTutorCarrec = getIdTutor()->idcarrecs;
		$idprofessor   = 0;
		$nom_tutor     = "";
		if(is_object(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrup))){
			$idprofessor   = utf8_encode(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrup)->idprofessors);
			$nom_tutor     = utf8_encode(getProfessor($idprofessor,TIPUS_nom_complet));
		}
		$retorno['tutor'] = $nom_tutor;
		
		$sql  = "SELECT *, ";
		$sql .= "CONCAT(SUBSTR(data,9,2),'-',SUBSTR(data,6,2),'-',SUBSTR(data,1,4)) AS data ";
		$sql .= "FROM missatges_tutor ";
		$sql .= "WHERE idalumne=$idAlumne ORDER BY idmissatges_tutor DESC";
		$rs = mysql_query($sql);
		$items = array();  
		while($row = mysql_fetch_object($rs)){  
			array_push($items, $row);  
		}  
		mysql_free_result($rs);
		$retorno['mensajes'] = $items;
		
		return $retorno;
	}
	
	function getDadesCentre() {
		$sql = "SELECT * FROM dades_centre WHERE iddades_centre = 1";
		$rec = mysql_query($sql);
		
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function getProfessorsbyCargos($idcarrecs) {
		if ($idcarrecs == 'undefined') {
			$idcarrecs = 0;
		}
		$sql  = "SELECT pc.* ";
		$sql .= "FROM professor_carrec pc ";
		$sql .= "INNER JOIN professors p ON pc.idprofessors = p.idprofessors ";
		$sql .= "LEFT JOIN carrecs     c ON pc.idcarrecs    = c.idcarrecs ";
		$sql .= "LEFT JOIN grups       g ON pc.idgrups      = g.idgrups ";
		$sql .= "WHERE pc.idcarrecs=$idcarrecs AND p.activat='S' ";
		$sql .= "ORDER BY c.nom_carrec,g.nom ";	
		$rec = mysql_query($sql);
		return $rec;
	}
	
	function getLiteralCarrec($idcarrecs) {
		$sql = "SELECT * FROM carrecs WHERE idcarrecs=".$idcarrecs;
		$rec = mysql_query($sql);
		
		$count = 0;
		$result = "";
		while($row = mysql_fetch_object($rec)) {
				$count++;
				$result = $row;
		}
		mysql_free_result($rec);
		return $result;
	}
	
	function isCarrecInGrup($idprofessors,$idcarrecs,$idgrups) {
		$sql  = "SELECT idprofessor_carrec FROM professor_carrec ";
		$sql .= "WHERE idprofessors = $idprofessors AND idcarrecs=$idcarrecs AND idgrups=$idgrups";
		$rec = mysql_query($sql);
		$count = 0;
		$result = "";
		
		while($row = mysql_fetch_object($rec)) {
			$count++;
			$result = $row;
		}
		mysql_free_result($rec);
		if ($count == 0) {
			return 0;
		}
		else {  
			return 1;
		}
	}
	
	function enviarMensaje($idAlumne,$missatge){
		$idgrup = getGrupAlumne($idAlumne)->idgrups;
		$idTutorCarrec = getIdTutor()->idcarrecs;
		$idprofessor   = 0;
		$nom_tutor     = "";
		if(is_object(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrup))){
			$idprofessor   = utf8_encode(getIdProfessorByCarrecGrup($idTutorCarrec,$idgrup)->idprofessors);
			$nom_tutor     = utf8_encode(getProfessor($idprofessor,TIPUS_nom_complet));
		}
		if ($idAlumne != 0) {
			$sql = "INSERT INTO missatges_tutor (idprofessor,idalumne,idgrup,data,hora,missatge) ";
			$sql .= "VALUES ('$idprofessor','$idAlumne','$idgrup','".date("Y-m-d")."','".date("H:i")."','$missatge')";
			$result = mysql_query($sql);
		}
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: '.getDadesCentre()->nom."<".getDadesCentre()->email.">".'' . "\r\n";
		$subject  =	"[Geisoft] Nou missatge al tutor/a de ".getGrup($idgrup)->nom." ";
		$content  = "Alumne:          ".getAlumne($idAlumne,TIPUS_nom_complet)."<br>";
		$content .= "Professor:       ".getProfessor($idprofessor,TIPUS_nom_complet)."<br><hr>";
		$content .= "Data:   ".date("d-m-Y")."<br>";
		$content .= "Hora:   ".date("H:i")."<br><hr>";
		$content .= "<b><u>Missatge</u></b><br>";
		$content .= $missatge."<br><hr>";
		$rsProfessorsCarrec = getProfessorsbyCargos(TIPUS_TUTOR);
		while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
			$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec(TIPUS_TUTOR)->nom_carrec;
			if (isCarrecInGrup($row_p['idprofessors'],TIPUS_TUTOR,$idgrup)) {
				$to = getProfessor($row_p['idprofessors'],TIPUS_nom_complet)."<".getProfessor($row_p['idprofessors'],TIPUS_email).">";			
				mail($to,$subject,$content.$rol,$header);
			}
		}
		
		$rsProfessorsCarrec = getProfessorsbyCargos(TIPUS_ADMINISTRADOR);
		while ($row_p = mysql_fetch_assoc($rsProfessorsCarrec)) {
			$rol = "<br><br><i> Missatge rebut com a </i>".getLiteralCarrec(TIPUS_ADMINISTRADOR)->nom_carrec;
			$to = getProfessor($row_p['idprofessors'],TIPUS_nom_complet)."<".getProfessor($row_p['idprofessors'],TIPUS_email).">";
			mail($to,$subject,$content.$rol,$header);
		}
		mysql_free_result($rsProfessorsCarrec);
		return true;
	}
	
	function enviarError($id,$missatge){
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$header .= 'From: Alexandre Catalán <acgalbany@gmail.com>' . "\r\n";
		$to = "Alexandre Catalán <acgalbany@gmail.com>";
		$subject  =	"[Geisoft] Nuevo ERROR del dispositivo: ".$id;
		$content  = "Dispositivo: ".$id."<br>";
		$content .= "Mensaje: ".$missatge."<br><hr>";
		$content .= "Data:   ".date("d-m-Y")."<br>";
		$content .= "Hora:   ".date("H:i")."<br><hr>";
		mail($to,$subject,$content,$header);
	}
?>