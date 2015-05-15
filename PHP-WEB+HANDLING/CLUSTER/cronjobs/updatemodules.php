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
 *	File: Module Cronjob.
 *		This script is ran every X minute, and this updates our module info.
 *		And update if needed the MySQL DB.
**/


/** Query to get modules who were offline 2 minutes **/
//UPDATE tbl_modules SET status='0' WHERE last_update < (NOW() - INTERVAL 2 MINUTE)


//Writing errors to screen for testing.
error_reporting(-1);
ini_set('display_errors', 'On');

//Set execution dir to the current file one
chdir(realpath(dirname(__FILE__)));


$ModuleTable = "tbl_modules";
$MaxOfflineTime = "2 MINUTE";


/** Include the MASTER file to establish MySQL Conn **/
include("../comm/MASTER.php");

$Query = "	UPDATE $ModuleTable 
			SET status='0' 
			WHERE last_update < (NOW() - INTERVAL $MaxOfflineTime);";

if ($MySQL->query($Query) === TRUE) 
{
	//Checking records was successfull
	//echo "success";
	if($MySQL->affected_rows > 0)
	{
		//Code if there was a change
	}
	
} 
else 
{
	//echo "Error: " . $Query . "<br>" . $MySQL->error;
	//There were errors in executing the record.
	//echo "Errors in checking modules: " .$MySQL->error;
}


/** Closing the MySQL connection **/
$MySQL->close();
?>