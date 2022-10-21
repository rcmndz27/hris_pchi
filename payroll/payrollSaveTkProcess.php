<?php

    include('../payroll/payroll_savetk.php');
    include('../config/db.php');

    $choice = $_POST['choice'];
    $empCode = $_POST['emp_code'];

    if ($choice == 1)
    {   
        $pfrom = $_POST['pfrom'];
        $pto = $_POST['pto'];
        $ppay = $_POST['ppay'];
        SaveTk($empCode,$pfrom,$pto,$ppay);
    }else{
        $pfrom = $_POST['pfrom'];
        $pto = $_POST['pto'];
        $ppay = $_POST['ppay'];
        SaveEmpTk($empCode,$pfrom,$pto,$ppay);
    }

?>