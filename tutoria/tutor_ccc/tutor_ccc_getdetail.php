<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$id   = $_REQUEST['id']; 
$sql  = "select * from ccc_taula_principal where idccc_taula_principal='$id'";
$rs   = mysql_query($sql); 
$item = mysql_fetch_array($rs);

$aa = explode('-',$id);
$idmotius    = $item['id_motius'];
$idgrup      = $item['idgrup'];
$idalumne    = $item['idalumne'];
$idprofessor = $item['idprofessor'];
		
$imgalum = "../images/alumnes/".$idalumne.".jpg";
$imgprof = "../images/prof/".$idprofessor.".jpg";
		
if (file_exists($imgalum)) {
	$imgalum = "./images/alumnes/".$idalumne.".jpg";
}
else {
	$imgalum = "./images/alumnes/alumne.png";
}
		
if (file_exists($imgprof)) {
	$imgprof = "./images/prof/".$idprofessor.".jpg";
}
else {
	$imgprof = "./images/prof/prof.png";
}

?>
    
<table class="dv-table" border="0" style="width:100%;">
<tr>
    <td style="border:0" valign=top width=120>
    <b>Alumne</b><br><?= getAlumne($item['idalumne'],TIPUS_nom_complet) ?><br>
    <?php echo "<img src=\"$imgalum\" style=\"border:1px dashed #eee;width:51px;height:70px;margin-right:1px\" />"; ?></td>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top width=120>
    <b>Professor</b><br><?= getProfessor($item['idprofessor'],TIPUS_nom_complet) ?><br>
    <?php echo "<img src=\"$imgprof\" style=\"border:1px dashed #eee;width:51px;height:70px;margin-right:1px\" />"; ?></td>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top width=150>
    <b>Grup</b><br><?= getGrup($idgrup)->nom ?><br>
    <b>Mat&egrave;ria</b><br><?= (intval($item['idmateria']!=0) ? getMateria($item['idmateria'])->nom_materia : '') ?><br>
    <b>Espai</b><br><?= ($item['idespais']!=0 ? getEspaiCentre($item['idespais'])->descripcio : '') ?><br>
    <b>Motiu</b><br><?= getLiteralMotiusCCC($idmotius)->nom_motiu ?><br>
    </td>
    <td width=2>&nbsp;</td>
    <td style="border:0" valign=top>
    <b>Descripci&oacute; detallada</b><br><?= nl2br($item['descripcio_detallada']) ?><br>
    </td>
</tr>
</table>
                            
<?php
mysql_free_result($rs);
mysql_close();
?>