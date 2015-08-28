<?php
	require_once('db.php');

	if (isset($_GET['recipe'])) {
		$recipe = $_GET['recipe'];
	}

	//RECIPE STEPS
	$recipeStepResults = $db->query('SELECT * FROM ZRECIPESTEP WHERE ZRECIPE = '.$recipe.' ORDER BY ZORDER ASC');
	$recipeSteps = array();

	while ($recipeStep = $recipeStepResults->fetchArray()) {
		$recipeSteps[] = array(
			'id'	=>	$recipeStep['Z_PK'],
			'order'	=>	$recipeStep['ZORDER'],
			'type'	=>	$recipeStep['ZTYPE'],
			'label'	=>	$recipeStep['ZLABEL']
		);
	}
?>