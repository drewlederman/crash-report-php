<?php 
  include('common.php');
  require('crashreport.php');
  
  $productid = isset($_GET['productid']) ? $_GET['productid'] : "";
  $version   = isset($_GET['version']) ? $_GET['version'] : "";
  $ip        = isset($_GET['ip']) ? $_GET['ip'] : "";
  $sort      = isset($_GET['sort']) ? $_GET['sort'] : "";
  
  $crashreports = GetCrashReports($productid, $version, $ip, $sort);
  
  $count = count($crashreports);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<script type='text/javascript' src='scripts/crashreport.js'></script>
<script type='text/javascript' src='scripts/jquery-1.7.1.js'></script>

<link type='text/css' rel="stylesheet" href='styles/report.css'>
<link rel='icon' type='image/ico' href='../favicon.ico'>

<body>

  <?php if ($count == 0): ?>
    <div class='no_reports'>No Crash Reports</div>
  <?php else: ?>
    <div class='report_container'>
      <?php 
        foreach($crashreports as $report) {
          $report->EchoHTML();
        }
      ?>
    </div>
  <?php endif ?>

</body>

</html>
