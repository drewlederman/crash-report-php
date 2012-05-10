<?php

require('database.php');

if (!isset($_GET['entryids']))
  die("No entryids provided!");


$db = new Database();

// Delete dump files

$query = "SELECT entries.dumpfile FROM entries " .
         "WHERE entryid in (" . mysql_real_escape_string($_GET['entryids']) . ");";

$results = $db->query($query);

if ($results !== NULL) {
  foreach ($results as $file) {
    unlink($file);
  }
}


// Delete database record

$query = "DELETE FROM entries " .
         "WHERE entryid in (" .mysql_real_escape_string($_GET['entryids']). ");";

$db->query($query);
  
?>
