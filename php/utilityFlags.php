<?php

	$db = new SQLite3('../Koochooloo.sqlite');

	$countriesResults = $db->query('SELECT * FROM ZCOUNTRY');
	while ($country = $countriesResults->fetchArray()) {
		$db->exec('UPDATE ZCOUNTRY SET ZFLAG="images/'.$country['ZCODE'].'_flag.png" WHERE Z_PK='.$country['Z_PK']);
	}
?>