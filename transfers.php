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
$ownerships = $sql->getOwnerships();
/*$verify=new verify();
$sql = new SQL();
*/
/*
$verify->verify();
$admin=$verify->isAdmin();    
*/

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
	<main>
		<table>
			<tr>
				<th>No.</th>
				<th>Inv</th>
				<th>HW</th>
				<th>Serial</th>
				<th>Original owner</th>
				<th>SSO</th>
				<th>New owner</th>
				<th>SSO</th>
				<th>Date</th>
				<th>Signed</th>
				<th>Created by</th>
			</tr>
			<?php
			foreach($ownerships as $o)
			{
				echo '<tr>';
				echo '<td>' . $o['id'] . '</td>';
				echo '<td>' . $o['inventory_number'] . '</td>';
				echo '<td>' . $o['m_name'] . ' ' .$o['model'] . '</td>';
				echo '<td>' . $o['serial'] . '</td>';
				echo '<td>' . $o['l_surname'] . ' ' .$o['l_name'] . '</td>';
				echo '<td>' . $o['l_sso'] . '</td>';
				echo '<td>' . $o['n_surname'] . $o['n_name'] . '</td>';
				echo '<td>' . $o['n_sso'] . '</td>';
				echo '<td>' . $o['date_created'] . '</td>';
				echo '<td>'  . '</td>';
				echo '<td>' .substr($o['c_name'], 0, 1) . substr($o['c_surname'], 0, 2),  '</td>';
				echo '</tr>';
			}
			?>
		</table
	</main>
	
  
  
   
  	
  

</body>

</html>
