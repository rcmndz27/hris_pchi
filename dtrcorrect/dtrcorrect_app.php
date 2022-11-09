<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

Class DtrCorrectApp{

    private $employeeCode;
    
    public function SetdtrcorrectAppParams($employeeCode){
        $this->employeeCode = $employeeCode;
    }


public function GetAlldtrcorrectAppHistory($date_from,$date_to,$status){
        global $connL;

        echo '<table id="DtrcListTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>DTR Date</th>
                <th>Name</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,b.lastname+','+b.firstname as fullname,a.rowid as dtrc_id,* FROM dbo.tr_dtrcorrect a
        left join employee_profile b on a.emp_code = b.emp_code
        where status = :status and dtrc_date between :startDate and :endDate";
        $param = array(":startDate" => date('Y-m-d', strtotime($date_from)),":endDate" => date('Y-m-d', strtotime($date_to)),":status" => $status);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                // dtrcdate,timein,timeout,remarks,stts
                $dtrcdate = "'".date('m-d-Y', strtotime($result['dtrc_date']))."'";
                $t_in = (isset($result['time_in'])) ? date('h:i A', strtotime($result['time_in'])) : 'n/a';
                $t_out = (isset($result['time_out'])) ? date('h:i A', strtotime($result['time_out'])) : 'n/a';
                $timein = "'".date('h:i a', strtotime($result['time_in']))."'";
                $timeout = "'".date('h:i a', strtotime($result['time_out']))."'";
                $rmrks = "'".$result['remarks']."'";
                $stts = "'".$result['stats']."'";
                $dtrcid = "'".$result['dtrc_id']."'";
                $empcode = "'".$result['emp_code']."'";
                echo '
                <tr>
                <td>'.date('F d,Y', strtotime($result['dtrc_date'])).'</td>
                <td>'.$result['fullname'] . '</td>
                <td>'.$t_in.'</td>
                <td>'.$t_out.'</td>
                <td>'.$result['remarks'] . '</td>
                <td id="st'.$result['dtrc_id'].'">'.$result['stats'].'</td>';
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm mb-1" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.')" title="View DTR Correction">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mb-1" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
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

    public function GetAlldtrcorrectRepHistory($date_from,$date_to,$empCode){
        global $connL;

        echo '<table id="DtrcListRepTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>DTR Date</th>
                <th>Name</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,b.lastname+','+b.firstname as fullname,a.rowid as dtrc_id,* FROM dbo.tr_dtrcorrect a
        left join employee_profile b on a.emp_code = b.emp_code
        where a.emp_code = :empCode and dtrc_date between :startDate and :endDate";
        $param = array(":startDate" => date('Y-m-d', strtotime($date_from)),":endDate" => date('Y-m-d', strtotime($date_to)),":empCode" => $empCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                // dtrcdate,timein,timeout,remarks,stts
                $dtrcdate = "'".date('m-d-Y', strtotime($result['dtrc_date']))."'";
                $timein = "'".date('h:i a', strtotime($result['time_in']))."'";
                $timeout = "'".date('h:i a', strtotime($result['time_out']))."'";
                $rmrks = "'".$result['remarks']."'";
                $stts = "'".$result['stats']."'";
                $dtrcid = "'".$result['dtrc_id']."'";
                $empcode = "'".$result['emp_code']."'";
                echo '
                <tr>
                <td>'.date('F d,Y', strtotime($result['dtrc_date'])).'</td>
                <td>'.$result['fullname'] . '</td>
                <td>'.date('h:i a', strtotime($result['time_in'])).'</td>
                <td>'.date('h:i a', strtotime($result['time_out'])).'</td>
                <td>'.$result['remarks'] . '</td>
                <td id="st'.$result['dtrc_id'].'">'.$result['stats'].'</td>';
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm mb-1" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.')" title="View DTR Correction">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm mb-1" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
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



    public function GetdtrcorrectAppHistory(){
        global $connL;

        echo '         
        <table id="dtrcorrectList" class="table table-sm">
        <thead>
            <tr>
                <th>DTR Date</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid as rowdy,b.firstname+' '+b.lastname as approver, * FROM dbo.tr_dtrcorrect a
        left join employee_profile b on a.reporting_to = b.emp_code where a.emp_code = :emp_code ORDER BY dtrc_date DESC";
        $param = array(':emp_code' => $this->employeeCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                // dtrcdate,timein,timeout,remarks,stts
                $dtrcdate = "'".date('m-d-Y', strtotime($result['dtrc_date']))."'";
                $t_in = (isset($result['time_in'])) ? date('h:i A', strtotime($result['time_in'])) : 'n/a';
                $t_out = (isset($result['time_out'])) ? date('h:i A', strtotime($result['time_out'])) : 'n/a';
                $timein = "'".$t_in ."'";
                $timeout = "'".$t_out."'";
                $rmrks = "'".$result['remarks']."'";
                $stts = "'".$result['stats']."'";
                $dtrcid = "'".$result['rowdy']."'";
                $empcode = "'".$result['emp_code']."'";
                $appr_over = "'".$result['approver']."'";
                $atch = "'".$result['attachment']."'";
                $onclick = 'onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.','.$appr_over.','.$atch.')"';
                echo '
                <tr class="csor-pointer">
                <td '.$onclick.'>'.date('F d, Y', strtotime($result['dtrc_date'])).'</td>
                <td '.$onclick.'>'.$t_in.'</td>
                <td '.$onclick.'>'.$t_out.'</td>
                <td '.$onclick.'>'.$result['remarks'] .'</td>
                <td '.$onclick.' id="st'.$result['rowdy'].'">'.$result['stats'].'</td>';
                if($result['stats'] == 'PENDING' || $result['stats'] == 'APPROVED'){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.','.$appr_over.','.$atch.')" title="View DTR Correction">
                    <i class="fas fa-binoculars"></i>
                </button>
                <button type="button" class="btn btn-warning btn-sm" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
                    <i class="fas fa-history"></i>
                </button>                           
                <button type="button" id="clv'.$result['rowdy'].'" class="btn btn-danger btn-sm" onclick="canceldtrcorrect('.$dtrcid.','.$empcode.')" title="Cancel DTR Correction">
                    <i class="fas fa-ban"></i>
                </button>
                </td>';
                }else{
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.','.$appr_over.','.$atch.')" title="View DTR Correction">
                        <i class="fas fa-binoculars"></i>
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
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

    public function InsertAppliedDtrCorrectApp($empCode,$empReportingTo,$dtrc_date,$time_in,$time_out,$dtrc_type,$remarks,$e_req,$n_req,$e_appr,$n_appr,$attchmnt){

            global $connL;

            $yquery = "SELECT timein,timeout from employee_attendance where emp_code = :empcode and punch_date = :todate";
            $ystmt =$connL->prepare($yquery);
            $yparam = array(":empcode" => substr($empCode,3),":todate" => $dtrc_date);
            $ystmt->execute($yparam);
            $yresult = $ystmt->fetch();  
            $emp_ti = (isset($yresult['timein']) ?  date('H:i', strtotime($yresult['timein'])) : null);
            $emp_to = (isset($yresult['timeout']) ?  date('H:i', strtotime($yresult['timeout'])) : null);

            if($dtrc_type == 'Both'){
                if($time_in > $time_out){
                    $dtrcdt1 = date('Y-m-d', strtotime($dtrc_date. ' + 1 day'));
                    $dtsd_tmp = $dtrc_date.'T'.$time_in;
                    $dtend_tmp = $dtrcdt1.'T'.$time_out;
                    $dtsd_d = date('m-d-Y H:i:s', strtotime($dtsd_tmp));
                    $dtend_d = date('m-d-Y H:i:s', strtotime($dtend_tmp));
                }else{
                    $dtsd_tmp = $dtrc_date.'T'.$time_in;
                    $dtend_tmp = $dtrc_date.'T'.$time_out;
                    $dtsd_d = date('m-d-Y H:i:s', strtotime($dtsd_tmp));
                    $dtend_d = date('m-d-Y H:i:s', strtotime($dtend_tmp));
                }
            }else if($dtrc_type == 'Time-in'){
                if($time_in > $emp_to){
                    $dtrcdt1 = date('Y-m-d', strtotime($dtrc_date. ' + 1 day'));
                    $dtsd_tmp = $dtrc_date.'T'.$time_in;
                    $dtend_tmp = $dtrcdt1.'T'.$emp_to;
                    $dtsd_d = date('m-d-Y H:i:s', strtotime($dtsd_tmp));
                    $dtend_d = null;
                }else{
                    $dtsd_tmp = $dtrc_date.'T'.$time_in;
                    $dtend_tmp = $dtrc_date.'T'.$emp_to;
                    $dtsd_d = date('m-d-Y H:i:s', strtotime($dtsd_tmp));
                    $dtend_d = null;
                }                
            }else{
                if($emp_ti > $time_out){
                    $dtrcdt1 = date('Y-m-d', strtotime($dtrc_date. ' + 1 day'));
                    $dtsd_tmp = $dtrc_date.'T'.$emp_ti;
                    $dtend_tmp = $dtrcdt1.'T'.$time_out;
                    $dtsd_d = null;
                    $dtend_d = date('m-d-Y H:i:s', strtotime($dtend_tmp));
                }else{
                    $dtsd_tmp = $dtrc_date.'T'.$emp_ti;
                    $dtend_tmp = $dtrc_date.'T'.$time_out;
                    $dtsd_d = null;
                    $dtend_d = date('m-d-Y H:i:s', strtotime($dtend_tmp));
                }                

            }
                    
            // echo $emp_to;
            // echo nl2br('\r \n');
            // echo $time_out;
            // exit();
       

            $query = "INSERT INTO tr_dtrcorrect(emp_code,dtrc_date,date_filed,time_in,time_out,remarks,attachment,reporting_to,audituser,auditdate) 
                VALUES(:emp_code,:dtrc_date,:date_filed,:time_in,:time_out,:remarks,:attachment,:empReportingTo,:audituser,:auditdate) ";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $empCode,
                    ":dtrc_date" => $dtrc_date,
                    ":date_filed"=>date('m-d-Y'),
                    ":empReportingTo" => $empReportingTo,
                    ":time_in"=> $dtsd_d,
                    ":time_out"=> $dtend_d,
                    ":remarks"=> $remarks,
                    ":attachment"=> $attchmnt,
                    ":audituser" => $empCode,
                    ":auditdate"=>date('m-d-Y H:i:s')
                );


                // var_dump($param);
                // exit();

            $result = $stmt->execute($param);

            echo $result;

            $squery = "SELECT lastname+', '+firstname as [fullname] FROM employee_profile WHERE emp_code = :empCode";
            $sparam = array(':empCode' => $empCode);
            $sstmt =$connL->prepare($squery);
            $sstmt->execute($sparam);
            $sresult = $sstmt->fetch();
            $sname = $sresult['fullname'];

            $qry = 'SELECT max(rowid) as maxid FROM tr_dtrcorrect WHERE emp_code = :emp_code';
            $prm = array(":emp_code" => $empCode);
            $stm =$connL->prepare($qry);
            $stm->execute($prm);
            $rst = $stm->fetch();

            $querys = "INSERT INTO logs_dtrc (dtrc_id,emp_code,emp_name,remarks,audituser,auditdate) 
                VALUES(:dtrc_id,:emp_code,:emp_name,:remarks,:audituser,:auditdate) ";
                $stmts =$connL->prepare($querys);    
                $params = array(
                    ":dtrc_id" => $rst['maxid'],
                    ":emp_code"=> $empCode,
                    ":emp_name"=> $sname,
                    ":remarks" => 'Apply DTR Correction for '.$dtrc_date,
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
        $mail->Subject = 'DTR Correction Request Sent to Approver: ';
        $mail->Body    = '<h1>Hi '.$napprover.' </b>,</h1>An employee has requested dtr correction(#'.$rst['maxid'].').<br><br>
                        <h2>From: '.$nrequester.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/dtrcorrect/dtrcorrect_app_view.php">DTR Correction Approval List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "DTR Correction Approval List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/dtrcorrect/dtrcorrect_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }                                 


    }
}

?>