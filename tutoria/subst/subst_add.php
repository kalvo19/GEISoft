<?php
include('../bbdd/connect.php');
include_once('../func/constants.php');
include_once('../func/generic.php');
mysql_query("SET NAMES 'utf8'");

$professor_substitut  = isset($_REQUEST['professor_substitut']) ? $_REQUEST['professor_substitut'] : 0 ;
$professor  		  = isset($_REQUEST['professor'])   	    ? $_REQUEST['professor']  		   : 0 ;

// Desactivem al professor principal, si així es demana
if (! isset($_REQUEST['acces'])) {
	$sql    = "update professors set activat='N' where idprofessors=$professor_substitut";
	$result = @mysql_query($sql);
}

// Esborrem totes les assignacions prèvies del professor substitut
$sql    = "delete from prof_agrupament where idprofessors=$professor";
$result = @mysql_query($sql);

$sql    = "delete from professor_carrec where idprofessors=$professor";
$result = @mysql_query($sql);

$sql    = "delete from guardies where idprofessors=$professor";
$result = @mysql_query($sql);

// Procedim ara sí amb el procès d'adjudicació de matèries, càrrecs i guàrdies al nou substitut
$sql    = "insert into prof_agrupament(idprofessors,idagrups_materies) ";
$sql   .= "select ".$professor.",idagrups_materies ";
$sql   .= "from prof_agrupament where idprofessors=$professor_substitut";
$result = @mysql_query($sql);

$sql    = "insert into professor_carrec(idprofessors,idcarrecs,idgrups) ";
$sql   .= "select ".$professor.",idcarrecs,idgrups ";
$sql   .= "from professor_carrec where idprofessors=$professor_substitut";
$result = @mysql_query($sql);

$sql    = "insert into guardies(idprofessors,id_dies_franges,idespais_centre) ";
$sql   .= "select ".$professor.",id_dies_franges,idespais_centre ";
$sql   .= "from guardies where idprofessors=$professor_substitut";
$result = @mysql_query($sql);

if (! isset($_REQUEST['acces'])) {
	// Si es desactiva el professor substituit, esborrem les seves assignacions
	// per tal de no interferir amb els horaris
	$sql    = "delete from prof_agrupament where idprofessors=$professor_substitut";
	$result = @mysql_query($sql);
	
	$sql    = "delete from professor_carrec where idprofessors=$professor_substitut";
	$result = @mysql_query($sql);
	
	$sql    = "delete from guardies where idprofessors=$professor_substitut";
	$result = @mysql_query($sql);
}

/*$fp = fopen("log.txt","a");
fwrite($fp, $sql ."\n ".PHP_EOL);
fclose($fp);*/

echo json_encode(array('success'=>true));
mysql_close();
?>