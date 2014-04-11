<?php

/*
	functions for saving an validating user profile images
*/

// function found at http://php.about.com/od/advancedphp/ss/rename_upload_2.htm by Angela Bradley
function findexts ($filename) { 
	$filename = strtolower($filename) ; 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1; 
	$exts = $exts[$n]; 
	return $exts; 
}

// remove image file from server directory and database entry
function removeimage($username, $userID, $mysqli) {
	$oldImagePath = (glob("./art/userimages/".$username.".*"));
	if (!empty($oldImagePath)) {
		unlink($oldImagePath[0]);
		$insert_stmt = $mysqli->prepare("UPDATE `userdata` SET `img`='No image' WHERE id=?");
        $insert_stmt->bind_param('s', $userID);
        if ($insert_stmt->execute()) {
        	return true;        
        }
		
	} else {
		return false;
	}
}

//	validates and saves file to server directory
// parts of function from http://www.sitepoint.com/file-uploads-with-php/ by Timothy Boronczyk
function validateAndSaveFile($file, $username, $userID, $mysqli) {
	define("UPLOAD_DIR", "./art/userimages/");
	 
	if (!empty($file["img"])) {

		// verify the file is a GIF, JPEG, or PNG
		$fileType = exif_imagetype($file["img"]["tmp_name"]);
		$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
		if (in_array($fileType, $allowed)) {

			$uplFile = $file["img"];

			if ($uplFile["error"] !== UPLOAD_ERR_OK) {
			    echo "An error occurred";
			    exit;
			}

			$fileExt = findexts($file['img']['name']);
			$name = $username.'.'.$fileExt;
			
			// deletes old if exists 
			removeimage($username, $userID, $mysqli);

			if (!move_uploaded_file($uplFile["tmp_name"], UPLOAD_DIR . $name)) { 
			    echo "Unable to save file";
			    exit;
			}

			// set proper permissions on the new file
			chmod(UPLOAD_DIR . $name, 0644);
			return $name;
		} elseif(!in_array($fileType, $allowed)){
		}
	} else {
	}
}