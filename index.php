<?php
/*
	collects error, message and data from database to be loaded to template file
	ghfdjghfdsygureycgnuergnucierngcueisl
*/

//header('Content-Type: text/plain; charset=utf-8');
include_once 'includes/db_connect.php';
include_once 'api/db_pdo_connect.php';
include_once 'includes/register.inc.php';
include_once 'includes/functions.php';
require_once '/usr/local/lib/Smarty-3.1.16/libs/Smarty.class.php';
require '../phpbrowscap/Browscap.php';
use phpbrowscap\Browscap;
$bc = new Browscap('../phpbrowscap/cache');

sec_session_start();
// if logged in, redirected to profile.php
if (login_check($mysqli) == true) {
	header ("Location: profile.php");
}

//	Stop the warning of unset timezone
date_default_timezone_set('Europe/Stockholm');

//	include and initialize the Smarty template

$smarty = new Smarty();
$smarty->setTemplateDir('/Library/WebServer/Documents/gamelendDev/templates/');
$smarty->setCompileDir('/Library/WebServer/Documents/gamelendDev/templates_c/');
$smarty->setConfigDir('/Library/WebServer/Documents/gamelendDev/configs/');
$smarty->setCacheDir('/Library/WebServer/Documents/gamelendDev/cache/');

//	if user not using firefox or chrome (platform not accounted for). alert message is presented
$current_browser = $bc->getBrowser();
if ($current_browser->Browser=="Firefox" || $current_browser->Browser=="Chrome") {
	$smarty->assign('browsersupport', true);
} else {
	$smarty->assign('browsersupport', false);
}

// populates city and platform Array
$stmt = $pdo -> prepare('SELECT id, name FROM cities');
$stmt -> execute();	
$cityArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo -> prepare('SELECT id, console FROM consoles');
$stmt -> execute();	
$consoleArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

// assigning register and login error
if (isset($_GET["err"])) {
	if ($_GET["err"]=="1" || $_GET["err"]=="Could not process login") {
		$smarty->assign('loginerror', 'Could not process login');
	}
	if ($_GET["err"]=="Account not validated") {
		$smarty->assign('loginerror', 'Account not validated');
	}
	if ($_GET["err"]=="validationerror") {
		$smarty->assign('loginerror', 'There was a problem with your validation');	
	}
}

//	assigning message variable
if (isset($_GET["message"])) {
	if ($_GET["message"]=="validated") {
		$smarty->assign('registered', 'successvalidation');	
	}
	if ($_GET["message"]=="registered") {
		$smarty->assign('registered', 'successregister');	
	}
}

//	assigning error messages depening on input validation
if(isset($_GET["errpswval"])) {
	$error["password"]=1;
}
if(isset($_GET["errusr"])) {
	$error["username"]=1;
}elseif(isset($_GET["usr"])) {
	$smarty->assign('username', $_GET["usr"]);
}
if(isset($_GET["errem"])) {
	$error["email"]=1;
}elseif(isset($_GET["em"])) {
	$smarty->assign('email', $_GET["em"]);
}

if (isset($error)) {
	$smarty->assign('error', $error);
}

// populating inputs if non valid inputs during register atempt
//fhghjgfdfgdugfudigdfsvnfjsvgfhgv
if(isset($_GET["city"])) {
	$smarty->assign('cityInput', $_GET['city']);
} else {
	$smarty->assign('cityInput', '');
}
if (isset($_GET["platforms"])) {
	$platformsArray = explode("a", $_GET['platforms']);
	$platformsInputArray = array();
	foreach ($platformsArray as $value) {

		$stmt = $pdo -> prepare('SELECT id, console FROM consoles WHERE id=:1');
		$stmt -> bindParam(':1', $value);
		$stmt -> execute();	
		$tempArray = $stmt->fetchAll(PDO::FETCH_ASSOC);	
		array_push($platformsInputArray, $tempArray);
	}
	$smarty->assign('platformsInput', $platformsInputArray);	
} else {
	unset($platformsInputArray);
}
if (isset($_GET["firstname"])) {
	$smarty->assign('firstname', $_GET["firstname"]);
} else {
	$smarty->assign('firstname', "");
}
if (isset($_GET["lastname"])) {
	$smarty->assign('lastname', $_GET["lastname"]);
} else {
	$smarty->assign('lastname', "");
}

$smarty->assign('register', $_SERVER["PHP_SELF"]);
$smarty->assign('cities', $cityArray);
$smarty->assign('consoles', $consoleArray);
$smarty->display('login.tpl');

?>
