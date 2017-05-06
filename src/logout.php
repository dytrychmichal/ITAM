<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
session_destroy();
header('Location: http://sorry.vse.cz/~dytm01/login.php');
?>