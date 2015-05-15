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
 *	File: Reboot the module from panel
**/

//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');

	//Set execution dir to the current file one
	chdir(realpath(dirname(__FILE__)));

	chdir("bin");
	exec("sudo ./init stop"); //dirty sudo (/dev/mem/ for BCM), sensoring gives first temperature, second the light (now)
	exec("sudo reboot");


?>