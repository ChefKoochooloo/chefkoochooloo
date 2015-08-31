<?php

require_once('db.php');

try {
  $id = isset($_GET['id']) ? $_GET['id'] : '';
  $ingredient = isset($_GET['ingredient']) ? $_GET['ingredient'] : '';
  $recipe = isset($_GET['recipe']) ? $_GET['recipe'] : '';
  $unit = isset($_GET['unit']) ? $_GET['unit'] : '';
  $amount = isset($_GET['amount']) ? $_GET['amount'] : '';

  switch ($_GET['action']) {
    case 'insert':
      $db->exec('INSERT INTO ZRECIPEINGREDIENT (ZINGREDIENT, ZRECIPE, ZUNIT, ZAMOUNT) VALUES (' . $ingredient . ', ' . $recipe . ', ' . $unit . ', ' . $amount . ')');
      $id = $db->lastInsertRowid();
      break;
    case 'update':
      $db->exec('UPDATE ZRECIPEINGREDIENT SET ZINGREDIENT=' . $ingredient . ', ZRECIPE=' . $recipe . ', ZUNIT=' . $unit . ', ZAMOUNT=' . $amount . ' WHERE Z_PK=' . $id);
      break;
    case 'delete':
      $db->exec('DELETE FROM ZRECIPEINGREDIENT WHERE Z_PK=' . $id);
      return;
  }

  if (!isset($id)) {
    return;
  }

  $ingredient = $db->querySingle('SELECT * FROM ZRECIPEINGREDIENT WHERE Z_PK=' . $id, true);

  echo json_encode(array(
      'id' => $id,
      'ingredient' => $ingredient['ZINGREDIENT'],
      'recipe' => $ingredient['ZRECIPE'],
      'unit' => $ingredient['ZUNIT'],
      'amount' => $ingredient['ZAMOUNT']
  ));
} finally {
  include 'db_cleanup.php';
}
?>