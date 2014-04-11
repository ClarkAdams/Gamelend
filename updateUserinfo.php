<?php

/*
    Function for updating the users personal infoamtion plus adding or removing image
*/
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/validateAndSaveIMG.php';
include_once 'api/db_pdo_connect.php';
sec_session_start();
$error_msg = "";

if (login_check($mysqli)) {
    $userID = $_SESSION['user_id'];

    // Sanitize and validate the data passed in
    $username = $_SESSION['username'];

    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    if (empty($firstname)) {
        $firstname="";
    }
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    if (empty($lastname)) {
        $lastname="";
    }
    
    $city = $_POST["city"];

    // forms a platform string for correct input in database
    $platforms="";
    if (!empty($_POST["platforms"])) {
        foreach ($_POST["platforms"] as $value) {
            $platforms .= "a".$value;    
        }
        $platforms .= "a";
    }
    // algorith for deleting, setting och leaving image variable as it is
    if(isset($_POST['removeimg'])) {

        removeimage($username, $userID, $mysqli);
        $stmt = $pdo -> prepare('UPDATE userdata SET firstname=:1, lastname=:2, city=:3, platforms=:4, img="No image" WHERE id=:5');
        $stmt -> bindParam(':1', $firstname);
        $stmt -> bindParam(':2', $lastname);
        $stmt -> bindParam(':3', $city);
        $stmt -> bindParam(':4', $platforms);
        $stmt -> bindParam(':5', $userID);
        $stmt -> execute();

        header( 'Location: ./profile.php' );
        
    } else {
        if (!empty($_FILES["img"]["tmp_name"])) {
        // validating and saving image on server
        $img = validateAndSaveFile($_FILES, $username, $userID, $mysqli);

        $stmt = $pdo -> prepare('UPDATE userdata SET firstname=:1, lastname=:2, city=:3, platforms=:4, img=:5 WHERE id=:6');
        $stmt -> bindParam(':1', $firstname);
        $stmt -> bindParam(':2', $lastname);
        $stmt -> bindParam(':3', $city);
        $stmt -> bindParam(':4', $platforms);
        $stmt -> bindParam(':5', $img);
        $stmt -> bindParam(':6', $userID);
        $stmt -> execute(); 

        header( 'Location: ./profile.php' );      

        } 
        else {
            $stmt = $pdo -> prepare('UPDATE userdata SET firstname=:1, lastname=:2, city=:3, platforms=:4 WHERE id=:5');
            $stmt -> bindParam(':1', $firstname);
            $stmt -> bindParam(':2', $lastname);
            $stmt -> bindParam(':3', $city);
            $stmt -> bindParam(':4', $platforms);
            $stmt -> bindParam(':5', $userID);
            $stmt -> execute(); 
            
            header( 'Location: ./profile.php' );      

        }

    }
$mysqli = NULL;
$pdo = NULL;
}