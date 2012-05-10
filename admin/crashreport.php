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
    $template = new Template('crashreport_row.tpl');
    
    $template->set('entryid',     $this->row['entryid']);
    $template->set('productid',   $this->row['productid']);
    $template->set('version',     $this->row['version']);
    $template->set('dumpfile',    $this->row['dumpfile']);
    $template->set('stacktrace',  $this->row['stacktrace']);
    $template->set('description', $this->row['description']);
    $template->set('ip',          $this->row['ip']);
    $template->set('ipstr',       long2ip($this->row['ip']));
    $template->set('timestamp',   date("m/d/Y g:i A", $this->row['timestamp']));
    $template->set('iconclass',   str_replace(" ", "", $this->row['productid']));
    
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