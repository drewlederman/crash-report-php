<?php

require_once('database.php');
require_once('template.php');

class CrashReport 
{
  private $row = array();
  
  public function __construct($row) {
    $this->row = $row;
  }
  
  private function GetEntryID() {
    return $this->row['entryid'];
  }
  
  private function GetProductID() {
    return $this->row['productid'];
  }
  
  private function GetVersion() {
    return $this->row['version'];
  }
  
  private function GetIP() {
    return long2ip($this->row['ip']);
  }
  
  private function GetDumpfile() {
    return $this->row['dumpfile'];
  }
  
  private function GetStackTrace() {
    return str_replace("\n", '<br>', htmlspecialchars($this->row['stacktrace']));
  }
  
  private function GetDescription() {
    return htmlspecialchars($this->row['description']);
  }
  
  private function GetTimeStamp() {
    return date("g:i A m/d/Y", $this->row['timestamp']);
  }
  
  private function GetIconClass() {
    return str_replace(" ", "", $this->row['productid']);
  }
  
  
  public function EchoHTML() {
    $template = new Template('templates/crashreport_row.tpl');
        
    $template->set('entryid',     $this->GetEntryID());
    $template->set('productid',   $this->GetProductID());
    $template->set('version',     $this->GetVersion());
    $template->set('dumpfile',    $this->GetDumpFile());
    $template->set('stacktrace',  $this->GetStackTrace());
    $template->set('description', $this->GetDescription());
    $template->set('ipstr',       $this->GetIP());
    $template->set('timestamp',   $this->GetTimeStamp());
    $template->set('iconclass',   $this->GetIconClass());
    
    echo $template->output();
  }
  
  public function EchoMobileHTML() {
    $template = new Template('templates/crashreport_row_mobile.tpl');
    
    $template->set('entryid',     $this->GetEntryID());
    $template->set('productid',   $this->GetProductID());
    $template->set('version',     $this->GetVersion());
    $template->set('stacktrace',  $this->GetStackTrace());
    $template->set('description', $this->GetDescription());
    
    echo $template->output();
  }

}

function GetCrashReports($productid, $version, $ip, $sortby) {  
  $db = new Database();
  
  $sortcolumn = ($sortby == '' ? 'timestamp' : $sortby);
  
  $query = "SELECT * " .
           "FROM entries " .
           "WHERE 1 " .
           ($productid != "" ? "AND productid = '{$productid}' " : "") .
           ($version != "" ? "AND version = '{$version}' " : "") .
           ($ip != "" ? "AND ip = '{$ip}' " : "") .
           "ORDER BY entries.{$sortcolumn} DESC;";

  $reports = array();       
         
  if ($results = $db->query($query)) {
    foreach($results as $res) {
      $rep = new CrashReport($res);
      $reports[] = $rep;
    }
  }
  
  return $reports;
}

?>