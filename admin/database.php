<?php

include('databasedefines.php');

class Database {

  public function __construct() { }
  
  public function open() {
    $con = mysql_connect(DB_HOST, DB_USER, DB_PASS)
      or die("Couldn't connect to MySql! " . mysql_error());

    mysql_select_db(DB_NAME, $con)
      or die("Couldn't select database! " . mysql_error());
     
    return $con;
  }
  
  public function close($con) {
    mysql_close($con);
  }
  
  public function query($sql) {
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

}

?>