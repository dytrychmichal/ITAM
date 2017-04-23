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


$verify=new verify();
$verify->verify();

$sql = new SQL();
$CCs = $sql->getCostcenters();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$dateSQL = date('Y-m-d', time());
	$sql->addUser($_POST['name'], $_POST['surname'], $_POST['sso'], $_POST['costCenter'], $dateSQL);
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
		<h1>Add User</h1>
		
		<?php include 'src/navbar.php' ?>
	</header>
	<main>
		<form method="POST">
			<input type="text"  placeholder="Name" name="name" required autocomplete="off"></input>
			<input type="text"  placeholder="Surname" name="surname" required autocomplete="off"></input>
			<input type="text"  placeholder="SSO" name="sso" required autocomplete="off"></input>
			<select name="costCenter">
				<?php foreach($CCs as $CC)
				{
					echo '<option value="'.$CC['code'].'">'.$CC['name'].' - '.$CC['code'].'</option>';
				}
				?>
			</select>
			<input type="submit" value="Submit"> 
		</form>
	</main>
	<?php include 'src/footer.php' ?>
  
  
   
  	
  

</body>

</html>
