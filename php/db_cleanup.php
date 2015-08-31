<?php

if ($db !== null) {
  $db->close();
  unset($db);
}
?>
