<?php

    include('../loans/updateloans.php');
    include('../config/db.php');

    $action = $_POST["action"];
    $emp_code = $_POST["emp_code"];
    $rowid = $_POST["rowid"];
    $loandec_id = $_POST["loandec_id"];
    $loan_amount = $_POST["loan_amount"];
    $loan_balance = $_POST["loan_balance"];
    $loan_totpymt = $_POST["loan_totpymt"];
    $loan_amort = $_POST["loan_amort"];
    $loan_date = $_POST["loan_date"];        
    $status = $_POST["status"];        

    if ($action == 1)
    {
        UpdateLoans($rowid,$emp_code,$loandec_id,$loan_amount,$loan_balance,$loan_totpymt,$loan_amort,$loan_date,$status);
    }

?>
