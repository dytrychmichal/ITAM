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
$assetTypes = $sql->getTypes();
$assetManufacturers = $sql->getManufacturers();
$assetSuppliers = $sql->getSuppliers();

function findSQLArray($arr, $n, $i)		//returns true if $_POST[$n][$i] exists in PostgreSQL Array $arr
{	
	foreach($arr as  $a)
	{
		if(strtoupper($a['name']) == strtoupper($_POST[$n][$i]))
		{
			return true;
		}
	}
	
	return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(isset($_POST["message"]))
	{
		$inv = json_decode($_POST["message"]);
		$hw=array();
		foreach($inv as $i)
		{
			array_push($hw, $sql->getHWInv($i));
		}
	}
	if(isset($_POST["Save"]) && isset($_POST['inv']))
	{
		$cnt = count($_POST["inv"]);
		for($i = 0 ; $i < $cnt ; $i++)
		{
			if($_POST['note'][$i] != null) 
			{
				$sql->scrapHW($_POST['inv'][$i], $_POST['note'][$i]);
			}
		}
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
	<header>
		<h1>Scrap HW</h1>
		
		<?php include './src/navbar.php' ?>
	</header>
	
	<main>
	<form method="post">
		<table class="new_HW_table">
			<thead>
			<tr class="table_header">
				<th>inventory</th>
				<th>Type</th>
				<th>Manufacturer</th>
				<th>Model</th>
				<th>Serial</th>
				<th>Supplier</th>
				<th>PO</th>
				<th>Date delivered</th>
				<th>Reason scrapped*</th>
			</tr>
			</thead>

			<tfoot>
			<tr class="table_header">
				<th>inventory</th>
				<th>Type</th>
				<th>Manufacturer</th>
				<th>Model</th>
				<th>Serial</th>
				<th>Supplier</th>
				<th>PO</th>
				<th>Date delivered</th>
				<th>Reason scrapped*</th>
			</tr>
			</tfoot>

			<tbody>
			<?php
				if(isset($_POST["message"]))
				{
					$i=0;
					foreach($hw as  $h)
					{ ?>
					<tr class="inv">
					<td class="inv">
						<input type="hidden" name="inv[<?php echo $i; ?>]" value="<?php echo $h["inventory_number"]?>">
						<?php echo $h['inventory_number'];?>
					</td>
					<td class="inv">
						<?php
							echo $h['asset_type'];
						?>
					</td>
					<td class="inv">
						<?php
							echo $h["manufacturer_name"]; 
						 ?>
					</td>
					<td class="inv">
						<?php 
							echo $h["model"];
						?>
					</td>
					<td class="inv">
						<?php 
							echo $h["serial"];
						?>
					</td>
					<td class="inv">
						<?php
							echo $h["supplier_name"];
						?>
					</td>
					<td class="inv">
						<?php
							echo $h["po"];
						?>
					</td>
					<td class="inv">
						<?php
							$date = DateTime::createFromFormat('Y-m-d', $h["date_supplied"]);
							echo $date->format('d.m.Y');
						?>
					</td>
					<td class="inv">
						<textarea class="inv" name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea>
					</td>
					</tr>
					
					
			<?php	$i++;
					}
				}
			?>
		</tbody>
		</table>
		
		<input type="submit" value="Save" name="Save">
		</form>
	</main>
	<?php include './src/footer.php' ?>

</body>

</html>
