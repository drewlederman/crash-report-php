<?php 
  include('common.php');
  require('crashreport.php');
  
  $productid = isset($_GET['productid']) ? $_GET['productid'] : "";
  $version   = isset($_GET['version']) ? $_GET['version'] : "";
  $ip        = isset($_GET['ip']) ? $_GET['ip'] : "";
  $sort      = isset($_GET['sort']) ? $_GET['sort'] : "";

  $isfiltered = ($productid != "" || $version != "" || $ip != "");
  
  $crashreports = GetCrashReports($productid, $version, $ip, $sort);
  
  $count = count($crashreports);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<script type='text/javascript' src='crashreport.js'></script>
<script type='text/javascript' src='jquery-1.7.1.js'></script>

<link type='text/css' rel="stylesheet" href='report.css'>
<link rel='icon' type='image/ico' href='favicon.ico'>

<body>

  <table>
      <tr>
        <?php if ($count != 0) : ?>
          <th><input type='checkbox' onclick='javascript:selectAll(this)'></input></th>
        <?php else : ?>
          <th></th>
        <?php endif ?>
        <th class='sortable' onclick='javascript:sort("productid");'>Product ID</th>
        <th class='sortable' onclick='javascript:sort("version");'>Version</th>
        <th class='sortable' onclick='javascript:sort("ip");'>IP Address</th>
        <th class='sortable' onclick='javascript:sort("timestamp");'>Date</th>
        <th></th>
        <th></th>
      </tr>
      
      <?php if ($count == 0) : ?>
        <tr><td colspan='7' align='middle'>No crash reports</td></tr>
      <?php endif ?>
      
      <?php 
        foreach($crashreports as $report) {
          $report->EchoHTML();
        }
      ?>
  </table>
  
  <br>
  
  <div class='massaction_panel'>
    Mass action:
    <br><br>
    <span title='Download' class='download icon' onclick='javascript:massActionDownload();'></span>
    <span title='Delete' class='delete icon' onclick='javascript:massActionDelete();'></span>
  </div>
  
  <?php if ($isfiltered) : ?>
    <br><a href=index.php>Clear filter</a>
  <?php endif ?>

</body>

</html>
