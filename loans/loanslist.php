<?php

Class LoansList{

    public function GetAllLoansList($empStatus){
        global $connL;

        echo '
        <table id="allloansList" class="table table-sm">
        <thead>

            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Loan Name</th>
                <th>Loan Amount</th>
                <th>Loan Balance</th>
                <th>Loan Total Payment</th>
                <th>Loan Monthly Amortization</th>
                <th>Loan Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.loan_id,c.firstname+' '+c.lastname as [fullname],a.emp_code,b.deduction_name as loan_name,b.rowid,a.loan_amount,a.loan_balance,a.loan_totpymt,a.loan_amort,a.loan_date,a.status from dbo.employee_loans_management a left join dbo.mf_deductions b on a.loandec_id = b.rowid left join employee_profile c  on a.emp_code = c.emp_code where c.emp_status = :empStatus ORDER by a.loan_id DESC";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();
        

        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $lnid = "'".$result['loan_id']."'";
                $flname = "'".$result['fullname']."'";
                $onclick = 'onclick="editLnModal('.$empcd.','.$lnid.','.$flname.')"';             
                 
    echo '
    <tr class="csor-pointer">
    <td '.$onclick.'>' . $result['emp_code']. '</td>
    <td '.$onclick.''.$onclick.'>' . $result['fullname']. '</td>
    <td '.$onclick.' id="ln'.$result['loan_id'].'">' . $result['loan_name']. '</td>
    <td '.$onclick.' id="lnh'.$result['loan_id'].'" hidden>' . $result['rowid']. '</td>                
    <td '.$onclick.' id="lah'.$result['loan_id'].'" hidden>'.round($result['loan_amount'],3).'</td>
    <td '.$onclick.' id="la'.$result['loan_id'].'">₱ '. number_format($result['loan_amount'],2,'.',',').'</td>
    <td '.$onclick.' id="lbh'.$result['loan_id'].'" hidden>'.round($result['loan_balance'],3).'</td>
    <td '.$onclick.' id="lb'.$result['loan_id'].'">₱ '. number_format($result['loan_balance'],2,'.',',').'</td> 
    <td '.$onclick.' id="ltph'.$result['loan_id'].'" hidden>'.round($result['loan_totpymt'],3).'</td>
    <td '.$onclick.' id="ltp'.$result['loan_id'].'">₱ '. number_format($result['loan_totpymt'],2,'.',',').'</td> 
    <td '.$onclick.' id="lamh'.$result['loan_id'].'" hidden>'.round($result['loan_amort'],3).'</td>
    <td '.$onclick.' id="lam'.$result['loan_id'].'">₱ '. number_format($result['loan_amort'],2,'.',',').'</td>            
    <td '.$onclick.' id="ldt'.$result['loan_id'].'">' . date('Y-m-d', strtotime($result['loan_date'])) . '</td>
    <td '.$onclick.' id="st'.$result['loan_id'].'">' . $result['status']. '</td>';
    echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editLnModal('.$empcd.','.$lnid.','.$flname.')"><i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm" onclick="viewLnLogs('.$empcd.','.$lnid.')" title="Loans Logs">
                    <i class="fas fa-history"></i>
                </button>  
                </td>';                
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="10" class="text-center">No Results Found</td></tr></tfoot>'; 
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
      </div>        ';
    }


}

?>

