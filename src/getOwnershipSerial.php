<?php
require_once('../classes/sql.php');
$sql = new SQL();

echo $sql->getLastOwnershipSerialJson($_POST['ser']);
?>