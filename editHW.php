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
		$cnt = count($_POST["type"]);
		for($i = 0 ; $i < $cnt ; $i++)
		{
			if($_POST['manufacturer'][$i] != null && $_POST['model'][$i] != null && $_POST['serial'][$i] != null && $_POST['supplier'][$i] != null && $_POST['date_supplied'][$i] != null) 
			{
				if(!findSQLArray($assetManufacturers, 'manufacturer', $i)) 	//If manufacturer does not exist, add new to DB
				{
					echo $_POST['manufacturer'][$i] . ' not in list, adding to DB<br>'; 
					$sql->addManufacturer($_POST['manufacturer'][$i]);			//add new manufacturer to DB
					$assetManufacturers = $sql->getManufacturers();				//and refresh list of manufacturers
				}
				
				if(!findSQLArray($assetSuppliers, 'supplier', $i))				//If supplier does not exist, add new to DB
				{
					echo $_POST['supplier'][$i] . ' not in list, adding to DB<br>'; 
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
				$sql->editHW($_POST['inv'][$i], $_POST['type'][$i], $_POST['manufacturer'][$i], $_POST['model'][$i], $_POST['serial'][$i], $_POST['supplier'][$i], $_POST['PO'][$i], $dateSQL, $_POST['note'][$i]);
			}
		}
	}
	//$sql->editHw();
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
		<h1>Edit HW</h1>
		
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
				<th>Supplier</th>
				<th>PO</th>
				<th>Date delivered</th>
				<th rowspan="1" colspan="2">Note</th>
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
				<th>Note</th>
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
						<select class="inv" name="type[<?php echo $i; ?>]">
							<?php
								foreach($assetTypes as  $t)
								{
									echo "<option value='". $t['name'] . "' ";
									if($t['name'] == $h['asset_type'])
									{
										echo "selected=\"selected\"";
									}
									echo ">" . $t['name'] . "</option>";
								}
							?>
						</select>
					</td>
					<td class="inv">
						<input  class="inv" list="manufacturer" name="manufacturer[<?php echo $i; ?>]" value="<?php echo $h["manufacturer_name"]?>" autocomplete="off">
						<datalist id="manufacturer">
							<?php
								foreach($assetManufacturers as  $m)
								{
									echo "<option value='". $m['name'] . "'> ";
								}
							?>
						</datalist>
					</td>
					<td class="inv">
						<input class="inv" name="model[<?php echo $i; ?>]" placeholder="Model" value="<?php echo $h["model"]?>">
					</td>
					<td class="inv">
						<input class="inv" name="serial[<?php echo $i; ?>]" placeholder="Serial No." value="<?php echo $h["serial"]?>" autocomplete="off">
					</td>
					<td class="inv">
						<input class="inv" type="text" list="suppliers" name="supplier[<?php echo $i; ?>]" value="<?php echo $h["supplier_name"]?>" autocomplete="off">
						<datalist class="inv" id="suppliers">
							<?php
								foreach($assetSuppliers as  $m)
								{
									echo "<option value='". $m['name'] . "' > ";
								}
							?>
						</datalist>
					</td>
					<td class="inv"><input class="inv" name="PO[<?php echo $i; ?>]" placeholder="PO" value="<?php echo $h["po"]?>" autocomplete="off"></td>
					<td class="inv"><input type="date" class="inv" name="date_supplied[<?php echo $i; ?>]" value="<?php $date = DateTime::createFromFormat('Y-m-d', $h["date_supplied"]); echo $date->format('d.m.Y'); ?>" autocomplete="off"></td>
					<td class="inv" rowspan="1" colspan="2">
						<textarea class="inv" name="note[<?php echo $i; ?>]" rows="1" cols="10"><?php echo $h["note"]?></textarea>
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
	<?php include 'src/footer.php' ?> 

</body>

</html>
