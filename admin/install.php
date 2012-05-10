<?php

$con = mysql_connect('localhost', 'root', '35yw46uje5jtn');
if (!$con)
  die("Couldn't connect to MySql! " . mysql_error());

$query = "CREATE DATABASE IF NOT EXISTS crashreports;";

if (!mysql_query($query))
  die("MySql query failed! " . mysql_error());
  
$query = "CREATE TABLE `crashreports`.`entries` (" .
           "`entryid` INT NOT NULL AUTO_INCREMENT ," .
           "`productid` TEXT NOT NULL ," .
           "`version` TEXT NOT NULL ," .
           "`dumpfile` TEXT NOT NULL ," .
           "`stacktrace` TEXT ," .
           "`description` TEXT ," .
           "`timestamp` INT NOT NULL ," .
           "`ip` INT NOT NULL ," .
           "PRIMARY KEY ( `entryid` )" .
           ") ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
           
if (!mysql_query($query))
  die("MySql query failed! " . mysql_error());  
  
mysql_close($con);

echo "Successfully created database and tables!";

?>