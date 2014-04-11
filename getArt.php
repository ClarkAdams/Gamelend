<?php
/*
	gets XML file from thegamesdb.net API baes on gameID
*/

header("Content-type: application/json");
  $id = $_POST["id"];
  $xml = file_get_contents('http://thegamesdb.net/api/GetArt.php?id='.$id); 
  $images = new SimpleXMLElement($xml);  
  echo json_encode($images);
  
?>