<?php
	$db = new PDO('pgsql:host=localhost;dbname=postgres', 'xxxx', 'xxxx');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
