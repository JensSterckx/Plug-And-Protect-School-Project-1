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
 *		Will have all the necessary functions to compose the DashBoard.
**/

function generateModulesList($MySQL, $Type)
{
	//Declare arrays:
	$Online = array();
	$Offline = array();

	//ONLINE PART
	$Query = "SELECT * FROM tbl_modules NATURAL JOIN tbl_minfo WHERE status = 1;";
	$result = $MySQL->query($Query);
	
	echo $MySQL->error;
	
	if ($result->num_rows > 0) 
	{
		//Add data of each row to a big array
		while($row = $result->fetch_assoc()) 
		{
			$Online[] = $row;
		}
	} 
	else 
	{
		//No modules are online, STUUP
	}
	
	//OFFLINE PART
	$Query = "SELECT * FROM tbl_modules NATURAL JOIN tbl_minfo WHERE status = 0;";
	$result = $MySQL->query($Query);

	if ($result->num_rows > 0) 
	{
		//Add data of each row to a big array
		while($row = $result->fetch_assoc()) 
		{
			$Offline[] = $row;
		}
	} 
	else 
	{
		//No modules are offline, STUUP
	}
	$response["Online"] = $Online;
	$response["Offline"] = $Offline;
	
	if($Type == "JSON")
	{
		return json_encode($response);
	}
	else
	{
		return $response;
	}
}

function generateModuleInformation($MySQL, $Type, $MAC)
{
	$Query = "SELECT * FROM tbl_modules NATURAL JOIN tbl_minfo WHERE MAC = '$MAC';";
	$result = $MySQL->query($Query);
	
	if ($result->num_rows > 0) 
	{
		//Add data of each row to a big array
		$row = $result->fetch_assoc();

	} 
	else 
	{
		//No modules are online, STUUP
	}
	if($Type == "JSON")
	{
		return json_encode($row);
	}
	else
	{
		return $row;
	}
}

function generateSensorInformation($MySQL, $MAC, $MIN, $MAX)
{

	$Types = array();
	$Data = array();

	$Query = "SELECT DISTINCT sensor_type FROM tbl_sensordata WHERE MAC = '$MAC';"; //First get an array with all the sensor types available. (This to make it easyer to organise an PHP array.
	$result = $MySQL->query($Query);
	
	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$Types[] = $row['sensor_type'];
		}
	} 
	else 
	{
		//echo $MySQL->error;
	}
	
	//Dataaas
	foreach($Types as $Type)
	{
		//Dit bug om een of andere reden.
		// nu is UpperLimit == MAX en LowerLimit == MIN
		/*if($UpperLimit != "ALL" || $LowerLimit != "ALL")
		{
			$Query = "SELECT * FROM tbl_sensordata WHERE MAC = '$MAC' AND sensor_type = '$Type' ORDER BY sdata_time DESC LIMIT $LowerLimit, $UpperLimit;";
		}
		else
		{*/
			$Query = "SELECT * FROM tbl_sensordata WHERE MAC = '$MAC' AND sensor_type = '$Type' ORDER BY sdata_time ASC;";
		//}
		$result = $MySQL->query($Query);
		$i = 1;
		if ($result->num_rows > 0) 
		{	
			/*if($MAX != "ALL" || $MIN != "ALL")
			{
				//LIMITS
				
				//echo "[".$MIN.",".$MAX."]";
				while($row = $result->fetch_assoc()) 
				{
					$i++;
					
					if($i <= $MIN)
					{
						//Continue if we don't need this;
						continue;
					}
					//echo "\n\n" . $i . "\n\n";
					$Data[$Type][] = $row;
					//print_r($row);
					
					//Gedaan
					if($i > $MAX)
					{
						//echo "break";
						break;
					}
					
				}
			}
			else
			{*/
				//Ze Allemaal
				while($row = $result->fetch_assoc()) 
				{
					$Data[$Type][] = $row;
				}
			//}
			$Data[$Type] = array_reverse ($Data[$Type]);
		}
		else 
		{
			echo $MySQL->error;
		}
	}
	//print_r();
	
	return json_encode($Data);
}
?>
