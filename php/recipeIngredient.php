<?php
	require_once('db.php');

	$id 			= $_GET['id'];
	$ingredient		= $_GET['ingredient'];
	$recipe 		= $_GET['recipe'];
	$unit 			= $_GET['unit'];
	$amount 		= $_GET['amount'];

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZRECIPEINGREDIENT (ZINGREDIENT, ZRECIPE, ZUNIT, ZAMOUNT) VALUES ('.$ingredient.', '.$recipe.', '.$unit.', '.$amount.')');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZRECIPEINGREDIENT SET ZINGREDIENT='.$ingredient.', ZRECIPE='.$recipe.', ZUNIT='.$unit.', ZAMOUNT='.$amount.' WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZRECIPEINGREDIENT WHERE Z_PK='.$id);
			return;
	}

	if (!isset($id)) {
		return;
	}

	$ingredient = $db->querySingle('SELECT * FROM ZRECIPEINGREDIENT WHERE Z_PK='.$id, true);

	echo json_encode(array(
		'id'			=>	$id,
		'ingredient'	=>	$ingredient['ZINGREDIENT'],
		'recipe'		=>	$ingredient['ZRECIPE'],
		'unit'			=>	$ingredient['ZUNIT'],
		'amount'		=>	$ingredient['ZAMOUNT']
	));
?>