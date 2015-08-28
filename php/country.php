<?php
	require_once('db.php');

	$id 			= $_GET['id'];
	$wish 			= $_GET['wish'];
	$capital 		= $_GET['capital'];
	$population 	= $_GET['population'];
	$languages 		= $_GET['languages'];

	switch ($_GET['action']) {
		case 'update':
			$db->exec('UPDATE ZCOUNTRY SET ZWISH="'.$wish.'", ZCAPITAL="'.$capital.'", ZPOPULATION='.$population.', ZLANGUAGES="'.$languages.'" WHERE Z_PK='.$id);
			break;
	}

	if (!isset($id)) {
		return;
	}

	$country = $db->querySingle('SELECT * FROM ZCOUNTRY WHERE Z_PK='.$id, true);

	$countryFactsResults = $db->query('SELECT * FROM ZCOUNTRYFACT WHERE ZCOUNTRY = '.$id);
	$countryFacts = array();

	while ($countryFact = $countryFactsResults->fetchArray()) {
		$countryFacts[] = array(
			'id'	=>	$countryFact['Z_PK'],
			'fact'	=>	$countryFact['ZFACT']
		);
	}

	$countryIssuesResults = $db->query('SELECT * FROM ZCOUNTRYCHARITY WHERE ZCOUNTRY = '.$id);
	$countryIssues = array();

	while ($countryIssue = $countryIssuesResults->fetchArray()) {
		$countryIssues[] = array(
			'id'	=>	$countryIssue['Z_PK'],
			'url'	=> 	$countryIssue['ZURL'],
			'issue'	=>	$countryIssue['ZDONATE']
		);
	}

	echo json_encode(array(
		'id'			=>	$id,
		'code'			=>	$country['ZCODE'],
		'name'			=>	$country['ZNAME'],
		'cover'			=>	$country['ZCOVER'],
		'flag'			=>	$country['ZFLAG'],
		'wish'			=> 	$country['ZWISH'],
		'capital'		=> 	$country['ZCAPITAL'],
		'population'	=> 	$country['ZPOPULATION'],
		'languages'		=> 	$country['ZLANGUAGES'],
		'facts'			=>	$countryFacts,
		'issues'		=>	$countryIssues
	));
?>