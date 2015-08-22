<?php
	function Mize_server(){
		$dbHost = 'oniddb.cws.oregonstate.edu';
		$dbName = 'mizen-db';
		$dbUser = 'mizen-db';
		$dbPassword = 'MTS74qhdVHGeN2qN';
		
		return new mysqli($dbHost, $dbUser, $dbPassword, $dbName); 
	}
?>