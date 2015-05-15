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
 *	File: Initialization file.
 *		This script is will start and save the session in our MySQL DB.
 *		This so that both servers can serve the same sessions.
**/


ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

//Including all required items defined in the master include file.
include("includes/master.inc.php");


/*
################## SESSION IP ##################
*/
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
	$cip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$cip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$cip = $_SERVER['REMOTE_ADDR'];
}





?>
