<?php

require('database.php');

if (!isset($_GET['entryids']))
  die("No entryids provided!");
$entryids = $_GET['entryids'];

$db = new Database();

// Delete dump files

$results = $db->GetCrashReportFiles($entryids);

if ($results !== NULL) {
  foreach ($results as $row) {
    $file = "./files/{$row['dumpfile']}";
    unlink($file);
  }
}

// Delete database record

$db->DeleteCrashReports($entryids);
  
?>
