<?php

$productid = $_POST['productid'];
$version   = $_POST['version'];

$success = false;

$filename = './'.uniqid().'.zip';

if (move_uploaded_file($_FILES['debugfiles']['tmp_name'], $filename)) {
  $zip = new ZipArchive();
  if ($zip->open($filename) === TRUE) {
    $dir = "../symbols/$productid/$version/";
    if (!is_dir($dir)) {
      mkdir($dir, 0, true);
    }
    $success = $zip->extractTo($dir);
    $zip->close();
  }
  unlink($filename);
}

if ($success == false) {
  header('HTTP/1.1 500 Internal server error');
}

?>