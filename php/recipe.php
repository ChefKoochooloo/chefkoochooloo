<?php
	require_once('db.php');

	$id 			= $_GET['id'];
	$country 		= $_GET['country'];
	$in_type 	    = $_GET['type'];
	$name	 		= $_GET['name'];
	$presentation 	= $_GET['presentation'];
	$time 			= $_GET['time'];

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZRECIPE (ZCOUNTRY) VALUES ('.$country.')');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZRECIPE SET ZNAME="'.$name.'", ZPRESENTATION="'.$presentation.'", ZTIME="'.$time.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$recipeImageResults = $db->query('SELECT * FROM ZRECIPEIMAGE WHERE ZRECIPE = '.$id);
			while ($recipeImage = $recipeImageResults->fetchArray()) {
        		unlink('../'.$recipeImage['ZURL']);
			}

			$db->exec('DELETE FROM ZRECIPE WHERE Z_PK='.$id);
			return;
	}

	if (!isset($id)) {
		return;
	}

	$recipe = $db->querySingle('SELECT * FROM ZRECIPE WHERE Z_PK='.$id, true);

	//RECIPE FLAGS
	$recipeFlagResults = $db->query('SELECT * FROM ZRECIPEFLAG WHERE ZRECIPE='.$id);
	$recipeFlags = array();

	while ($recipeFlag = $recipeFlagResults->fetchArray()) {
		$recipeFlags[] = array(
			'id'		=>	$recipeFlag['Z_PK'],
			'recipe'	=>	$recipeFlag['ZRECIPE'],
			'flag'		=> 	$recipeFlag['ZFLAG']
		);
	}

	//RECIPE IMAGES
  	$recipeImageResults = $db->query('SELECT * FROM ZRECIPEIMAGE WHERE ZRECIPE = '.$id);
	$recipeImages = array();
	while ($recipeImage = $recipeImageResults->fetchArray()) {
		$recipeImages[] = array(
			'id'			=>	$recipeImage['Z_PK'],
			'url'			=>	$recipeImage['ZURL'],
			'alt'			=>	$recipeImage['ZALT'],
			'presentation'	=>	$recipeImage['ZPRESENTATION']
		);
	}

	//RECIPE STEPS
	$recipeStepResults = $db->query('SELECT * FROM ZRECIPESTEP WHERE ZRECIPE = '.$id.' ORDER BY ZORDER ASC');
	$recipeSteps = array();

	while ($recipeStep = $recipeStepResults->fetchArray()) {
		$recipeSteps[] = array(
			'id'	=>	$recipeStep['Z_PK'],
			'order'	=>	$recipeStep['ZORDER'],
			'type'	=>	$recipeStep['ZTYPE'],
			'label'	=>	$recipeStep['ZLABEL']
		);
	}

  	//RECIPE INGREDIENTS
	$recipeIngredientResults = $db->query('SELECT * FROM ZRECIPEINGREDIENT WHERE ZRECIPE = '.$id);
	$recipeIngredients = array();

	while ($recipeIngredient = $recipeIngredientResults->fetchArray()) {
		$recipeIngredients[] = array(
			'id'			=>	$recipeIngredient['Z_PK'],
			'ingredient'	=>	$recipeIngredient['ZINGREDIENT'],
			'unit'			=>	$recipeIngredient['ZUNIT'],
			'amount'		=>	$recipeIngredient['ZAMOUNT']
		);
	}

  	//RECIPE TOOLS
	$recipeToolResults = $db->query('SELECT * FROM ZRECIPETOOL WHERE ZRECIPE = '.$id);
	$recipeTools = array();

	while ($recipeTool = $recipeToolResults->fetchArray()) {
		$recipeTools[] = array(
			'id'	=>	$recipeTool['Z_PK'],
			'tool'	=>	$recipeTool['ZTOOL']
		);
	}

	echo json_encode(array(
		'id'			=>	$id,
		'country'		=>	$recipe['ZCOUNTRY'],
		'type'			=>	$recipe['ZTYPE'],
		'name'			=>	$recipe['ZNAME'],
		'presentation'	=>	$recipe['ZPRESENTATION'],
		'time'			=>	$recipe['ZTIME'],

		'flags'			=>	$recipeFlags,
		'images'		=> 	$recipeImages,
  		'steps'			=>	$recipeSteps,
  		'ingredients'	=>	$recipeIngredients,
  		'tools'			=>	$recipeTools
	));
?>