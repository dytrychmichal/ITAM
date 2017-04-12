<?php
	$db = new PDO('pgsql:host=localhost;dbname=postgres', 'itam', 'geacitam01');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>