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
 *	File: Member authentication file.
 *		Will deny unauthorized access to files where login is required!
**/
 
 
 //This script must be included on every page where one logged in users got access to!

if (!(isset($_SESSION['clientid'])) && !(isset($_SESSION['loggedin'])))
{

	header("Location: /index.php?noauth=yes");
	die();
}

?>