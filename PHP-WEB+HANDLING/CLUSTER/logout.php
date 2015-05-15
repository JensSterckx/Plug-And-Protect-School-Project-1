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
 *	File: logout file.
**/

include 'init.php';

session_unset();
session_destroy();

session_start();

header("Location: index.php");
die();


?>