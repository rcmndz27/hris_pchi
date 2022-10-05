<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

    Class DtrCorrectApproval{

        function GetDtrCorrectSummary($empCode){

            global $connL;
            
            $query = "SELECT count(dtrc_date) as [dtrc],b.firstname,b.middlename,b.lastname,a.emp_code from tr_dtrcorrect a left join employee_profile b on a.emp_code = b.emp_code  WHERE a.reporting_to = :reporting_to and status = 1 GROUP BY b.firstname,b.middlename,b.lastname,a.emp_code";
            $stmt =$connL->prepare($query);
            $param = array(":reporting_to" => $empCode);
            $stmt->execute($param);
            $result = $stmt->fetch();


            echo "
                <table id='employeeDTRCSummaryList' class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Filed DTR Total (days) for Approval</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            if($result){
                do{
                    $dtrcFiled = (isset($result['dtrc']) ? $result['dtrc'] : 0);
                    $dtrcFiled = ($dtrcFiled === ".00" ? 0 : $dtrcFiled);
                    echo"
                        <tr>
                            <td>".$result['lastname'].",".$result['firstname']." ".$result['middlename']."</td>
                            <td>"."<button style='width: 9.375rem;' class='penLeave btnPending' id='".$result['emp_code']."' 
                            type='submit'>".$dtrcFiled."</button>
                            <button id='alertdtrc' value='".$dtrcFiled."' hidden></button></td>
                        </tr>";
                } while($result = $stmt->fetch());

                echo "</tbody>";
            }else{
                echo '<tfoot><tr><td colspan="2" class="text-center">No Results Found</td></tr></tfoot>'; 
            }

            echo "</table>";
        }

        function GetDtrCorrectDetails($empReportingTo,$empId){

            global $connL;

            $query = "SELECT a.rowid,b.firstname,b.middlename,b.lastname,a.emp_code,a.dtrc_date,a.time_in,a.time_out,a.remarks,a.date_filed,a.status,a.reporting_to,a.attachment from tr_dtrcorrect a left join employee_profile b on a.emp_code = b.emp_code WHERE a.reporting_to = :reporting_to AND a.emp_code = :emp_code and status = 1";
            $stmt =$connL->prepare($query);
            $param = array(":reporting_to" => $empReportingTo , ":emp_code" => $empId );
            $stmt->execute($param);
            $result = $stmt->fetch();

            echo "
                <table id='employeedtrcDetailList' class='table table-striped table-sm'>
                    <thead>
                        <tr>
                            <th colspan ='6' class='text-center'>".$result['lastname'].",".$result['firstname']." ".$result['middlename']."</th>
                        </tr>
                        <tr>
                            <th>Date Filed</th>
                            <th>DTR Date</th>
                            <th>Time-In</th>
                            <th>Time-Out </th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            if($result){
                do{
                    $t_in = (isset($result['time_in'])) ? date('h:i A', strtotime($result['time_in'])) : 'n/a';
                    $t_out = (isset($result['time_out'])) ? date('h:i A', strtotime($result['time_out'])) : 'n/a';
                    $attchmnt = $result['attachment'];
                    echo"
                             <tr id='clv".$result['rowid']."'>
                            <td>".date('m-d-Y',strtotime($result['date_filed']))."</td>
                            <td>".date('F d, Y', strtotime($result['dtrc_date']))."</td>
                            <td>".$t_in."</td>
                            <td>".$t_out."</td>
                            <td>".$result['remarks']."</td>
                            <td hidden>"."<input type='text' class='form-control' 
                            value='".$result['reporting_to']."' >"."</td>

                            <td>";

                            if(empty($attchmnt)){
                            }else {
                            echo"<button type='button' class='btn btn-primary btn-sm'><a title='Attachment' href='../uploads/dtrc/".$result['attachment']."' style='color:#ffff;font-weight:bold;'  
                            target='popup' onclick='window.open('../uploads/dtrc/".$result['attachment']."' ','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;'><i class='fas fa-paperclip'></i></a></button>&nbsp;";  
                            }  
                            echo"<button class='btn btn-success btn-sm btnApproved' id='".$result['rowid']."'><i class='fas fa-check'></i></button> &nbsp".
                                "<button class='btn btn-danger btn-sm btnRejectd' id='".$result['rowid']."'><i class='fas fa-times'></i></button>&nbsp;";

                            if($result['reporting_to'] == 'PCHI20000205') {

                            }else{
                            echo '<button class="btn btn-warning btn-sm btnFwd" id="'.$result['rowid'].'" value="'.$result['rowid'].'"><i class="fas fa-arrow-right"></i><button id="empcode" value="'.$result['emp_code'].'" hidden></button>';
                            }


                                echo "</td></tr>";                                
                } while($result = $stmt->fetch());
                echo "</tbody>";
            }else{
                echo '<tfoot><tr><td colspan="6" class="text-center">No Results Found</td></tr></tfoot>'; 
            }

            echo "</table>";
        }

        function ApproveDtrCorrect($empReportingTo,$empId,$rowId){

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

        $pquery = "SELECT * FROM tr_dtrcorrect WHERE emp_code = :emp_code and rowid = :rowId";
        $pparam = array(':emp_code' => $empId,':rowId' => $rowId);
        $pstmt =$connL->prepare($pquery);
        $pstmt->execute($pparam);
        $presult = $pstmt->fetch();
        $p_in = $presult['time_in'];
        $p_out = $presult['time_out'];
        $p_date = $presult['dtrc_date'];

        $fquery = "SELECT * FROM employee_attendance WHERE emp_code = :emp_code and punch_date = :p_date ";
        $fparam = array(':emp_code' => substr($empId,3),':p_date' => $p_date);
        $fstmt =$connL->prepare($fquery);
        $fstmt->execute($fparam);    
        $fresult = $fstmt->fetch();  
        $ea_date = $fresult['punch_date']; 
        $ea_name = $fresult['name']; 
        $ea_in = $fresult['timein'];
        $ea_out = $fresult['timeout'];
        $ea_empc = $fresult['emp_code'];  


        if(isset($ea_date)){
                if(isset($ea_in) and isset($ea_out)){
                    $queryn = "UPDATE employee_attendance SET timein = :timein, timeout = :timeout , emp_id = 0
                    WHERE emp_code = :emp_code AND punch_date = :punch_date";
                    $stmtn =$connL->prepare($queryn);
                    $paramn = array(
                        ":emp_code"=> $ea_empc,
                        ":punch_date"=> $ea_date,
                        ":timein" => $p_in,
                        ":timeout"=> $p_out
                    );
                    $stmtn->execute($paramn);
                }else if(isset($ea_in) and !isset($ea_out)){
                    $queryn = "UPDATE employee_attendance SET timeout = :timeout , emp_id = 0
                    WHERE emp_code = :emp_code AND punch_date = :punch_date";
                    $stmtn =$connL->prepare($queryn);
                    $paramn = array(
                        ":emp_code"=> $ea_empc,
                        ":punch_date"=> $ea_date,
                        ":timeout" => $p_out
                    );   
                    $stmtn->execute($paramn);
                }else{
                    $queryn = "UPDATE employee_attendance SET timein = :timein , emp_id = 0
                    WHERE emp_code = :emp_code AND punch_date = :punch_date";
                    $stmtn =$connL->prepare($queryn);
                    $paramn = array(
                        ":emp_code"=> $ea_empc,
                        ":emp_code"=> $ea_empc,
                        ":punch_date"=> $ea_date,
                        ":timein" => $p_in
                    );
                    $stmtn->execute($paramn);                                     
                }
        }else{
            $querym = "INSERT INTO employee_attendance (emp_code,emp_id,name,punch_date,timein,timeout) 
            VALUES(:emp_code,:emp_id,:name,:punch_date,:timein,:timeout) ";

            $stmtm =$connL->prepare($querym);

            $paramm = array(
                ":emp_code"=> substr($empId,3),
                ":emp_id"=> 0,
                ":name"=> $n_req,
                ":punch_date"=> $p_date,
                ":timein" => $p_in,
                ":timeout"=> $p_out
            );

             $resultm = $stmtm->execute($paramm);
            echo $resultm;
        }    

            $querys = "INSERT INTO logs_dtrc (dtrc_id,emp_code,emp_name,remarks,audituser,auditdate) 
            VALUES(:dtrc_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";

            $stmts =$connL->prepare($querys);

            $params = array(
                ":dtrc_id" => $rowId,
                ":emp_code"=> $empId,
                ":emp_name"=> $apprv_name,
                ":remarks" => 'Approved by '.$apprv_name,
                ":audituser" => $empReportingTo,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

            $results = $stmts->execute($params);

            echo $results;

            $query = " UPDATE tr_dtrcorrect SET status = :apvd_stat, audituser = :audituser, auditdate = :auditdate 
            WHERE reporting_to = :reporting_to AND emp_code = :emp_code AND rowid = :rowid";

            $stmt =$connL->prepare($query);

            $param = array(
                ":emp_code" => $empId,
                ":rowid" => $rowId,
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
        $mail->Subject = 'Approved DTR Correction Request  ';
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>Your dtr correction request #'.$rowId.' has been approved.<br><br>
                        <h2>From: '.$napprover.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php">DTR Correction Request List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "DTR Correction Request List" button, copy and paste the URL below into your web browser: http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }                       
        }

        function RejectDtrCorrect($empReportingTo,$empId,$rjctRsn,$rowId){

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


            $querys = "INSERT INTO logs_dtrc (dtrc_id,emp_code,emp_name,remarks,audituser,auditdate) 
            VALUES(:dtrc_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";

            $stmts =$connL->prepare($querys);

            $params = array(
                ":dtrc_id" => $rowId,
                ":emp_code"=> $empId,
                ":emp_name"=> $apprv_name,
                ":remarks" => 'Rejected by '.$apprv_name.'. Reason:'.$rjctRsn,
                ":audituser" => $empReportingTo,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

            $results = $stmts->execute($params);

            echo $results;

            $query = "UPDATE tr_dtrcorrect SET status = :apvd_stat, audituser = :audituser, auditdate = :auditdate, reject_reason = :reject_reason WHERE reporting_to = :reporting_to AND emp_code = :emp_code AND rowid = :rowid";

            $stmt =$connL->prepare($query);

            $param = array(
                ":apvd_stat" => 3,
                ":emp_code" => $empId,
                ":rowid" => $rowId,
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
        $mail->Subject = 'Rejected DTR Correction Request  ';
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>Your dtr correction request #'.$rowid.' has been rejected.<br><br>
                        <h2>From: '.$napprover.' <br></h2>
                        <h2>Reason: '.$rjctRsn.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php">DTR Correction Request List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "DTR Correction Request List" button, copy and paste the URL below into your web browser: http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }            
                       
        }


function FwdDtrCorrect($empReportingTo,$empId,$approver,$rowid){
        

        global $connL;

        $cmd = $connL->prepare("UPDATE dbo.tr_dtrcorrect SET reporting_to = :approval where rowid = :rowid");
        $cmd->bindValue('approval','PCHI20000205');         
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

        $query = "INSERT INTO logs_dtrc (dtrc_id,emp_code,emp_name,remarks,audituser,auditdate) 
                VALUES(:dtrc_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";
    
                $stmt =$connL->prepare($query);
    
                $param = array(
                    ":dtrc_id" => $rowid,
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
        $mail->Body    = '<h1>Hi '.$nrequester.' </b>,</h1>The dtr correction request #'.$rowid.' has been forwarded to you for your approval.<br><br>
                        <h2>From: '.$napprover.' <br><br></h2>
    
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php">DTR Correction Approval List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br><br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "DTR Correction Approval List" button, copy and paste the URL below into your web browser: http://124.6.185.87:4200/hris_pchi/dtrcorrect/dtrcorrect_app_view.php <h6>
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
        
    }
?>