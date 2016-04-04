<?php
  include_once('../bbdd/connect.php');
  include_once('../func/constants.php');
  include_once('../func/generic.php');
  mysql_query("SET NAMES 'utf8'");
	
  $idprofessors = $_REQUEST['idprofessors'];
  
  $imgprof = "../images/prof/".$idprofessors.".jpg";

  if (file_exists($imgprof)) {
	$imgprof = "./images/prof/".$idprofessors.".jpg";
  }
  else {
	$imgprof = "./images/prof/prof.png";
  }
  
  echo "<table>";
  echo "<tr>";
  echo "<td width='70' valign='top'>";
  echo "<img src='".$imgprof."' style='width:51px;height:70px'>";
  echo "</td>";
  echo "<td>";
  echo "<form name='fm_fitxa' method='post'>";
  echo "<div class='fitem'>";
  echo "<label>Codi Professor:</label> ";
  echo "<input name='codi_professor' class='easyui-validatebox' size='65' value='".getCodiProfessor($idprofessors)."'>";
  echo "</div>";
  echo "<hr>";
  
  $rsTipusContacte = getallTipusContacte();
  while ($row = mysql_fetch_assoc($rsTipusContacte)) {
      echo "<div class='fitem'>";
      
      $valor = getValorTipusContacteProfessor($idprofessors,$row['idtipus_contacte']);
      
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