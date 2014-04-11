<?php
/*
	creates connection to gamelend database 
	username and passwords are removed obviously
*/
	$username = "username";
	$password = "password";

	try {
	    $pdo = new PDO('mysql:host=localhost;dbname=secure_login;charset=utf8', $username, $password);
	    /*		PDO::ERRMODE_SILENT / PDO::ERRMODE_WARNING / PDO::ERRMODE_EXCEPTION		*/
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e) {
	    echo 'ERROR: ' . $e->getMessage();
	}	

	function kill_pdo(){
		$pdo = NULL;
	}