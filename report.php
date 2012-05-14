<?php

require('admin/database.php');

if (!CreateReport()) {
  header('HTTP/1.1 500 Internal server error');
  
  error_log('Failed to process crash dump!');
  
  die();
}

function CreateReport() {

  // Copy zip file from tmp
  
  $file = CopyTempFile();
  if ($file === false) {
    return false;
  }
      
  // Process report
  
  $report = ProcessReport($file);
  
  $stacktrace  = $report ? $report['stacktrace']  : '';
  $description = $report ? $report['description'] : '';
      
  // Insert entry into database

  $db = new Database();
  
  $db->open();

  $productid = mysql_real_escape_string($_POST['productid']);
  $version   = mysql_real_escape_string($_POST['version']);
  $dumpfile  = mysql_real_escape_string(pathinfo($file, PATHINFO_BASENAME));
  $timestamp = mysql_real_escape_string($_SERVER['REQUEST_TIME']);
  $iplong    = mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR']));
  $stack     = mysql_real_escape_string($stacktrace);
  $desc      = mysql_real_escape_string($description);

  $query = "INSERT INTO entries (productid, version, dumpfile, stacktrace, description, timestamp, ip) VALUES ('$productid', '$version', '$dumpfile', '$stack', '$desc', '$timestamp', '$iplong');";
  
  return $db->query($query);
}

function CopyTempFile() {
  if (!isset($_FILES['crashrpt']['tmp_name'])) {
    return false;
  }

  $tmpfile = $_FILES['crashrpt']['tmp_name'];
  $newfile = "./admin/files/".uniqid().".zip";

  if (!copy($tmpfile, $newfile)) {
    return false;
  }
    
  return $newfile;
}

function Unzip($file) {
  $zip = new ZipArchive();
  if ($zip->open($file) === TRUE) {
    $dir = './admin/files/temp/' . pathinfo($file, PATHINFO_FILENAME);
    $zip->extractTo($dir);
    $zip->close();
    return $dir;
  }
  return null;
}

function ProcessReport($file) {
  $dir = Unzip($file);
  if ($dir === null) {
    return null;
  }
    
  $res = array();

  // Pull the description out of the zip
  $res['description'] = GetDescription($dir);
  
  // Pull the stack trace out of the crash dump
  $res['stacktrace'] = GetStackTrace($dir);
  
  // Delete the unzipped report
  DeleteFolder($dir);

  return $res;
}

function GetDescription($dir) {
  $file = $dir . '/Description.txt';
  if (file_exists($file)) {
    return file_get_contents($file);
  }
  else {
    return '';
  }
}

function GetStackTrace($dir) {
  $files = glob($dir . '/*.dmp');
  if (isset($files[0])) {
    $dbgpath = ".\\admin\\debug\\{$_POST['productid']}\\{$_POST['version']}\\";
    $sympath = 'SRV*.\\admin\\debug\\cache\\*http://msdl.microsoft.com/download/symbols/;' . str_replace(' ', '', $dbgpath);
        
    $output = shell_exec("cdb -z $files[0] -c .ecxr;kcn;q -y $sympath");
    
    return StripStackTrace($output);
  }
  else {
    return '';
  }
}

function StripStackTrace($output) {
  $pattern = '/Stack trace for last set context - \.thread\/\.cxr resets it\n # \n(.*)quit:\n\z/s';
  $matches = array();
  $nummatches = preg_match($pattern, $output, $matches);
  if ($nummatches == 0) {
    return 'Stack trace unavailable.';
  }
  return $matches[1];
}

function DeleteFolder($dir) {
  $files = glob($dir . '/*.*');
  foreach ($files as $file) {
    unlink($file);
  }
  rmdir($dir);
}

?>
