<?php
/*
	function for receiving genereated validation code sent to user during registration fase.
*/
include_once 'api/db_pdo_connect.php';
$code= $_GET["val"];

try {
	//check if any posts with given uniqe validation code. Sets status to validated(1)
	$stmt = $pdo -> prepare('UPDATE `accountValidate` SET `status`=1 WHERE `code`=:1');
	$stmt -> bindParam(':1', $code);
	//returns message or error depending on how the validation procedure went
	if ($stmt -> execute()) {
		header('Location: index.php?message=validated');
    	exit();	
	} else {
		header('Location: index.php?err=validationerror');
    exit();
	}
			
} catch(Exeption $e) {
	header('Location: index.php?err=validationerror');
    exit();
}
?>