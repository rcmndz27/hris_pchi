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
                <th>Period Cutoff</th>
                <th>Deduction Amount</th>
                <th>Effectivity Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.deduction_emp_id,c.firstname+' '+c.lastname as [fullname],a.emp_code,b.deduction_name,b.rowid,a.period_cutoff,a.amount,a.effectivity_date,a.status from dbo.employee_deduction_management a left join dbo.mf_deductions b on a.deduction_id = b.rowid left join employee_profile c on a.emp_code = c.emp_code 
        where c.emp_status = :empStatus ORDER by a.deduction_emp_id DESC ";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $dedcid = "'".$result['deduction_emp_id']."'";
                $flname = "'".$result['fullname']."'";
                $onclick = 'onclick="editDedModal('.$empcd.','.$dedcid.','.$flname.')"';
             
                echo '
                <tr class="csor-pointer">
                <td '.$onclick.'>' . $result['emp_code']. '</td>
                <td '.$onclick.''.$onclick.' >' . $result['fullname']. '</td>
                <td '.$onclick.' id="dn'.$result['deduction_emp_id'].'">' . $result['deduction_name']. '</td>
                <td '.$onclick.' id="dnr'.$result['deduction_emp_id'].'" hidden>' . $result['rowid']. '</td>
                <td '.$onclick.' id="pc'.$result['deduction_emp_id'].'">' . $result['period_cutoff']. '</td>
                <td '.$onclick.' id="am'.$result['deduction_emp_id'].'" hidden>'.round($result['amount'],3).'</td>
                <td '.$onclick.' id="amtn'.$result['deduction_emp_id'].'">₱ ' . number_format($result['amount'],2,'.',',').'</td>
                <td '.$onclick.' id="ed'.$result['deduction_emp_id'].'">' . date('Y-m-d', strtotime($result['effectivity_date'])) . '</td>
                <td '.$onclick.' id="st'.$result['deduction_emp_id'].'">' . $result['status']. '</td>';
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
      </div>         ';
    }


}

?>

