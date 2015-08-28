<?php

	require_once('db.php');

    $flag = isset($_GET['flag']) ? $_GET['flag'] : '';
    $recipe = isset($_GET['recipe']) ? $_GET['recipe'] : '';

	switch ($_GET['action']) {
		case 'add':
			$db->exec('INSERT INTO ZRECIPEFLAG (ZRECIPE, ZFLAG) VALUES ('.$recipe.', '.$flag.')');
			$id = $db->lastInsertRowid();
			break;
		case 'remove':
			$db->exec('DELETE FROM ZRECIPEFLAG WHERE ZRECIPE='.$recipe.' and ZFLAG='.$flag);
			break;
	}

	$recipeFlagResults = $db->query('SELECT * FROM ZRECIPEFLAG WHERE ZRECIPE='.$recipe);
	$recipeFlags = array();

	while ($recipeFlag = $recipeFlagResults->fetchArray()) {
		$recipeFlags[] = array(
			'id'		=>	$recipeFlag['Z_PK'],
			'recipe'	=>	$recipeFlag['ZRECIPE'],
			'flag'		=> 	$recipeFlag['ZFLAG']
		);
	}
	echo json_encode($recipeFlags);

?>