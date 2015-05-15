/**
 *	Plug & Protect
 *
 *	-> Sterckx Jens
 *	-> Moinil Wesley
 *	-> Dauwe Jelle
 *
 *
 *
 *	File: jQuery login handler file.
 *		Will manage the behaviour of the login system.
**/

//Enter Key Event (For Login)
$(document).keypress(function(e) {
    if(e.which == 13) {
        $("#SendLoginDetails").click();
    }
});

//Wait for the document to be ready
$(document).ready(function(){
	//Get parameters for unauthorized access pages
	var noauth = getParameterByName("noauth");
	if (noauth != "")
	{
		alert("You must be logged in before accessing this pageeeee!");
	}
	
	//Click event wordt hier behandeld
	$("body").on("click", "#SendLoginDetails", function() {
		//Reset validation
		UNameError(0);
		PWError(0);
		error = false;
		
		//Werd op de knop geklikt
		//LOGIN LOGIC
		
		if ($("#uname").val().length == 0) {
			UNameError(1, "Enter your user name!<br />");
			error = true;
		}
		if ($("#pw").val().length == 0) {
			PWError(1, "Enter your password!<br />");
			error = true;
		}
		//Als er gene errors zijn, gaan we verder met de POST naar den backend
		if(!error) {
			$.post("process/login.php", {
				uname:$("#uname").val(),
				pw:$("#pw").val()
			},
			function(data,status){
				var Response = $.parseJSON(data);
				if (Response.error == 1) {
				    switch (Response.reason) {
				        //Some checks if there should be a bug in JavaCode above
				        case "nouname":
				            MailError(1, "Enter your user name!<br />");
				            break;
				        case "nopas":
				            PWError(1, "Enter your password!<br />");
				            break;

				        //Real errors!
				        case "BADLOGIN":
							PWError(1, "User name or Password are wrong!<br />");
				            break;
							
						//Something went reaaaaly baddd
				        default:
							alert("Error, front-end version differs from back-end.\nTry again later!");
				            break;
				    }
				} else {
				    $("#SendLoginDetails").attr("value", "Loading!");
					$("#SendLoginDetails").css("background", "green");
					$("#rotater").css("visibility", "visible");
					PreLoadRedirectHomeLogIn();
				}
			});
		}
	});
});

//Preload page (Stay on current page while home is loaded, once loaded, go to home)
var PreLoadRedirectHomeLogIn = function() {

	setTimeout(function() {
		window.location = "home.php";
	}, 2000);

	/*window.history.pushState("Home", "Home | Plug & Protect", "/home.php");

	//Preload page
	$.get("home.php", function (data) {
		//Wait before posting in here a bit until we passed our minimum of 2 seconds (So that peeps without servers still see a message)
		//Creating a minimum waiter
		setTimeout(function(){
			document.open();
			document.write(data);
			document.close();
			$.cache = {};
		}, 1000);
	}, "html");
	*/
}

//Functions
var UNameError = function(mode, message) {
	if (mode == 1) {	//Enable error
		$(".UnameElems").addClass("error");
		$("small.UnameElems").append(message);
		$("#SendLoginDetails").css("background", "#f04124");
		$("#SendLoginDetails").attr("value", "Log-In");
	} else {			//Disable error
		$(".UnameElems").removeClass("error");
		$("small.UnameElems").text("");
		$("#SendLoginDetails").css("background", "#FFA500");
		$("#SendLoginDetails").attr("value", "Busy");
		mailmode = 0;
	}
};

var PWError = function(mode, message) {
	if (mode == 1) {	//Enable error
		$(".PWElems").addClass("error");
		$("small.PWElems").append(message);
		$("#SendLoginDetails").css("background", "#f04124");
		$("#SendLoginDetails").attr("value", "Log-In");
	} else {			//Disable error
		$(".PWElems").removeClass("error");
		$("small.PWElems").text("");
		$("#SendLoginDetails").css("background", "#FFA500");
		$("#SendLoginDetails").attr("value", "Busy");
	}
};

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

