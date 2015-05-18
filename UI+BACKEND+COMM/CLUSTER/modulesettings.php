<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("init.php");
include("includes/MEMBER.php");

if(isset($_POST["SECTION"]))
{
	if(!isset($_POST["MAC"]))
	{
		die("No MAC specified");
	}
	
	if($_POST["SECTION"] == "general")
	{
		//The general settings were modified
		$Querys = array();
		$Types = array();
		if(!empty($_POST["module_name"]))
		{
			$Querys[] = "UPDATE tbl_minfo SET NAME='" . $_POST["module_name"] . "' WHERE MAC='" . $_POST["MAC"] . "'; ";
		}
		if(!empty($_POST["module_info"]))
		{
			$Querys[] = "UPDATE tbl_minfo SET `DESC`='" . $_POST["module_info"] . "' WHERE `MAC`='" . $_POST["MAC"] . "'; ";
		}
		if(!empty($_POST["module_type_camera"]))
		{	
			$Types[] = "Camera";
		}
		if(!empty($_POST["module_type_t-l-sensor"]))
		{
			$Types[] = "T-L-Sensor";
		}
		if(!empty($_POST["module_type_alarm"]))
		{
			$Types[] = "Alarm";
		}
		if(empty($Types))
		{
			$Types[] = "NEW";
		}
		$TypeString = implode("+", $Types);
		$Querys[] = "UPDATE tbl_minfo SET TYPE='$TypeString' WHERE MAC='" . $_POST["MAC"] . "'; ";
		
		
		$Error = "";
		//Had to do it this way, multi_query breaks cause of buffering, (Commands out of sync);
		foreach($Querys as $Query)
		{
			if ($MySQL->query($Query) === TRUE) 
			{
				//No error
			}
			else 
			{
				$Error .= $MySQL->error;
			}
		}
	}
	else if ($_POST["SECTION"] == "advanced")
	{
		$Error = false;
		?>
		<?php echo str_replace(array('&lt;?php&nbsp;','?&gt;'), '', highlight_string( '<?php ' .     var_export($_POST, true) . ' ?>', true ) ); ?>
		<?php
	}
	else
	{
		$Error = "Unknown TYPE!";
		
	}
	
	echo "<!--";
	print_r($_POST);
	echo "-->";
	
	if(!$Error)
	{
		$SaveResponse = "Succesfully saved!";
	}
	else
	{
		$SaveResponse = $Query . "<br>" .$Error;
	}
}

if(isset($_GET['MAC']) && $_GET['MAC'] != "")
{
	$Info = generateModuleInformation($MySQL, "ARRAY", $_GET['MAC']);
	$Configs = gatherModuleConfigurationFiles($MySQL, $_GET['MAC']);
	
	$ModuleName = $Info['NAME'];
	$PageName = $ModuleName . " Settings";
	$IP = $Info['IP'];
	$MAC = $Info['MAC'];
	$Description = $Info['DESC'];
	$Hostname = $Info["HOSTNAME"] . ".cluster.lan";
	
	
	$Types = $Info['TYPE'];
	$Types = explode("+", $Types);
	
	
	//Type set
	$ModuleTypes = array();
	foreach($Types as $Type)
	{
		$ModuleTypes[$Type] = true;
	}
	
	//print_r($Info);
	
	/* CONTENT FOR THE FILE CONFIGURATIONS */
	/* DISABLED, NOT WORKED OUT
	$ConfigPrint = "";
	if(empty($Configs))
	{
		$ConfigPrint .= "<tr><td>No Configurations!</td></tr>";
	}
	else
	{
		foreach($Configs as $Config)
		{
			$ConfigPrint .= "<tr>";
			$ConfigPrint .= "<td>" . $Config['type'] . "</td>";
			$ConfigPrint .= "<td>" . $Config['file'] . "</td>";
			$ConfigPrint .= "</tr><tr>";
			$ConfigPrint .= "<td colspan=\"2\"><textarea name=\"CONTENT_" . $Config['file'] ."\"rows=\"10\" placeholder=\"Config File Content\">" . $Config['content'] . "</textarea></td>";
			$ConfigPrint .= "</tr>";
			
			//print_r($Config);
		}
	}
	*/
	
	include("templates/header.html");
	include("templates/modulesettings.html");
	include("templates/footer.html");
}
else
{
	//Echo the missing MAC
	$PageName = "Settings overview";
	
	$Modules = generateModulesList($MySQL, "ARRAY");
	
	//print_r($Modules);
	//echo "<br><br>";
	
	$OverviewPrint = "";
	foreach($Modules as $ModulesO)
	{
		foreach($ModulesO as $Module)
		{
			$OverviewPrint .= "
		<a href=\"modulesettings.php?MAC=" . $Module["MAC"] . "\">
			<div class=\"kadertje\">
				<p>
					<span class=\"bold\" style=\"font-size: 15px;\">" . $Module["NAME"] . "</span>
					<br>" . $Module["IP"] . "<br>";
			$Types = explode("+", $Module["TYPE"]);
			foreach($Types as $Type)
			{
				$OverviewPrint .= "	<img alt=\"" . $Type . "\" title=\"" . $Type . "\" src=\"images/".$Type.".png\"> ";
			}
			
			$OverviewPrint .= "
				</p>
			</div>
		</a>";
		}
	}
	
	include("templates/header.html");
	include("templates/modulesettings_overview.html");
	include("templates/footer.html");
}



?>
