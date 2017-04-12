<?php

class SQL
{
	public function getCostcenters()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name,  code from itam.costcenter order by code");   
		  $stmt->execute();
		  $costcenters = $stmt->fetchAll();
		  
		  return $costcenters;
	}
	
	public function getTypes()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name from itam.asset_type order by name");   
		  $stmt->execute();
		  $types = $stmt->fetchAll();
		  
		  return $types;
	}
	
	public function getManufacturers()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name from itam.manufacturer order by name");   
		  $stmt->execute();
		  $manufacturers = $stmt->fetchAll();
		  
		  return $manufacturers;
	}
	
	public function getSuppliers()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name from itam.supplier order by name");   
		  $stmt->execute();
		  $suppliers = $stmt->fetchAll();
		  
		  return $suppliers;
	}
		
	public function addUser($name, $surname, $sso)
	{
		  require ('db.php');
		  try
		  {
			$stmt = $db->prepare("insert into itam.user (name, surname, sso) values(:na, :su, :ss)");   
			$stmt->execute(array(':na' => $name, ':su' => $surname, ':ss' => $sso));
		  }
		  catch (PDOException $e)
		  {
			if((int)$e->getCode( ) === 23505)
			{
				echo "Username " . $sso . " already in DB";	//better than echo
			}
			else
			{
				echo $e->getMessage();
			}
			return;
		  }
		  echo 'Inserted successfully';
		  
	}
	
	public function addManufacturer($name)
	{
		  require ('db.php');
		  try
		  {
			$stmt = $db->prepare("insert into itam.manufacturer (name) values(:na)");   
			$stmt->execute(array(':na' => $name));
		  }
		  catch (PDOException $e)
		  {
			echo $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}
	
	public function addSupplier($name)
	{
		  require ('db.php');
		  try
		  {
			$stmt = $db->prepare("insert into itam.supplier (name) values(:na)");   
			$stmt->execute(array(':na' => $name));
		  }
		  catch (PDOException $e)
		  {
			echo $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}

	
	public function addHW($ownership, $type, $manufacturer, $model, $serial, $supplier, $date, $note)
	{
		  require ('db.php');
		  try
		  {
			//get type id
			//get manufacturer id
			$stmt = $db->prepare("insert into itam.asset (name) values(:na)");   
			$stmt->execute(array(':na' => $name));
		  }
		  catch (PDOException $e)
		  {
			echo $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}

}

