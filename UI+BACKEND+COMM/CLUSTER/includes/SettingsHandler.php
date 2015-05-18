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
 *	File: Settings manager
 *		This file contains all functions in changing and retrieving config files for the nodes.
**/

function gatherModuleConfigurationFiles($MySQL, $MAC)
{
	$ConfigTable = "tbl_mconfig";
	
	$Query = "	SELECT type, file, content 
				FROM $ConfigTable 
				WHERE MAC='$MAC';";
	$result = $MySQL->query($Query);
	
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$Configs[] = $row;
		}
		
		return $Configs;
	}
	else 
	{
		echo $MySQL->error;
	}
}
?>
