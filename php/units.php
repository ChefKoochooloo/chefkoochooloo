<?php

require_once('db.php');

try {
  $unitResults = $db->query('SELECT * FROM ZUNIT ORDER BY ZNAME ASC');
  $units = array();

  while ($unit = $unitResults->fetchArray()) {
    $units[] = array(
        'id' => $unit['Z_PK'],
        'name' => $unit['ZNAME']
    );
  }

  echo json_encode($units);
} finally {
  include 'db_cleanup.php';
}
?>
