/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: jQuery Module Manager handler file.
 *		Will manage the behaviour of the Module overview system.
 *		Manage what information is requested and what info that's showed.
**/

function moduleClick(MAC)
{
	//Reset view
	HTMLBlock.innerHTML = "";
	RefreshEnabled = false;
	localstorage = "";
	
	$.getJSON("process/modulelist.php?MAC=" + MAC, function(data) {
		var i;
				
		var MAC;
		var IP;
		var NAME;
		var UPTIME;
		var TYPE;
		var STATUS;
		
		MAC = data.MAC;
		IP = data.IP;
		NAME = data.NAME;
		UPTIME = data.uptime;
		TYPE = data.TYPE;
		STATUS = data.status;
		
		
		//HTMLBlock.innerHTML = "<h1>" + NAME + "</h1><br />Loading module...";
		
		//HTMLBlock.innerHTML = '<p>' + NAME + '<br />' + IP + '<br />' + TYPE + '<br />' + UPTIME + 'min<br /></p>';
		console.log(data);
		

		$.get("templates/modulemanage.html", function(data) {
			localstorage = localstorage + data;
				
			localstorage = localstorage.replace("{MODULE_DESCRIPTION}", NAME);
			localstorage = localstorage.replace("{MAC}", MAC);
				
			HTMLBlock.innerHTML = HTMLBlock.innerHTML + localstorage;
			
			/* EVEEENTS  */
			//Enable event handlers for input fields at snapshot and sensors
			$("#MINV").change(function () {
				//console.log($("#MINV").val());
				GenerateSnapshotImages(MAC, parseInt($("#MAXV").val()), parseInt($("#MINV").val()));
			});
			$("#MAXV").change(function () {
				//console.log($("#MAXV").val());
				GenerateSnapshotImages(MAC, parseInt($("#MAXV").val()), parseInt($("#MINV").val()));
			});
			$("#MINS").change(function () {
				GenerateTemperatureValues(MAC, parseInt($("#MAXS").val()), parseInt($("#MINS").val()));
			});
			$("#MAXS").change(function () {
				GenerateTemperatureValues(MAC, parseInt($("#MAXS").val()), parseInt($("#MINS").val()));
			});
			
			$("#alarmswitchValue").change(function() {
				if($(this).is(":checked")) 
				{
					//alert("aan");
					AlarmManager(MAC, "aan");
				}
				else
				{
					//alert("uit");
					AlarmManager(MAC, "uit");
				}   
			});
			
			//Kruisjes op de panels voor eventuele sluiting van panels
			$(".close_modul_panel").click(function () {
				//$(this).parent().addClass("hidden");
				$(this).nextAll().next().slideToggle();
			});

			if(STATUS == 1)
			{
				//Type checks
				var isConfigured = false;
				if(TYPE.indexOf("Camera") > -1)
				{
					//It's a camera
					$("#Live").removeClass("hidden");
				
					$("#LiveCamStreams").html('<img id="LiveCam" class="LiveCam" src="/' + MAC + '" alt="LiveStream is offline/loading or not supported!"/>');
					
					//Embed the live stream if it's clicked
					$("#LiveCam").click(function () {
						//console.log($("#MAXV").val());
						var livepopup = window.open("", "Live Cam", "width=600px, height=500px, top=20px, left=20px");
						livepopup.document.write("<!doctype html><html><head><meta charset=\"utf-8\" /><style>body { background-color: lightgray; }</style></head><body>");
						
						livepopup.document.write('<center><h2>' + NAME + '</h2><img width="100%" height="100%" class="LiveCam" src="//www.plugandprotect.eu/' + MAC + '" alt="LiveStream is loading or not supported"/></center>');
						livepopup.document.write("</body></html>");
						livepopup.document.close();
					});
					
					
					GenerateSnapshotImages(MAC, 50, 1)
					$("#Snapshots").removeClass("hidden");
					
					//<img class="LiveCam" src="//www.plugandprotect.eu/{MAC}" alt="LiveStream is not supported"/>
					isConfigured = true;
				}
				else
				{
					//If it's not a camera, pull the right box to the center (So it's not standing alone at the right side).
					$("#Rightbox").addClass("large-pull-3").removeAttr("style");
				}
				if(TYPE.indexOf("Sensor") > -1)
				{
					//It's a sensor
					GenerateTemperatureValues(MAC, 25, 1);
					
					//First run:
					LiveSensorData(MAC);
					//Schedule
					SensorInterval = setInterval(function() { LiveSensorData(MAC); }, 5000);
					$("#SensorLive").removeClass("hidden");
					$("#Sensor").removeClass("hidden");
					$("live").removeClass("cameraclass");
					isConfigured = true;
				}
				if(TYPE.indexOf("Alarm") > -1)
				{
					$("#AlarmManage").removeClass("hidden");
					AlarmManager(MAC, "FETCH"); //Initial bringup
					isConfigured = true;
				}
				if(!(TYPE.indexOf("Sensor") > -1 || TYPE.indexOf("Alarm") > -1))
				{
					//If it's not an alarm or a sensor, we place the livestream box in the center
					$("#Live").addClass("large-push-3").removeAttr("style");
				}
			}
			else
			{
				$("#Offline").removeClass("hidden");
				if(TYPE.indexOf("Camera") > -1)
				{
					GenerateSnapshotImages(MAC, 50, 1);
					$("#Snapshots").removeClass("hidden");
					isConfigured = true;
				}
				else
				{
					//If it's not a camera, pull the right box to the center (So it's not standing alone at the right side).
					$("#Rightbox").addClass("large-pull-3").removeAttr("style");
				}
				
				if(TYPE.indexOf("Sensor") > -1)
				{
					//It's a sensor
					GenerateTemperatureValues(MAC, 25, 1)
					$("#Sensor").removeClass("hidden");
					isConfigured = true;
				}
				if(TYPE.indexOf("Alarm") > -1)
				{
					isConfigured = true;
				}
			}
			if(!isConfigured)
			{
				$("#Offline").removeClass("hidden").html("Not configured!<br />Click on the wrench next to the module name and select the correct module functions!");
			}
			$(document).foundation();
			$(document).foundation('clearing', 'reflow');
		});
	});
}

function GenerateSnapshotImages(MAC, MAX, MIN)
{
	var i;
	
	var SnapPlace = $("#CameraSnapshots ul");
	
	if (MIN <= 0 || MAX <= 0)
	{
		SnapPlace.html("");
		SnapPlace.append("Values can't be lower than ONE!");
	}
	else if(MIN <= MAX)
	{
		$.getJSON("process/motion_snapshot.php?MAC=" + MAC + "&MAX=" + MAX + "&MIN=" + MIN, function(data) {
			SnapPlace.html("");
			console.log("MIN: " + MIN + " MAX: " + MAX);
			for (i = 0; i < data.length; i++)
			{

					if(data[i].indexOf(".avi") > -1)
					{
						//SnapPlace.append('<li><div class="flex-video"><embed class="th" src="http://www.plugandprotect.eu/' + data[i] + '" autostart="false"></div></li>');
						SnapPlace.append('<li><a href="http://www.plugandprotect.eu/' + data[i] + '">Video</a></li>');
					}
					else
					{
						SnapPlace.append('<li><a class="th" href="' + data[i] + '"><img src="' + data[i] + '"></a></li>');
					}
			}
			$(document).foundation();
			$(document).foundation('clearing', 'reflow');
		});
	}
	else
	{
		SnapPlace.html("");
		SnapPlace.append("Maximum value must be higher than the Small value!");
	}
}

function GenerateTemperatureValues(MAC, MAX, MIN)
{
	var i;
	var SensorPlace = $("#SensorPlace");
	
	if (MIN <= 0 || MAX <= 0)
	{
		SensorPlace.html("");
		SensorPlace.append("Values can't be lower than ONE!");
	}
	else if(MIN <= MAX)
	{
		$.getJSON("process/sensor_data.php?MAC=" + MAC + "&MAXS=" + MAX + "&MINS=" + MIN, function(data) {
			//clear
			SensorPlace.html("");
			
			//Obtain all the types from the JSON string (first keys)
			var Types = [];
			$.each(data, function(k, v) {
				Types.push(k);
			});
			
			//Elk type afgaan
			var place = document.getElementById("SensorPlace");
			if(place == null)
			{
				return;
			}
			
			for(var i = 0; i < Types.length; i++)
			{
				console.log(data[Types[i]]);
				// WAAJOOOO, AAN DEES LOGICA GRAAKTE ER MAAR NIE UIT, MET .APPEND ETC WOU HET NIE WERKE.....
				
				var div = document.createElement("div");
				div.style.display = "inline-block";
				div.style.margin = "5px";
				var title = document.createElement("h4");
				var titletxt = document.createTextNode(Types[i].ucfirst() + " Sensor");
				title.appendChild(titletxt);
				div.appendChild(title);
				place.appendChild(div);
				
				var table = document.createElement("table");
				table.style.width = "230px";
				table.style.marginLeft = "auto";
				table.style.marginRight = "auto";
				div.appendChild(table);

				var nodetr = document.createElement("tr");
				var nodetd = document.createElement("th");
				nodetd.style.width = "200px";
				var nodetxt = document.createTextNode("Time");
				nodetd.appendChild(nodetxt);
				nodetr.appendChild(nodetd);
				
				nodetd = document.createElement("th");
				nodetxt = document.createTextNode("Value");
				nodetd.appendChild(nodetxt);
				nodetr.appendChild(nodetd);
				table.appendChild(nodetr);
				
				for(var j = 0; j < data[Types[i]].length; j++)
				{
					nodetr = document.createElement("tr");
					nodetd = document.createElement("td");
					nodetd.style.width = "200px";
					nodetxt = document.createTextNode(data[Types[i]][j].sdata_time);
					nodetd.appendChild(nodetxt);
					nodetr.appendChild(nodetd);
				
					nodetd = document.createElement("td");
					nodetxt = document.createTextNode(data[Types[i]][j].value);
					nodetd.appendChild(nodetxt);
					nodetr.appendChild(nodetd);
					table.appendChild(nodetr);
				}
			}
			
			/*setTimeout(function()
			{ 
					GenerateTemperatureValues(MAC, MAX, MIN);
			}, 60000); //Elke minuut updaten*/
		});
	}
	else
	{
		//alert(MAX + " " + MIN);
		SensorPlace.html("");
		SensorPlace.append("Maximum value must be higher than the Small value!");
	}
}

function LiveSensorData(MAC)
{
	$.getJSON("process/sensor_data.php?MAC=" + MAC + "&MODE=live", function(data) {
		//Obtain all the types from the JSON string (first keys)
		var Types = [];
		$.each(data, function(k, v) {
			Types.push(k);
		});

		//Elk type afgaan
		var place = document.getElementById("LiveSensorPlace");
		if(place == null)
		{
			return;
		}
		
		place.innerHTML = "<tr><th>Sensor</th><th>Value</th></tr>";
		
		var nodetr, nodetd, nodetxt;
		for (var i = 0; i < Types.length; i++) {
			//console.log(data[Types[i]]);
			nodetr = document.createElement("tr");
			nodetd = document.createElement("td");
			nodetxt = document.createTextNode(Types[i].ucfirst());
			nodetd.appendChild(nodetxt);
			nodetr.appendChild(nodetd);
				
			nodetd = document.createElement("td");
			nodetxt = document.createTextNode(data[Types[i]]);
			nodetd.appendChild(nodetxt);
			nodetr.appendChild(nodetd);
			place.appendChild(nodetr);
		}
	});
}

function AlarmManager(MAC, MODE)
{
	if(MODE == "FETCH")
	{
		$.get("process/alarm_proxy.php?MAC=" + MAC + "&MODE=status", function( data ) {
			//If the data contains error, means no good, so break function.
			if(data.indexOf("server") > -1 || data.indexOf("404") > -1 || data.indexOf("error") > -1)
			{
				$("#AlarmStatus").html("<font color=\"orange\"> ERROR </font>");
				return;
			}
			if(data.indexOf("aan") > -1)
			{
				$("#AlarmStatus").html("<font color=\"red\"> ON </font>");
				$("#alarmswitchValue").attr("checked", "checked");
			}
			else
			{
				$("#AlarmStatus").html("<font color=\"green\"> OFF </font>");
				$("#alarmswitchValue").removeAttr("checked", "checked");
			}
			
			$("#alarmswitch").removeClass("hidden");
			
		}).fail(function() {
			$("#AlarmStatus").html("<font color=\"red\"> BAD ERROR </font>");
			return;
		});
	}
	else 
	{
		$.get("process/alarm_proxy.php?MAC=" + MAC + "&MODE=" + MODE, function( data ) {
			//If the data contains error, means no good, so break function.
			if(data.indexOf("server") > -1 || data.indexOf("404") > -1 || data.indexOf("error") > -1)
			{
				$("#AlarmStatus").html("<font color=\"orange\"> ERROR </font>");
				return;
			}
			if(data.indexOf("aan") > -1)
			{
				$("#AlarmStatus").html("<font color=\"red\"> ON </font>");
			}
			else
			{
				$("#AlarmStatus").html("<font color=\"green\"> OFF </font>");
			}
			
			$("#alarmswitch").removeClass("hidden");
			
		}).fail(function() {
			$("#AlarmStatus").html("<font color=\"red\"> BAD ERROR </font>");
			return;
		});
	}
}


//Eerste letter uppercase make
String.prototype.ucfirst = function()
{
    return this.charAt(0).toUpperCase() + this.substr(1);
}
