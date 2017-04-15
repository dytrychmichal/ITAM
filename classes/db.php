<?php
	$db = new PDO('pgsql:host=localhost;dbname=postgres', 'xxxxx', 'xxxxx');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>