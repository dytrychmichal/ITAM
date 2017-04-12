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
$assetTypes = $sql->getTypes();
$assetManufacturers = $sql->getManufacturers();
$assetSuppliers = $sql->getSuppliers();

$emptyCols = 5;		//determines how many empty columns there will be on the bottom of the table

print_r(array_values($assetManufacturers));

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
	//var_dump($_POST);
	echo "<br>";
	for($i = 1 ; $i <= $emptyCols ; $i++)
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
			
			if($_POST['PO'][$i] != null) //finish this
			{
			//	$sql->insertHWPO();
			}
			else
			{
				//$sql->insertHWPO();
			}
		}
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
	
</head>

<body>
	<header>
		<h1>ITInvent</h1>
		
		<?php include 'src/navbar.php' ?>
	
	</header>
	<main>
	<form method="post">
		<table class="new_HW_table">
			<tr class="table_header">
				<th>Inventary</th>
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
			<tr>
				<td>GVL3356</td>
				<td>Computer</td>
				<td>Dell</td>
				<td>Lattitude E7270</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>1.1.2017</td>
				<td></td>
				<td>MDy</td>
				<td></td>
			</tr>
			<tr>
				<td>GDVT1415</td>
				<td>Mobile</td>
				<td>Apple</td>
				<td>iPhone SE 64GB</td>
				<td></td>
				<td>Johnson Brian</td>
				<td>212666686</td>
				<td>14152678</td>
				<td>6.6.2016</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			
				<?php for($i=1 ; $i <= $emptyCols; $i++) {?>
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
					<input list="manufacturer" name="manufacturer[<?php echo $i; ?>]">
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
					<input name="serial[<?php echo $i; ?>]" placeholder="Serial No.">
				</td>
				<td></td>
				<td><input name="SSO[<?php echo $i; ?>]" placeholder="SSO"></td>
				<td>
					<input type="text" list="suppliers" name="supplier[<?php echo $i; ?>]">
					<datalist id="suppliers">
						<?php
							foreach($assetSuppliers as  $m)
							{
								echo "<option value='". $m['name'] . "' > ";
							}
						?>
					</datalist>
				</td>
				<td><input name="PO[<?php echo $i; ?>]" placeholder="PO"></td>
				<td><input type="date" name="date_supplied[<?php echo $i; ?>]" placeholder="Date delivered"></td>
				<td></td>
				<td></td>
				<td>
					<textarea name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea>
				</td>
		</tr>
		
		<?php } ?>
		
		</table>
		
		<input type="submit" value="Add HW">
		</form>
	</main>
  
  
   
  	
  

</body>

</html>
