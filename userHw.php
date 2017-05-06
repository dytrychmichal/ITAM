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
require_once('classes/pdf.php');

$verify=new verify();
$verify->verify();

$sql = new SQL();
$pdf = new transferPdf();

$userHw = null;
$emptyRows = 5;

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
	if(isset($_POST['Search']))
	{
		$userHw = $sql->getLastOwnershipUser($_POST['sso']);
	}
	if(isset($_POST['Save']))
	{
		$invSize = count($_POST['inv']);	//inv is ALWAYS present - count how many HW does the user have
		$hw = array();
		$ind = 0;
		for($i = 0 ; $i < $invSize ; $i++)
		{
			if($_POST['ssoNew'][$i] != null && $_POST['ssoNew'][$i] != $_POST['ssoOld'][$i]) //check if SSO has been inserted and not transfering to the same user
			{
				$hw[$ind]['userOld'] = $_POST['userOld'][$i];
				$hw[$ind]['userNew'] = $_POST['userNew'][$i];
				$hw[$ind]['ssoOld'] = $_POST['ssoOld'][$i];				
				$hw[$ind]['ssoNew'] = $_POST['ssoNew'][$i];
				$hw[$ind]['inv'] = $_POST['inv'][$i];
				$hw[$ind]['hwName'] = $_POST['hwName'][$i];
				$ind++;
				$dateSQL = date('Y-m-d', time());
				
				$hw[$i]['no'] =	$sql->createOwnership($_POST['inv'][$i], $_POST['ssoOld'][$i], $_POST['ssoNew'][$i], $dateSQL, $_POST['note'][$i]);
				echo "creating ownership for " . $_POST['inv'][$i]; 
				
			}
		}
		
		$pdf->getTransferPdf($hw);
	}
}
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
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="./src/userHwScripts.js" type="text/javascript"></script>
	
</head>

<body>
	<header>
		<h1>User HW</h1>
		
		<?php include './src/navbar.php' ?>
	</header>
	<main>
		<form method="post" name="searchForm">
			<input type="text" name="sso" autocomplete="off">
			<input type="submit" name="Search" value="Search">
		</form>
		<br>
		<form method="post" name="hwTableForm" onkeypress="return event.keyCode != 13;">
			<table id="transfersTable">
				<thead>
					<tr>
						<th>Inv</th>
						<th>HW</th>
						<th>Serial</th>
						<th>Original owner</th>
						<th>SSO</th>
						<th>New owner</th>
						<th>SSO</th>
						<th>Note</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if($userHw != null)
				{
					$i=0;
					foreach($userHw as $o)
				{?>
					<tr>
					<td>
						<input name="inv[<?php echo $i; ?>]" type="hidden" disabled="disabled"  value="<?php echo $o['inv'];?>">
						<?php echo $o['inv'];?>
					</td>
					<td>
						<input name="hwName[<?php echo $i; ?>]" type="hidden" disabled="disabled"  value="<?php echo $o['manufacturer_name'] . " " . $o['model'];?>">
						<?php echo $o['manufacturer_name'] . " " . $o['model'];?>
					</td>
					<td>
						<input name="serial[<?php echo $i; ?>]" type="hidden" disabled="disabled"  value="<?php echo $o['serial'];?>">
						<?php echo $o['serial'];?>
					</td>
					<td>
						<input name="userOld[<?php echo $i; ?>]" type="hidden" disabled="disabled"  value="<?php echo $o['user_surname'] . " " . $o['user_name'];?>">
						<?php echo $o['user_surname'] . " " . $o['user_name'];?>
					</td>
					<td>
						<input name="ssoOld[<?php echo $i; ?>]" type="hidden" disabled="disabled"  value="<?php echo $o['sso'];?>">
						<?php echo $o['sso'];?>
					</td>					
					<td>
						<input name="userNew[<?php echo $i; ?>]" type="text" disabled="disabled" readonly>
					</td>
					<td>
						<input name="ssoNew[<?php echo $i; ?>]" placeholder="SSO" onblur="userOnblur(this)" autocomplete="off">
					</td>
					<td>
						<textarea name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea>
					</td>
					</tr>
					
				<?php
				$i++;
				}
					}
				?>
				</tbody>
			</table>
			<br>
			<input type="submit" name="Save" value="Save" onclick="unAble()">
		</form>
	</main>
	<?php include './src/footer.php' ?>
</body>

</html>
