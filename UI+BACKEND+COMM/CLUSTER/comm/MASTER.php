<?php
/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: Master configuration file.
 *		Contains all the information about connecting to the MySQL DB etc.
**/

$MySQLHost = "127.0.0.1";
$MySQLUser = "root";
$MySQLPW = "User-123";
$DataBase = "plugandprotect";

// Create connection
$MySQL = new mysqli($MySQLHost, $MySQLUser, $MySQLPW, $DataBase);

// Check connection
if ($MySQL->connect_error) {
    die("Connection failed: " . $MySQL->connect_error);
}


if(isset($APIScript) && $APIScript)
{
	/** DEFINE: Origin IP Address **/
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$OriginIP = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$OriginIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$OriginIP = $_SERVER['REMOTE_ADDR'];
	}
}

?>