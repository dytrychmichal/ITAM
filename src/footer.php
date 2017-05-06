<?php
if(!isset($_SESSION)) 
{ 
  session_start(); 
} 
if(!isset($_SESSION['name']))
{
  header('Location: https://sorry.vse.cz/~dytm01/login.php');
}

?>
<footer>
&copy; Michal Dytrych GEAC 2017
</footer>
