<?php

Class DeductionList{

    public function GetAllDeductionList($empStatus){
        global $connL;

        echo '       
        <table id="allDeductionList" class="table table-sm">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Deduction Name</th>
                <th hidden>Period Cutoff</th>
                <th>Period Cutoff</th>
                <th hidden>Deduction Amount</th>
                <th>Deduction Amount</th>
                <th>Effectivity Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.deduction_emp_id,c.lastname+' '+c.firstname as [fullname],a.emp_code,b.deduction_name,b.rowid,a.period_cutoff,a.amount,a.effectivity_date,a.end_date,a.status from dbo.employee_deduction_management a 
        left join dbo.mf_deductions b on a.deduction_id = b.rowid left join employee_profile c on a.emp_code = c.emp_code 
        where c.emp_status = :empStatus and a.status = 'Active' ORDER by c.lastname ASC ";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $deidn = $result['deduction_emp_id'];
                $dedcid = "'".$deidn."'";
                $flname = "'".$result['fullname']."'";
                $onclick = 'onclick="editDedModal('.$empcd.','.$dedcid.','.$flname.')"';
                $edate = (isset($result['end_date'])) ? date('Y-m-d', strtotime($result['end_date'])):'n/a'; 
             
                echo '
                <tr class="csor-pointer">
                <td '.$onclick.'>'.$result['emp_code'].'</td>
                <td '.$onclick.''.$onclick.' >'.$result['fullname'].'</td>
                <td '.$onclick.' id="dn'.$deidn.'">'.$result['deduction_name'].'</td>
                <td '.$onclick.' id="dnr'.$deidn.'" hidden>'.$result['rowid'].'</td>
                <td '.$onclick.' id="pc'.$deidn.'">'.$result['period_cutoff'].'</td>
                <td '.$onclick.' id="am'.$deidn.'" hidden>'.round($result['amount'],3).'</td>
                <td '.$onclick.' id="amtn'.$deidn.'">₱ '.number_format($result['amount'],2,'.',',').'</td>
                <td '.$onclick.' id="ed'.$deidn.'">'.date('Y-m-d', strtotime($result['effectivity_date'])).'</td>
                <td '.$onclick.' id="end'.$deidn.'">'.$edate.'</td>
                <td '.$onclick.' id="st'.$deidn.'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editDedModal('.$empcd.','.$dedcid.','.$flname.')" title="Update Deduction">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="viewDedLogs('.$empcd.')" title="Deduction  Logs">
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


}

?>

