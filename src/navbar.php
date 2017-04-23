<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 


?>

<nav class="navbar">
 <ul class="left" style="float: left">
	<li><a href="../index.php"><span>DDHM</span></a></li>
	<li><a href="../transfers.php"><span>Transfers</span></a></li>
	<li><a href="../userHw.php"><span>User's HW</span></a></li>
	<li><a href="../addUser.php"><span>Add User</span></a></li>
 </ul>
 <ul class="right" style="float: right">
	<li><a href="../me.php">Me</a></li>
	<li><a href="src/logout.php">Logout</a></li>
 </ul>
</nav>
