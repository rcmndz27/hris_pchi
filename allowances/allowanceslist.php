<?php

Class AllowancesList{

    public function GetAllAllowancesList($empStatus){
        global $connL;

        echo '<table id="allAllowancesList" class="table table-sm">
        <thead>

            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Allowances Name</th>
                <th hidden>Period Name</th>
                <th>Period Cutoff</th>
                <th hidden>Amount Name</th>
                <th>Allowances Amount</th>
                <th>Effectivity Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.benefits_emp_id,c.lastname+' '+c.firstname as [fullname],a.emp_code,b.benefit_name,b.rowid,a.period_cutoff,a.amount,a.effectivity_date,a.status from dbo.employee_allowances_management a left join dbo.mf_benefits b on a.benefit_id = b.rowid 
        left join employee_profile c  on a.emp_code = c.emp_code and a.status = 'Active' where c.emp_status = :empStatus  ORDER by c.lastname ASC";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();
        

        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $benfid = "'".$result['benefits_emp_id']."'";
                $flname = "'".$result['fullname']."'";  
                $onclick = 'onclick="editAlwModal('.$empcd.','.$benfid.','.$flname.')"';              
                 
    echo '
    <tr class="csor-pointer">
    <td '.$onclick.'>'. $result['emp_code']. '</td>
    <td '.$onclick.'>'. $result['fullname']. '</td>
    <td '.$onclick.' id="bn'.$result['benefits_emp_id'].'">' . $result['benefit_name']. '</td>
    <td '.$onclick.' id="bnr'.$result['benefits_emp_id'].'" hidden>' . $result['rowid']. '</td>                
    <td '.$onclick.'id="pc'.$result['benefits_emp_id'].'">' . $result['period_cutoff']. '</td>
    <td '.$onclick.' id="am'.$result['benefits_emp_id'].'" hidden>'.round($result['amount'],3).'</td>
    <td '.$onclick.' id="amtn'.$result['benefits_emp_id'].'">₱ ' . number_format($result['amount'],2,'.',',').'</td>
    <td '.$onclick.' id="ed'.$result['benefits_emp_id'].'">' . date('Y-m-d', strtotime($result['effectivity_date'])) . '</td>
    <td '.$onclick.' id="st'.$result['benefits_emp_id'].'">' . $result['status']. '</td>';
    echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editAlwModal('.$empcd.','.$benfid.','.$flname.')">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="viewAlwLogs('.$empcd.')" title="Allowances Logs">
                    <i class="fas fa-history"></i>
                </button>  
                </td>';                
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>';
    }


}

?>

