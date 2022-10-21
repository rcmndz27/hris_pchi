<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';


Class WfhApp{

    private $employeeCode;
    
    public function SetWfhAppParams($employeeCode){
        $this->employeeCode = $employeeCode;
    }

        public function GetAllWfhAppHistory($date_from,$date_to,$status){

        global $connL;

        echo '
        <div class="form-row mb-2">  
                    <div class="col-lg-1">
                        <select class="form-select" name="state" id="maxRows">
                             <option value="5000">ALL</option>
                             <option value="5">5</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="50">50</option>
                             <option value="70">70</option>
                             <option value="100">100</option>
                        </select> 
                </div>         
                <div class="col-lg-8">
                </div>                               
                <div class="col-lg-3">        
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for work from home.." title="Type in work from home details"> 
                        </div>                     
                </div>   ';
                echo"    
        <button id='btnExport' onclick='exportReportToExcel(this)' class='btn btn-primary'><i class='fas fa-file-export'></i>Export</button>  ";
        echo'        
        <table id="WfhListTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>WFH Date</th>
                <th>Name</th>
                <th>Task</th>
                <th>Expected Output</th>
                <th>Output</th>
                <th>Percentage %</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid  as wfhid,a.emp_code as empcd,b.rowid as attid,c.lastname+','+c.firstname as fullname,* 
                    FROM dbo.tr_workfromhome a
                    left join employee_attendance b
                    on RIGHT(A.emp_code, LEN(A.emp_code) - 3) = b.emp_code
                    and a.wfh_date = b.punch_date
                    left join employee_profile c on a.emp_code = c.emp_code
                     where status = :status  and wfh_date between :startDate and :endDate 
                    ORDER BY wfh_date DESC";
        $param = array(":startDate" => date('Y-m-d',strtotime($date_from)),":endDate" => date('Y-m-d',strtotime($date_to)),":status" => $status);                    
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 

                $wfhdate = "'".date('m-d-Y', strtotime($result['wfh_date']))."'";
                $wfhtask = "'".$result['wfh_task']."'";
                $wfhoutput = "'".$result['wfh_output']."'";
                $wfhpercentage = "'".$result['wfh_percentage']."'";
                $wfhstats = "'".$result['stats']."'";
                $wfhid = "'".$result['wfhid']."'";
                $empcode = "'".$result['empcd']."'";
                $attid = "'".$result['attid']."'";
                echo "
                <tr>
                <td>" . date('F d,Y', strtotime($result['wfh_date']))."</td>
                <td>" . $result['fullname']."</td>
                <td>" . $result['wfh_task'] ."</td>
                <td>" . (isset($result['wfh_output']) ? $result['wfh_output'] : 'n/a') ."</td>
                <td>" . (isset($result['wfh_output2']) ? $result['wfh_output2'] : 'n/a') ."</td>
                <td>" . $result['wfh_percentage']."</td>
                <td id='ti".$result['wfhid']."'>".(isset($result['timein']) ? date('h:i A', strtotime($result['timein'])) : 'n/a') . "</td>
                <td id='to".$result['wfhid']."'>".(isset($result['timeout']) ? date('h:i A', strtotime($result['timeout'])) : 'n/a') . "</td>
                <td id='st".$result['wfhid']."'>" . $result['stats']."</td>";
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhpercentage.','.$wfhstats.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                        
                            </td>';
                                          


            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="8" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>
        <div class="pagination-container">
        <nav>
          <ul class="pagination">
            
            <li data-page="prev" >
                <span> << <span class="sr-only">(current)</span></span></li>
    
          <li data-page="next" id="prev">
                  <span> >> <span class="sr-only">(current)</span></span>
            </li>
          </ul>
        </nav>
      </div>';
    }

public function GetAllWfhRepHistory($date_from,$date_to,$empCode){

        global $connL;

        echo '
        <div class="form-row mb-2">  
                    <div class="col-lg-1">
                        <select class="form-select" name="state" id="maxRows">
                             <option value="5000">ALL</option>
                             <option value="5">5</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="50">50</option>
                             <option value="70">70</option>
                             <option value="100">100</option>
                        </select> 
                </div>         
                <div class="col-lg-8">
                </div>                               
                <div class="col-lg-3">        
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunctionRep()" placeholder="Search for work from home.." title="Type in work from home details"> 
                        </div>                     
                </div>         
        <table id="WfhListRepTab" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>WFH Date</th>
                <th>Name</th>
                <th>Task</th>
                <th>Expected Output</th>
                <th>Output</th>
                <th>Percentage %</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid  as wfhid,a.emp_code as empcd,b.rowid as attid,c.lastname+','+c.firstname as fullname,* 
                    FROM dbo.tr_workfromhome a
                    left join employee_attendance b
                    on RIGHT(A.emp_code, LEN(A.emp_code) - 3) = b.emp_code
                    and a.wfh_date = b.punch_date
                    left join employee_profile c on a.emp_code = c.emp_code
                     where a.emp_code = :empCode  and wfh_date between :startDate and :endDate 
                    ORDER BY wfh_date DESC";
        $param = array(":startDate" => date('Y-m-d',strtotime($date_from)),":endDate" => date('Y-m-d',strtotime($date_to)),":empCode" => $empCode);                    
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 

                $wfhdate = "'".date('m-d-Y', strtotime($result['wfh_date']))."'";
                $wfhtask = "'".$result['wfh_task']."'";
                $wfhoutput = "'".$result['wfh_output']."'";
                $wfhpercentage = "'".$result['wfh_percentage']."'";
                $wfhstats = "'".$result['stats']."'";
                $wfhid = "'".$result['wfhid']."'";
                $empcode = "'".$result['empcd']."'";
                $attid = "'".$result['attid']."'";
                echo "
                <tr>
                <td>" . date('F d,Y', strtotime($result['wfh_date']))."</td>
                <td>" . $result['fullname']."</td>
                <td>" . $result['wfh_task'] ."</td>
                <td>" . (isset($result['wfh_output']) ? $result['wfh_output'] : 'n/a') ."</td>
                <td>" . (isset($result['wfh_output2']) ? $result['wfh_output2'] : 'n/a') ."</td>
                <td>" . $result['wfh_percentage']."</td>
                <td id='ti".$result['wfhid']."'>".(isset($result['timein']) ? date('h:i A', strtotime($result['timein'])) : 'n/a') . "</td>
                <td id='to".$result['wfhid']."'>".(isset($result['timeout']) ? date('h:i A', strtotime($result['timeout'])) : 'n/a') . "</td>
                <td id='st".$result['wfhid']."'>" . $result['stats']."</td>";
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhpercentage.','.$wfhstats.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                        
                            </td>';
                                          


            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="8" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>
        <div class="pagination-container">
        <nav>
          <ul class="pagination">
            
            <li data-page="prev" >
                <span> << <span class="sr-only">(current)</span></span></li>
    
          <li data-page="next" id="prev">
                  <span> >> <span class="sr-only">(current)</span></span>
            </li>
          </ul>
        </nav>
      </div>';
    }


    public function GetWfhAppHistory(){
        global $connL;

        echo '
        <div class="form-row">  
                    <div class="col-lg-1">
                        <select class="form-select" name="state" id="maxRows">
                             <option value="5000">ALL</option>
                             <option value="5">5</option>
                             <option value="10">10</option>
                             <option value="15">15</option>
                             <option value="20">20</option>
                             <option value="50">50</option>
                             <option value="70">70</option>
                             <option value="100">100</option>
                        </select> 
                </div>         
                <div class="col-lg-8">
                </div>                               
                <div class="col-lg-3">        
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for work from home.." title="Type in work from home details"> 
                        </div>                     
                </div>         
        <table id="wfhList" class="table table-sm">
        <thead>
            <tr>
                <th>WFH Date</th>
                <th>Task</th>
                <th>Expected Output</th>
                <th>Output</th>
                <th>Percentage %</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid  as wfhid,a.emp_code as empcd,b.rowid as attid,c.firstname+' '+c.lastname as approver,* 
                    FROM dbo.tr_workfromhome a
                    left join employee_profile c on a.reporting_to = c.emp_code
                    left join employee_attendance b
                    on RIGHT(A.emp_code, LEN(A.emp_code) - 3) = b.emp_code
                    and a.wfh_date = b.punch_date where a.emp_code = :emp_code ORDER BY wfh_date DESC";
        $param = array(':emp_code' => $this->employeeCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 

                $wfhdate = "'".date('m-d-Y', strtotime($result['wfh_date']))."'";
                $wfhtask = "'".$result['wfh_task']."'";
                $wfhoutput = "'".$result['wfh_output']."'";
                $wfhoutput2 = "'".$result['wfh_output2']."'";
                $wfhpercentage = "'".$result['wfh_percentage']."'";
                $wfhstats = "'".$result['stats']."'";
                $wfhid = "'".$result['wfhid']."'";
                $appr_over = "'".$result['approver']."'";
                $empcode = "'".$result['empcd']."'";
                $attid = "'".$result['attid']."'";
                $atch = "'".$result['attachment']."'";
                $onclick = 'onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')"';
                echo "
                <tr class='csor-pointer'>
                <td ".$onclick.">" . date('F d, Y', strtotime($result['wfh_date']))."</td>
                <td ".$onclick.">" . $result['wfh_task'] ."</td>
                <td ".$onclick.">" . (isset($result['wfh_output']) ? $result['wfh_output'] : 'n/a') ."</td>
                <td ".$onclick.">" . (isset($result['wfh_output2']) ? $result['wfh_output2'] : 'n/a') ."</td>
                <td ".$onclick.">" . $result['wfh_percentage']."</td>
                <td ".$onclick." id='ti".$result['wfhid']."'>".(isset($result['timein']) ? date('h:i A', strtotime($result['timein'])) : 'n/a') . "</td>
                <td ".$onclick." id='to".$result['wfhid']."'>".(isset($result['timeout']) ? date('h:i A', strtotime($result['timeout'])) : 'n/a') . "</td>
                <td ".$onclick." id='st".$result['wfhid']."'>" . $result['stats']."</td>";
                if($result['stats'] == 'PENDING' and $result['wfh_date'] <> date('Y-m-d')){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                           
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>
                            </td>';
                }else if($result['stats'] == 'PENDING'and $result['wfh_date'] == date('Y-m-d')){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                           ';

                            if(empty($result['timein']) && empty($result['timeout'])){
                                echo'
                            <button type="button"  id="tin'.$result['wfhid'].'" class="btn btn-primary btn-sm" onclick="timeInModal('.$wfhid.','.$empcode.')" title="Time In">
                                <i class="fas fa-play"></i>
                            </button> 
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>                                                       
                            </td>';
                            }else if(!empty($result['timein']) && empty($result['timeout'])){
                                echo'<button type="button" id="tout'.$result['wfhid'].'" class="btn btn-success btn-sm" onclick="timeOutModal('.$wfhid.','.$empcode.','.$attid.')" title="Time Out">
                                <i class="fas fa-hand-paper"></i>
                            </button>
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>                                                        
                            </td>';

                            }else{

                            }

                }else if($result['stats'] == 'APPROVED'and $result['wfh_date'] == date('Y-m-d')){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                           ';

                            if(empty($result['timein']) && empty($result['timeout'])){
                                echo'
                            <button type="button" id="tin'.$result['wfhid'].'" class="btn btn-primary btn-sm" onclick="timeInModal('.$wfhid.','.$empcode.')" title="Time In">
                                <i class="fas fa-play"></i>
                            </button>
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>                                                        
                            </td>';
                            }else if(!empty($result['timein']) && empty($result['timeout'])){
                                echo'<button type="button"  id="tout'.$result['wfhid'].'" class="btn btn-success btn-sm" onclick="timeOutModal('.$wfhid.','.$empcode.','.$attid.')" title="Time Out">
                                <i class="fas fa-hand-paper"></i>
                            </button>
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>                                                        
                            </td>';

                            }else{

                            }
                }else if($result['stats'] == 'APPROVED' and $result['wfh_date'] <> date('Y-m-d') and !isset($result['timein']) and !isset($result['timeout'])){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>
                            <button type="button" id="clv'.$result['wfhid'].'" class="btn btn-danger btn-sm" onclick="cancelWfh('.$wfhid.','.$empcode.')" title="Cancel Work From Home">
                                <i class="fas fa-ban"></i>
                            </button>                         ';

                }else if($result['stats'] == 'CANCELLED'){
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                                                   
                            </td>';
                }else{
                echo'
                <td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewWfhModal('.$wfhdate.','.$wfhtask.','.$wfhoutput.','.$wfhoutput2.','.$wfhpercentage.','.$wfhstats.','.$appr_over.','.$atch.')" title="View Work From Home">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="viewWfhHistoryModal('.$wfhid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button> 
                            </td>';
                }                            


            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="9" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>
        <div class="pagination-container">
        <nav>
          <ul class="pagination">
            
            <li data-page="prev" >
                <span> << <span class="sr-only">(current)</span></span></li>
    
          <li data-page="next" id="prev">
                  <span> >> <span class="sr-only">(current)</span></span>
            </li>
          </ul>
        </nav>
      </div>';
    }

    public function InsertAppliedWfhApp($empCode,$empReportingTo,$wfhDate,$wfh_task,$wfh_output,$wfh_percentage,$e_req,$n_req,$e_appr,$n_appr,$attachment){

            global $connL;

            $query = "INSERT INTO tr_workfromhome (emp_code,wfh_date,date_filed,wfh_task,wfh_output,wfh_percentage,reporting_to,attachment,audituser,auditdate) 
                VALUES(:emp_code,:wfhDate,:date_filed,:wfh_task,:wfh_output,:wfh_percentage,:empReportingTo,:attachment,:audituser,:auditdate) ";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $empCode,
                    ":wfhDate" => $wfhDate,
                    ":date_filed"=>date('m-d-Y'),
                    ":empReportingTo" => $empReportingTo,
                    ":wfh_task"=> $wfh_task,
                    ":wfh_output"=> $wfh_output,
                    ":wfh_percentage"=> $wfh_percentage,
                    ":attachment"=> $attachment,
                    ":audituser" => $empCode,
                    ":auditdate"=>date('m-d-Y H:i:s')
                );

            $result = $stmt->execute($param);

            echo $result;

            $squery = "SELECT lastname+', '+firstname as [fullname] FROM employee_profile WHERE emp_code = :empCode";
            $sparam = array(':empCode' => $empCode);
            $sstmt =$connL->prepare($squery);
            $sstmt->execute($sparam);
            $sresult = $sstmt->fetch();
            $sname = $sresult['fullname'];

            $qry = 'SELECT max(rowid) as maxid FROM tr_workfromhome WHERE emp_code = :emp_code';
            $prm = array(":emp_code" => $empCode);
            $stm =$connL->prepare($qry);
            $stm->execute($prm);
            $rst = $stm->fetch();

            $querys = "INSERT INTO logs_wfh (wfh_id,emp_code,emp_name,remarks,audituser,auditdate) 
                VALUES(:wfh_id, :emp_code,:emp_name,:remarks,:audituser, :auditdate) ";
    
                $stmts =$connL->prepare($querys);
    
                $params = array(
                    ":wfh_id" => $rst['maxid'],
                    ":emp_code"=> $empCode,
                    ":emp_name"=> $sname,
                    ":remarks" => 'Apply WFH for '.$wfhDate,
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
        $mail->Password   = '@dmin123@dmin123';                              
        $mail->SMTPSecure = 'tls';            
        $mail->Port       = 587;                                   

        $mail->setFrom('hris-support@obanana.com','HRIS-NOREPLY');
        $mail->addAddress($eapprover,'Approver');    

        $mail->isHTML(true);                          
        $mail->Subject = 'Work from Home Request Sent to Approver: ';
        $mail->Body    = '<h1>Hi '.$napprover.' </b>,</h1>An employee has requested a work from home(#'.$rst['maxid'].').<br><br>
                        <h2>From: '.$nrequester.' <br><br></h2>
                        <h2>Check the request in :
                        <a href="http://124.6.185.87:6868/wfhome/wfh-approval-view.php">Work from Home Approval List</a> 
                        <br><br></h2>

                        Thank you for using our application! <br>
                        Regards, <br>
                        Human Resource Information System <br> <br>

                        <h6>If you are having trouble clicking the "Work from Home Approval List" button, copy and paste the URL below into your web browser: http://124.6.185.87:6868/wfhome/wfh-approval-view.php <h6>
                       ';
            $mail->send();
            // echo 'Message has been sent';
            } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }                                 

    }
}

?>