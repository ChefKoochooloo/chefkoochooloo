<?php
	require_once('db.php');

	$countryResults = $db->query('SELECT * FROM ZCOUNTRY ORDER BY ZNAME ASC');
	$countries = array();

	while ($country = $countryResults->fetchArray()) {
		$countryFactsResults = $db->query('SELECT * FROM ZCOUNTRYFACT WHERE ZCOUNTRY = '.$country['Z_PK']);
		$countryFacts = array();

		while ($countryFact = $countryFactsResults->fetchArray()) {
			$countryFacts[] = array(
				'id'	=>	$countryFact['Z_PK'],
				'fact'	=>	$countryFact['ZFACT']
			);
		}

		$countryIssuesResults = $db->query('SELECT * FROM ZCOUNTRYCHARITY WHERE ZCOUNTRY = '.$country['Z_PK']);
		$countryIssues = array();

		while ($countryIssue = $countryIssuesResults->fetchArray()) {
			$countryIssues[] = array(
				'id'	=>	$countryIssue['Z_PK'],
				'url'	=> 	$countryIssue['ZURL'],
				'issue'	=>	$countryIssue['ZDONATE']
			);
		}

		$countries[] = array(
			'id'			=>	$country['Z_PK'],
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
		);
	}

	echo json_encode($countries);
?>