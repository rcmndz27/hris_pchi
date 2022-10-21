<?php

    include('../payroll/payroll.php');
    include('../config/db.php');

    $action = $_POST["_action"];
    $dateFrom = $_POST["_from"];
    $dateTo = $_POST["_to"];
    $location = $_POST["_location"];
    $empCode = $_POST["_empCode"];

    if ($action == 1)
    {
        GetPayrollList($action, $dateFrom, $dateTo,$location,$empCode);
    }else{
        $emp_code = $_POST["emp_code"];
        GetPayrollEmpList($action,$dateFrom,$dateTo,$location,$empCode,$emp_code);
    }

?>