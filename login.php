<?php
session_start();
require_once('classes/sql.php');

$sql=new SQL();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  $name = $_POST['name']; 
  $pass = $_POST['password'];
  
    if ($usr=$sql->logIn($name, $pass))
    {
		
      session_start();

      $_SESSION['name'] = $name;
      setcookie("name", $_POST['name'], time()+3600); # ted + 3600 sekund = 1 hodina
      header('Location: index.php');
    }
    else {
    $error = ' <span style="color: red"> Incorrect username or password! </span>';
	
    }
  
  
  
	
}
?>


<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>GEAC IT Asset Management</title>
	
	<link rel="stylesheet" type="text/css" href="./styles.css">
</head>

<body class="outer">               
	<div class="middle">
		<header>
			<h1>Log in</h1>
		</header>
		<main>
			<form method="POST">			
				Username:<br/>
				<input type="text" name="name" size="25"><br/>
				Password:<br/>
				<input type="password" name="password" size="25"> <br/>
				<input type="submit" value="Log in" >
				<br/>
			</form>
				<?php
				   if(isset($error))
				   {
					   echo $error.'<br/>';
				   }
				?>
		</main>
	</div>

</body>

</html>