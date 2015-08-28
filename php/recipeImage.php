<?php
	require_once('db.php');
	require_once('imageManipulator.php');

  $id       = $_GET['id'];
	$recipeId = $_GET['recipeId'];
  
  if (!isset($_GET['action'])) {
    if (!empty($_FILES)) {
      $recipeImageId = $db->querySingle('SELECT Z_PK FROM ZRECIPEIMAGE ORDER BY Z_PK DESC LIMIT 1')+1;

      $validExtensions = array('.jpg', '.jpeg', '.gif', '.png', '.JPG', '.JPEG');
        // get extension of the uploaded file
        $fileExtension = strrchr($_FILES['file']['name'], ".");
        // check if file Extension is on the list of allowed ones
        if (in_array($fileExtension, $validExtensions)) {
            $newNamePrefix = time() . '_';
            $manipulator = new ImageManipulator($_FILES['file']['tmp_name']);
   
            $db->exec('INSERT INTO ZRECIPEIMAGE (ZURL, ZRECIPE) VALUES ("images/'. $recipeImageId . $fileExtension .'", '.$recipeId.')');

            // saving file to uploads folder
            $manipulator->save('../images/' . $recipeImageId . $fileExtension);

            echo json_encode(array(
              'id'  =>  $recipeImageId,
              'url' =>  'images/'. $recipeImageId . $fileExtension,
              'alt' =>  ''
            ));
        } else {
        } 
      }
  } else {
    switch ($_GET['action']) {
      case 'select':
        $recipeImage = $db->querySingle('SELECT * FROM ZRECIPEIMAGE WHERE ZRECIPE='.$recipeId.' and ZPRESENTATION=1', true);
        if (count($recipeImage) > 0) {
          $db->exec('UPDATE ZRECIPEIMAGE SET ZPRESENTATION=0 WHERE Z_PK='.$recipeImage['Z_PK']);
        }
        
        $db->exec('UPDATE ZRECIPEIMAGE SET ZPRESENTATION=1 WHERE Z_PK='.$id);

        $recipeImageResults = $db->query('SELECT * FROM ZRECIPEIMAGE WHERE ZRECIPE = '.$recipeId);
        $recipeImages = array();

        while ($recipeImage = $recipeImageResults->fetchArray()) {
          $recipeImages[] = array(
            'id'            =>  $recipeImage['Z_PK'],
            'url'           =>  $recipeImage['ZURL'],
            'alt'           =>  $recipeImage['ZALT'],
            'presentation'  =>  $recipeImage['ZPRESENTATION']
          );
        }

        echo json_encode($recipeImages);

        break;
      case 'delete':
        $imageUrl = $db->querySingle('SELECT ZURL FROM ZRECIPEIMAGE WHERE Z_PK='.$id);
        unlink('../'.$imageUrl);

        $db->exec('DELETE FROM ZRECIPEIMAGE WHERE Z_PK='.$id);
        break;
    }
  }
	
?>