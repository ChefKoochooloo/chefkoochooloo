<?php
	require_once('db.php');

	$ingredientResults = $db->query('SELECT * FROM ZINGREDIENT ORDER BY ZNAME ASC');
	$ingredients = array();

  	while ($ingredient = $ingredientResults->fetchArray()) {
  		$ingredients[] = array(
  			'id'		=>	$ingredient['Z_PK'],
  			'name'		=>	$ingredient['ZNAME'],
  			'spotlight'	=>	$ingredient['ZSPOTLIGHT'],
  		);
  	}

  	echo json_encode($ingredients);

	$db->close();
?>
