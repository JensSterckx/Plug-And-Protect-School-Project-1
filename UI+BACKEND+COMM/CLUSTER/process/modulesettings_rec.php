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
 *	File: DashBoard file.
 *		Dashboard template.
**/

include("../init.php");
include("../includes/MEMBER.php");

if(isset($_POST["SECTION"]))
{
	if(!isset($_POST["MAC"]))
	{
		die("No MAC specified");
	}
	
	if($_POST["SECTION"] == "general")
	{
		//The general settings were modified
		$Query = "";
		if(!empty($_POST["module_name"]))
		{
		
		}
		if(!empty($_POST["module_info"]))
		{
		
		}
		if(!empty($_POST["module_type_camera"]))
		{
		
		}
		if(!empty($_POST["module_type_t-l-sensor"]))
		{
		
		}
		if(!empty($_POST["module_type_alarm"]))
		{
		
		}
	}
	














	echo "Post DATA DEBUUUG <a href=\"" . $_SERVER['HTTP_REFERER'] . "\">RETUURN</a><br />";
	echo "POST";
	?>
	<?php echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($_POST, true) . ' ?>', true ) ); ?>
	<br><?php
}




//header("Location: " . $_SERVER['HTTP_REFERER']); //Send back to previous page
//die();

?>
