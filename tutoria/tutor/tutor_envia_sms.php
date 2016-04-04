<?php
// NOTE: Code tested with HttpRequest v1.0, upgrade your DH library if it is older.
// See https://dinahosting.com/api/downloads/ page.
session_start();
include_once('../func/constants.php');
include_once('../func/generic.php');
include_once('../func/sms.php');

/* SMS's */
// ************************************************************ !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// MANCARIA UN CONTROL DE SESSIO PER ASSEGURAR-NOS QUE NO ES POT ACCEDIR PER url SENSE ESTAR LOGINAT
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

// Continguts i destinataris
$username          = USERNAME_SMS;
$code              = PASSWD_SMS;
$contents          = substr(str_replace("'","\'",$_REQUEST['contingut']),0,160);
$contents		   = treureAccentsSms($contents);
$destinataris      = $_SESSION['target_sms'];
$nom_destinataris  = $_SESSION['target_nom_alumnes'];
$header            = HEADER_SMS ;

//recorregut de l'array de destinataris i preparació de dades

//Crea la cadena final a enviar
$post_string_dest  = implode (',', $destinataris);
$post_string_nom   = implode (',', $nom_destinataris);
//echo $post_string_dest."<br>";
 
//creació de l'array de dadesper enviar
$post_data['user']             = $username;
$post_data['code']             = $code;
$post_data['contents']         = $contents;
$post_data['destinataris']     = $post_string_dest; 
$post_data['nom_destinataris'] = $post_string_nom;
$post_data['header']           = $header;

//recorregut de l'array i preparació de dades
foreach ( $post_data as $key => $value) {
    $post_items[] = $key . '=' . $value;
}
 
//Crea la cadena final a enviar
$post_string = implode ('&', $post_items);
//echo $post_string."<br>";
 
//crea la connexió  cURL
$curl_connection = 
  curl_init('http://www.geisoft.cat/sms_gest/enviament_sms.php');
 
//set options
curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($curl_connection, CURLOPT_USERAGENT, 
  "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
 
//set data to be posted
curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
 
//perform our request
$result = curl_exec($curl_connection);
 
//show information regarding the request
//print_r(curl_getinfo($curl_connection));
//echo curl_errno($curl_connection) . '-' . 
$error=curl_errno($curl_connection);
//$error= curl_error($curl_connection);
if ($error=="0") {
	echo "Informació rebuda correctament al servidor de geisoft.<br>";
	echo "Saldo actual de missatges: <strong>".extreu_saldo(USERNAME_SMS)."</strong>";
}
else {echo "S'ha produit un error amb codi".$error;}
 
//close the connection
curl_close($curl_connection);

?>
