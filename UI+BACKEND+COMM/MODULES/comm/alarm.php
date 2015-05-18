<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//voor school
if(isset($_GET['MODE']))
{
	$_POST['MODE'] = $_GET['MODE'];
}


if(isset($_POST['MODE']))
{
	chdir(realpath(dirname(__FILE__)));
	chdir("bin");
	
	switch($_POST['MODE'])
	{
		case "status":
			exec("cat /tmp/alarm.pid", $AlarmPid);
			if($AlarmPid == null)
			{
				echo "uit";
			}
			else
			{
				echo "aan";
			}
			break;
			
		case "aan":
			//First check if it's already on
			exec("cat /tmp/alarm.pid", $AlarmPid);
			if(isset($AlarmPid[0]))
			{
				echo "al aan";
			}
			else
			{
				exec("sudo ./alarm_gpio 1 > /dev/null 2>/dev/null &");
				echo "aan";
			}
			break;
			
		case "uit":
		default:
			exec("sudo ./alarm_gpio 0");
			echo "uit";
			break;
	}
}

/*
<html>
<body>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="MODE" value="aan" />
		<input type="submit" value="AAN" />
	</form>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="MODE" value="uit" />
		<input type="submit" value="UIT" />
	</form>
</body>
</html>

*/
?>

