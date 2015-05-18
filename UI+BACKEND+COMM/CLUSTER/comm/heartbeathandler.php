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
 *	File: Hearbeat Handler.
 *		This script receives the POST messages of the modules.
 *		And update if needed the MySQL DB.
**/


//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');


$response['error'] = 0;
$response['reason'] = "";


/** Check if it's a POST, if not drop **/  
if (!(isset($_POST['MAC']) && isset($_POST['IP']) && isset($_POST['status']) && isset($_POST['HOSTNAME'])))
{
	$response['error'] = true;
	$response['type'] = "badrequest";
	$response['reason'] = "Bad requests (POST not set)";
	echo json_encode($response);
	die();
}

$APIScript = true;

/** Include the MASTER file to establish MySQL Conn **/
include("MASTER.php");

$ModuleTable = "tbl_modules";

$MAC = $_POST['MAC'];
$IP = $_POST['IP'];
$STATUS = $_POST['status'];
$HOSTNAME = $_POST['HOSTNAME'];


/*if ($IP != $OriginIP)
{
	//The client sends another IP than it's own?
	$response['error'] = 1;
	$response['type'] = "badip";
	$response['reason'] = "Bad IP address (Origin is not same as values)";
	echo json_encode($response);
	$MySQL->close();
	die();
}*/


/** TEST VALUES **/
/*
$MAC = "b8:27:eb:d5:dc:f0";
$IP = "192.168.100.123";
$STATUS = "1";
$HOSTNAME = "test";
*/

/** Check if the module is already registered or not. **/
$Query = "	SELECT MAC, IP, HOSTNAME, status, uptime 
			FROM $ModuleTable 
			WHERE MAC='$MAC';";

$result = $MySQL->query($Query);

if ($result->num_rows > 0) 
{
	//Row is found		(Can only be 1 value since MAC is unique in db)
	//Since it already exist, we must just update some stuff.
    $Info = $result->fetch_assoc();
    //echo "MAC: " . $row["MAC"]. " - IP: " . $row["IP"]. "<br>";
	//echo "Module is found, should be updated now!";
	
	/** Checking if the update differs from the database values **/
	$Update = false;	//Did it this way to have extension capabilities.
	if ($Info["IP"] != $IP || $Info["status"] != $STATUS || $Info["HOSTNAME"] != $HOSTNAME)
	{
		$Update = true;
	}
	
	if($Update)
	{
		$Query = "UPDATE $ModuleTable SET IP='$IP', HOSTNAME='$HOSTNAME', status='$STATUS', uptime='0' WHERE MAC='$MAC';";

		if ($MySQL->query($Query) === TRUE) 
		{
			$response['error'] = 0;
			$response['type'] = "updated";
			$response['reason'] = "Module updated!";
			echo json_encode($response);
		} 
		else 
		{
			$response['error'] = 1;
			$response['type'] = "updateerror";
			$response['reason'] = "Errors in updating module: " .$MySQL->error;
			echo json_encode($response);
		}
	}
	if (!($response['error'] || $Update))
	{
		$Info["uptime"]++;
		$uptime = $Info["uptime"];
		
		/** Update the Uptime counter  (this updates timestamp too) **/
		$Query = "UPDATE $ModuleTable SET uptime='$uptime' WHERE MAC='$MAC'";

		if ($MySQL->query($Query) === TRUE) 
		{
			$response['error'] = 0;
			$response['type'] = "HB";
			$response['reason'] = "Heartbeat received!";
			echo json_encode($response);
		} 
		else 
		{
			$response['error'] = 1;
			$response['type'] = "HBerror";
			$response['reason'] = "Errors in updating module: " .$MySQL->error;
			echo json_encode($response);
		}
	}
} 
else 
{
    //Nothing is found in DB for this MAC address.
	//Inserting it into our DB
	$Query = "	INSERT INTO $ModuleTable (MAC, IP, HOSTNAME, status, uptime) 
				VALUES ('$MAC', '$IP', '$HOSTNAME', '1', '0');";

	if ($MySQL->query($Query) === TRUE) 
	{
		//Creating record was successfull
		//Now we generate the default module information row
		$Query = "INSERT INTO `plugandprotect`.`tbl_minfo` (`MAC`, `NAME`, `DESC`, `TYPE`) VALUES ('$MAC', '$HOSTNAME', 'This is a new module, rename it at the settings page.', 'NEW');";
		if ($MySQL->query($Query) === TRUE) 
		{
			$response['error'] = 0;
			$response['type'] = "registered";
			$response['reason'] = "Registered module";
			echo json_encode($response);
		}
		else 
		{
			//echo "Error: " . $Query . "<br>" . $MySQL->error;
			//There were errors in creating the record.
			$response['error'] = 1;
			$response['type'] = "registererror";
			$response['reason'] = "Errors in registering module: " .$MySQL->error;
			echo json_encode($response);
		}
	}
	else 
	{
		//echo "Error: " . $Query . "<br>" . $MySQL->error;
		//There were errors in creating the record.
		$response['error'] = 1;
		$response['type'] = "registererror";
		$response['reason'] = "Errors in registering module: " .$MySQL->error;
		echo json_encode($response);
	}
}

/** Closing the MySQL connection **/
$MySQL->close();
?>
