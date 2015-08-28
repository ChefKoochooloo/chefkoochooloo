<?php
  print_r(SQLite3::version());

	require_once('db.php');

	$toolResults = $db->query('SELECT * FROM ZTOOL ORDER BY ZNAME ASC');
	$tools = array();

  	while ($tool = $toolResults->fetchArray()) {
  		$tools[] = array(
  			'id'		=>	$tool['Z_PK'],
  			'name'	=>	$tool['ZNAME'],
  			'url'	  =>	$tool['ZURL']
  		);
  	}

  	echo json_encode($tools);

	$db->close();
?>
