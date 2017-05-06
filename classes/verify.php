<?php
	class verify
	{
	public function verify()
	{
	  if(!isset($_SESSION)) 
	  { 
		  session_start(); 
	  } 
	  if(!isset($_SESSION['name']))
	  {
		  header('Location: http://sorry.vse.cz/~dytm01/login.php');
	  }
	}  

	}


?>