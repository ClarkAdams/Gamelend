<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

  
  $xml = file_get_contents('http://thegamesdb.net/api/GetPlatformsList.php'); 
  $platforms = new SimpleXMLElement($xml);  
  $jsonencoded = json_encode($platforms);
  $jsondecoded = json_decode($jsonencoded, true);
  echo "<pre>";
	print_r($jsondecoded);
	echo "</pre>";
  $platforms = $jsondecoded["Platforms"]["Platform"];
  foreach ($platforms as $value) {
  	$stmt = $mysqli->prepare("INSERT INTO consoles (id, console) values ( ?, ? )");
	$stmt->bind_param('ss', $value["id"], $value["name"]);
	$stmt->execute();
  	echo $value["id"]." ".$value["name"];
  }
  
  /*
  $xml = file_get_contents('cities.xml');
  $platforms = new SimpleXMLElement($xml);  
  $jsonencoded = json_encode($platforms);
  $jsondecoded = json_decode($jsonencoded, true);
  echo "<pre>";
  print_r($jsondecoded);
  echo "</pre>";
  
  
   $cities = $jsondecoded["tr"];
   $val = 0;
  foreach ($cities as $value) {
    $val+=1;
    $stmt = $mysqli->prepare("INSERT INTO cities (id, name) values ( ?, ? )");
    $stmt->bind_param('ss', $val, $value["td"]["p"]);
    $stmt->execute();
  }
  */
  //while ($row = $jsondecoded["Platforms"]) {
  	//$stmt = $mysqli->prepare("INSERT INTO consoles (id, console) values ( ?, ? )");
	//$stmt->bind_param('ss', $row["id"]);
	//$stmt->execute();
	//echo $row["id"];

  //}
  
?>