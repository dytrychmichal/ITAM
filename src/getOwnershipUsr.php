<?php
if(!isset($_SESSION)) 
{ 
  session_start(); 
} 
if(!isset($_SESSION['name']))
{
  header('Location: https://sorry.vse.cz/~dytm01/login.php');
}

require_once('../classes/sql.php');
$sql = new SQL();

echo $sql->getLastOwnershipUserJson($_POST['usr']);
?>