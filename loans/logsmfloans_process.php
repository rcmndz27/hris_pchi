<?php
  
    session_start();

    include('../loans/logsmfloans.php');
    include('../config/db.php');
    include('../controller/empInfo.php');

    $empInfo = new EmployeeInformation();
    $empInfo->SetEmployeeInformation($_SESSION['userid']);
    $empCode = $empInfo->GetEmployeeCode();
    $emp_code = $_POST["emp_code"];   
    $column_name = $_POST["column_name"]; 
    $new_data = $_POST["new_data"];
    $old_data = $_POST["old_data"]; 
    $rowid = $_POST["rowid"]; 
          
    insertMfLnLogs($emp_code,$emp_name,$column_name,$new_data,$old_data,$empCode,$rowid);


?>
