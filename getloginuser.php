<?php
/*
	returns user data based on logged in user
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$id = $_SESSION['user_id'];

	$stmt = $mysqli->prepare("SELECT firstname, lastname, city, platforms, rating, username, img, email FROM usernamesdndid WHERE id = ?");
	$stmt->bind_param('s', $id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($firstname, $lastname, $city, $platforms, $rating, $username, $img, $email);
	$stmt->fetch();

	$arr = array('firstname' => $firstname, 
		'lastname' => $lastname, 
		'rating' => $rating, 
		'platforms' => $platforms, 
		'username' => $username, 
		'img' => $img, 
		'email' => $email, 
		'city' => $city);
	echo json_encode($arr);
}

$mysqli = NULL;
?>