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
 *	File: index file.
**/

include("init.php");

if (isset($_SESSION['clientid']) && isset($_SESSION['loggedin']))
{

	header("Location: /home.php#");
	exit();
}
//Login has it's own template
include("templates/login.html");

?>
