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
 *	File: Log handling file.
 *		Contains basic functions to write log entries to DB.
**/




function LogWriteEntry($MySQL, $cip, $desc)
{
	//Building the query
	$Query = "	INSERT INTO tbl_logs (description, IP) 
				VALUES ('$desc', '$cip');";
				
	if ($MySQL->query($Query) === TRUE) 
	{
		//Creating record was successfull
	} 
	else 
	{
		die("Failed to update logs: " . $MySQL->error);
	}
}
?>