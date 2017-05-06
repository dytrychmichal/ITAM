<?php
class SQL
{
	public function logIn($sso, $pass)
	{
			require ('db.php');
			require_once('passwordLib.php');
			
			
			$stmtusr = $db->prepare("select pwd from itam.user where upper(sso)=upper(:sso)");                  
			$stmtusr->execute(array(':sso' => $sso));
			$passDB = $stmtusr->fetchColumn();	
			return password_verify($pass, $passDB);
	}
	
	public function getUser($sso)
	{
		require ('db.php');
		$stmt = $db->prepare("select name, surname from itam.user where sso = :sso");   
		$stmt->execute(array(':sso' => $sso));
		$usr = $stmt->fetch();

		return $usr;
	}
	
	public function updatePass($sso, $hash)
	{
		require ('db.php'); 
		$stmtret = $db->prepare("update itam.user set 
		pwd=:pwd
		where sso=:usr");                 
		$stmtret->execute(array(':pwd' => $hash, ':usr' => $sso));
	}
	
	public function getTypeID($name)
	{
		  require ('db.php');
		  $stmt = $db->prepare("select id from itam.asset_type where name = :na");   
		  $stmt->execute(array(':na' => $name));
		  $costcenters = $stmt->fetchColumn();
		  
		  return $costcenters;
	}
	
	public function getCostcenters()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name,  code from itam.costcenter order by name");   
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
	
	public function getUsersActiveJson()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name, surname, sso from itam.user 
								where active_to is null order by name");   
		  $stmt->execute();
		  $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
		  $json = json_encode($users);
		  
		  return $json;
	}
	
	public function getSuppliers()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select name from itam.supplier order by name");   
		  $stmt->execute();
		  $suppliers = $stmt->fetchAll();
		  
		  return $suppliers;
	}
	
	public function getHW()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select A.inventory_number, AT.name as asset_type, M.name as manufacturer_name, A.model, A.serial, S.name as supplier_name, A.po, A.date_supplied, A.note
								from itam.asset A
								join itam.asset_type AT on A.type_id=AT.id
								join itam.manufacturer M on A.manufacturer_id=M.id
								left join itam.supplier S on A.supplier_id=S.id
								where date_scrapped is null
								order by A.id;");   
		  $stmt->execute();
		  $hw = $stmt->fetchAll();
		  
		  return $hw;
	}
	
	public function getHWScrapped()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select A.inventory_number, AT.name as asset_type, M.name as manufacturer_name, A.model, A.serial, S.name as supplier_name, A.po, A.date_supplied, A.note
								from itam.asset A
								join itam.asset_type AT on A.type_id=AT.id
								join itam.manufacturer M on A.manufacturer_id=M.id
								left join itam.supplier S on A.supplier_id=S.id
								where date_scrapped is null
								order by A.id;");   
		  $stmt->execute();
		  $hw = $stmt->fetchAll();
		  
		  return $hw;
	}
	
	public function getHWInv($inv)
	{
		  require ('db.php');
		  $stmt = $db->prepare("select A.inventory_number, AT.name as asset_type, M.name as manufacturer_name, A.model, A.serial, S.name as supplier_name, A.po, A.date_supplied, A.note
								from itam.asset A
								join itam.asset_type AT on A.type_id=AT.id
								join itam.manufacturer M on A.manufacturer_id=M.id
								left join itam.supplier S on A.supplier_id=S.id
                                where A.inventory_number = :inv
								order by A.id;");   
		  $stmt->execute(array(':inv' => $inv));
		  $hw = $stmt->fetch();
		  
		  return $hw;
	}
	
	public function getOwnerships()
	{
		  require ('db.php');
		  $stmt = $db->prepare("select O.id, O.date_created, O.note, ASS.inventory_number, ASS.model, ASS.serial, M.name as m_name, N.name as n_name, N.surname as n_surname, N.sso as n_sso,
								L.name as l_name, L.surname as l_surname, L.sso as l_sso, C.name as c_name, C.surname as c_surname
								from itam.ownership O  left join itam.asset A on O.asset_id=A.id
								join itam.asset ASS on O.asset_id=ASS.id
								join itam.manufacturer M on ASS.manufacturer_id=M.id
								join itam.user N on O.user_id=N.sso
								left join itam.user L on O.from_user=L.sso
								join itam.user C on O.created_by=C.sso
								order by O.id;");   
		  $stmt->execute();
		  $ownerships = $stmt->fetchAll();
		  
		  return $ownerships;
	}
	
	public function getLastOwnershipInvJson($inv)
	{
	 require ('db.php');
		  $stmt = $db->prepare(/*"select A.inventory_number as inv, A.model, A.serial, M.name as manufacturer_name, U.name as user_name, U.surname as user_surname, U.sso, O.note from itam.ownership O
								join itam.asset A on O.asset_id=A.id
								join itam.Manufacturer M on A.manufacturer_id=M.id
								join itam.user U on O.user_id=U.sso
								where date_ended is null and inventory_number=:inv;"*/
								"select A.inventory_number as inv, A.model, A.serial, M.name as manufacturer_name, U.name as user_name, U.surname as user_surname, U.sso, O.note from itam.ownership O
								join itam.user U on O.user_id=U.sso
                                right join itam.asset A on O.asset_id=A.id
                                join itam.Manufacturer M on A.manufacturer_id=M.id
								where date_ended is null and inventory_number=:inv;");   
		  $stmt->execute(array(':inv' => $inv));
		  $ownership = $stmt->fetchAll(PDO::FETCH_ASSOC);
		  $json = json_encode($ownership);
		  
		  return $json;
	}
	
	public function getLastOwnershipSerialJson($ser)
	{
	 require ('db.php');
		  $stmt = $db->prepare(/*"select A.inventory_number as inv, A.model, A.serial, M.name as manufacturer_name, U.name as user_name, U.surname as user_surname, U.sso, O.note from itam.ownership O
								join itam.asset A on O.asset_id=A.id
								join itam.Manufacturer M on A.manufacturer_id=M.id
								join itam.user U on O.user_id=U.sso
								where date_ended is null and A.serial=:ser;"*/
								"select A.inventory_number as inv, A.model, A.serial, M.name as manufacturer_name, U.name as user_name, U.surname as user_surname, U.sso, O.note from itam.ownership O
								join itam.user U on O.user_id=U.sso
                                right join itam.asset A on O.asset_id=A.id
                                join itam.Manufacturer M on A.manufacturer_id=M.id
								where date_ended is null and A.serial=:ser;");   
		  $stmt->execute(array(':ser' => $ser));
		  $ownership = $stmt->fetchAll(PDO::FETCH_ASSOC);
		  $json = json_encode($ownership);
		  
		  return $json;
	}
	
	public function getLastOwnershipUser($usr)
	{
	 require ('db.php');
		  $stmt = $db->prepare("select A.inventory_number as inv, A.model, A.serial, M.name as manufacturer_name, U.name as user_name, U.surname as user_surname, U.sso
								from itam.ownership O
								join itam.user U on O.user_id=U.sso
								join itam.asset A on O.asset_id=A.id
								join itam.manufacturer M on A.manufacturer_id=M.id
								where date_ended is null and O.user_id=:usr
								order by A.id");   
		  $stmt->execute(array(':usr' => $usr));
		  $ownership = $stmt->fetchAll();
		  
		  return $ownership;
	}
	
	public function addUser($name, $surname, $sso, $cc, $date)
	{
		  require ('db.php');
		  try
		  {
			$stmt = $db->prepare("insert into itam.user (name, surname, sso, costcenter_id, active_from) values(:na, :su, :ss, :cc, :active)");   
			$stmt->execute(array(':na' => $name, ':su' => $surname, ':ss' => $sso, ':cc' => $cc, ':active' => $date));
		  }
		  catch (PDOException $e)
		  {
			if((int)$e->getCode( ) === 23505)
			{
				echo "Username " . $sso . " already in DB";	//do it better than echo
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
			$stmt->execute(array(':na' => ucfirst($name)));
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
			$stmt->execute(array(':na' => ucfirst($name)));
		  }
		  catch (PDOException $e)
		  {
			echo $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}

	public function addHW($inv, $type, $manufacturer, $model, $serial, $supplier, $po, $date, $note)
	{
		  require ('db.php');
		  try
		  {
			//get type id - can get the IDs because NAME is unique
			$stmtT = $db->prepare("select id from itam.asset_type where name = :na");   
			$stmtT->execute(array(':na' => $type));
			$tID = $stmtT->fetchColumn();
			
			//get manufacturer id
			$stmtM = $db->prepare("select id from itam.manufacturer where upper(name) = upper(:na)");   
			$stmtM->execute(array(':na' => $manufacturer));
			$mID = $stmtM->fetchColumn();
			
			//get supplier id
			$stmtS = $db->prepare("select id from itam.supplier where upper(name) = upper(:na)");   
			$stmtS->execute(array(':na' => $supplier));
			$sID = $stmtS->fetchColumn();
			
			//and now for the insertion
			$stmt = $db->prepare("insert into itam.asset (inventory_number, type_id, manufacturer_id, model, serial, date_supplied, note, supplier_id, po) values(:inv, :type, :manuf, :model, upper(:serial), :date, :note, :supp, :po)");   
			$stmt->execute(array(':inv' => $inv, ':type' => $tID,  ':manuf' => $mID,  ':model' => $model, ':serial' => $serial, ':date' => $date, ':note' => $note, ':supp' => $sID, ':po' => $po));
		  }
		  catch (PDOException $e)
		  {
			echo 'AddHW '. $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}

	public function createOwnership($inv, $ssoOld, $ssoNew, $date, $note)
	{
		require ('db.php');
		try
		{
			//end old ownership
			$stmtUpdate = $db->prepare("UPDATE itam.ownership 
								SET date_ended = :date
								WHERE
								date_ended is null AND id=(select O.id FROM itam.asset A JOIN itam.ownership O on A.id=O.asset_id WHERE A.inventory_number=:inv and O.date_ended is null)");
			$stmtUpdate->execute(array(':inv' => $inv, ':date' => $date));
			$author = '212586720';

			//get asset id
			$stmtA = $db->prepare("select id from itam.asset where inventory_number=:inv");
			$stmtA->execute(array(':inv' => $inv));
			$aID = $stmtA->fetchColumn();
			
			//get author SSO
			$author = $_SESSION['name'];
			
			//create new ownership
			$stmt = $db->prepare("insert into itam.ownership (asset_id, date_created, note, from_user, user_id, created_by) values(:asset, :date, :note, :userO, :userN, :author)");   
			$stmt->execute(array(':asset' => $aID, ':date' => $date, ':note' => $note, ':userO' => $ssoOld, 'userN'=>$ssoNew, ':author' => $author));
			
			$stmtI = $db->prepare("select max(id) from itam.ownership");
			$stmtI->execute();
			$inv = $stmtI->fetch();
			
			return $inv[0];
			
		}
		catch (PDOException $e)
		{
			echo 'AddOwnership'. $e->getMessage();
			return;
		}
		echo 'Inserted successfully';

	}
	
	public function createOwnershipNew($inv, $sso, $date, $note)
	{
		require ('db.php');
		try
		{
			//get asset id - last id in asset table
			$stmtA = $db->prepare("select id from itam.asset where inventory_number = :inv");
			$stmtA->execute(array(':inv' => $inv));
			$aID = $stmtA->fetchColumn();
			
			//get author SSO
			$author = $_SESSION['name'];
			
			//echo $aID . '|' . date('Y-m-d') . '|'  . $note . '|' . $author . '|'. $sso;
			
			//and now for the insertion
			$stmt = $db->prepare("insert into itam.ownership (asset_id, date_created, note, user_id, created_by) values(:asset, :date, :note, :user, :author)");   
			$stmt->execute(array(':asset' => $aID, ':date' => date('Y-m-d'), ':note' => $note, ':user' => $sso, ':author' => $author));
			
			$stmtI = $db->prepare("select max(id) from itam.ownership");
			$stmtI->execute();
			$inv = $stmtI->fetch();
			
			return $inv[0];
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			return;
		}
		echo 'Inserted successfully';
		  
	}
	
	public function addOwnershipNew($sso, $note)	//decomissioned, no longer in use
	{
		require ('db.php');
		try
		{
			//get asset id - last id in asset table
			$stmtA = $db->prepare("select max(id) from itam.asset");
			$stmtA->execute();
			$aID = $stmtA->fetchColumn();
			
			//get author SSO
			$author = $_SESSION['name'];
			
			//echo $aID . '|' . date('Y-m-d') . '|'  . $note . '|' . $author . '|'. $sso;
			
			//and now for the insertion
			$stmt = $db->prepare("insert into itam.ownership (asset_id, date_created, note, user_id, created_by) values(:asset, :date, :note, :user, :author)");   
			$stmt->execute(array(':asset' => $aID, ':date' => date('Y-m-d'), ':note' => $note, ':user' => $sso, ':author' => $author));
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			return;
		}
		echo 'Inserted successfully';
		  
	}

	public function editHW($inv, $type, $manufacturer, $model, $serial, $supplier, $po, $date, $note)
	{
		  require ('db.php');
		  try
		  {
			  echo "editing " . $inv . "<br>";
			//get type id - can get the IDs because NAME is unique
			$stmtT = $db->prepare("select id from itam.asset_type where name = :na");   
			$stmtT->execute(array(':na' => $type));
			$tID = $stmtT->fetchColumn();
			
			//get manufacturer id
			$stmtM = $db->prepare("select id from itam.manufacturer where upper(name) = upper(:na)");   
			$stmtM->execute(array(':na' => $manufacturer));
			$mID = $stmtM->fetchColumn();
			
			//get supplier id
			$stmtS = $db->prepare("select id from itam.supplier where upper(name) = upper(:na)");   
			$stmtS->execute(array(':na' => $supplier));
			$sID = $stmtS->fetchColumn();
			
			//and now for the insertion
			$stmt = $db->prepare("update itam.asset set type_id=:type, manufacturer_id=:manuf, model=:model, serial=upper(:serial), date_supplied=:date, note=:note, supplier_id=:supp, po=:po
									where inventory_number=:inv");   
			$stmt->execute(array(':inv' => $inv, ':type' => $tID,  ':manuf' => $mID,  ':model' => $model, ':serial' => $serial, ':date' => $date, ':note' => $note, ':supp' => $sID, ':po' => $po));
		  }
		  catch (PDOException $e)
		  {
			echo 'editHW '. $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}
	
	public function scrapHW($inv, $note)
	{
		  require ('db.php');
		  try
		  {
			$date = date('Y-m-d', time());
			$note = $note . "\n scrapped by " . $_SESSION['name']; 
			$stmt = $db->prepare("update itam.asset set date_scrapped=:date, reason_scrapped=:note
									where inventory_number=:inv");   
			$stmt->execute(array(':inv' => $inv, ':date' => $date, ':note' => $note));
		  }
		  catch (PDOException $e)
		  {
			echo 'editHW '. $e->getMessage();
			return;
		  }
		  echo 'Inserted successfully';
		  
	}

}

