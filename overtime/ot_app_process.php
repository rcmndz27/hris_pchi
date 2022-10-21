<?php

    session_start();

    include('../overtime/ot_app.php');
    include('../config/db.php');
    include('../controller/empInfo.php');

    $empInfo = new EmployeeInformation();

    $empInfo->SetEmployeeInformation($_SESSION['userid']);

    $empCode = $empInfo->GetEmployeeCode();
    $empReportingTo = $empInfo->GetEmployeeReportingTo();

    $otApp = new OtApp(); 

    $otApplication = json_decode($_POST["data"]);

    
    if($otApplication->{"Action"} == "ApplyOtApp"){

        $otStartDtime = $otApplication->{"otstartdtime"};
        $otEndDtime = $otApplication->{"otenddtime"};
        $remarks = $otApplication->{"remarks"};
        $e_req = $otApplication->{"e_req"};
        $n_req = $otApplication->{"n_req"};
        $e_appr = $otApplication->{"e_appr"};
        $n_appr = $otApplication->{"n_appr"};
        $otDate = $otApplication->{"otdate"} ;
        $attachment = $otApplication->{"attachment"} ;
        $otApp->InsertAppliedOtApp($empCode,$empReportingTo,$otDate,$otStartDtime,$otEndDtime,$remarks,$e_req,$n_req,$e_appr,$n_appr,$attachment);
    }

?>