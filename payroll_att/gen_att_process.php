<?php

    include('../payroll_att/gen_att.php');
    include('../config/db.php');

    $action = $_POST["action"];
    $eMplogName = $_POST["eMplogName"];
    $pyrollco_from = $_POST["pyrollco_from"];
    $pyrollco_to = $_POST["pyrollco_to"];

    if ($action == 1)
    {
        GenAttendance($eMplogName,$pyrollco_from,$pyrollco_to);
    }else{
        $empCode = $_POST["empCode"];
        GenEmpAttendance($eMplogName,$empCode,$pyrollco_from,$pyrollco_to);
    }

?>
