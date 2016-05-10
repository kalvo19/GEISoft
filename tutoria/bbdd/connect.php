<?php
ini_set("session.cookie_lifetime","7200");
ini_set("session.gc_maxlifetime","7200");

define("DB_HOST", "localhost");
define("DB_NAME", "prueba");
define("DB_USER", "root");
define("DB_PASS", "");

$conn = @mysql_connect(DB_HOST,DB_USER,DB_PASS);

if (!$conn) {
	die('Could not connect: ' . mysql_error());
} 

mysql_select_db(DB_NAME, $conn);
?>
