<?php

function sendUserdataToNewUser($to, $username, $firstname, $lastname, $uniqid) {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugg enabled
    $mail->SMTPAuth = true; //     authentication 
    $mail->SMTPSecure = 'ssl'; // secure transfer. Gmail require this
    $mail->Host = 'smtp.gmail.com';
    //username and passwords are removed obviously
    $mail->Username = "username";
    $mail->Password = "password";
    $mail->Port = 465; //cant use port 25 due to my ISP
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->SetFrom('gamelendcom@gmail.com', 'admin', true);
    //$mail->AddAddress($to);
    $mail->AddAddress('gamelendcom@gmail.com');
    $mail->Subject = 'Welcome to GameLend!';
    $mail->Body = '<p>Welcome '.$firstname.' '.$lastname.' ( '.$username.' ) to the community. I hope this can help you get to play a lot of good games you otherwise wouldn\'t!<br />
At the moment the site is under development and will be so under a period. But the functions to browse and request to borrow games from other users are already implemented. So please use the site and point out bugs, improvements and other tips for future releases.<br />
My name is Manfred and im doing this for myself</p><p>To validate your emailadress press the link below:</p><p><a href="http://www.manfredjohansson.com/gamelenddev/validateAccount.php?val='.$uniqid.'">http://www.manfredjohansson.com/gamelenddev/validateAccount.php?val='.$uniqid.'</a></p>';
    if(!$mail->Send()){
        //echo "Error: " . $mail->ErrorInfo;
    }
}

/*
    above functions made by Manfred Johansson
*/

/* 
 * Copyright (C) 2013 peter
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */



include_once 'db_connect.php';
include_once 'psl-config.php';
include './api/validateFunctions.php';
include './api/PHPMailer-master/class.phpmailer.php';

$error_msg = "";
$get_msg = "";

if (isset($_POST['username'], $_POST['email'], $_POST['p'])) {
    // Sanitize and validate the data passed in

    $_POST = sanitize($_POST);

    $rating = 0;
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $platforms="";
    if (isset($_POST["platforms"])) {
        foreach ($_POST["platforms"] as $value) {
            $platforms .= "a".$value;    
        }
        $platforms .= "a";
    }
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '&errem=3';
    }
    
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '&errpsw=1';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
    // check if email exists
    $prep_stmt = "SELECT id FROM members WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '&errem=1';
        } else {
            $get_msg .="&em=".$email;
        }
    } else {
        $error_msg .= '&errem=2';
    }

    // check if username exists
    $prep_stmt = "SELECT id FROM members WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);
    
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '&errusr=1';
        } else {
            $get_msg .="&usr=".$username;
        }
    } else {
        $error_msg .= '&errusr=2';
    }
    
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.

    if (empty($error_msg)) {
        // Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));

        // Create salted password 
        $password = hash('sha512', $password . $random_salt);

        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO members (username, email, password, salt) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $username, $email, $password, $random_salt);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ../index.php?err=Registration failure: INSERT');
                exit();
            }

            $id ="";
            $stmt = $mysqli->prepare("SELECT id FROM members WHERE email = ? LIMIT 1");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();

            //additional inserts in databse. Made by Manfred Johanssson
            $insert_stmt = $mysqli->prepare("INSERT INTO userdata(`id`, `firstname`, `lastname`, `city`, `rating`, `platforms`, `img`) VALUES ( ?, ?, ?, ?, ?, ?, 'No image' )");
            $insert_stmt->bind_param('ssssss', $id, $firstname, $lastname, $city, $rating, $platforms);
            $insert_stmt->execute();
            //additional iserts in database. Made by Manfred johansson
            $uniqid = uniqid($username, true);
            $status = 0;
            $insert_stmt = $mysqli->prepare("INSERT INTO `accountValidate`(`email`, `status`, `code`) VALUES (?, ?, ?)");
            $insert_stmt->bind_param('sis', $email, $status, $uniqid);
            $insert_stmt->execute();
            //additinal function, made by Manfred Johansson
            sendUserdataToNewUser($email, $username, $firstname, $lastname, $uniqid);

            header('Location: ./index.php?message=registered');
            exit();
        }
        
    } else {

        header('Location: ./index.php?'.$error_msg.$get_msg.'&firstname='.$firstname.'&lastname='.$lastname.'&city='.$city.'&platforms='.$platforms);
    }
}






