/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: jQuery DashBoard handler file.
 *		Will manage the behaviour of the DashBoard system.
 *		Manage what information is requested and what info that's showed.
**/

// Global Variables
var IdOfMainContent = "main";
var RefreshMax = 30;
var RefreshSec = RefreshMax;
var RefreshEnabled = true;
var HTMLBlock;
var localstorage = "";
var SensorInterval = 0;

//Wait for the document to be ready
$(document).ready(function(){
	HTMLBlock = document.getElementById(IdOfMainContent);
	
	//Set the refresh interval
	setInterval(refresh, 1000);
	hashSwitcher();
});

function refresh()
{
	//Debug
	if(!RefreshEnabled)
	{
		return;
	}
	var timer = document.getElementById("timer");
	timer.innerHTML = RefreshSec;
	
	//Count down 1 second
	RefreshSec--;
	if(RefreshSec < 0)
	{
		//On zero, reload the right page.
		hashSwitcher();
	}
}

window.onhashchange = function() {
	hashSwitcher();
}


function hashSwitcher()
{
	/* Kill the TIMER for the sensor intervals 
		(dashboard_modulemanage.js)
	*/
	if(SensorInterval != 0)
	{
		clearInterval(SensorInterval);
		SensorInterval = 0
	}
	//On page ready, start loading all the modules.
	if(window.location.hash == "#DashBoard" || window.location.hash == "")
	{
		moduleOverview();
	}
	else
	{
		var loc = window.location.hash.split("#");
		if(loc[1] == "ModuleManage")
		{
			moduleClick(loc[2]);
		}
		else
		{
			moduleOverview();
		}
	}
	
	//Reset refresh timer
	RefreshSec = RefreshMax;
}

function moduleOverview()
{
	//reset view
	HTMLBlock.innerHTML = "";
	RefreshEnabled = true;
	localstorage = "";
	
	//Get the module information:
	$.getJSON("process/modulelist.php", function(data) {
		var i, j;
		var HTMLBlock = document.getElementById(IdOfMainContent);
		
		var MAC;
		var IP;
		var NAME;
		var UPTIME;
		var TYPE, TYPEIMG;

		
		for (i = 0; i < data.Online.length; i++)
		{
			TYPEIMG = "";
			MAC = data.Online[i].MAC;
			IP = data.Online[i].IP;
			NAME = data.Online[i].NAME;
			UPTIME = data.Online[i].uptime;
			TYPE = data.Online[i].TYPE;
			
			UPTIME = UptimeCalc(parseInt(UPTIME));
			
			TYPE = TYPE.split("+");
			for (j = 0; j < TYPE.length; j++)
			{
				TYPEIMG = TYPEIMG + "<img src=\"images/" + TYPE[j] + "\" title=\"" + TYPE[j] + "\" alt=\"" + TYPE[j] + "\"/>";
			}
			
			HTMLBlock.innerHTML = HTMLBlock.innerHTML + '<a href="#ModuleManage#' + MAC +'"><div class="kadertje online"><p><span class="bold">' + NAME + '</span><br />' + IP + '<br />' + TYPEIMG + '<br />' + UPTIME + '<br /></p></div></a>';

			//Text FIT https://github.com/jquery-textfill/jquery-textfill
			/*$(".kadertje").textfill({
				//widthOnly: true,
				maxFontPixels: 20,
				explicitHeight: 40
			});*/
		}
		for (i = 0; i < data.Offline.length; i++)
		{
			TYPEIMG = "";
			MAC = data.Offline[i].MAC;
			IP = data.Offline[i].IP;
			NAME = data.Offline[i].NAME;
			UPTIME = data.Offline[i].last_update;
			TYPE = data.Offline[i].TYPE;
			
			TYPE = TYPE.split("+");
			for (j = 0; j < TYPE.length; j++)
			{
				TYPEIMG = TYPEIMG + "<img src=\"images/" + TYPE[j] + "\" title=\"" + TYPE[j] + "\" alt=\"" + TYPE[j] + "\"/>";
			}
			
			HTMLBlock.innerHTML = HTMLBlock.innerHTML + '<a href="#ModuleManage#' + MAC +'"><div class="kadertje offline"><p><span class="bold">' + NAME + '</span><br />' + IP + '<br />' + TYPEIMG + '<br /><span class="last_update">' + UPTIME + '</span><br /></p></div></a>';
			
			//Text Fit
			/*$(".kadertje").textfill({
				//widthOnly: true,
				maxFontPixels: 20,
				explicitHeight: 40
			});*/
		}
		
		$(".kadertje").each(function ()
		{
			$(this).textfill({
				//widthOnly: true,
				maxFontPixels: 20,
				explicitHeight: 40
			});
		});
		console.log(data);
	});
}


function UptimeCalc(minutes)
{
	var days = Math.floor(minutes / 1440);
	minutes -= days * 1440;
	var hours = Math.floor(minutes / 60);
	minutes -= hours * 60;
	
	var time = "";
	if(days > 0)
	{
		time += days + "D,";
	}
	if(hours > 0)
	{
		time += hours + "H, ";
	}
	time += minutes + "M";
	
	return time;
}
