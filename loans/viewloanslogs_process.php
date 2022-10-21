<?php

    include('../loans/viewloanslogs.php');
    include('../config/db.php');

    $emp_code = $_POST["emp_code"];
    $rowid = $_POST["rowid"];
    
    ViewLnLogs($emp_code,$rowid);
    
?>