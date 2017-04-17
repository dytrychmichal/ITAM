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

$sql = new SQL();
$assetTypes = $sql->getTypes();
$assetManufacturers = $sql->getManufacturers();
$assetSuppliers = $sql->getSuppliers();
$hw = $sql->getHW();

$emptyRows = 5;		//determines how many empty columns there will be on the bottom of the table


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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//var_dump($_POST);
	echo "<br>";
	for($i = 0 ; $i < $emptyRows ; $i++)
	{
		if($_POST['manufacturer'][$i] != null && $_POST['model'][$i] != null && $_POST['serial'][$i] != null && $_POST['supplier'][$i] != null && $_POST['date_supplied'][$i] != null) //check if all required values are present
		{
			
			if(!findSQLArray($assetManufacturers, 'manufacturer', $i)) 	//If manufacturer does not exist, add new to DB
			{
				echo $_POST['manufacturer'][$i] . ' not in list, adding to DB'; 
				$sql->addManufacturer($_POST['manufacturer'][$i]);			//add new manufacturer to DB
				$assetManufacturers = $sql->getManufacturers();				//and refresh list of manufacturers
			}
			
			if(!findSQLArray($assetSuppliers, 'supplier', $i))				//If supplier does not exist, add new to DB
			{
				echo $_POST['supplier'][$i] . ' not in list, adding to DB'; 
				$sql->addSupplier($_POST['supplier'][$i]);					//add new supplier to DB
				$assetSuppliers = $sql->getSuppliers();						//and refresh list of suppliers
			}
			
			$date = DateTime::createFromFormat('m.d.Y', $_POST['date_supplied'][$i]);
			$dateSQL = $date->format('Y-m-d');
			
			
			//echo 'inserting '. $_POST['gvl_gdvt'][$i] . ' |' . $_POST['type'][$i] . '| |' . $_POST['manufacturer'][$i] . '| |' . $_POST['model'][$i] . '| |' . $_POST['serial'][$i] . '| |' . $_POST['supplier'][$i] . '| |' .  $dateSQL . '| |' . $_POST['note'][$i]  . '| |' .  $_POST['PO'][$i] . "|";
			if($_POST['PO'][$i] == '')
			{
				$_POST['PO'][$i] = null;
			}
			$sql->addHW($_POST['gvl_gdvt'][$i], $_POST['type'][$i], $_POST['manufacturer'][$i], $_POST['model'][$i], $_POST['serial'][$i], $_POST['supplier'][$i], $_POST['PO'][$i], $dateSQL, $_POST['note'][$i]);
			
			if($_POST['SSO'][$i] != null)
			{
				$sql->addOwnershipNew($_POST['SSO'][$i], $_POST['note'][$i]);
			}
			
			$hw = $sql->getHW();
			
		}
	}
	$assetManufacturers = $sql->getManufacturers();
	$assetSuppliers = $sql->getSuppliers();
	$hw = $sql->getHW();
	
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
	<script src="/src/addHWScripts.js" type="text/javascript"></script>
	<script>
	// after dom is loaded go to the end of the page
	$(function() {
	  // scroll all the way down
	  $('html, body').scrollTop($(document).height() - $(window).height());
	});
	</script>
	
</head>

<body>
	<header>
		<h1>ITInvent</h1>
		
		<?php include 'src/navbar.php' ?>
	
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
				<th>Owner Name</th>
				<th>Owner SSO</th>
				<th>Supplier</th>
				<th>PO</th>
				<th>Date delivered</th>
				<th>AP signed</th>
				<th>Activated by</th>
				<th>Note</th>
			</tr>
			</thead>

			<tfoot>
			<tr class="table_header">
				<th>inventory</th>
				<th>Type</th>
				<th>Manufacturer</th>
				<th>Model</th>
				<th>Serial</th>
				<th>Owner Name</th>
				<th>Owner SSO</th>
				<th>Supplier</th>
				<th>PO</th>
				<th>Date delivered</th>
				<th>AP signed</th>
				<th>Activated by</th>
				<th>Note</th>
			</tr>
			</tfoot>

			<tbody>
			<?php
				$i=0;
				foreach($hw as  $h)
				{
					echo "<tr>";
					echo "<td id=\"invTd[" .$i .  "]\">". $h['inventory_number']."</td>";
					echo "";
					echo "<td id=\"assetTd[" .$i .  "]\">" . $h['asset_type']."</td>";
					echo "";
					echo "<td id=\"manufacturerTd[" .$i .  "]\">" . $h['manufacturer_name']."</td>";
					echo "";
					echo "<td id=\"modelTd[" .$i .  "]\">" . $h['model']."</td>";
					echo "";
					echo "<td id=\"serialTd[" .$i .  "]\">" . $h['serial']."</td>";
					echo "";
					echo "<td id=\"ownerTd[" .$i .  "]\">" . $h['owner_surname']. " ". $h['owner_name']."</td>";
					echo "";
					echo "<td id=\"ownerIDTd[" .$i .  "]\">" . $h['owner_id']."</td>";
					echo "";
					echo "<td id=\"supplierTd[" .$i .  "]\">" . $h['supplier_name']."</td>";
					echo "";
					echo "<td id=\"poTd[" .$i .  "]\">" . $h['po']."</td>";
					echo "";
					echo "<td id=\"dateTd[" .$i .  "]\">" . $h['date_supplied']."</td>";
					echo "";
					echo "<td id=\"signedTd[" .$i .  "]\">" . $h['signed']."</td>";
					echo "";
					echo "<td id=\"createdByTd[" .$i .  "]\">" . $h['created_by']."</td>";
					echo "";
					echo "<td id=\"noteTd[" .$i .  "]\">" . $h['note']."</td>";
					echo "</tr>";
					$i++;
				}
			?>

			<tr>
				<td>
					<select name="gvl_gdvt[0]" autofocus>
						<option>GDVT</option>
						<option>GVL</option>
					</select>
				</td>
				<td>
					<select name="type[0]">
						<?php
							foreach($assetTypes as  $t)
							{
								echo "<option value='". $t['name'] . "'>" . $t['name'] . "</option>";
							}
						?>
					</select>
				</td>
				<td>
					<input list="manufacturer" name="manufacturer[0]" autocomplete="off">
					<datalist id="manufacturer">
						<?php
							foreach($assetManufacturers as  $m)
							{
								echo "<option value='". $m['name'] . "'> ";
							}
						?>
					</datalist>
				</td>
				<td>
					<input name="model[0]" placeholder="Model" onkeypress="return event.keyCode != 13;">
				</td>
				<td>
					<input name="serial[0]" placeholder="Serial No." autocomplete="off" onkeypress="return event.keyCode != 13;">
				</td>
				<td><input name="user[0]" type="text" disabled="disabled" readonly></td>
				<td><input name="SSO[0]" placeholder="SSO" onblur="userOnblur(this)" autocomplete="off" onkeypress="return event.keyCode != 13;"></td>
				<td>
					<input type="text" list="suppliers" name="supplier[0]" autocomplete="off">
					<datalist id="suppliers">
						<?php
							foreach($assetSuppliers as  $m)
							{
								echo "<option value='". $m['name'] . "' > ";
							}
						?>
					</datalist>
				</td>
				<td><input name="PO[0]" placeholder="PO" autocomplete="off" onkeypress="return event.keyCode != 13;"></td>
				<td><input type="date" name="date_supplied[0]" autocomplete="off" placeholder="mm.dd.yyy"></td>
				<td></td>
				<td></td>
				<td>
					<textarea name="note[0]" rows="1" cols="10"></textarea>
				</td>
			</tr>

			<?php 
				for($i=1 ; $i < $emptyRows; $i++) {?>
				<tr>
				<td>
					<select name="gvl_gdvt[<?php echo $i; ?>]">
						<option>GDVT</option>
						<option>GVL</option>
					</select>
				</td>
				<td>
					<select name="type[<?php echo $i; ?>]">
						<?php
							foreach($assetTypes as  $t)
							{
								echo "<option value='". $t['name'] . "'>" . $t['name'] . "</option>";
							}
						?>
					</select>
				</td>
				<td>
					<input list="manufacturer" name="manufacturer[<?php echo $i; ?>] autocomplete="off"">
					<datalist id="manufacturer">
						<?php
							foreach($assetManufacturers as  $m)
							{
								echo "<option value='". $m['name'] . "'> ";
							}
						?>
					</datalist>
				</td>
				<td>
					<input name="model[<?php echo $i; ?>]" placeholder="Model">
				</td>
				<td>
					<input name="serial[<?php echo $i; ?>]" placeholder="Serial No." autocomplete="off">
				</td>
				<td><input name="user[<?php echo $i; ?>]" type="text" disabled="disabled" readonly></td>
				<td><input name="SSO[<?php echo $i; ?>]" placeholder="SSO" onblur="userOnblur(this)" autocomplete="off"></td>
				<td>
					<input type="text" list="suppliers" name="supplier[<?php echo $i; ?>]" autocomplete="off">
					<datalist id="suppliers">
						<?php
							foreach($assetSuppliers as  $m)
							{
								echo "<option value='". $m['name'] . "' > ";
							}
						?>
					</datalist>
				</td>
				<td><input name="PO[<?php echo $i; ?>]" placeholder="PO" autocomplete="off"></td>
				<td><input type="date" name="date_supplied[<?php echo $i; ?>]" placeholder="mm.dd.yyy" autocomplete="off"></td>
				<td></td>
				<td></td>
				<td>
					<textarea name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea>
				</td>
				</tr>
		
			<?php } ?>
		</tbody>
		</table>
		
		<input type="submit" value="Add HW">
		</form>
	</main>
  
  
</body>

</html>
