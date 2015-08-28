<?php
	require_once('db.php');
	require_once('imageManipulator.php');

	$id = $_GET['id'];

	if (!empty($_FILES)) {
		$country = $db->querySingle('SELECT * FROM ZCOUNTRY WHERE Z_PK='.$id, true);

		$validExtensions = array('.jpg', '.jpeg', '.gif', '.png', '.JPG', '.JPEG');
    	// get extension of the uploaded file
   		$fileExtension = strrchr($_FILES['file']['name'], ".");
   		// check if file Extension is on the list of allowed ones
   		if (in_array($fileExtension, $validExtensions)) {
    	  	$newNamePrefix = time() . '_';
     	   	$manipulator = new ImageManipulator($_FILES['file']['tmp_name']);
 
     	   	$db->exec('UPDATE ZCOUNTRY SET ZCOVER="images/'. $country['ZCODE'] . $fileExtension .'" WHERE Z_PK='.$country['Z_PK']);

      	 	// saving file to uploads folder
      	  	$manipulator->save('../images/' . $country['ZCODE'] . $fileExtension);
   	 	} else {
    	}	
    }
?>