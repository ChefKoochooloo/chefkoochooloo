<?php
	require_once('db.php');

	$countryCode = $_GET['country'];
	if (!isset($countryCode)) {
		return;
	}

	$country = $db->querySingle('SELECT Z_PK FROM ZCOUNTRY WHERE ZCODE = "'.$countryCode.'"', true);

	$recipeResults = $db->query('SELECT * FROM ZRECIPE WHERE ZCOUNTRY = '.$country['Z_PK']);
	$recipes = array();

  	while ($recipe = $recipeResults->fetchArray()) {
  		//RECIPE IMAGES
  		$recipeImageResults = $db->query('SELECT * FROM ZRECIPEIMAGE WHERE ZRECIPE = '.$recipe['Z_PK']);
		$recipeImages = array();

		while ($recipeImage = $recipeImageResults->fetchArray()) {
			$recipeImages[] = array(
				'id'            =>  $recipeImage['Z_PK'],
           		'url'           =>  $recipeImage['ZURL'],
            	'alt'           =>  $recipeImage['ZALT'],
            	'presentation'  =>  $recipeImage['ZPRESENTATION']
			);
		}

		//RECIPE STEPS
		$recipeStepResults = $db->query('SELECT * FROM ZRECIPESTEP WHERE ZRECIPE = '.$recipe['Z_PK'].' ORDER BY ZORDER ASC');
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
		$recipeIngredientResults = $db->query('SELECT * FROM ZRECIPEINGREDIENT WHERE ZRECIPE = '.$recipe['Z_PK']);
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
		$recipeToolResults = $db->query('SELECT * FROM ZRECIPETOOL WHERE ZRECIPE = '.$recipe['Z_PK']);
		$recipeTools = array();

		while ($recipeTool = $recipeToolResults->fetchArray()) {
			$recipeTools[] = array(
				'id'	=>	$recipeTool['Z_PK'],
				'tool'	=>	$recipeTool['ZTOOL']
			);
		}

  		// 
  		$recipes[] = array(
  			'id'			=>	$recipe['Z_PK'],
  			'name'			=>	$recipe['ZNAME'],
  			'type'			=>	$recipe['ZTYPE'],
  			'presentation'	=>	$recipe['ZPRESENTATION'],
  			'time'			=>	$recipe['ZTIME'],

  			'images'		=> 	$recipeImages,
  			'steps'			=>	$recipeSteps,
  			'ingredients'	=>	$recipeIngredients,
  			'tools'			=>	$recipeTools
  		);
  	}

  	echo json_encode($recipes);

	$db->close();
?>
