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
 *	File: Mode file.
 *		This script will request all the configuration files associated with this MAC address.
**/

/** Including the Master configuration file. **/
include("/var/www/comm/MASTER.php");

//set POST variables
$url = "http://$CluserHostName/comm/ModuleConfigHandler.php";
$fields = array(
						'MAC' => urlencode($MAC)
				);
				
//url-ify the data for the POST
$fields_string = "";
foreach($fields as $key=>$value) 
{ 
	$fields_string .= $key.'='.$value.'&'; 
}
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

$result = json_decode($result, true);

$timenow = date("Y-m-d H:i:s"); 
echo "$timenow | Installing config files\n";
exec("echo \"\n\n$timenow | Installing config files...\n\" >> log.txt");


foreach ($result as $config)
{
	$type = $config['type'];
	$path = $config['file'];
	//echo $config['content'];
	
	file_put_contents($config['file'], $config['content']);
	//Changing to UNIX format (MySQL sometimes places in WIN format (rc.local bugs)
	exec("dos2unix " . $config['file']);
	exec("chmod +x " . $config['file']); //Dirty fix to solve non executable scripts
	
	
	$timenow = date("Y-m-d H:i:s"); 
	echo "$timenow | Installing $type IN $path...\n";
	exec("echo \"$timenow | Installing $type IN $path...\" >> log.txt");

}
echo "$timenow | DONE Installing config files\n";
exec("echo \"$timenow | DONE Installing config files...\" >> log.txt");
?>