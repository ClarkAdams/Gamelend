<?php

	/*
		Using thegamesDB.net to get list of games by filters and searchstring
		ThegamesDB.net has its own API for queries and only returns a maximum och 20 results
	*/

	include_once 'api/xml2array.php';

	// preparing filterstring if any filters are selcted in form
	$filterstring ="";
	if (($platform_encode = str_replace(' ', '+', $_POST["platform"])) && $_POST["platform"]!="Platform") {
		$filterstring .= "&platform=".$platform_encode;
	}
	if (($genre_encode = str_replace(' ', '+', $_POST["genre"])) && $_POST["genre"]!="Genre") {
		$filterstring .= "&genre=".$genre_encode;
	}
	$gamename_encode = str_replace(' ', '+', $_POST["name"]);

	$xml = new DOMDocument();
	$xml->load('http://thegamesdb.net/api/GetGamesList.php?name='.$gamename_encode.$filterstring);
	$games = XML2Array::createArray($xml);
	
	// thegamesDB.net retuns defferent depths of arrays if one or more then one query results.
	// if only one gams is returned the game is ,oved from Data to Data["Game"]
	if (isset($games["Data"]["Game"]["id"])) {
		$tempgames["Data"]["Game"][0] = $games["Data"]["Game"];
		$games=$tempgames;
	}
	
	echo json_encode($games);
  
?>