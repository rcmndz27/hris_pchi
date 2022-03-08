<?php

Class ObApp{

    private $employeeCode;
    
    public function SetObAppParams($employeeCode){
        $this->employeeCode = $employeeCode;
    }


    public function GetObAppHistory(){
        global $connL;

        echo '
        <table id="dtrList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th colspan="9" class ="text-center">History of Official Business</th>
            </tr>
            <tr>
                <th>Date Filed</th>
                <th>Destination</th>
                <th>OB Date</th>
                <th>Time</th>
                <th>Purpose</th>
                <th>Person/Company to See</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE when status = 1 then 'PENDING'
                    when   status = 2 then 'APPROVED'
                    when   status = 3 then 'REJECTED'
                    when   status = 4 then 'VOID' ELSE 'N/A' END) as stats,* FROM dbo.tr_offbusiness where emp_code = :emp_code ORDER BY date_filed DESC";
        $param = array(':emp_code' => $this->employeeCode);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                $datefiled = "'".date('m-d-Y', strtotime($result['date_filed']))."'";
                $obdestination = "'".(isset($result['ob_destination']) ? $result['ob_destination'] : 'n/a')."'";
                $obdate = "'".date('m-d-Y', strtotime($result['ob_date']))."'";
                $obtime = "'".date('h:i a', strtotime($result['ob_time']))."'";
                $obpurpose = "'".$result['ob_purpose']."'";
                $obpercmp = "'".$result['ob_percmp']."'";
                $stats = "'".$result['stats']."'";
                $otid = "'".$result['rowid']."'";
                echo '
                <tr>
                <td>' . date('m-d-Y', strtotime($result['date_filed'])) . '</td>
                <td>' . $result['ob_destination'] . '</td>
                <td>' . date('m-d-Y', strtotime($result['ob_date'])). '</td>
                <td>' . date('h:i a', strtotime($result['ob_time'])) . '</td>
                <td>' . $result['ob_purpose'] . '</td>
                <td>' . $result['ob_percmp'] . '</td>
                <td>' . $result['stats'] . '</td>
                <td><button type="button" class="hactv" onclick="viewObModal('.$obdestination.','.$obdate.','.$obtime.','.$obpurpose.','.$obpercmp.','.$stats.')" title="View Overtime">
                                <i class="fas fa-binoculars"></i>
                            </button>
                            <button type="button" class="hdeactv" onclick="viewObHistoryModal('.$otid.')" title="View Logs">
                                <i class="fas fa-history"></i>
                            </button>
                            </td>';

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="9" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>';
    }

    public function InsertAppliedObApp($empCode,$empReportingTo,$ob_time,$ob_destination,$ob_purpose,$ob_percmp, 
            $obDate){

        global $connL;

            $query = "INSERT INTO tr_offbusiness (emp_code,date_filed,ob_date,ob_reporting,ob_time,ob_destination,ob_purpose,ob_percmp,audituser,auditdate) 
                VALUES(:emp_code,:date_filed,:ob_date,:ob_reporting,:ob_time,:ob_destination,:ob_purpose,:ob_percmp,:audituser,:auditdate) ";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $empCode,
                    ":ob_date" => $obDate,
                    ":date_filed"=>date('m-d-Y'),
                    ":ob_reporting" => $empReportingTo,
                    ":ob_time" => $ob_time,
                    ":ob_destination"=> $ob_destination,
                    ":ob_purpose"=> $ob_purpose,
                    ":ob_percmp"=> $ob_percmp,
                    ":audituser" => $empCode,
                    ":auditdate"=>date('m-d-Y')
                );

            $result = $stmt->execute($param);

            echo $result;

            $qry = 'SELECT max(rowid) as maxid FROM tr_offbusiness WHERE emp_code = :emp_code';
            $prm = array(":emp_code" => $empCode);
            $stm =$connL->prepare($qry);
            $stm->execute($prm);
            $rst = $stm->fetch();

            $querys = "INSERT INTO logs_ob (ob_id,emp_code,remarks,audituser,auditdate) 
                VALUES(:ob_id, :emp_code, :remarks,:audituser, :auditdate) ";
    
                $stmts =$connL->prepare($querys);
    
                $params = array(
                    ":ob_id" => $rst['maxid'],
                    ":emp_code"=> $empCode,
                    ":remarks" => 'Apply OB for '.$obDate,
                    ":audituser" => $empCode,
                    ":auditdate"=>date('m-d-Y')
                );

            $results = $stmts->execute($params);

            echo $results;


    }

}

?>