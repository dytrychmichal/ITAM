<?php
session_start();
// http://php.net/manual/en/session.examples.basic.php
// Sessions can be started manually using the session_start() function. If the session.auto_start directive is set to 1, a session will automatically start on request startup.
// http://stackoverflow.com/questions/4649907/maximum-size-of-a-php-session
// You can store as much data as you like within in sessions. All sessions are stored on the server. The only limits you can reach is the maximum memory a script can consume at one time, which by default is 128MB.
//http://stackoverflow.com/questions/217420/ideal-php-session-size

require_once('classes/verify.php');
require_once('classes/sql.php');

$sql = new SQL();
$CCs = $sql->getCostcenters();


/*$verify=new verify();
$sql = new SQL();
*/
/*
$verify->verify();
$admin=$verify->isAdmin();    
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$sql->addUser($_POST['name'], $_POST['surname'], $_POST['sso']);
}

?>


<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>GEAC IT Asset Management</title>
	
	<link rel="stylesheet" type="text/css" href="./styles.css">
	
</head>

<body>
	<header>
		<h1>ITInvent</h1>
		
		<?php include 'src/navbar.php' ?>
	</header>
	
	<form method="POST">
		<input type="text"  placeholder="Name" name="name" required></input>
		<input type="text"  placeholder="Surname" name="surname" required></input>
		<input type="text"  placeholder="SSO" name="sso" required></input>
		<select>
			<?php foreach($CCs as $CC)
			{
				echo '<option value="'.$CC['name'].'">'.$CC['name'].' - '.$CC['code'].'</option>';
			}
			?>
		</select>
		<input type="submit" value="Submit"> 
	</form>
  
  
   
  	
  

</body>

</html>