<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
/*NOT GOING TO VERIFY NOW
require_once ('classes/verify.php');
$verify = new verify();
$verify->verify();
*/
?>

<nav class="navbar">
 <ul>
	<li><a href="../index.php"><span>DDHM</span></a></li>
	<li><a href="../transfers.php"><span>Transfers</span></a></li>
	<li><a href="../userHw.php"><span>User's HW</span></a></li>
	<li><a href="../addUser.php"><span>Add User</span></a></li>
 </ul>
</nav>
