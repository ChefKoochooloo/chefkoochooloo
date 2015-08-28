<?php
	require_once('db.php');

	$id 	= $_GET['id'];
	$name 	= $_GET['name'];
	$url 	= $_GET['url'];

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZTOOL (ZNAME, ZURL) VALUES ("'.$name.'", "'.$url.'")');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZTOOL SET ZNAME="'.$name.'", ZURL="'.$url.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZRECIPETOOL WHERE ZTOOL='.$id);
			$db->exec('DELETE FROM ZTOOL WHERE Z_PK='.$id);

			echo "true";
			return;
	}

	echo json_encode(array(
		'id'		=>	$id,
		'name'		=>	$name,
		'url'		=>	$url
	));
?>