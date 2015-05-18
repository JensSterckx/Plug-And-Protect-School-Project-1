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
 *	File: First boot file.
 *		Will test if the PI have a valid ip,
 *		if not, the pi will keep rebooting every 30 seconds.
**/

//Set execution dir to the current file one
chdir(realpath(dirname(__FILE__)));

exec("hostname -I", $IP);
$IP = $IP[0];

exec("echo \"Started logging...\n\" >> log.txt");

//All this not needed if ip is already configured (Ethernet Cable is faster)
if($IP == null || $IP == "")
{
	$IPTimeOut = 30; //seconden die aftellen tot wanneer er overgaat tot REBOOT
	$IPTimeOutS = $IPTimeOut;
	$timenow = date("Y-m-d H:i:s"); 
	echo "$timenow | IP NOT FOUND! REBOOT IN $IPTimeOut seconds\n";
	exec("echo \"$timenow | IP NOT FOUND! REBOOT IN $IPTimeOut seconds\" >> log.txt");
	
	while($IPTimeOut >= 0)
	{
		sleep(1); //1 second sleep
		//Recheck if we got IP already
		exec("hostname -I", $IP);
		$IP = $IP[0];
		if($IP == null || $IP == "")
		{
			$IPTimeOut--;
			$timenow = date("Y-m-d H:i:s"); 
			echo "$timenow | IP NOT FOUND! REBOOT IN $IPTimeOut seconds\n";
			exec("echo \"$timenow | IP NOT FOUND! REBOOT IN $IPTimeOut seconds\" >> log.txt");
		}
		else
		{
			$left = $IPTimeOutS - $IPTimeOut;
			$timenow = date("Y-m-d H:i:s"); 
			echo "$timenow | IP found after $left seconds!: My IP is $IP\n";
			exec("echo \"$timenow | IP found after $left seconds!: My IP is $IP\" >> log.txt");
			$IPTimeOut = -1;	//This will let us jump out of the While loop.
		}
		
		//When the timeout hits 0, reboot the module!
		if($IPTimeOut == 0)
		{
			$timenow = date("Y-m-d H:i:s"); 
			echo "$timenow | IP NOT FOUND! MODULE IS GOING TO REBOOT!\n";
			exec("echo \"$timenow | REBOOT!!!\n$timenow | IP NOT FOUND! MODULE IS GOING TO REBOOT!\n\n\" >> log.txt");
			exec("reboot");
			die();
		}
	}
}
else
{
	$timenow = date("Y-m-d H:i:s"); 
	echo "$timenow | IP found: My IP is $IP\n";
	exec("echo \"$timenow | IP found: My IP is $IP\" >> log.txt");
}

//Sending the first heartbeat
exec("php -q /var/www/comm/heartbeat.php");

echo "\n\nIP done, continue to configs.";
?>
