<?php
include_once 'api/SimpleImage.php';
include_once 'api/xml2array.php';
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';
include_once 'api/cacheGameQuery.php';

sec_session_start();

if (login_check($mysqli)) {

	header("Content-type: application/json");
	// if game is from the lent element
	$dataArray = explode("lend",$_POST["id"]);

	$id = $_POST["id"];
	if (isset($dataArray[1])) {
		$id = $dataArray[1];
	}
	
	$xml = new DOMDocument();
	
	if (!checkCacheForEntry($id, $pdo)) {
		$xml->load('http://thegamesdb.net/api/GetGame.php?id='.$id);
		cacheQuery($xml, $id, $pdo);
	} else {
		$xml->load("api/xmlcache/".$id.".xml");
	}
	
	if (isset($games['Data']["Game"]["Co-op"])) {
		$games['Data']["Game"]["Coop"] = $games['Data']["Game"]["Co-op"];
	} else {
		$games['Data']["Game"]["Coop"] = "N/A";
	}
	if (!isset($games['Data']["Game"]["Players"])) {
		$games['Data']["Game"]["Players"] = "N/A";
	}

	$games = XML2Array::createArray($xml);
	if (!file_exists('art/boxartthumb/'.$id.'.jpg')) {
		$games['Data']["Game"]["Images"]["noimage"] = "no image";
		$image = new SimpleImage(); 
		
		//print_r(is_array($games['Data']["Game"]["Images"]));
		if (is_array($games['Data']["Game"]["Images"])) {
			//echo "id ".$id.":".is_array($games['Data']["Game"]["Images"])."\n\n";
			if (isset($games['Data']["Game"]["Images"]["boxart"][0]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"][1]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
				//echo $urlstring;
				//print_r($games);
				/*$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);*/
				//$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/'.$id.'.jpg';
			} elseif(isset($games['Data']["Game"]["Images"]["boxart"]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
				//echo $urlstring;
				//print_r($games);
				/*$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);*/
				
			} elseif(isset($games['Data']["Game"]["Images"]["fanart"][0]["thumb"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["fanart"][0]["thumb"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
				//echo $urlstring;
				//print_r($games);
				/*$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);*/
				
			} elseif(isset($games['Data']["Game"]["Images"]["clearlogo"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["clearlogo"]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
				//echo $urlstring;
				//print_r($games);
				/*$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);*/
				
			} else {
				$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/noimage.jpg';
				$games['Data']["Game"]["Images"]["noimage"] = "";
				echo json_encode($games); 
			}
		} else {
			//echo "id ".$id.":".is_array($games['Data']["Game"]["Images"])."\n\n";
			$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/noimage.jpg';
			$games['Data']["Game"]["Images"]["noimage"] = "";
			echo json_encode($games); 
		}
		
	} elseif(file_exists('art/boxartthumb/'.$id.'.jpg')) {
		$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/'.$id.'.jpg';
		echo json_encode($games); 
	}
}

?>