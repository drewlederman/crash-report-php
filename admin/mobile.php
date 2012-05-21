<?php
include('common.php');
require('crashreport.php');

$crashreports = GetCrashReports('','','','');
?>

<!DOCTYPE html>

<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/jquery.mobile-1.1.0.css">
	<link rel="stylesheet" href="styles/report.mobile.css">
	<script src="scripts/jquery-1.7.1.js"></script>
	<script src="scripts/jquery.mobile-1.1.0.js"></script>
	<script src="scripts/crashreport.mobile.js"></script>
</head>

<body>

  <div data-role="page">
  
    <div data-role="header">
      <div class='logo'></div>
    </div>
  
    <div data-role="content">
      <?php 
        foreach($crashreports as $report) {
          $report->EchoMobileHTML();
        }
      ?>
    </div>
  
  </div>

</body>

</html>