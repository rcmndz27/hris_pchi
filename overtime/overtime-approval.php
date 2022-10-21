<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

    Class OvertimeApproval{

        function GetOTSummary($empCode){

            global $connL;

            
            $query = "SELECT  SUM(a.ot_req_hrs) as ot_req_hrs,max(a.rowid) as otrid,b.firstname,b.middlename,b.lastname,a.emp_code from tr_overtime a left join employee_profile b on a.emp_code = b.emp_code WHERE a.reporting_to = :reporting_to and status = 1 GROUP BY b.firstname,b.middlename,b.lastname,a.emp_code";
            $stmt =$connL->prepare($query);
            $param = array(":reporting_to" => $empCode);
            $stmt->execute($param);
            $result = $stmt->fetch();

            echo "
                <table id='employeeOTSummaryList' class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Filed OT Total (Hrs)for Approval</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            if($result){
                do{
                    $otFiled = (isset($result['ot_req_hrs']) ? round($result['ot_req_hrs'],2) : 0);
                    $otFiled = ($otFiled === ".00" ? 0 : $otFiled);
                    echo"
                        <tr>
                            <td>".$result['lastname'].",".$result['firstname']." ".$result['middlename']."</td>
                            <td>"."<button style='width: 9.375rem;' class='penLeave btnPending ".$result['otrid']." ' id='".$result['emp_code']."' type='submit'>".round($result['ot_req_hrs'],2)."</button>
                            <button id='alertot".$result['otrid']."' value='".$otFiled."' hidden></button></td>
                        </tr>";
                } while($result = $stmt->fetch());

                echo "</tbody>";
            }else{
                echo '<tfoot><tr><td colspan="2" class="text-center">No Results Found</td></tr></tfoot>'; 
            }

            echo "</table>";
        }

        function GetOTDetails($empReportingTo,$empId){

            global $connL;

            $query = "SELECT a.ot_req_hrs,a.ot_date,a.ot_ren_hrs,b.firstname,b.middlename,b.lastname,a.emp_code,a.remarks,a.rowid,a.reporting_to,a.attachment from tr_overtime a left join employee_profile b on a.emp_code = b.emp_code WHERE a.reporting_to = :reporting_to AND a.emp_code = :emp_code and status = 1";
            $stmt =$connL->prepare($query);
            $param = array(":reporting_to" => $empReportingTo , ":emp_code" => $empId );
            $stmt->execute($param);
            $result = $stmt->fetch();

            echo "
                <table id='employeeOTDetailList' class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th colspan ='6' class='text-center'>".$result['lastname'].",".$result['firstname']." ".$result['middlename']."</th>
                        </tr>
                        <tr>
                            <th>OT Date</th>
                            <th>Remarks</th>
                            <th>Plan OT</th>
                            <th>OT Rendered</th>
                            <th>OT Approved</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            if($result){
                do{

                    $actualOT = (isset($result['ot_req_hrs']) ? round($result['ot_req_hrs'],2) : 0);

                    echo"
                        <tr id='clv".$result['rowid']."'>
                            <td>".date('F d, Y',strtotime($result['ot_date']))."</td>
                            <td>".$result['remarks']."</td>
                            <td>".round($result['ot_req_hrs'],2)."</td>
                            <td>".$result['ot_ren_hrs']."</td>
                            <td>"."<input type='number' id='ac".$result['rowid']."' class='form-control' value='".round($actualOT,2)."' max='".round($actualOT,2)."' onkeydown='return false' min='0.5' step='0.5'>"."</td>
                            <td hidden>"."<input type='text' class='form-control' value='".$result['reporting_to']."' >"."</td>
                            <td>".
                            "<a title='Attachment' href='../uploads/".$result['attachment']."'   
                                target='popup'); return false;'><button type='button' class='btn btn-primary btn-sm text-white font-weight-bold mr-1'><i class='fas fa-paperclip'></i></button></a>".
                                "<button class='btn btn-success btn-sm btnApproved' id='".$result['rowid']."'><i class='fas fa-check'></i></button> &nbsp;".
                                "<button class='btn btn-danger btn-sm btnRejectd' id='".$result['rowid']."'><i class='fas fa-times'></i></button> &nbsp;";

                           if($result['reporting_to'] == 'PCH20000205') {

                           }else{
                            echo '<button class="btn btn-primary btn-sm btnFwd" id="'.$result['rowid'].'" value="'.$result['rowid'].'"><i class="fas fa-arrow-right"></i><button id="empcode" value="'.$result['emp_code'].'" hidden></button>';
                           }    
                        
                        echo"</td></tr>";
                } while($result = $stmt->fetch());
                echo "</tbody>";
            }else{
                echo '<tfoot><tr><td colspan="6" class="text-center">No Results Found</td></tr></tfoot>'; 
            }

            echo "</table>";
        }

        function ApproveOT($empReportingTo,$empId,$apvdot,$rowid){

            global $connL;


          $rquery = "SELECT firstname+' '+lastname as [fullname],emailaddress FROM employee_profile 
            WHERE emp_code = :empcode";
            $rparam = array(':empcode' => $empId);
            $rstmt =$connL->prepare($rquery);
            $rstmt->execute($rparam);
            $rresult = $rstmt->fetch();
            $e_req = $rresult['emailaddress'];
            $n_req = $rresult['fullname'];

            $query = "SELECT firstname+' '+lastname as [fullname],emailaddress FROM employee_profile WHERE emp_code = :approver";
            $param = array(':approver' => $empReportingTo);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();
            $e_appr = $result['emailaddress'];
            $n_appr = $result['fullname'];
            $apprv_name = $result['fullname'];


            $querys = "INSERT INTO logs_ot (ot_id,emp_code,emp_name,remarks,audituser,auditdate) 
            VALUES(:ot_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";

            $stmts =$connL->prepare($querys);

            $params = array(
                ":ot_id" => $rowid,
                ":emp_code"=> $empId,
                ":emp_name"=> $apprv_name,
                ":remarks" => 'Approved '.$apvdot.' hr/s by '.$apprv_name,
                ":audituser" => $empReportingTo,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

            $results = $stmts->execute($params);

            echo $results;

            $query = "UPDATE tr_overtime SET ot_apprv_hrs = :ot_apvd,ot_req_hrs = :ot_rqd,  status = :apvd_stat, audituser = :audituser, auditdate = :auditdate 
            WHERE reporting_to = :reporting_to AND emp_code = :emp_code AND rowid = :rowid";

            $stmt =$connL->prepare($query);

            $param = array(
                ":ot_apvd" => $apvdot,
                ":ot_rqd" => $apvdot,
                ":rowid" => $rowid,
                ":emp_code" => $empId,
                ":reporting_to" => $empReportingTo,
                ":apvd_stat" => 2,
                ":audituser" => $empReportingTo,
                ":auditdate" => date('m-d-Y')
            );

            $result = $stmt->execute($param);

            echo $result;

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
        $mail->Password   = '@dmin123@dmin123';                              
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                   

        $mail->setFrom('hris-support@obanana.com','HRIS-NOREPLY');
        $mail->addAddress($erequester,'Requester');    

        $mail->isHTML(true);                          
        $mail->Subject = 'Approved Overtime Request  ';
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>Your overtime request #'.$rowid.' has been approved.<br><br>
                        <h2>From: '.$napprover.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/overtime/ot_app_view.php">Overtime Request List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "Overtime Request List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/overtime/ot_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
         


             
        }

        function RejectOT($empReportingTo,$empId,$rjctRsn,$rowid){

            global $connL;


         $rquery = "SELECT firstname+' '+lastname as [fullname],emailaddress FROM employee_profile 
            WHERE emp_code = :empcode";
            $rparam = array(':empcode' => $empId);
            $rstmt =$connL->prepare($rquery);
            $rstmt->execute($rparam);
            $rresult = $rstmt->fetch();
            $e_req = $rresult['emailaddress'];
            $n_req = $rresult['fullname'];

            $query = "SELECT firstname+' '+lastname as [fullname],emailaddress FROM employee_profile WHERE emp_code = :approver";
            $param = array(':approver' => $empReportingTo);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();
            $e_appr = $result['emailaddress'];
            $n_appr = $result['fullname'];
            $apprv_name = $result['fullname'];

                $querys = "INSERT INTO logs_ot (ot_id,emp_code,emp_name,remarks,audituser,auditdate) 
                VALUES(:ot_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";
    
                $stmts =$connL->prepare($querys);
    
            $params = array(
                ":ot_id" => $rowid,
                ":emp_code"=> $empId,
                ":emp_name"=> $apprv_name,
                ":remarks" => 'Rejected by '.$apprv_name.'. Reason: '.$rjctRsn,
                ":audituser" => $empReportingTo,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

            $results = $stmts->execute($params);

            echo $results;

            // exit();

            $query = " UPDATE tr_overtime 
            SET status = :apvd_stat, audituser = :audituser, auditdate = :auditdate, reject_reason = :reject_reason
            WHERE reporting_to = :reporting_to AND emp_code = :emp_code AND rowid = :rowid";

            $stmt =$connL->prepare($query);

            $param = array(
                ":apvd_stat" => 3,
                ":emp_code" => $empId,
                ":rowid" => $rowid,
                ":reporting_to" => $empReportingTo,
                ":reject_reason" => $rjctRsn,
                ":audituser" => $empReportingTo,
                ":auditdate" => date('m-d-Y')
            );

            $result = $stmt->execute($param);

            echo $result;

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
        $mail->Password   = '@dmin123@dmin123';                              
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                   

        $mail->setFrom('hris-support@obanana.com','HRIS-NOREPLY');
        $mail->addAddress($erequester,'Requester');    

        $mail->isHTML(true);                          
        $mail->Subject = 'Rejected Overtime Request  ';
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>Your overtime request #'.$rowid.' has been rejected.<br><br>
                        <h2>From: '.$napprover.' <br></h2>
                        <h2>Reason: '.$rjctRsn.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/overtime/ot_app_view.php">Overtime Request List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "Overtime Request List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/overtime/ot_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            
        }


        function FwdOT($empReportingTo,$empId,$approver,$rowid){
        

        global $connL;

        $cmd = $connL->prepare("UPDATE dbo.tr_overtime SET reporting_to = :approval where rowid = :rowid");
        $cmd->bindValue('approval','PCH20000205');         
        $cmd->bindValue('rowid',$rowid);                           
        $cmd->execute();
    

        $query = "SELECT firstname+' '+lastname as [fullname],emailaddress FROM employee_profile 
        WHERE emp_code = :empcode";
        $param = array(':empcode' => $approver);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();
        $e_appr = $result['emailaddress'];
        $n_appr = $result['fullname'];        
        $aprvname = $result['fullname'];

        $query = "INSERT INTO logs_ot (ot_id,emp_code,emp_name,remarks,audituser,auditdate) 
                VALUES(:ot_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";
    
                $stmt =$connL->prepare($query);
    
                $param = array(
                    ":ot_id" => $rowid,
                    ":emp_code"=> $approver,
                    ":emp_name"=> $aprvname,
                    ":remarks" => 'Forwarded to Sir.Francis Calumba',
                    ":audituser" => $approver,
                    ":auditdate"=>date('m-d-Y H:i:s')
                );

            $result = $stmt->execute($param);

            echo $result;
        // $erequester = 'fcalumba@premiummegastructures.com';
        // $nrequester = 'Francis Calumba';
        $erequester = 'fcalumba@premiummegastructures.com';
        $nrequester = 'Francis Calumba';            
        $eapprover = $e_appr;
        $napprover = $n_appr;

        $mail = new PHPMailer(true);
        try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;      
        $mail->isSMTP();                                           
        $mail->Host       = 'mail.obanana.com'; 
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'hris-support@obanana.com';        
        $mail->Password   = '@dmin123@dmin123';                              
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                   

        $mail->setFrom('hris-support@obanana.com','HRIS-NOREPLY');
        $mail->addAddress($erequester,'President');    

        $mail->isHTML(true);                          
        $mail->Subject = 'Forward Request to the President: ';
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>The overtime request #'.$rowid.' has been forwarded to you for your approval.<br><br>
                        <h2>From: '.$napprover.' <br><br></h2>
    
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/overtime/ot_app_view.php">Overtime Approval List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br><br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "Overtime Approval List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/overtime/ot_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }        
    
    }

        function GetEmployeeList($employee){

            global $connL;
    
            $query = 'SELECT emp_code, employee FROM dbo.view_employee WHERE employee LIKE :employee ORDER BY employee';
            $param = array(':employee' => $employee);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
    
            $result = $stmt->fetchAll();
    
            $employeeList = '';
            $employeeList =  '<ul id="empList">';
    
            foreach ($result as $key => $value) {
                $employeeList.= '<li value="'.$value['emp_code'].'">'.$value['employee'].'</li>';
            }
    
            $employeeList.= '</ul>';
    
            echo  $employeeList;
        }
    
        function GetApprovedList($employee){
            
            global $connL;

            $query = 'SELECT b.firstname,b.middlename,b.lastname, a.datefiled, a.ot_date, a.ot_ren_hrs, a.ot_type FROM dbo.tr_overtime a left join employee_profile b on a.emp_code = b.emp_code WHERE a.emp_code = :emp_code and a.status = 2';
            $param = array(':emp_code' => $employee);
            $stmt = $connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();
    
    
            echo '
            <table id="approvedList" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Date Filed</th>
                    <th>OT Date</th>
                    <th>Rendererd OT</th>
                    <th>OT Type</th>
                </tr>
            </thead>
            <tbody>';
    
            
            if($result){
                do { 
                    echo '
                    <tr>
                    <td>'.$result['lastname'].",".$result['firstname']." ".$result['middlename'].'</td>
                    <td>' . date('m-d-Y', strtotime($result['datefiled'])) . '</td>
                    <td>' . date('m-d-Y', strtotime($result['ot_date'])) . '</td>
                    <td>' . $result['ot_ren_hrs'] . '</td>
                    <td>' . $result['ot_type'] . '</td>
                    </tr>';
    
                } while ($result = $stmt->fetch());
    
                echo '</tbody>';
    
            }else { 
                echo '<tfoot><tr><td colspan="8" class="text-center">No Results Found</td></tr></tfoot>'; 
            }
            echo '</table>';
        } 
    
        
    }
?>