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
 *	File: Login authentication file.
 *		Will manage the users session at login and checks passwords etc.
**/
 
include("../init.php");
include("../includes/MEMBER.php");

if(isset($_GET['MAC']))
{
	echo generateModuleInformation($MySQL, "JSON", $_GET['MAC']);
}
else
{
	echo generateModulesList($MySQL, "JSON");
}

?>