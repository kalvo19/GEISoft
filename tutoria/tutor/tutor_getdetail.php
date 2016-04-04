<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id   = $_REQUEST['id']; 
$sql  = "select * from incidencia_alumne where idincidencia_alumne='$id'";
$rs   = mysql_query($sql); 
$item = mysql_fetch_array($rs);

$aa = explode('-',$id);
$idalumnes = $item['idalumnes'];
$idprofessors = $item['idprofessors'];
		
$imgalum = "../images/alumnes/".$idalumnes.".jpg";
$imgprof = "../images/prof/".$idprofessors.".jpg";
		
if (file_exists($imgalum)) {
	$imgalum = "./images/alumnes/".$idalumnes.".jpg";
}
else {
	$imgalum = "./images/alumnes/alumne.png";
}
		
if (file_exists($imgprof)) {
	$imgprof = "./images/prof/".$idprofessors.".jpg";
}
else {
	$imgprof = "./images/prof/prof.png";
}

?>
    
<table class="dv-table" border="0" style="width:100%;">
<tr>
    <td style="border:0" valign=top width=180>
    <b>Alumne</b><br><?= getAlumne($item['idalumnes'],TIPUS_nom_complet) ?><br>
    <?php echo "<img src=\"$imgalum\" style=\"border:1px dashed #eee;width:51px;height:70px;margin-right:1px\" />"; ?></td>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top width=150>
    <b>Professor</b><br><?= getProfessor($item['idprofessors'],TIPUS_nom_complet) ?><br>
    <?php echo "<img src=\"$imgprof\" style=\"border:1px dashed #eee;width:51px;height:70px;margin-right:1px\" />"; ?></td>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top>  
    <b>Mat&egrave;ria</b><br><?= getMateria($item['id_mat_uf_pla'])->nom_materia ?><br>
    <b>Comentari</b><br><?= nl2br($item['comentari']) ?><br>
    </td>
</tr>
</table>
                            
<?php
mysql_free_result($rs);
mysql_close();
?>