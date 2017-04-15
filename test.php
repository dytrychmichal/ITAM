<?php
session_start();
// http://php.net/manual/en/session.examples.basic.php
// Sessions can be started manually using the session_start() function. If the session.auto_start directive is set to 1, a session will automatically start on request startup.
// http://stackoverflow.com/questions/4649907/maximum-size-of-a-php-session
// You can store as much data as you like within in sessions. All sessions are stored on the server. The only limits you can reach is the maximum memory a script can consume at one time, which by default is 128MB.
//http://stackoverflow.com/questions/217420/ideal-php-session-size

date_default_timezone_set('Europe/Prague');

require_once('classes/verify.php');
require_once('classes/sql.php');

$sql = new SQL();
$assetTypes = $sql->getTypes();
$assetManufacturers = $sql->getManufacturers();
$assetSuppliers = $sql->getSuppliers();
$hw = $sql->getHW();


?>


<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>GEAC IT Asset Management</title>
	
	<link rel="stylesheet" type="text/css" href="./styles.css">
	
</head>

<body onload="userOnblur();">
	<header>
		<h1>ITInvent</h1>
		
		<?php include 'src/navbar.php' ?>
	
	</header>
	<main>
		<span id="txt"></span>
		<form>

			<input type="text" id="usr" placeholder="user">
		</form>
		
	</main>
	
	 <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	 <script>
		function reqListener ()
		{
			console.log(this.responseText);
		}
		
		function userOnblur()
		{
			userField.onblur = updateUser;
		}
				
		function findUser(sso)
		{
			for(var i=0; i<usersJson.length; i++)
			{
				if(usersJson[i].sso==sso)
				{
					userText.textContent = usersJson[i].surname + " " + usersJson[i].name;
					return;
				}
			}
			userText.textContent = "User not found";
		}
		
		function updateUser()
		{
			var user=userField.value;
			findUser(user);
		}
		
		var userField = document.getElementById("usr");
		var userText = document.getElementById("txt");
		var usersJson;
		var oReq = new XMLHttpRequest(); //New request object
		
		oReq.onload = function()
		{
				//This is where you handle what to do with the response.
				//The actual data is found on this.responseText
			usersJson = JSON.parse(this.responseText);
		};
		
		oReq.open("get", "src/getUsersActive.php", true);
					//                             ^ Don't block the rest of the execution.
					//                               Don't wait until the request finishes to 
					//                               continue.
		oReq.send();
		
			
			

	  
	</script> 
  
   
  	
  

</body>

</html>
