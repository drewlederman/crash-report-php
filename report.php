<?php

require_once('admin/database.php');

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
  if ($report === null) {
      return false;
  }
      
  // Insert entry into database
 
  $productid   = $_POST['productid'];
  $version     = $_POST['version'];
  $dumpfile    = pathinfo($file, PATHINFO_BASENAME);
  $timestamp   = $_SERVER['REQUEST_TIME'];
  $ipaddress   = ip2long($_SERVER['REMOTE_ADDR']);
  $stacktrace  = $report['stacktrace'];
  $description = $report['description'];

  $db = new Database();
  
  return $db->InsertCrashReport($productid, $version, $dumpfile, $timestamp, $ipaddress, $stacktrace, $description);
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
    $dbgpath = str_replace(' ', '', ".\\admin\\debug\\{$_POST['productid']}\\{$_POST['version']}\\");
    $sympath = 'SRV*.\\admin\\debug\\cache\\*http://msdl.microsoft.com/download/symbols/;' . $dbgpath;
        
    $output = shell_exec("cdb -z $files[0] -c .ecxr;kcn;q -y $sympath");
    
    return StripStackTrace($output);
  }
  else {
    return 'Dump file not found.';
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
