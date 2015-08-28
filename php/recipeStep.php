<?php
	require_once('db.php');

    $id = isset($_GET['id']) ? $_GET['id'] : '';
    $order = isset($_GET['order']) ? $_GET['order'] : '';
    $in_type = isset($_GET['type']) ? $_GET['type'] : '';
    $label = isset($_GET['label']) ? $_GET['label'] : '';
    $recipe = isset($_GET['recipe']) ? $_GET['recipe'] : '';

	switch ($_GET['action']) {
		case 'insert':
			$db->exec('INSERT INTO ZRECIPESTEP (ZORDER, ZTYPE, ZLABEL, ZRECIPE) VALUES ('.$order.', '.$in_type.', "'.$label.'", '.$recipe.')');
			$id = $db->lastInsertRowid();
			break;
		case 'update':
			$db->exec('UPDATE ZRECIPESTEP SET ZORDER='.$order.', ZTYPE='.$in_type.', ZLABEL="'.$label.'" WHERE Z_PK='.$id);
			break;
		case 'delete':
			$db->exec('DELETE FROM ZRECIPESTEP WHERE Z_PK='.$id);
			return;
	}

	if (!isset($id)) {
		return;
	}

	$step = $db->querySingle('SELECT * FROM ZRECIPESTEP WHERE Z_PK='.$id, true);

	echo json_encode(array(
		'id'		=>	$id,
		'order'		=> 	$step['ZORDER'],
		'type'		=>	$step['ZTYPE'],
		'label'		=>	$step['ZLABEL'],
		'recipe'	=>	$step['ZRECIPE']
	));
?>