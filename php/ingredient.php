<?php
	require_once('db.php');

	$id = $_GET['id'];
	$name = $_GET['name'];
	$spotlight = $_GET['spotlight'];

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZINGREDIENT (ZNAME, ZSPOTLIGHT) VALUES ("'.$name.'", "'.$spotlight.'")');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZINGREDIENT SET ZNAME="'.$name.'", ZSPOTLIGHT="'.$spotlight.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZRECIPEINGREDIENT WHERE ZINGREDIENT='.$id);
			$db->exec('DELETE FROM ZINGREDIENT WHERE Z_PK='.$id);
			
			echo "true";
			return;
	}

	echo json_encode(array(
		'id'		=>	$id,
		'name'		=>	$name,
		'spotlight'	=>	$spotlight
	));
?>