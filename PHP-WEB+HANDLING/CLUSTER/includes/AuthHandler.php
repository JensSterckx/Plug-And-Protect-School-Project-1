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
 *	File: Auth functie file.
 *		Bevat de authenticatie functies voor login en aanmaken users.
**/

function AuthLoginUser($MySQL, $uname, $pw)
{
	$UserTable = "tbl_users";

	//test if the uname exists in db first:
	$Query = "	SELECT id, password, passwordkey
				FROM $UserTable 
				WHERE uname='$uname';";

	$result = $MySQL->query($Query);
	
	$Info = $result->fetch_assoc();

	if($Info == null)
	{
		//User is not found (No rows returned)
		$returnv['code'] = -1;
		$returnv['clientid'] = -1;
		return $returnv;
	}
	else  //zowel, dan retrieven we van dat id het passwoord en de hash
	{
		$id = $Info['id'];

		$pw = crypt($pw,$Info['passwordkey']);
		
		if ($pw == $Info['password'])
		{
			
			//lOGIN WAS SUCCESFULL
			$returnv['code'] = 0;
			$returnv['clientid'] = $id;

			return $returnv;
			
		}
		else
		{
			$returnv['code'] = -1;
			$returnv['clientid'] = $id;
			return $returnv;
		}
	}
}

function InfoGatherClientInfo($MySQL, $id)
{
	$UserTable = "tbl_users";

	//test if the uname exists in db first:
	$Query = "	SELECT prename, lastname
				FROM $UserTable 
				WHERE id='$id';";
				
	$result = $MySQL->query($Query);
	
	$Info = $result->fetch_assoc();
	
	return $Info;
}

?>