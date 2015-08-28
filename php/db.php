<?php
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	$db = new SQLite3('../Koochooloo.sqlite', SQLITE3_OPEN_READWRITE);
        # var_dump($db);
?>
