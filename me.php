<?php
session_start();
require_once('classes/sql.php');
require_once('classes/verify.php');
require_once('classes/passwordLib.php');


$verify=new verify();
$verify->verify();

$sql = new SQL();
$user = $sql->getUser($_SESSION['name']);



if ($_SERVER["REQUEST_METHOD"] == "POST")
{   
   
    if($_POST['newPassword']===$_POST['newPassword2'])
    {
      # new passwords do match
      if($sql->logIn($_SESSION['name'], $_POST['oldPassword']))			//SESSION IS SAFE - IT IS ON SERVER SIDE
      {
        #old password hash matches the one in DB
        $sql->updatePass($_SESSION['name'], password_hash($_POST['newPassword'], PASSWORD_DEFAULT));
        $message=' <span style="color: green">Password changed successfully! </span>';
      }
      else
      {
        #new password hash do not match the one in DB
        $message=' <span style="color: red"> Error: Bad password  </span>';
      }
      #new passwords do not match
    }
    else
    {
      $message=' <span style="color: red"> Error: passwords do not match </span>';
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

<body>
	<main>
		<?php include './src/navbar.php' ?>
		
		  <div>	
			<h1>About me</h1>
			Username:  <?= $_SESSION['name'] ?>   <br/>
			Name:  <?= $user['name']; ?>   <br/>
			Surname:  <?= $user['surname']; ?>   <br/>
			<br/>
		  </div>
	  
		<div  class="lineOver">
			<h2>Change password</h2>  
		 
			<form method="POST" style >

			<div class="newPass">
				<div class="left">
					<p>Old password:</p>
					<p>New password:</p>
					<p>Confirm password:</p>
				</div>
				<div class="right">
					<input type="password" class="mepwd" name="oldPassword"><br>
					<input type="password" class="mepwd" name="newPassword"><br>
					<input type="password" class="mepwd" name="newPassword2">
				</div>
			</div>
			  <?php
			  if(isset($message))
			  {
				echo $message;
			  }
			  ?>
			<br/>
			<input type="submit" style="margin-top:10px" value="Change"> or <a href="me.php">Cancel</a>
			</form>
		
		</div>
	</main>
	<?php include './src/footer.php' ?>
</body>

</html>        