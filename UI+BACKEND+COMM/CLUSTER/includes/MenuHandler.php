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
 *	File: Menu handler
 * 		Will generate the menu lists / buttons etc.
**/

function printSideModuleList($MySQL)
{
	$Modules = generateModulesList($MySQL, "ARRAY");
	echo "<!--";
	print_r($Modules);
	echo "-->";
	
	//We maken eerst het Online gedeelte, en dan het offline gedeelte
	if(!empty($Modules['Online']))
	{
		foreach($Modules['Online'] as $Module)
		{
			echo "<li><a href=\"home.php#ModuleManage#" . $Module['MAC'] . "\">" . $Module['NAME'] . "</a></li>";
		}
	}
	
	//Ofline gedeelte
	if(!empty($Modules['Offline']))
	{
		foreach($Modules['Offline'] as $Module)
		{
			echo "<li><a href=\"home.php#ModuleManage#" . $Module['MAC'] . "\"><font color=\"red\">" . $Module['NAME'] . "</font></a></li>";
		}
	}
}
?>
