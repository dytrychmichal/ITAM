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

$emptyCols = 5;		//determines how many empty columns there will be on the bottom of the table


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
	for($i = 0 ; $i < $emptyCols ; $i++)
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
			
			
			//echo 'inserting '. $_POST['gvl_gdvt'][$i] . ' ' . $_POST['type'][$i] . ' ' . $_POST['manufacturer'][$i] . ' ' . $_POST['model'][$i] . ' ' . $_POST['serial'][$i] . ' ' . $_POST['supplier'][$i] . ' ' .  $dateSQL . ' ' . $_POST['note'][$i]  . ' ' .  $_POST['PO'][$i];
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

			<?php 
				for($i=0 ; $i < $emptyCols; $i++) {?>
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
				<td><input name="user[<?php echo $i; ?>]" type="text" disabled="disabled" readonly></td>
				<td><input name="SSO[<?php echo $i; ?>]" placeholder="SSO" onblur="userOnblur(this)"></td>
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
  
  
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	 <script>
		function reqListener ()
		{
			console.log(this.responseText);
		}
		
		function userOnblur(elem)
		{
			var no = elem.name.charAt(4);
			var elemUser = document.getElementsByName("user[" + no + "]")[0];
			findUser(elem.value, elemUser, elem);
			
			//elem.onblur = updateUser;
		}
				
		function findUser(sso, elemUser, elem)
		{	
			for(var i=0; i<usersJson.length; i++)
			{
				if(usersJson[i].sso==sso)
				{	
					elemUser.value = usersJson[i].surname + " " + usersJson[i].name;
					return;
				}
			}
			elemUser.value = null;
			elem.value=null;
			
		}
		
		function updateUser()
		{
			var user=userField.value;
			findUser(user);
		}
		
		var userField = document.getElementById("usr");
		var userText = document.getElementById("txt");
		var usersJson;
		var oReq = new XMLHttpRequest(); //New request object
		
		oReq.onload = function()
		{
				//This is where you handle what to do with the response.
				//The actual data is found on this.responseText
			usersJson = JSON.parse(this.responseText);
		};
		
		
		oReq.open("get", "src/getUsersActive.php", true);
		oReq.send();
		
			
			

	  
	</script> 
 
  
  
</body>

</html>
