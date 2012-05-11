<?php

require_once('database.php');
require_once('template.php');

class CrashReport 
{
  private $row = array();
  
  public function __construct($row) {
    $this->row = $row;
  }
  
  public function EchoHTML() {
    $template = new Template('templates/crashreport_row.tpl');
    
    $stacktrace  = str_replace("\n", '<br>', htmlspecialchars($this->row['stacktrace']));
    $description = htmlspecialchars($this->row['description']);
    $ipstr       = long2ip($this->row['ip']);
    $timestamp   = date("g:i A m/d/Y", $this->row['timestamp']);
    $iconclass   = str_replace(" ", "", $this->row['productid']);
    
    $template->set('entryid',     $this->row['entryid']);
    $template->set('productid',   $this->row['productid']);
    $template->set('version',     $this->row['version']);
    $template->set('dumpfile',    $this->row['dumpfile']);
    $template->set('ip',          $this->row['ip']);
    $template->set('stacktrace',  $stacktrace);
    $template->set('description', $description);
    $template->set('ipstr',       $ipstr);
    $template->set('timestamp',   $timestamp);
    $template->set('iconclass',   $iconclass);
    
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