<?php

include_once 'api/SimpleImage.php';

$image = new SimpleImage(); 
$image->load('http://thegamesdb.net/banners/_gameviewcache/boxart/original/front/2779-1.jpg'); 
$image->resizeToWidth(250); 
$image->save('art/boxartthumb/2779-1.jpg');

?>


<!DOCTYPE html>
<html>
      <head>
            <meta charset="utf-8">
            <title>2.2.1 Information sänd via formulär</title>
            <script type="text/javascript" src="js/javascript.js"></script>
            <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
            <script type="text/javascript">

   
            </script>
      </head>
      <body>
            <script type="text/javascript">
            window.onload = function() {
                  $("#knapp").click(function() {
                        console.log("fhjdsfhjsdk");
                        $('form#editform').submit();
                  });
            }
            </script>
      </br>


            <form method="post" enctype="multipart/form-data" action="api/validateAndSaveIMG.php">
                  <label>Select image</label>
                  </br>
                  <input type="file" name="img" />
                  </br></br>
                  <button>upload</button>
            </form>
            <p id="knapp">dsadsa</p>
      </body>
</html>

<?php
//phpinfo();
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
/*


include_once 'includes/db_connect.php';
include_once 'includes/psl-config.php';


            $id ="";
            $email ="test@example.com";
            $stmt = $mysqli->prepare("SELECT id FROM members");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id);
            $stmt->fetch();
            print_r($id);
            echo "string";
/*
            $firstname = "David";
            $lastname = "Johansson";
            $city = "10";
            $platforms = "a2a25a26a27";
            $id = "10";
            $rating = "10";
/*
            $insert_stmt2 = $mysqli->prepare("INSERT INTO userdata (id, firstname, lastname, city, rating platforms) VALUES (?, ?, ?, ?, ?, ?)");
            $insert_stmt2->bind_param('ssssss', $id, $firstname, $lastname, $city, $rating, $platforms);
            $insert_stmt2->execute();

            $stmt = $mysqli->prepare("INSERT INTO userdata(`id`, `firstname`, `lastname`, `city`, `rating`, `platforms`) VALUES ( ?, ?, ?, ?, ?, ? )");
            $stmt->bind_param('ssssss', $id, $firstname, $lastname, $city, $rating, $platforms);
            $stmt->execute();




*/
