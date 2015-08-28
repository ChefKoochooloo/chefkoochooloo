<?php
	require_once('db.php');

	$id 		= $_GET['id'];
	$country	= $_GET['country'];
	$fact		= $_GET['fact'];

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZCOUNTRYFACT (ZCOUNTRY, ZFACT) VALUES ('.$country.', "'.$fact.'")');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZCOUNTRYFACT SET ZFACT="'.$fact.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZCOUNTRYFACT WHERE Z_PK='.$id);
			return;
	}

	if (!isset($id)) {
		return;
	}

	$fact = $db->querySingle('SELECT * FROM ZCOUNTRYFACT WHERE Z_PK='.$id, true);

	echo json_encode(array(
		'id'		=>	$id,
		'country'	=> 	$fact['ZCOUNTRY'],
		'fact'		=>	$fact['ZFACT']
	));
?>