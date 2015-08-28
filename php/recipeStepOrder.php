<?php

require_once('db.php');

$recipeResults = $db->query('SELECT * FROM ZRECIPE');

while ($recipe = $recipeResults->fetchArray()) {
	$recipeStepResults = $db->query('SELECT * FROM ZRECIPESTEP WHERE ZRECIPE = '.$recipe['Z_PK'].' ORDER BY Z_PK ASC');

	$index = 0;
	while ($recipeStep = $recipeStepResults->fetchArray()) {
		$db->exec('UPDATE ZRECIPESTEP SET ZORDER='.$index.' WHERE Z_PK='.$recipeStep['Z_PK']);

		$index++;
	}
}

echo "reset of step order done...";

?>