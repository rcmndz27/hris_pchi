<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


Class OtApp{

    private $employeeCode;
    
    public function SetOtAppParams($employeeCode){
        $this->employeeCode = $employeeCode;
    }

    public function GetAllOtAppHistory($date_from,$date_to,$status){
        global $connL;

        echo '       
        <table id="OtListTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>OT Date</th>
                <th>Name</th>
                <th>OT Type</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Plan OT</th>
                <th>Rendered OT</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,b.lastname+','+b.firstname as fullname,a.rowid as ot_rowid,* FROM dbo.tr_overtime a
        left join employee_profile b on a.emp_code = b.emp_code
        where a.status = :status and a.ot_date between :startDate and :endDate";
        $param = array(":startDate" => date('Y-m-d', strtotime($date_from)),":endDate" => date('Y-m-d', strtotime($date_to)),":status" => $status);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                $otdate = "'".date('m-d-Y', strtotime($result['ot_date']))."'";
                $ottype = "'".(isset($result['ot_type']) ? $result['ot_type'] : 'n/a')."'";
                $otstartdtime = "'".date('h:i A', strtotime($result['ot_start_dtime']))."'";
                $otenddtime = "'".date('h:i A', strtotime($result['ot_end_dtime']))."'";
                $remark = "'".(isset($result['remarks']) ? $result['remarks'] : 'n/a')."'";
                $otreqhrs = "'".$result['ot_req_hrs']."'";
                $otrenhrs = "'".$result['ot_ren_hrs']."'";
                $rejectreason = "'".(isset($result['reject_reason']) ? $result['reject_reason'] : 'n/a')."'";
                $stats = "'".$result['stats']."'";
                $otid = "'".$result['ot_rowid']."'";
                $empcode = "'".$result['emp_code']."'";
                echo '
                <tr>
                <td>' . date('F d,Y', strtotime($result['ot_date'])) . '</td>
                <td>' . $result['fullname'] . '</td>
                <td>' . $result['ot_type'] . '</td>
                <td>' . date('h:i A', strtotime($result['ot_start_dtime'])) . '</td>
                <td>' . date('h:i A', strtotime($result['ot_end_dtime'])) . '</td>
                <td>' . round($result['ot_req_hrs'],2) . '</td> 
                <td>' . round($result['ot_ren_hrs'],2) . '</td>
                <td>' . $result['remarks'] . '</td>
                <td id="st'.$result['ot_rowid'].'">' . $result['stats'] . '</td>';
                echo'
                <td><button type="button" class="btn btn-info btn-sm mb-1" onclick="viewOtModal('.$otdate.','.$ottype.','.$otstartdtime.','.$otenddtime.','.$remark.','.$otreqhrs.','.$otrenhrs.','.$rejectreason.','.$stats.')" title="View Overtime">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="viewOtHistoryModal('.$otid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                        
                            </td>';

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    } 

public function GetAllOtRepHistory($date_from,$date_to,$empCode){
        global $connL;

        echo '        
        <table id="OtListRepTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>OT Date</th>
                <th>Name</th>
                <th>OT Type</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Plan OT</th>
                <th>Rendered OT</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,b.lastname+','+b.firstname as fullname,a.rowid as ot_rowid,* FROM dbo.tr_overtime a
        left join employee_profile b on a.emp_code = b.emp_code
        where a.emp_code = :empCode and a.ot_date between :startDate and :endDate";
        $param = array(":startDate" => date('Y-m-d', strtotime($date_from)),":endDate" => date('Y-m-d', strtotime($date_to)),":empCode" => $empCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                $otdate = "'".date('m-d-Y', strtotime($result['ot_date']))."'";
                $ottype = "'".(isset($result['ot_type']) ? $result['ot_type'] : 'n/a')."'";
                $otstartdtime = "'".date('h:i A', strtotime($result['ot_start_dtime']))."'";
                $otenddtime = "'".date('h:i A', strtotime($result['ot_end_dtime']))."'";
                $remark = "'".(isset($result['remarks']) ? $result['remarks'] : 'n/a')."'";
                $otreqhrs = "'".$result['ot_req_hrs']."'";
                $otrenhrs = "'".$result['ot_ren_hrs']."'";
                $rejectreason = "'".(isset($result['reject_reason']) ? $result['reject_reason'] : 'n/a')."'";
                $stats = "'".$result['stats']."'";
                $otid = "'".$result['ot_rowid']."'";
                $empcode = "'".$result['emp_code']."'";
                echo '
                <tr>
                <td>' . date('F d,Y', strtotime($result['ot_date'])) . '</td>
                <td>' . $result['fullname'] . '</td>
                <td>' . $result['ot_type'] . '</td>
                <td>' . date('h:i A', strtotime($result['ot_start_dtime'])) . '</td>
                <td>' . date('h:i A', strtotime($result['ot_end_dtime'])) . '</td>
                <td>' . round($result['ot_req_hrs'],2) . '</td> 
                <td>' . round($result['ot_ren_hrs'],2) . '</td>
                <td>' . $result['remarks'] . '</td>
                <td id="st'.$result['ot_rowid'].'">' . $result['stats'] . '</td>';
                echo'
                <td><button type="button" class="btn btn-info btn-sm mb-1" onclick="viewOtModal('.$otdate.','.$ottype.','.$otstartdtime.','.$otenddtime.','.$remark.','.$otreqhrs.','.$otrenhrs.','.$rejectreason.','.$stats.')" title="View Overtime">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="viewOtHistoryModal('.$otid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                        
                            </td>';

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    }          

    public function GetOtAppHistory(){
        global $connL;

        echo '                 
        <table id="otList" class="table table-sm">
        <thead>
            <tr>
                <th>OT Date</th>
                <th>OT Type</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Plan OT (Hrs)</th>
                <th>Rendered OT (Hrs)</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid as rowdy,b.firstname+' '+b.lastname as approver,* FROM dbo.tr_overtime a
                    left join employee_profile b on a.reporting_to = b.emp_code
                    where a.emp_code = :emp_code ORDER BY ot_date DESC";
        $param = array(':emp_code' => $this->employeeCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 

            $otdate = "'".date('m-d-Y', strtotime($result['ot_date']))."'";
            $ottype = "'".(isset($result['ot_type']) ? $result['ot_type'] : 'n/a')."'";
            $otstartdtime = "'".date('h:i A', strtotime($result['ot_start_dtime']))."'";
            $otenddtime = "'".date('h:i A', strtotime($result['ot_end_dtime']))."'";
            $remarkz = "'".(isset($result['remarks']) ?  trim(str_replace("'",'',$result['remarks'])) : 'n/a')."'";
            $remark = preg_replace( "/\r|\n/", "", $remarkz );
            $otreqhrs = "'".$result['ot_req_hrs']."'";
            $otrenhrs = "'".$result['ot_ren_hrs']."'";
            $appr_over = "'".$result['approver']."'";
            $rejectreason = "'".(isset($result['reject_reason']) ? $result['reject_reason'] : 'n/a')."'";
            $stats = "'".$result['stats']."'";
            $otid = "'".$result['rowdy']."'";
            $empcode = "'".$result['emp_code']."'";
            $atch = "'".$result['attachment']."'";
            $lenr = strlen($result['remarks']);
            $onclick = 'onclick="viewOtModal('.$otdate.','.$ottype.','.$otstartdtime.','.$otenddtime.','.$remark.','.$otreqhrs.','.$otrenhrs.','.$rejectreason.','.$stats.','.$appr_over.','.$atch.')"';
            if($lenr > 35){
                $rmrks = substr($result['remarks'], 0, 35).'....';
            }else{
                $rmrks = $result['remarks'];
            }
            echo '
            <tr class="csor-pointer">
            <td '.$onclick.'>' . date('F d, Y', strtotime($result['ot_date'])) . '</td>
            <td '.$onclick.'>' . $result['ot_type'] . '</td>
            <td '.$onclick.'>' . date('h:i A', strtotime($result['ot_start_dtime'])) . '</td>
            <td '.$onclick.'>' . date('h:i A', strtotime($result['ot_end_dtime'])) . '</td>
            <td '.$onclick.'>' . round($result['ot_req_hrs'],2) . '</td> 
            <td '.$onclick.'>' . round($result['ot_ren_hrs'],2) . '</td>
            <td '.$onclick.'>' . $rmrks.'</td>
            <td '.$onclick.' id="st'.$result['rowdy'].'">' . $result['stats'] . '</td>';
                if($result['stats'] == 'PENDING' || $result['stats'] == 'APPROVED'){
                echo'
                <td><button type="button" class="btn btn-info btn-sm" onclick="viewOtModal('.$otdate.','.$ottype.','.$otstartdtime.','.$otenddtime.','.$remark.','.$otreqhrs.','.$otrenhrs.','.$rejectreason.','.$stats.','.$appr_over.','.$atch.')" title="View Overtime">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mb-1" onclick="viewOtHistoryModal('.$otid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                           
                            <button type="button" id="clv'.$result['rowdy'].'" class="btn btn-danger btn-sm" onclick="cancelOvertime('.$otid.','.$empcode.')" title="Cancel Overtime">
                                <i class="fas fa-ban"></i>
                            </button>
                            </td>';
                }else{
                echo'
                <td><button type="button" class="btn btn-info btn-sm" onclick="viewOtModal('.$otdate.','.$ottype.','.$otstartdtime.','.$otenddtime.','.$remark.','.$otreqhrs.','.$otrenhrs.','.$rejectreason.','.$stats.','.$appr_over.','.$atch.')" title="View Overtime">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mb-1" onclick="viewOtHistoryModal('.$otid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                        
                            </td>';
                }

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>';
    }

    public function InsertAppliedOtApp($empCode,$empReportingTo,$otDate,$otStartDtime,$otEndDtime,$remarks,$e_req,$n_req,$e_appr,$n_appr,$attachment){

        global $connL;

        if($otStartDtime > $otEndDtime){
            $fixed_date = date('m-d-Y',strtotime($otDate)).' 22:00:00';
            $otenddate1 = date('Y-m-d', strtotime($otDate. ' + 1 day'));
            $otsd_tmp = $otDate.'T'.$otStartDtime;
            $otend_tmp = $otenddate1.'T'.$otEndDtime;
            $fxdate_tmp = $otDate.'T22:00';
            $fxdate_tmp2 = $otDate.'T06:00';
            $otsd_d = date('m-d-Y H:i:s', strtotime($otsd_tmp));
            $otend_d = date('m-d-Y H:i:s', strtotime($otend_tmp));
            $otsd_dt = date('H:i', strtotime($otsd_tmp));
            $otend_dt = date('H:i', strtotime($otend_tmp));            
            $otsd = strtotime($otsd_tmp);
            $otend = strtotime($otend_tmp);
            $fxdate = strtotime($fxdate_tmp);
            $fxdate2 = strtotime($fxdate_tmp2);
            $total = round(($otend - $otsd)/3600,2);
            $total_fxs = round(($fxdate - $otsd)/3600,2);
            $total_fxe = round(($otend - $fxdate)/3600,2);
            $total_fxs2 = round(($fxdate2 - $otsd)/3600,2);
            $total_fxe2 = round(($otend - $fxdate2)/3600,2);             
        }else{
            $fixed_date = date('m-d-Y',strtotime($otDate)).' 22:00:00';
            $fixed_date2 = date('m-d-Y',strtotime($otDate)).' 06:00:00';
            $otsd_tmp = $otDate.'T'.$otStartDtime;
            $otend_tmp = $otDate.'T'.$otEndDtime;
            $fxdate_tmp = $otDate.'T22:00';
            $fxdate_tmp2 = $otDate.'T06:00';
            $otsd_d = date('m-d-Y H:i:s', strtotime($otsd_tmp));
            $otend_d = date('m-d-Y H:i:s', strtotime($otend_tmp));
            $otsd_dt = date('H:i', strtotime($otsd_tmp));
            $otend_dt = date('H:i', strtotime($otend_tmp));            
            $otsd = strtotime($otsd_tmp);
            $otend = strtotime($otend_tmp);
            $fxdate = strtotime($fxdate_tmp);
            $fxdate2 = strtotime($fxdate_tmp2);
            $total = round(($otend - $otsd)/3600,2);
            $total_fxs = round(($fxdate - $otsd)/3600,2);
            $total_fxe = round(($otend - $fxdate)/3600,2);
            $total_fxs2 = round(($fxdate2 - $otsd)/3600,2);
            $total_fxe2 = round(($otend - $fxdate2)/3600,2);                         
        }


        $date = date_create($otDate);
        $daydate = date_format($date,"l");
        $wdays = array('Monday','Tuesday','Wednesday','Thursday','Friday');

        // echo $otsd_d;
        // echo  "\r\n";
        // echo  $otend_d;
        // echo  "\r\n";
        // exit();

    if(($otsd_dt < '22:00' and $otsd_dt > '06:00') and ($otend_dt > '22:00' or $otend_dt < '06:00') and in_array($daydate,$wdays)){

        // echo 'night diff with insert and update';
        //  exit();

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser,auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $result = $stmt->execute($param);
        echo $result;

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

        $queryt = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtt =$connL->prepare($queryt);

        $paramt = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $fixed_date,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total_fxe,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultt = $stmtt->execute($paramt);
        echo $resultt;        

        $query_payt = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payt->bindValue(':ot_date',$otDate);
        $query_payt->bindValue(':empCode',$empCode);
        $query_payt->execute(); 


    }else if(($otsd_dt >= '22:00' or $otsd_dt < '06:00') and ($otend_dt >= '22:00' or $otend_dt < '06:00') and in_array($daydate,$wdays)){

        // echo 'night diff with insert only';
        // exit();
        
        $queryt = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtt =$connL->prepare($queryt);

        $paramt = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultt = $stmtt->execute($paramt);
        echo $resultt;        

        $query_payt = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payt->bindValue(':ot_date',$otDate);
        $query_payt->bindValue(':empCode',$empCode);
        $query_payt->execute(); 

    }else if(($otsd_dt >= '22:00' or $otsd_dt < '06:00') and ($otend_dt >= '06:00') and in_array($daydate,$wdays)){

        // echo 'night diff with insert nd morning and update only';
        // exit();

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser,auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $result = $stmt->execute($param);
        echo $result;

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

        $queryte = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtte =$connL->prepare($queryte);

        $paramte = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $fixed_date2,
        ":otReqHrs"=> $total_fxs2,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultte = $stmtte->execute($paramte);
        echo $resultte;        

        $query_payte = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payte->bindValue(':ot_date',$otDate);
        $query_payte->bindValue(':empCode',$empCode);
        $query_payte->execute(); 

    }else if(($otsd_dt < '22:00' and $otsd_dt > '06:00') and ($otend_dt > '22:00' or $otend_dt < '06:00') and !in_array($daydate,$wdays)){

        // echo 'night diff with insert and update weekend';
        //  exit();

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser,auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $result = $stmt->execute($param);
        echo $result;

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

        $queryt = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtt =$connL->prepare($queryt);

        $paramt = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $fixed_date,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total_fxe,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultt = $stmtt->execute($paramt);
        echo $resultt;        

        $query_payt = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payt->bindValue(':ot_date',$otDate);
        $query_payt->bindValue(':empCode',$empCode);
        $query_payt->execute(); 


    }else if(($otsd_dt >= '22:00' or $otsd_dt < '06:00') and ($otend_dt >= '22:00' or $otend_dt < '06:00') and !in_array($daydate,$wdays)){

        // echo 'night diff with insert only weekend';
        // exit();
        

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser,auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );


        $result = $stmt->execute($param);
        echo $result;

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

        $queryte = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtte =$connL->prepare($queryte);

        $paramte = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultte = $stmtte->execute($paramte);
        echo $resultte;        

        $query_payte = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payte->bindValue(':ot_date',$otDate);
        $query_payte->bindValue(':empCode',$empCode);
        $query_payte->execute(); 

    }else if(($otsd_dt >= '22:00' or $otsd_dt < '06:00') and ($otend_dt >= '06:00') and !in_array($daydate,$wdays)){

        // echo 'night diff with insert nd morning and update only weekend';
        // exit();

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser,auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $result = $stmt->execute($param);
        echo $result;

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

        $queryte = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmtte =$connL->prepare($queryte);

        $paramte = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $fixed_date2,
        ":otReqHrs"=> $total_fxs2,
        ":remarks"=> $remarks,
        ":attachment"=> $attachment,
        ":audituser" => $empCode,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $resultte = $stmtte->execute($paramte);
        echo $resultte;        

        $query_payte = $connL->prepare('EXEC GenerateOTNDType :ot_date,:empCode');
        $query_payte->bindValue(':ot_date',$otDate);
        $query_payte->bindValue(':empCode',$empCode);
        $query_payte->execute(); 

    }else{

        // echo 'regular pay only';
        // exit();

        $query = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
        VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

        $stmt =$connL->prepare($query);

        $param = array(
        ":emp_code"=> $empCode,
        ":otDate" => $otDate,
        ":datefiled"=>date('m-d-Y'),
        ":empReportingTo" => $empReportingTo,
        ":otStartDtime" => $otsd_d,
        ":otEndDtime"=> $otend_d,
        ":otReqHrs"=> $total,
        ":remarks"=> $remarks,
        ":audituser" => $empCode,
        ":attachment"=> $attachment,
        ":auditdate"=>date('m-d-Y H:i:s')
        );

        $result = $stmt->execute($param);
        echo $result;   

        $query_pay = $connL->prepare('EXEC GenerateOTType :ot_date,:empCode');
        $query_pay->bindValue(':ot_date',$otDate);
        $query_pay->bindValue(':empCode',$empCode);
        $query_pay->execute(); 

    }


// $resquery = "SELECT * FROM tr_overtime WHERE ot_date = :otDate and emp_code = :empCode and status = 1 and ot_type like '%Rest Day%'";
// $resparam = array(':empCode' => $empCode,':otDate' => $otDate);
// $resstmt =$connL->prepare($resquery);
// $resstmt->execute($resparam);
// $resresult = $resstmt->fetch();
// $res_ot = round($resresult['ot_req_hrs']);
// $res_date = $resresult['ot_date'];
// $res_start = $resresult['ot_start_dtime'];
// $res_end = $resresult['ot_end_dtime'];
// $res_id = $resresult['rowid'];
// $rot_start = date('m-d-Y H:i:s', strtotime($res_start));
// $rot_end = date('m-d-Y H:i:s', strtotime($res_end));
// $rot_10pm = date('m-d-Y 22:00:00', strtotime($res_date));
// $hi_start = date('H:i', strtotime($res_start));
// $hi_end = date('H:i', strtotime($res_end));  


//     if($hi_start > $hi_end){
//         $hienddate1 = date('Y-m-d', strtotime($res_date. ' + 1 day')); 
//         $hi_stmp = $res_date.'T'.$hi_start;
//         $hi_etmp = $hienddate1.'T'.$hi_end; 
//         $hi10_tmp = $otDate.'T22:00';    
//         $fxhistart = strtotime($hi_stmp);
//         $fxhiend = strtotime($hi_etmp);  
//         $fxhi10 = strtotime($hi10_tmp); 
//         $totfxhi = round(($fxhiend - $fxhistart)/3600,2);   
//         $totfxs = round(($fxhi10 - $fxhistart)/3600,2);
//         $totfxe = round(($fxhiend - $fxhi10)/3600,2);       
//     }else{
//         $hi_stmp = $res_date.'T'.$hi_start;
//         $hi_etmp = $res_date.'T'.$hi_end;
//         $hi10_tmp = $otDate.'T22:00';
//         $fxhistart = strtotime($hi_stmp);
//         $fxhiend = strtotime($hi_etmp);
//         $fxhi10 = strtotime($hi10_tmp);        
//         $totfxhi = round(($fxhiend - $fxhistart)/3600,2);   
//         $totfxs = round(($fxhi10 - $fxhistart)/3600,2);
//         $totfxe = round(($fxhiend - $fxhi10)/3600,2);   
//     }    


//     if(($hi_start < '22:00' and $hi_start > '06:00') and ($hi_end > '22:00' or $hi_end < '06:00')){

//         $cmdui = $connL->prepare("UPDATE dbo.tr_overtime SET ot_end_dtime = :rot_10pm,ot_req_hrs = :totfxs 
//             where rowid = :res_id");
//         $cmdui->bindValue('rot_10pm',$rot_10pm);
//         $cmdui->bindValue('totfxs',$totfxs);
//         $cmdui->bindValue('res_id',$res_id);
//         $cmdui->execute();     


//         $queryUI = "INSERT INTO tr_overtime (emp_code,ot_date,datefiled,reporting_to,ot_start_dtime,ot_end_dtime,ot_req_hrs,remarks,attachment,audituser, auditdate) 
//         VALUES(:emp_code,:otDate,:datefiled,:empReportingTo,:otStartDtime,:otEndDtime,:otReqHrs,:remarks,:attachment,:audituser,:auditdate) ";

//         $stmtUI =$connL->prepare($queryUI);

//         $paramUI = array(
//         ":emp_code"=> $empCode,
//         ":otDate" => $res_date,
//         ":datefiled"=>date('m-d-Y'),
//         ":empReportingTo" => $empReportingTo,
//         ":otStartDtime" => $rot_10pm,
//         ":otEndDtime"=> $rot_end,
//         ":otReqHrs"=> $totfxe,
//         ":remarks"=> $remarks,
//         ":attachment"=> $attachment,
//         ":audituser" => $empCode,
//         ":auditdate"=>date('m-d-Y H:i:s')
//         );

//         $resultUI = $stmtUI->execute($paramUI);
//         echo $resultUI;      

//         $query_payUI = $connL->prepare('EXEC GenerateOTRNDNType :ot_date,:empCode,:res_id');
//         $query_payUI->bindValue(':ot_date',$otDate);
//         $query_payUI->bindValue(':empCode',$empCode);
//         $query_payUI->bindValue(':res_id',$res_id);
//         $query_payUI->execute();                   
                
//     }else if(($hi_start >= '22:00' or $hi_start < '06:00') and ($hi_end >= '22:00' or $hi_end < '06:00')){

//         $query_payio = $connL->prepare('EXEC GenerateOTRNDType :res_id');
//         $query_payio->bindValue(':res_id',$res_id);
//         $query_payio->execute(); 

//     }else{
//         // echo 'as is';
//     }    

     
        $squery = "SELECT lastname+', '+firstname as [fullname] FROM employee_profile WHERE emp_code = :empCode";
        $sparam = array(':empCode' => $empCode);
        $sstmt =$connL->prepare($squery);
        $sstmt->execute($sparam);
        $sresult = $sstmt->fetch();
        $sname = $sresult['fullname'];

        $qry = 'SELECT max(rowid) as maxid FROM tr_overtime WHERE emp_code = :emp_code';
        $prm = array(":emp_code" => $empCode);
        $stm =$connL->prepare($qry);
        $stm->execute($prm);
        $rst = $stm->fetch();

        $querys = "INSERT INTO logs_ot (ot_id,emp_code,emp_name,remarks,audituser,auditdate) 
            VALUES(:ot_id,:emp_code,:emp_name,:remarks,:audituser,:auditdate) ";

            $stmts =$connL->prepare($querys);

            $params = array(
                ":ot_id" => $rst['maxid'],
                ":emp_code"=> $empCode,
                ":emp_name"=> $sname,
                ":remarks" => 'Apply OT for '.$otDate,
                ":audituser" => $empCode,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

        $results = $stmts->execute($params);

        echo $results;


        $erequester = $e_req;
        $nrequester = $n_req;
        $eapprover = $e_appr;
        $napprover = $n_appr;

        $mail = new PHPMailer(true);
        try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;      
        $mail->isSMTP();                                           
        $mail->Host       = 'mail.obanana.com'; 
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'hris-support@obanana.com';        
        $mail->Password   = '@dmin2021@dmin2022';                              
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                   

        $mail->setFrom('hris-support@obanana.com','HRIS-NOREPLY');
        $mail->addAddress($eapprover,'Approver');    

        $mail->isHTML(true);                          
        $mail->Subject = 'Overtime Request Sent to Approver: ';
        $mail->Body    = '<h1>Hi '.$napprover.' </b>,</h1>An employee has requested a overtime(#'.$rst['maxid'].').<br><br>
                        <h2>From: '.$nrequester.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/overtime/overtime-approval-view.php">Overtime Approval List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "Overtime Approval List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/overtime/overtime-approval-view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }            

    }

}

?>