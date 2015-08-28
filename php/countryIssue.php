<?php
	require_once('db.php');

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $country = isset($_GET['country']) ? $_GET['country'] : '';
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    $issue = isset($_GET['issue']) ? $_GET['issue'] : '';

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZCOUNTRYCHARITY (ZCOUNTRY, ZURL, ZDONATE) VALUES ('.$country.', "'.$url.'", "'.$issue.'")');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZCOUNTRYCHARITY SET ZURL="'.$url.'", ZDONATE="'.$issue.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZCOUNTRYCHARITY WHERE Z_PK='.$id);
			return;
	}

	if (!isset($id)) {
		return;
	}

	$fact = $db->querySingle('SELECT * FROM ZCOUNTRYCHARITY WHERE Z_PK='.$id, true);

	echo json_encode(array(
		'id'		=>	$id,
		'country'	=> 	$fact['ZCOUNTRY'],
		'url'		=>	$fact['ZURL'],
		'issue'		=>	$fact['ZDONATE']
	));
?>