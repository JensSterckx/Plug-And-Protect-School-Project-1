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

if(!(isset($_POST['uname']) && isset($_POST['uname'])))
{
	die();
}

$uname = htmlspecialchars(str_replace(' ', '', strtolower($_POST['uname'])));
$pw = htmlspecialchars($_POST['pw']);

$msg["error"] = 0;


if ($uname == null)
{
	$msg["error"] = 1;
	$msg["reason"] = "nouname";
	echo json_encode($msg);
} elseif ($pw == null)
{
	$msg["error"] = 1;
	$msg["reason"] = "nopas";
	echo json_encode($msg);
}

if ($msg["error"] != 1)
{
	$returnv =  AuthLoginUser($MySQL, $uname, $pw);
	if ($returnv['code'] == -1)
	{
		
		$details = "AUTH:FAILED -> $uname";
		//LogWriteEntry($MySQL, $cip, $details);
		
		$msg["error"] = 1;
		$msg["reason"] = "BADLOGIN";
		echo json_encode($msg);
	}
	else
	{
		//resetting session
		//session_unset();
		
		$_SESSION['loggedin'] = 1;
		$_SESSION['clientid'] = $returnv['clientid'];
		
		$_SESSION['clientinfo'] = InfoGatherClientInfo($MySQL, $returnv['clientid']);

		$prename = $_SESSION['clientinfo']['prename'];
		$lastname = $_SESSION['clientinfo']['lastname'];
		
		$details = "AUTH:SUCCESS -> $prename $lastname";
		//LogWriteEntry($MySQL, $cip, $details);
		
		$msg["error"] = 0;
		$msg["name"] = "$lastname $prename";
		$msg["id"] = $_SESSION['clientid'];
		sleep(1); //wait some time before outputting.
		echo json_encode($msg);
	}
}

?>