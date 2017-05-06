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

	if(isset($_POST["save"]))
	{
		for($i = 0 ; $i < $emptyRows ; $i++)
		{
			if($_POST['manufacturer'][$i] != null && $_POST['model'][$i] != null && $_POST['serial'][$i] != null && $_POST['supplier'][$i] != null && $_POST['date_supplied'][$i] != null) //check if all required values are present
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
				
				if($date = DateTime::createFromFormat('m.d.Y', $_POST['date_supplied'][$i]))
				{
					$dateSQL = $date->format('Y-m-d');
				}
				else if($date = DateTime::createFromFormat('Y-m-d', $_POST['date_supplied'][$i]))
				{
					$dateSQL = $date->format('Y-m-d');
				}
				else
				{				
					echo "bad dateformat, using today's date";
					$dateSQL = date('Y-m-d', time());
				}
				
				
				//echo 'inserting '. $_POST['gvl_gdvt'][$i] . ' |' . $_POST['type'][$i] . '| |' . $_POST['manufacturer'][$i] . '| |' . $_POST['model'][$i] . '| |' . $_POST['serial'][$i] . '| |' . $_POST['supplier'][$i] . '| |' .  $dateSQL . '| |' . $_POST['note'][$i]  . '| |' .  $_POST['PO'][$i] . "|";
				if($_POST['PO'][$i] == '')
				{
					$_POST['PO'][$i] = null;
				}
				$sql->addHW($_POST['gvl_gdvt'][$i], $_POST['type'][$i], $_POST['manufacturer'][$i], $_POST['model'][$i], $_POST['serial'][$i], $_POST['supplier'][$i], $_POST['PO'][$i], $dateSQL, $_POST['note'][$i]);

				
				$hw = $sql->getHW();
				
			}
		}
		$assetManufacturers = $sql->getManufacturers();
		$assetSuppliers = $sql->getSuppliers();
		$hw = $sql->getHW();
	}
	
	if(isset($_POST["freeRows"]))
	{
		$emptyRows = $_POST["freeRows"];
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
	<script src="./src/addHWScripts.js" type="text/javascript"></script>
	
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
		<h1>Hardware</h1>
		
		<?php include './src/navbar.php' ?>
	
	</header>
	<main>
	<form method="post">
		<table class="new_HW_table">
			<thead>
			<tr class="table_header">
				<th>Inventory</th>
				<th>Type</th>
				<th>Manufacturer*</th>
				<th>Model*</th>
				<th>Serial*</th>
				<th>Supplier*</th>
				<th>PO</th>
				<th>Date delivered*</th>
				<th>Note</th>
				<th>
					<button type="button" value="editAll" name="buttonEditAll" onclick="editAll()">edit all</button>
					<button type="button" value="deleteAll" name="buttonDeleteAll" onclick="scrapAll()">scrap all</button>
				</th>
			</tr>
			</thead>

			<tfoot>
			<tr class="table_header">
				<th>Inventory</th>
				<th>Type</th>
				<th>Manufacturer*</th>
				<th>Model*</th>
				<th>Serial*</th>
				<th>Supplier*</th>
				<th>PO</th>
				<th>Date delivered*</th>
				<th>Note</th>
				<th>
					<button type="button" value="editAll" name="buttonEditAll" onclick="editAll()">edit all</button>
					<button type="button" value="deleteAll" name="buttonDeleteAll" onclick="scrapAll()">scrap all</button>
				</th>
			</tr>
			</tfoot>

			<tbody>
			<?php
				$i=0;
				foreach($hw as  $h)
				{
					echo "<tr id=\"invTr[" . $i . "]\">\n";			// added \n for better HTML readability
					echo "<td id=\"invTd[" .$i .  "]\">". $h['inventory_number']."</td>\n";					
					echo "<td id=\"assetTd[" .$i .  "]\">" . $h['asset_type']."</td>\n";					
					echo "<td id=\"manufacturerTd[" .$i .  "]\">" . $h['manufacturer_name']."</td>\n";					
					echo "<td id=\"modelTd[" .$i .  "]\">" . $h['model']."</td>\n";					
					echo "<td id=\"serialTd[" .$i .  "]\">" . $h['serial']."</td>\n";			
					echo "<td id=\"supplierTd[" .$i .  "]\">" . $h['supplier_name']."</td>\n";					
					echo "<td id=\"poTd[" .$i .  "]\">" . $h['po']."</td>\n";					
					echo "<td id=\"dateTd[" .$i .  "]\">" . DateTime::createFromFormat('Y-m-d', $h['date_supplied'])->format('d.m.Y') ."</td>\n";				
					echo "<td id=\"noteTd[" .$i .  "]\">" . $h['note']."</td>\n";
					echo "<td id=\"buttonTd[" .$i .  "]\">" . 
					"<button type=\"button\" value=\"edit\" name=\"buttonEdit\" onclick=\"editAdd(" . $i . ");\">edit</button>" . 
					"<button type=\"button\" value=\"scrap\" name=\"buttonScrap\" onclick=\"scrapAdd(" . $i . ");\">scrap</button>".  "</td>\n";
					echo "</tr>\n";
					$i++;
				}
			?>

			<tr class="inv" id="firstEditable">
				<td class="inv">
					<select class="inv" name="gvl_gdvt[0]" autofocus>
						<option>GDVT</option>
						<option>GVL</option>
					</select>
				</td>
				<td id="firstSelect" class="inv">
					<select  class="inv" name="type[0]">
						<?php
							foreach($assetTypes as  $t)
							{
								echo "<option value='". $t['name'] . "'>" . $t['name'] . "</option>";
							}
						?>
					</select>
				</td>
				<td class="inv">
					<input class="inv" list="manufacturerDatalist[0]" name="manufacturer[0]" autocomplete="off">
					<datalist  class="inv" id="manufacturerDatalist[0]">
						<?php
							foreach($assetManufacturers as  $m)
							{
								echo "<option value='". $m['name'] . "'> ";
							}
						?>
					</datalist>
				</td>
				<td class="inv">
					<input type="text" class="inv" name="model[0]" placeholder="Model" onkeypress="return event.keyCode != 13;">
				</td>
				<td class="inv">
					<input type="text" class="inv" name="serial[0]" placeholder="Serial No." autocomplete="off" onkeypress="return event.keyCode != 13;">
				</td>
				<td class="inv">
					<input type="text" list="suppliersDatalist[0]" class="inv" name="supplier[0]" autocomplete="off">
					<datalist class="inv" id="suppliersDatalist[0]">
						<?php
							foreach($assetSuppliers as  $m)
							{
								echo "<option value='". $m['name'] . "' > ";
							}
						?>
					</datalist>
				</td>
				<td class="inv"><input type="text" name="PO[0]" class="inv" placeholder="PO" autocomplete="off" onkeypress="return event.keyCode != 13;"></td>
				<td class="inv"><input type="date" class="inv" name="date_supplied[0]" autocomplete="off" placeholder="mm.dd.yyy"></td>		<!--placeholder is for FF compatibility!-->
				<td class="inv" rowspan="1" colspan="2">
					<textarea class="inv" name="note[0]" rows="1" cols="10"></textarea>
				</td>
			</tr>

			<?php 
				for($i=1 ; $i < $emptyRows; $i++) {?>
				<tr class="inv">
				<td class="inv">
					<select  class="inv" name="gvl_gdvt[<?php echo $i; ?>]">
						<option>GDVT</option>
						<option>GVL</option>
					</select>
				</td>
				<td class="inv">
					<select class="inv" name="type[<?php echo $i; ?>]">
						<?php
							foreach($assetTypes as  $t)
							{
								echo "<option value='". $t['name'] . "'>" . $t['name'] . "</option>";
							}
						?>
					</select>
				</td>
				<td class="inv">
					<input  class="inv" list="manufacturerDatalist[<?php echo $i; ?>]" name="manufacturer[<?php echo $i; ?>]" autocomplete="off">
					<datalist id="manufacturerDatalist[<?php echo $i; ?>]">
						<?php
							foreach($assetManufacturers as  $m)
							{
								echo "<option value='". $m['name'] . "'> ";
							}
						?>
					</datalist>
				</td>
				<td class="inv">
					<input class="inv" name="model[<?php echo $i; ?>]" placeholder="Model">
				</td>
				<td class="inv">
					<input class="inv" name="serial[<?php echo $i; ?>]" placeholder="Serial No." autocomplete="off">
				</td>
				<td class="inv">
					<input class="inv" type="text" list="suppliersDatalist[<?php echo $i; ?>]" name="supplier[<?php echo $i; ?>]" autocomplete="off">
					<datalist class="inv" id="suppliersDatalist[<?php echo $i; ?>]">
						<?php
							foreach($assetSuppliers as  $m)
							{
								echo "<option value='". $m['name'] . "' > ";
							}
						?>
					</datalist>
				</td>
				<td class="inv"><input class="inv" name="PO[<?php echo $i; ?>]" placeholder="PO" autocomplete="off"></td>
				<td class="inv"><input type="date" class="inv" name="date_supplied[<?php echo $i; ?>]" placeholder="mm.dd.yyy" autocomplete="off"></td> <!--placeholder is for FF compatibility!-->
				<td class="inv" rowspan="1" colspan="2">
					<textarea class="inv" name="note[<?php echo $i; ?>]" rows="1" cols="10"></textarea>
				</td>
				</tr>
		
			<?php } ?>
		</tbody>
		</table>
		
		<input class="left" type="submit" name="save" value="Add HW">
		<input class="right" type="number" name="freeRows" min="5" max="30" value="<?php echo $emptyRows;?>">
		</form>
	</main>
	<?php include './src/footer.php' ?>
  
</body>

</html>
