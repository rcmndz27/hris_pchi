<?php

    include('../payroll/payroll_confirm.php');
    include('../config/db.php');

    $choice = $_POST['choice'];

    if ($choice == 1)
    {   
        $empCode = $_POST['emp_code'];
        ConfirmPayRegView($empCode);
    }else if ($choice == 2){
        $empCode = $_POST['empCode'];
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        DeletePayReg($date_from,$date_to,$empCode);
    }else if ($choice == 4)
    {   
        $empCode = $_POST['emp_code'];
        $badgeno = $_POST['badgeno'];
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];        
        ConfirmPayRegEmpView($date_from,$date_to,$empCode,$badgeno);
    }else{
        $empCode = $_POST['empCode'];
        $date_from = $_POST['date_from'];
        $date_to = $_POST['date_to'];
        $emp_code = $_POST['emp_code'];
        DeletePayEmpReg($date_from,$date_to,$empCode,$emp_code);        

    }
?>