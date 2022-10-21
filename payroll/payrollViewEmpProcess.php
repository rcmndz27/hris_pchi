<?php

    include('../payroll/payroll_save_emp.php');
    include('../config/db.php');

    $choice = $_POST['choice'];
    $empCode = $_POST['emp_code'];
    $badgeno = $_POST['badgeno'];

    if ($choice == 1)
    {   
        $pfrom = $_POST['pfrom'];
        $pto = $_POST['pto'];
        $ppay = $_POST['ppay'];
        ApprovePayEmpView($empCode,$pfrom,$pto,$ppay,$badgeno);
    }else{
        $pfrom = $_POST['pfrom'];
        $pto = $_POST['pto'];
        $pfrom30 = $_POST['pfrom30'];
        $pto30 = $_POST['pto30'];        
        $ppay = $_POST['ppay'];
        ApprovePayEmpView30($empCode,$pfrom,$pto,$pfrom30,$pto30,$ppay,$badgeno);
    }

?>