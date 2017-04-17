<?php
require_once('../classes/sql.php');
$sql = new SQL();

echo $sql->getLastOwnershipUserJson($_POST['usr']);
?>