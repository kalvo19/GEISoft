<?php
	$idprofessors = isset($_SESSION['professor'])    ? $_SESSION['professor']    : 0;
	$curs_escolar = isset($_SESSION['curs_escolar']) ? $_SESSION['curs_escolar'] : 0;
	
	if ($idprofessors==0 || $curs_escolar==0) {
		exit;
	}	
?>
   
    <div class="easyui-accordion" style="width:220px; border-top:1px solid #aacae6;">
		<div title="Classes d'avui" style="overflow:auto;padding:1px; ">
            <?php  
                $rsFranges      = getFrangesHoraries();
							
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
				 	$fi_link = "</a>";
				 	if ($aquesta_classe) {
						$link    = "<a style='color:#333; font-weight:bold; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./guard/guard_grid.php?act=1&idprofessors=".$idprofessors.'&hora='.$row['hora_fi']."\",this)'>";
						echo $link."<div style='border:1px dashed #162b48; background:url(./images/fons_quadre_classe_actual.png); width:214px; margin-bottom:2px;'>".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."";
					}
					else {
						//$link    = "<a style='color:#333; font-weight:bold; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./guard/guard_grid.php?act=0&idprofessors=".$idprofessors.'&hora='.$row['hora_fi']."\",this)'>";
						$link    = "<a style='color:#333; font-weight:bold; text-decoration:none;' href='javascript:void(0)'>";
						echo $link."<div style='border:1px dashed #162b48; background:url(./images/fons_quadre_guardia.png); width:214px; margin-bottom:2px;'>".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."";
					}
				 	echo "<div>GU&Agrave;RDIA<br>".$row_g['espaicentre']."</div>".$fi_link."</div>";
			   	  }
				  
				  $rsMateries = getMateriesDiaHoraProfessor(date('w'),$row['idfranges_horaries'],$curs_escolar,$idprofessors);
				  while ($rowm = mysql_fetch_assoc($rsMateries)) {
					$fi_link = "</a>";
					
					if ($aquesta_classe) {

					  $_SESSION['grup_classe_actual']    = $rowm['idgrups'] ;
					  $_SESSION['materia_classe_actual'] = $rowm['idmateria'] ;
					  $_SESSION['fh_classe_actual']      = $row['idfranges_horaries'] ;
		  
					  $link    = "<a style='color:#333; font-weight:bold; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./assist/assist_grid.php?act=1&idprofessors=".$idprofessors."&idgrups=".$rowm['idgrups']."&idmateria=".$rowm['idmateria']."&idfranges_horaries=".$row['idfranges_horaries']."&idespais_centre=".$rowm['idespais_centre']."\",this)'>";
					  echo "<div style='border:1px dashed #162b48; background:url(./images/fons_quadre_classe_actual.png); width:214px; margin-bottom:2px;'>";
					  echo "<div>$link".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."$fi_link</div>";
				      echo "<div>$link".$rowm['materia']."$fi_link</div>";
				      echo "<div>$link".$rowm['grup']."$fi_link</div>";
					  echo "<div>$link".$rowm['espaicentre']."$fi_link</div>";
					  echo "</div>";
					}
					else {
					  $link    = "<a style='color:#162b48; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./assist/assist_grid.php?act=0&idprofessors=".$idprofessors."&idgrups=".$rowm['idgrups']."&idmateria=".$rowm['idmateria']."&idfranges_horaries=".$row['idfranges_horaries']."&idespais_centre=".$rowm['idespais_centre']."\",this)'>";
					  $linkwhite = "<a style='color:white; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./assist/assist_grid.php?act=0&idprofessors=".$idprofessors."&idgrups=".$rowm['idgrups']."&idmateria=".$rowm['idmateria']."&idfranges_horaries=".$row['idfranges_horaries']."\",this)'>";
					  echo "<div style='background:url(./images/fons_quadre_classe.png); width:217px; margin-bottom:2px;'>";
					  echo "<div style=''>$linkwhite".substr($row['hora_inici'],0,5)."-".substr($row['hora_fi'],0,5)."$fi_link</div>";
				      echo "<div style=''>$link".$rowm['materia']."$fi_link</div>";
				      echo "<div style=''>$link".$rowm['grup']."$fi_link</div>";
					  echo "<div style=''>$link".$rowm['espaicentre']."$fi_link</div>";
					  echo "</div>";
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
          
		</div>
        
        <div title="Les meves gestions" style="overflow:auto;padding:1px;">
          <ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
          <li style=""><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./assist_dant/assist_dant_grid.php',this)">Assist&egrave;ncia altres dies</a></li>
          <li style=""><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./alum_class/alum_class_grid.php?idprofessor=<?=$idprofessors?>',this)">Gesti&oacute; alumnes</a></li>
          <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./seg_class/seg_class_grid.php?idprofessor=<?=$idprofessors?>',this)">Seguiment classes</a></li>
          <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./mat_automat/mat_automat_grid.php',this)">Automatriculacions</a></li>
          <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./uf_class/uf_class_grid.php?idprofessor=<?=$idprofessors?>',this)">Establir dates UF</a></li>
          <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./inf_assist/inf_assist_prof_see.php?idprofessor=<?=$idprofessors?>',this)">Les meves incid&egrave;ncies</a></li>
        <?php 
        
         $hosting		   = 1; //0 per instal.lacions lliures
		 $modul_ccc        = getModulsActius()->mod_ccc;
		 $modul_ass_servei = getModulsActius()->mod_ass_servei;
		 $modul_reg_prof   = getModulsActius()->mod_reg_prof;

         if (($hosting) AND ($modul_ccc))
            print('<li style="border-top:padding-top:3px;padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc/ccc_grid.php\',this)">Les meves CCC</a></li>');
         else
            print('<li style="border-top:padding-top:3px;padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Les meves CCC(N/D)</a></li>');
 
         if (($hosting) AND ($modul_ass_servei))
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./abs_prof/com_abs_prof_grid.php\',this)">Comunicaci&oacute; abs&egrave;ncia</a></li>');
         else
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">Comunicaci&oacute; abs&egrave;ncia.(N/D)</a></li>');
         
         if (($hosting) AND ($modul_ass_servei))
            print('<li style="padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./abs_prof/abs_prof_grid.php\',this)">Abs&egrave;ncies comunicades</a></li>');
         else
            print('<li style="padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">Abs&egrave;ncies comunicades.(N/D)</a></li>');
        
         if (($hosting) AND ($modul_ass_servei))
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./sortides/plan_sortides_grid.php\',this)">Planificaci&oacute; sortida</a></li>');
         else
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">Planificaci&oacute; sortida.(N/D)</a></li>');
         
         if (($hosting) AND ($modul_ass_servei))
				print('<li><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./sortides/sortides_grid.php\',this)">Sortides enregistrades</a></li>');
         else
				print('<li><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">Sortides enregistrades.(N/D)</a></li>');
         
            /**
             * Mostra les opcions de gestió que té el 'COORDINADOR' sobre les programacions.
             */ 
            if (isCarrec($idprofessors, 1)) {
                echo "<li style='border-top:1px dashed #ccc; padding-top:3px;'><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./vista_prog/prog_gen_grid.php?idprofessor=" . $idprofessors . "\",this)'>Programacions generals</a></li>";
                echo "<li style='padding-bottom:3px;'><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./vista_prog/prog_gen_grid.php?idprofessor=" . $idprofessors . "\",this)'>Programacions d'aula</a></li>";
            }
         ?>
          
          </ul>
        </div>
                
        <?php
            $rsCarrecs = getCargosProfessor($idprofessors);
            while ($row = mysql_fetch_assoc($rsCarrecs)) {
			   
			   if ($row['nom_carrec'] == 'SUPERADMINISTRADOR') {
			     $_SESSION['super'] = 1;
			   }
			   
			   if ($row['nom_carrec'] == 'ADMINISTRADOR') {
			     $_SESSION['admin'] = 1;
			   }
			   
			   if ( ($row['nom_carrec'] != 'SUPERADMINISTRADOR') && ($row['nom_carrec'] != 'ADMINISTRADOR') ) {
        ?>
		<div title="<strong><?= $row['nom_carrec']."</strong><br>".$row['nom'] ?>" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:1px; text-align:left;">
             <?php
			   if ($row['nom_carrec'] == 'COORDINADOR') {
			     echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor/tutor_grid.php?grup=".$row['idgrups']."\",this)'>Justificar faltas</a></li> ";
				 //cho "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor/tutor_ccc_grid.php?grup=".$row['idgrups']."\",this)'>Gestionar CCC</a></li> ";
	
			   
			  if (($hosting) AND ($modul_ccc))
					echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor_ccc/tutor_ccc_grid.php?grup=".$row['idgrups']."\",this)'>Gestionar CCC</a></li> ";
			  else
					echo "<li><a style='color:#6a6a6a; text-decoration:none;' href='javascript:void(0)'>Gestionar CCC</a></li> ";
        
			   }
			   if ($row['nom_carrec'] == 'TUTOR') {
			     echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor/tutor_alum_grid.php?grup=".$row['idgrups']."\",this)'>Gesti&oacute; alumnes</a></li> ";
			     echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor/tutor_grid.php?grup=".$row['idgrups']."\",this)'>Justificar faltes</a></li> ";
				 echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor_msg/tutor_msg_grid.php?grup=".$row['idgrups']."\",this)'>Missatges families</a></li> ";
 
			  if (($hosting) AND ($modul_ccc))
					echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./tutor_ccc/tutor_ccc_grid.php?grup=".$row['idgrups']."\",this)'>Gestionar CCC</a></li> ";
			  else
					echo "<li><a style='color:#6a6a6a; text-decoration:none;' href='javascript:void(0)'>Gestionar CCC</a></li> ";
                                      
                               
			   }
			    if ($row['nom_carrec'] == 'RESPONSABLE DE FALTES') {
			     echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./conserge/conserge_grid.php\",this)'>Incid&egrave;ncies del dia</a></li> ";
			     echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./assist_adm/assist_adm_grid.php\",this)'>Passar faltes</a></li> ";
				  echo "<li><a style='color:#000033; text-decoration:none;' href='javascript:void(0)' onclick='open1(\"./sms_sent/sms_sent_grid.php\",this)'>SMS enviats</a></li> ";

            if (($hosting) AND ($modul_ass_servei))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./guard/guard_grid_adm.php?act=0\',this)">Qui està a classe</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Qui està a classe.(N/D)</a></li>');              
              
              
              
              
          }
			   			   
			 ?>
            </ul>
		</div>
        <?php
		      }
		    }		
		?>
        
        <?php
		if (isset($_SESSION['super']) || isset($_SESSION['admin'])) {
		?>
        <div title="Tauler de control" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./conserge/conserge_grid.php',this)">Incid&egrave;ncies del dia</a></li>
              <li style=""><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./assist_adm/assist_adm_grid.php',this)">Passar faltes</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./guard_sign/guard_sign_grid.php',this)">Gu&agrave;rdies signades</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./subst/subst_grid.php',this)">Substitucions professorat</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./sms_sent/sms_sent_grid.php',this)">SMS enviats</a></li>
              
			  <!--  <li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./ccc_adm/ccc_adm_grid.php',this)">CCC introdu&iuml;des</a></li> -->
                        
			  <?php 
			  
            if (($hosting) AND ($modul_ccc))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc_adm/ccc_adm_grid.php\',this)">CCC introdu&iuml;des</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">CCC introdu&iuml;des.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ass_servei))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./abs_prof_adm/abs_prof_adm_grid.php\',this)">Professorat absent</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Professorat absent.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ass_servei))
					print('<li style="padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./abs_prof_adm/com_abs_prof_adm_grid.php\',this)">Introdu&iuml;r professorat absent</a></li>');
		      else
					print('<li style="padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Introdu&iuml;r professorat absent.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ass_servei))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px; padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./sortides_adm/sortides_adm_grid.php\',this)">Sortides enregistrades</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px; padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">Sortides enregistrades.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ass_servei))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ass_servei/quies_enlinia.php\',this)">Qui està en línia</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Qui està en línia.(N/D)</a></li>');
            
            if (($hosting) AND ($modul_ass_servei))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./guard/guard_grid_adm.php?act=0\',this)">Qui està a classe</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Qui està a classe.(N/D)</a></li>');

            ?>
                    
              
            </ul>
		</div>
        
        <?php
		}
		?>
        
        <?php
		if (isset($_SESSION['super'])) {
		?>
        <div title="Configuracions generals" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./dades_centre/dades_centre_grid.php',this)">Dades centre</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./ce/ce_grid.php',this)">Cursos escolars</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./pe/pe_grid.php',this)">Plan estudis</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./ec/ec_grid.php',this)">Espais de centre</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./torn/torn_grid.php',this)">Torns</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./fh/fh_grid.php',this)">Franges hor&agrave;ries</a></li>
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./textos_sms/textos_sms_grid.php',this)">Textos SMS</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./incidents_tipus/incidents_tipus_grid.php',this)">Tipus seguiments</a></li>
              
              <!-- <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./ccc_tipus/ccc_tipus_grid.php',this)">CCC Tipus</a></li> -->
                                      
			  <?php 
			   if (($hosting) AND ($modul_ccc))
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc_tipus/ccc_tipus_grid.php\',this)">CCC Tipus</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">CCC Tipus.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ccc))
					print('<li style="padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc_motius/ccc_motius_grid.php\',this)">CCC Motius</a></li>');
		      else
					print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)">CCC Motius.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ccc))
					print('<li><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc_mesures/ccc_mesures_grid.php\',this)">CCC Mesures</a></li>');
		      else
					print('<li><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >CCC Mesures.(N/D)</a></li>');
			  
            if (($hosting) AND ($modul_ccc))
					print('<li style="padding-bottom:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ccc_limits/ccc_limits_grid.php\',this)">CCC L&iacute;mits</a></li>');
		      else
					print('<li style="padding-bottom:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >CCC L&iacute;mits.(N/D)</a></li>');
			  ?>
              
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./func/ordena_alumnes.php',this)">Organitzar fitxers</a></li>
            </ul>
		</div>
        
        <div title="Mat&egrave;ries/M&ograve;duls/UF's" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">              
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./mat/mat_grid.php',this)">Mat&egrave;ries</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./mod/mod_grid.php',this)">M&ograve;duls professionals</a></li>
              <!--<li><a href="javascript:void(0)" onclick="open1('./ufs/ufs_grid.php',this)">Unitats Formatives</a></li>-->
            </ul>
		</div>
        
        <div title="Grups" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./grup/grup_grid.php',this)">Grups</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./grma/grma_grid.php',this)">Mat&egrave;ries :: grups</a></li>
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./hor/hor_grid.php',this)">Horaris :: mat&egrave;ries :: grups</a></li> 
              
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./grmod/grmod_grid.php',this)">M&ograve;duls :: UF's :: grups</a></li>
                 
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./hormod/hormod_grid.php',this)">Horaris :: m&ograve;duls :: UF's :: grups</a></li>
            </ul>
		</div>
        
        <div title="Professors" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prof/prof_grid.php',this)">Manteniment</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prmat/prmat_grid.php',this)">Mat&egrave;ries :: professors</a></li>
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prmod/prmod_grid.php',this)">M&ograve;duls :: UF's :: professors</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prgua/prgua_grid.php',this)">Gu&agrave;rdies</a></li>
              
              <li style="padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prdir/prdir_grid.php',this)">Direcci&oacute;</a></li>
              <li style="padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prcoo/prcoo_grid.php',this)">Coordinacions</a></li>
              <li style="padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prate/prate_grid.php',this)">Atencions</a></li>
              <li style="padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prper/prper_grid.php',this)">Perman&egrave;ncies</a></li>
              <li style="border-bottom:1px dashed #ccc; padding-top:3px; padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prreu/prreu_grid.php',this)">Reunions</a></li>
                     
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./prcar/prcar_grid.php',this)">C&agrave;rrecs</a></li>
            </ul>
		</div>

        <div title="Alumnes" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./alum/alum_grid.php',this)">Manteniment</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./algr/algr_grid.php',this)">Grups :: mat&egrave;ries :: alumnes</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./almat/almat_grid.php',this)">Mat&egrave;ries :: alumnes</a></li>
              <li><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./almat_tree/almat_tree_grid.php',this)">Matriculacions :: alumnes</a></li>
            </ul>
		</div>
        
        <div title="Informes" style="overflow:auto;padding:1px;">
			<ul style="list-style:none; padding-left:0px; padding-left:3px; text-align:left">
              <li style="padding-bottom:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./inf_assist/inf_assist_grup_see.php?idgrup=0',this)">Grups</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./inf_assist/inf_assist_alum_see.php?idalumne=0',this)">Alumnes</a></li>
              <li style="border-top:1px dashed #ccc; padding-top:3px;"><a style='color:#000033; text-decoration:none;' href="javascript:void(0)" onclick="open1('./inf_global/inf_global_see.php?idalumne=0',this)">Dades Globals</a></li>
         
         <?php     
         if (($hosting) AND ($modul_reg_prof))     
            {
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ctrl_prof/ctrl_prof_grid_in.php?idalumne=0\',this)">Professorat arriba tard</a></li>');
            print('<li style="padding-top:3px;"><a style=\'color:#000033; text-decoration:none;\' href="javascript:void(0)" onclick="open1(\'./ctrl_prof/ctrl_prof_grid_out.php?idalumne=0\',this)">Professorat marxa aviat</a></li>');
            }
         else 
            {
            print('<li style="border-top:1px dashed #ccc; padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Professorat arriba tard</a></li>');
            print('<li style="padding-top:3px;"><a style=\'color:#6a6a6a; text-decoration:none;\' href="javascript:void(0)" >Professorat marxa aviat</a></li>');
            }
              
         ?>     
              </ul>
		</div>
        <?php
		}
		?>
        
    </div>
    
<div id="dlg_hor" class="easyui-dialog" style="width:1100px;height:650px;"  
            closed="true" collapsible="true" resizable="true" modal="true" toolbar="#dlg_hor-toolbar">  
        <!--<iframe width="890" height="680" scrolling="auto" src="./jcrop/demos/crop.php?idprofessors=5" style="border:0px;"></iframe>-->
</div>
    
<div id="dlg_hor-toolbar">  
    <table cellpadding="0" cellspacing="0" style="width:100%">  
        <tr>  
            <td>
                <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="javascript:$('#dlg_hor').dialog('refresh')">Recarregar</a>
                <a href="#" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:imprimirInforme()">Imprimir</a>  
                <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="javascript:$('#dlg_hor').dialog('close')">Tancar</a>  
            </td>
        </tr>  
    </table>  
</div>

<script type="text/javascript">  
        var url;	
		
		function verHorario(idprofessors){  
				url = './prmat/prmat_see.php?idprofessors='+idprofessors;
				$('#dlg_hor').dialog('open').dialog('setTitle','El teu horari');
				$('#dlg_hor').dialog('refresh', url);
        }
</script>
