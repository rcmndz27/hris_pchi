<?php

Class DtrCorrectApp{

    private $employeeCode;
    
    public function SetdtrcorrectAppParams($employeeCode){
        $this->employeeCode = $employeeCode;
    }


    public function GetdtrcorrectAppHistory(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for dtr correction.." title="Type in dtr correction details"> 
                        </div>                     
                </div>         
        <table id="wfhList" class="table table-striped table-sm">
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
                    when   status = 4 then 'VOID' ELSE 'N/A' END) as stats,* FROM dbo.tr_dtrcorrect where emp_code = :emp_code ORDER BY dtrc_date DESC";
        $param = array(':emp_code' => $this->employeeCode);
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
                $dtrcid = "'".$result['rowid']."'";
                $empcode = "'".$result['emp_code']."'";
                echo '
                <tr>
                <td>'.date('m-d-Y', strtotime($result['dtrc_date'])).'</td>
                <td>'.date('h:i a', strtotime($result['time_in'])).'</td>
                <td>'.date('h:i a', strtotime($result['time_out'])).'</td>
                <td>'.$result['remarks'] . '</td>
                <td id="st'.$result['rowid'].'">'.$result['stats'].'</td>';
                if($result['stats'] == 'PENDING'){
                echo'
                <td><button type="button" class="hactv" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.')" title="View DTR Correction">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="hdeactv" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                           
                            <button type="button" id="clv" class="voidBut" onclick="canceldtrcorrect('.$dtrcid.','.$empcode.')" title="Cancel DTR Correction">
                                <i class="fas fa-ban"></i>
                            </button>
                            </td>';
                }else{
                echo'
                <td><button type="button" class="hactv" onclick="viewdtrcorrectModal('.$dtrcdate.','.$timein.','.$timeout.','.$rmrks.','.$stts.')" title="View DTR Correction">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="hdeactv" onclick="viewdtrcorrectHistoryModal('.$dtrcid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>                       
                            </td>';
                }                            


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

    public function InsertAppliedDtrCorrectApp($empCode,$empReportingTo,$dtrc_date,$time_in,$time_out,$remarks){

            global $connL;

            $query = "INSERT INTO tr_dtrcorrect(emp_code,dtrc_date,date_filed,time_in,time_out,remarks,reporting_to,audituser,auditdate) 
                VALUES(:emp_code,:dtrc_date,:date_filed,:time_in,:time_out,:remarks,:empReportingTo,:audituser,:auditdate) ";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $empCode,
                    ":dtrc_date" => $dtrc_date,
                    ":date_filed"=>date('m-d-Y'),
                    ":empReportingTo" => $empReportingTo,
                    ":time_in"=> $time_in,
                    ":time_out"=> $time_out,
                    ":remarks"=> $remarks,
                    ":audituser" => $empCode,
                    ":auditdate"=>date('m-d-Y')
                );

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
                    ":auditdate"=>date('m-d-Y')
                );

            $results = $stmts->execute($params);

            echo $results;            

    }
}

?>