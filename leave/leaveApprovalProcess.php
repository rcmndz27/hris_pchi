<?php
    session_start();

    include('../leave/leaveApproval.php');
    include('../config/db.php');
    include('../controller/empInfo.php');

    $empInfo = new EmployeeInformation();

    $empInfo->SetEmployeeInformation($_SESSION['userid']);

    $empCode = $empInfo->GetEmployeeCode();
    $empName = $empInfo->GetEmployeeName();
    $empDept = $empInfo->GetEmployeeDepartment();
    $empReportingTo = $empInfo->GetEmployeeReportingTo();

  
    $leaveApproval = json_decode($_POST["data"]);
   
   

    if($leaveApproval->{"Action"} == "GetLeaveList"){

        $employee = $leaveApproval->{"employee"};
        $leavetype = $leaveApproval->{"leavetype"};

        if(empty($employee) && empty($leavetype)){
            $type = '0'; //show all per approval
            $employee = $empID;
        }elseif(!empty($employee) && !empty($leavetype)){
            $type = '1'; //show per employee and leavetype
        }elseif(empty($employee) && !empty($leavetype)){
            $type = '2'; //show all per leavetype
            $employee = $empID;
        }elseif(!empty($employee) && empty($leavetype)){
            $type = '3'; //show all leavetype per employee
        }

        ViewLeaveSummaryList($employee,$leavetype,$type);

    }else if($leaveApproval->{"Action"} == "ApproveLeave") {

        $curLeaveType = $leaveApproval->{"curLeaveType"};
        $curDateFrom = $leaveApproval->{"curDateFrom"};
        $curDateTo = $leaveApproval->{"curDateTo"};
        $curApproved = $leaveApproval->{"curApproved"};
        $employee = $leaveApproval->{"employee"};
        $rowid = $leaveApproval->{"rowid"};
        $approver = $leaveApproval->{"approver"};
        $empcode = $leaveApproval->{"empcode"};

        ApproveLeave($employee,$curApproved,$curDateFrom,$curDateTo,$curLeaveType,$rowid,$approver,$empcode);


    }else if($leaveApproval->{"Action"} == "ApproveAllLeave") {

        $empcode = $leaveApproval->{"empcode"};
        $arr = $leaveApproval->{"rowid"} ;

        foreach($arr as $value){
            $rowid = $value;
            ApproveAllLeave($rowid,$empcode);
        }
            ApproveAllEmail($rowid,$empcode);

    }else if ($leaveApproval->{"Action"} == "RejectLeaveAll") {

        $remarks = $leaveApproval->{"remarks"};
        $empcode = $leaveApproval->{"empcode"};
        $arr = $leaveApproval->{"rowid"};

        foreach($arr as $value){
            $rowid = $value;
            RejectAllLeave($rowid,$empcode,$remarks);
        }
            RejectAllEmail($rowid,$empcode,$remarks);


    }else if ($leaveApproval->{"Action"} == "RejectLeave") {

        $curLeaveType = $leaveApproval->{"curLeaveType"};
        $curDateFrom = $leaveApproval->{"curDateFrom"};
        $curDateTo = $leaveApproval->{"curDateTo"};
        $curRejected = $leaveApproval->{"curRejected"};
        $employee = $leaveApproval->{"employee"};
        $remarks = $leaveApproval->{"remarks"};
        $rowid = $leaveApproval->{"rowid"};
        $rejecter = $leaveApproval->{"rejecter"};
        $empcode = $leaveApproval->{"empcode"};

        RejectLeave($employee,$curDateFrom,$curDateTo,$curLeaveType,$curRejected,$remarks,$rowid,$rejecter,$empcode);

    }else if ($leaveApproval->{"Action"} == "FwdLeaveAll") {

        $empcode = $leaveApproval->{"empcode"};
        $arr = $leaveApproval->{"rowid"};

        foreach($arr as $value){
            $rowid = $value;
            FwdAllLeave($rowid,$empcode);
        }
            FwdAllEmail($rowid,$empcode);        


    }else if ($leaveApproval->{"Action"} == "FwdLeave") {

        $rowid = $leaveApproval->{"rowid"};
        $approver = $leaveApproval->{"approver"};
        $empcode = $leaveApproval->{"empcode"};

        FwdLeave($rowid,$approver,$empcode);

    }else if($leaveApproval->{"Action"} == "GetPendingList"){

        $employee = $leaveApproval->{"employee"};
        $logEmpCode = $leaveApproval->{"logEmpCode"};
        ShowAllLeave($employee,$logEmpCode);

    }else if($leaveApproval->{"Action"} == "GetLeaveListBlank"){
    
        ViewLeaveSummaryList($empCode);
    }else if($leaveApproval->{"Action"} == "GetLeaveHistory"){

        $employee = $leaveApproval->{"employee"};

        GetLeaveHistory($employee);
    }else if($leaveApproval->{"Action"} == "VoidLeave"){

        $employee = $leaveApproval->{"employee"};
        $creditleave = $leaveApproval->{"creditleave"};
        $leavetype = $leaveApproval->{"leavetype"};
        $remarks = $leaveApproval->{"remarks"};

        VoidLeave($employee,$creditleave,$leavetype,$remarks);

    }else if($leaveApproval->{"Action"} == "GetEmployeeList"){
        
        $employee = $leaveApproval->{"employee"};
        $employee = mb_substr($employee, 0, 3);
        $employee = '%'.$employee.'%';

        GetEmployeeList($employee);

    }else if($leaveApproval->{"Action"} == "GetApprovedList"){

        $employee = $leaveApproval->{"employee"};

        GetApprovedList($employee);

    }
    

?>