<?php
require_once('../classes/sql.php');
$sql = new SQL();

echo $sql->getLastOwnershipInvJson($_POST['inv']);
?>