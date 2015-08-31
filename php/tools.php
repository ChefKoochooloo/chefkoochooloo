<?php

require_once('db.php');

try {
  $toolResults = $db->query('SELECT * FROM ZTOOL ORDER BY ZNAME ASC');
  $tools = array();

  while ($tool = $toolResults->fetchArray()) {
    $tools[] = array(
        'id' => $tool['Z_PK'],
        'name' => $tool['ZNAME'],
        'url' => $tool['ZURL']
    );
  }

  echo json_encode($tools);
} finally {
  include 'db_cleanup.php';
}
?>
