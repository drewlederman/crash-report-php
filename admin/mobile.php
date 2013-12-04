<?php

include_once('../include/common.php');
require_once('../classes/database.php');
require_once('../classes/crashreport.php');

$db = new Database();

$crashreports = $db->GetCrashReports('','','','');

?>

<!DOCTYPE html>

<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../styles/jquery.mobile-1.1.0.css">
	<link rel="stylesheet" href="../styles/report.mobile.css">
	<script src="../scripts/jquery-1.7.1.js"></script>
	<script src="../scripts/jquery.mobile-1.1.0.js"></script>
	<script src="../scripts/crashreport.mobile.js"></script>
</head>

<body>

  <div data-role="page">
  
    <div data-role="header">
      <div class='logo'>Crash Reporter</div>
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