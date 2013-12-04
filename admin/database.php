<?php

include_once('databasedefines.php');
require_once('crashreport.php');

class Database {

  public function __construct() { }
  
  function open() {
    $con = mysql_connect(DB_HOST, DB_USER, DB_PASS)
      or die("Couldn't connect to MySql! " . mysql_error());

    mysql_select_db(DB_NAME, $con)
      or die("Couldn't select database! " . mysql_error());
     
    return $con;
  }
  
  function close($con) {
    mysql_close($con);
  }
  
  function query($sql) {
    $con = $this->open();
    
    $res = mysql_query($sql);
    if ($res) {
      if (strpos($sql, 'SELECT') === false) {
        return true;
      }
    }
    else {
      if (strpos($sql, 'SELECT') === false) {
        return false;
      }
      else {
        return null;
      }
    }
    
    $results = array();
    
    while ($row = mysql_fetch_assoc($res)) {
      $results[] = $row;
    }
    
    $this->close($con);
    
    return $results;
  }
  
  public function InsertCrashReport($productid, $version, $dumpfile, $timestamp, $ipaddress, $stacktrace, $description) {
      $productid   = mysql_real_escape_string($productid);
      $version     = mysql_real_escape_string($version);
      $dumpfile    = mysql_real_escape_string($dumpfile);
      $timestamp   = mysql_real_escape_string($timestamp);
      $ipaddress   = mysql_real_escape_string($ipaddress);
      $stacktrace  = mysql_real_escape_string($stacktrace);
      $description = mysql_real_escape_string($description);
      
      $query = "INSERT INTO entries (productid, version, dumpfile, stacktrace, description, timestamp, ip) VALUES ('$productid', '$version', '$dumpfile', '$stacktrace', '$description', '$timestamp', '$ipaddress');";
      
      return $this->query($query);
  }
  
  public function GetCrashReports($productid, $version, $ip, $sortby) {  
    $sortcolumn = ($sortby == '' ? 'timestamp' : $sortby);

    $query = "SELECT * " .
             "FROM entries " .
             "WHERE 1 " .
             ($productid != "" ? "AND productid = '{$productid}' " : "") .
             ($version != "" ? "AND version = '{$version}' " : "") .
             ($ip != "" ? "AND ip = '{$ip}' " : "") .
             "ORDER BY entries.{$sortcolumn} DESC;";

    $reports = array();       

    if ($results = $this->query($query)) {
      foreach($results as $res) {
        $rep = new CrashReport($res);
        $reports[] = $rep;
      }
    }

    return $reports;
  }
  
  public function DeleteCrashReports($reportids) {
      $query = "DELETE FROM entries WHERE entryid in ($reportids);";

      return $this->query($query);
  }
  
  public function GetCrashReportFiles($reportids) {
      $query = "SELECT entries.dumpfile FROM entries WHERE entryid in ($reportids);";

      return $this->query($query);
  }

}

?>