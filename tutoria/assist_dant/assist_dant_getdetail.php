<?php
$idalumnes = $_REQUEST['id'];	
$imgalum = "../images/alumnes/".$idalumnes.".jpg";
		
if (file_exists($imgalum)) {
	$imgalum = "./images/alumnes/".$idalumnes.".jpg";
}
else {
	$imgalum = "./images/alumnes/alumne.png";
}
		
?>
    
<table class="dv-table" border="0" style="width:100%;">
<tr>
    <td style="border:0" valign=top width=180>
    <?php echo "<img src=\"$imgalum\" style=\"border:1px dashed #eee;width:51px;height:70px;margin-right:1px\" />"; ?></td>
    <td width=2>&nbsp;</td>
</tr>
</table>