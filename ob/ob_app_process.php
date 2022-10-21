<?php

    session_start();

    include('../ob/ob_app.php');
    include('../config/db.php');
    include('../controller/empInfo.php');

    $empInfo = new EmployeeInformation();

    $empInfo->SetEmployeeInformation($_SESSION['userid']);

    $empCode = $empInfo->GetEmployeeCode();
    $empReportingTo = $empInfo->GetEmployeeReportingTo();

    $obApp = new ObApp(); 

    $obApplication = json_decode($_POST["data"]);

    
    if($obApplication->{"Action"} == "ApplyObApp"){

        $ob_time = $obApplication->{"ob_time"};
        $ob_destination = $obApplication->{"ob_destination"};
        $ob_purpose = $obApplication->{"ob_purpose"};
        $ob_percmp = $obApplication->{"ob_percmp"};
        $e_req = $obApplication->{"e_req"};
        $n_req = $obApplication->{"n_req"};
        $e_appr = $obApplication->{"e_appr"};
        $n_appr = $obApplication->{"n_appr"};
        $attachment = $obApplication->{"attachment"};
        $arr = $obApplication->{"ob_date"} ;

        foreach($arr as $value){
            $obDate = $value;

        $obApp->InsertAppliedObApp($empCode,$empReportingTo,$ob_time,$ob_destination,$ob_purpose,$ob_percmp, 
            $obDate,$e_req,$n_req,$e_appr,$n_appr,$attachment);

        }

    }else{

    }
    

    

?>