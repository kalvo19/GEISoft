<?php
define("DB_HOST", "geisoft.cat:3306");
define("DB_NAME", "sms_geisoft");
define("DB_USER", "consulta_sms");
define("DB_PASS", "consulta");

$conn = @mysql_connect(DB_HOST,DB_USER,DB_PASS);

if (!$conn) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db(DB_NAME, $conn);
?>