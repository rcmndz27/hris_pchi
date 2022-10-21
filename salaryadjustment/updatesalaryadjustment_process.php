<?php

    include('../salaryadjustment/updatesalaryadjustment.php');
    include('../config/db.php');

    $action = $_POST["action"];
    $emp_code = $_POST["emp_code"];
    $period_from = $_POST["period_from"];
    $period_to = $_POST["period_to"];
    $description = $_POST["description"];  
    $inc_decr = $_POST["inc_decr"];  
    $amount = $_POST["amount"];
    $remarks = $_POST["remarks"];
    $saladjid = $_POST["saladjid"];                


    if ($action == 1)
    {
        UpdateSalaryAdj($emp_code,$period_from,$period_to,$description,$inc_decr,$amount,$remarks,$saladjid);
    }
    else {

    }

?>
