<?php

    include('../newhireaccess/update_nhaccess.php');
    include('../config/db.php');


    $action = $_POST["_action"];
    $department = $_POST["department"];
    $position = $_POST["position"];
    $location = $_POST["location"];
    $emp_type = $_POST["emp_type"];
    $emp_level = $_POST["emp_level"];
    $work_sched_type = $_POST["work_sched_type"];
    $minimum_wage = $_POST["minimum_wage"];
    $pay_type = $_POST["pay_type"];
    $emp_status = $_POST["emp_status"];
    $reporting_to = $_POST["reporting_to"];
    $lastname = $_POST["lastname"];
    $firstname = $_POST["firstname"];
    $middlename = $_POST["middlename"];
    $emailaddress = $_POST["emailaddress"];
    $telno = $_POST["telno"];
    $celno = $_POST["celno"];
    $emp_address = $_POST["emp_address"];
    $emp_address2 = $_POST["emp_address2"];
    $sss_no = $_POST["sss_no"];
    $phil_no = $_POST["phil_no"];
    $pagibig_no = $_POST["pagibig_no"];
    $tin_no = $_POST["tin_no"];
    $birthdate = $_POST["birthdate"];
    $datehired = $_POST["datehired"];
    $birthplace = $_POST["birthplace"];
    $sex = $_POST["sex"];
    $marital_status = $_POST["marital_status"];
    $emp_pic_loc = $_POST["emp_pic"];
    $rowid = $_POST["rowid"];    
    $emp_id = $_POST["emp_id"];    

    if ($action == 1)
    {
        UpdateEmployeeLevel($department,$position,$location,$emp_type,$emp_level,$work_sched_type,$minimum_wage,$pay_type,$emp_status,$reporting_to,$lastname,$firstname,$middlename,$emailaddress,$telno,$celno,$emp_address,$emp_address2,$sss_no,$phil_no,$pagibig_no,$tin_no,$datehired,$birthdate,$birthplace,$sex,$marital_status,$emp_pic_loc,$rowid,$emp_id);
    }



?>
