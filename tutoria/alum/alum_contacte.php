<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
  
  $fechaSegundos     = time();
  $strNoCache        = "?nocache=$fechaSegundos";
  $idalumnes         = isset($_REQUEST['idalumnes']) ? $_REQUEST['idalumnes'] : 0 ;
  $dadesalumneArray  = array(TIPUS_nom_complet,TIPUS_iden_ref,TIPUS_cognom1_alumne,
  							 TIPUS_cognom2_alumne,TIPUS_nom_alumne,TIPUS_genere,
							 TIPUS_a_determinar,TIPUS_nom_grup,TIPUS_login,TIPUS_contrasenya);
  
  $imgalum = "../images/alumnes/".$idalumnes.".jpg";
		
  if (file_exists($imgalum)) {
	$imgalum = "./images/alumnes/".$idalumnes.".jpg";
  }
  else {
	$imgalum = "./images/alumnes/alumne.png";
  }
  echo "<table>";
  echo "<tr>";
  echo "<td width='70' valign='top'>";
  echo "<img src='".$imgalum.$strNoCache."' style='width:51px;height:70px'>";
  echo "</td>";
  echo "<td>";
  echo "<form name='fm_fitxa' method='post'>";
  echo "<div class='fitem'>";
  echo "<label>Codi SAGA:</label> ";
  echo "<input name='codi_alumnes_saga' class='easyui-numberbox' size='65' value='".getCodiSagaAlumne($idalumnes)."'>";
  echo "</div>";
  echo "<hr>";
  
  $rsTipusContacte = getallTipusContacte();
  while ($row = mysql_fetch_assoc($rsTipusContacte)) {
      echo "<div class='fitem'>";
      
	  if (in_array($row['idtipus_contacte'], $dadesalumneArray)) {
			$valor = getValorTipusContacteAlumne($idalumnes,$row['idtipus_contacte']);
	  }
	  else {
	  		$valor = getValorTipusContacteFamilies($idalumnes,$row['idtipus_contacte']);
	  }
	  
	  if ($row['idtipus_contacte']==TIPUS_contrasenya_notifica) {
	  }
      else if ($row['idtipus_contacte']==TIPUS_contrasenya) {
          $valor = "";
		  echo "<input type='hidden' name='".$row['idtipus_contacte']."' class='easyui-validatebox' size='65' value='".$valor."'>";
      }
	  else {
	      echo "<label>".$row['dada_contacte'].":</label> ";
	  	  echo "<input name='".$row['idtipus_contacte']."' class='easyui-validatebox' size='65' value='".$valor."'>";
	  }
      
      echo "</div>"; 
  }
  echo "</form>";
  echo "</td>";
?>

<td valign="bottom">
<div style="padding:5px 0;text-align:right;padding-right:5px">
<a href="#" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="saveItem(<?php echo $_REQUEST['index'];?>)">Guardar</a>
<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="cancelItem(<?php echo $_REQUEST['index'];?>)">Cancel.lar</a>
</div>
</td></tr></table>