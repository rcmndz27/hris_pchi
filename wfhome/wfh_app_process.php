<?php

    session_start();

    include('../wfhome/wfh_app.php');
    include('../config/db.php');
    include('../controller/empInfo.php');

    $empInfo = new EmployeeInformation();

    $empInfo->SetEmployeeInformation($_SESSION['userid']);

    $empCode = $empInfo->GetEmployeeCode();
    $empReportingTo = $empInfo->GetEmployeeReportingTo();

    $wfhApp = new WfhApp(); 

    $wfhApplication = json_decode($_POST["data"]);

    if($wfhApplication->{"Action"} == "ApplyWfhApp"){

        $wfh_task = $wfhApplication->{"wfh_task"};
        $wfh_output = $wfhApplication->{"wfh_output"};
        $wfh_percentage = $wfhApplication->{"wfh_percentage"};
        $e_req = $wfhApplication->{"e_req"};
        $n_req = $wfhApplication->{"n_req"};
        $e_appr = $wfhApplication->{"e_appr"};
        $n_appr = $wfhApplication->{"n_appr"};
        $wfhDate = $wfhApplication->{"wfhdate"};
        $attachment = $wfhApplication->{"attachment"};
        $wfhApp->InsertAppliedWfhApp($empCode,$empReportingTo,$wfhDate,$wfh_task,$wfh_output,$wfh_percentage,$e_req,$n_req,$e_appr,$n_appr,$attachment);
   }
       

?>