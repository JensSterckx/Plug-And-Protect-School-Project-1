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
 *	File: Send sensor data on request
**/

//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');


//Systeem om ervoor te zorgen dat sensor chip niet overbelast graakt (2 keer de BCM uitlezen op ongeveer exact hetzelfde moment genereert errors in output).
$lock = '/tmp/sensorlive.lock';
$f = @fopen($lock, 'x');
if ($f === false) {
	$ReturnD["locked"] = "Program in use";
	$ReturnD["overload"] = "protection";
	$ReturnD["too many"] = "viewers on this module!";
} 
else 
{
	//Set execution dir to the current file one
	chdir(realpath(dirname(__FILE__)));

	chdir("bin");
	exec("sudo ./sensoring", $SenseData); //dirty sudo (/dev/mem/ for BCM), sensoring gives first temperature, second the light (now)
	chdir("..");


	if($SenseData == null || $SenseData[0] == 0)
	{
		//Error, shield not connected/not inited
		$ReturnD["error, sensors"] = "not initialized";
		$ReturnD["init"] = "reinitializing";
		chdir("bin");
		exec("sudo ./init"); //dirty sudo (/dev/mem/ for BCM), sensoring gives first temperature, second the light (now)
		chdir("..");
	}
	else
	{
		//print_r($SenseData);
		$ReturnD["temperature"] = $SenseData[0];
		$ReturnD["light"] = $SenseData[1];
	}
	
	// Cleanup the lock
	fclose($f);
	unlink($lock);
}
echo json_encode($ReturnD);


?>