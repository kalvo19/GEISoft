<?php
	$idfamilies   = isset($_SESSION['familia'])              ? $_SESSION['familia']              : 0;
	$curs_escolar = isset($_SESSION['curs_escolar'])         ? $_SESSION['curs_escolar']         : 0;
	$curs_literal = isset($_SESSION['curs_escolar_literal']) ? $_SESSION['curs_escolar_literal'] : 0;
	
	if ($idfamilies==0 || $curs_escolar==0) {
		exit;
	}	

?>

<h2 style="padding-left:10px; padding-top:7px; ">Els teus fills</h2> 
<div class="easyui-accordion" data-options="multiple:true" style="width:260px; border-top:1px solid #aacae6;">    
        
        <?php  
                $rsAlumnes    = getAlumnesFamilia($idfamilies);				
            	while ($row = mysql_fetch_assoc($rsAlumnes)) {
				   $link_horari     = "<a style='color:#333; font-weight:normal; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./families/families_horari_see.php?idalumnes=".$row['idalumnes']."&curs=".$curs_escolar."&cursliteral=".$curs_literal."\",this)'>Horari</a>";
				   
				   $url_informe      = "./families/families_informe_see.php?c_alumne=".$row['idalumnes'];
				   $url_informe     .= "&box_dg=1&box_faltes=1&box_retards=1&box_justificacions=1&box_incidencies=1&box_CCC=1";			   
				   $link_informe    = "<a style='color:#333; font-weight:normal; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"".$url_informe."\",this)'>Informe assist&egrave;ncia</a>";
				   
				   $link_missatges  = "<a style='color:#333; font-weight:normal; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./families/families_missatge_tutor_grid.php?c_alumne=".$row['idalumnes']."\",this)'>Missatges al tutor</a>";
				   
				   $link_sms        = "<a style='color:#333; font-weight:normal; text-decoration:none;' href='javascript:void(0)' onClick='open1(\"./families/families_sms_grid.php?idalumne=".$row['idalumnes']."\",this)'>SMS rebuts</a>";
				   
				   echo "<div title='".getValorTipusContacteAlumne($row['idalumnes'],TIPUS_nom_complet)."' data-options='collapsed:false,collapsible:false' style='overflow:auto;padding:1px;'>";
        		   echo "<ul style='list-style:none; padding-left:0px; padding-left:3px; text-align:left'>";
            	   echo "<li>".$link_horari."</li>";
                   echo "<li>".$link_informe."</li>";
				   echo "<li>".$link_missatges."</li>";
				   echo "<li>".$link_sms."</li>";
           		   echo "</ul></div>";
				}
				mysql_free_result($rsAlumnes);
	    ?>
</div>