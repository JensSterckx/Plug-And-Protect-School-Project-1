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
 *		Contains all the information about connecting to the cluster etc.
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



?>