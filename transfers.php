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

$sql = new SQL();
$ownerships = $sql->getOwnerships();

$emptyRows = 4;

function findSQLArray($arr, $n, $i)		//returns true if $_POST[$n][$i] exists in PostgreSQL Array $arr
{	
	foreach($arr as  $a)
	{
		if($a['name'] == $_POST[$n][$i])
		{
			return true;
		}
	}
	
	return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	if(isset($_POST['Save']))
	{
		for($i = 0 ; $i < $emptyRows ; $i++)
		{
			if($_POST['ssoNew'][$i] != null && $_POST['ssoNew'][$i] != $_POST['ssoOld'][$i]) //check if SSO has been inserted and new SSO is not the same as old SSO
			{
				$dateSQL = date('Y-m-d', time());				
				echo 'inserting '. $_POST['inv'][$i] . ' ' . $_POST['ssoOld'][$i] . ' ' .  $dateSQL  . ' ' .  $_POST['ssoNew'][$i] . '<br>';
				if($_POST['ssoOld'][$i] == null)	//old SSO is null - transferring a new Hardware
				{
					$sql->createOwnershipNew($_POST['inv'][$i], $_POST['ssoNew'][$i], $dateSQL, $_POST['note'][$i]);
				}
				else
				{					
					$sql->createOwnership($_POST['inv'][$i], $_POST['ssoOld'][$i], $_POST['ssoNew'][$i], $dateSQL, $_POST['note'][$i]);
				}
			}
		}
		$ownerships = $sql->getOwnerships();
	}
}

?>


<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>GEAC IT Asset Management</title>
	
	<link rel="stylesheet" type="text/css" href="./styles.css">
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="/src/transfersScripts.js" type="text/javascript"></script>	<script>
	// after dom is loaded go to the end of the page
	$(function() {
	  // scroll all the way down
	  $('html, body').scrollTop($(document).height() - $(window).height());
	});
	</script>
	
</head>

<body>
	<header>
		<h1>Transfers</h1>
		
		<?php include 'src/navbar.php' ?>
	</header>
	<main>
	<form method="post" name="hwTableForm" onkeypress="return event.keyCode != 13;">
		<table id="transfersTable">
			<thead>
			<tr>
				<th>No.</th>
				<th>Inv</th>
				<th>HW</th>
				<th>Serial</th>
				<th>Original owner</th>
				<th>SSO</th>
				<th>New owner</th>
				<th>SSO</th>
				<th>Note</th>
				<th>Date</th>
				<th>Created by</th>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<th>No.</th>
				<th>Inv</th>
				<th>HW</th>
				<th>Serial</th>
				<th>Original owner</th>
				<th>SSO</th>
				<th>New owner</th>
				<th>SSO</th>
				<th>Note</th>
				<th>Date</th>
				<th>Created by</th>
			</tr>
			</tfoot>
			<tbody>
			<?php
			foreach($ownerships as $o)
			{
				echo "<tr>\n";				// \n for better HTML readability
				echo "<td>" . $o['id'] . "</td>\n";
				echo "<td>" . $o['inventory_number'] . "</td>\n";
				echo "<td>" . $o['m_name'] . " " .$o['model'] . "</td>\n";
				echo "<td>" . $o['serial'] . "</td>\n";
				echo "<td>" . $o['l_surname'] . " " .$o['l_name'] . "</td>\n";
				echo "<td>" . $o['l_sso'] . "</td>\n";
				echo "<td>" . $o['n_surname'] . " " . $o['n_name'] . "</td>\n";
				echo "<td>" . $o['n_sso'] . "</td>\n";
				echo "<td>" . $o['note'] . "</td>\n";
				echo "<td>" . DateTime::createFromFormat('Y-m-d', $o['date_created'])->format('d.m.Y') . "</td>\n";
				echo "<td>" .substr($o['c_name'], 0, 1) . substr($o['c_surname'], 0, 2),  "</td>\n";
				echo "</tr>\n";
			}
			?>
			<?php
				for($i=0 ; $i<$emptyRows; $i++) 
				{ ?>
					<tr class="inv">
					<td class="inv">id</td>
					<td class="inv"><input type="text" class="inv" name="inv[<?php echo $i; ?>]" placeholder="INV" onblur="invOnblur(this)" autocomplete="off"></td>
					<td class="inv"><input type="text" class="inv" name="hwName[<?php echo $i; ?>]" type="text" disabled="disabled" readonly></td>
					<td class="inv"><input type="text" class="inv" name="serial[<?php echo $i; ?>]" placeholder="Serial" onblur="serialOnblur(this)" autocomplete="off"></td>
					<td class="inv"><input type="text" class="inv" name="userOld[<?php echo $i; ?>]" type="text" disabled="disabled" readonly></td>
					<td class="inv"><input type="text" class="inv" name="ssoOld[<?php echo $i; ?>]" disabled="disabled" readonly></td>					
					<td class="inv"><input type="text" class="inv" name="userNew[<?php echo $i; ?>]" type="text" disabled="disabled" readonly></td>
					<td class="inv"><input type="text" class="inv" name="ssoNew[<?php echo $i; ?>]" placeholder="SSO" disabled="disabled" onblur="userOnblur(this)" autocomplete="off"></td>
					<td class="inv" colspan="3"><textarea type="text" class="inv" name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		
	<input type="submit" value="Save" name="Save" onclick="unAble()">
	</form>
	</main>
	<?php include 'src/footer.php' ?>

</body>

</html>
