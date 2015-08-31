<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = new SQLite3('../Koochooloo.sqlite', SQLITE3_OPEN_READWRITE);
$db->busyTimeout(5000);

# var_dump($db);
?>
