<?php

/*  getEspaiCentre --> Dades espai centre */
function getEspaiCentre($idespais_centre) {
    $sql = "SELECT * FROM espais_centre WHERE idespais_centre = '$idespais_centre'";
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
/* ********************************************************************************************************* */

?>