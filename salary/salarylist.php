<?php

Class SalaryList{

    public function GetAllSalaryList($empStatus){
        global $connL;

        echo '
        <table id="allSalaryList" class="table table-sm">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Bank</th>
                <th>Account No.</th>
                <th>Pay Type</th>
                <th>Pay Rate</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT s.emp_code,s.bank_type,s.bank_no,s.bank_no,s.pay_rate,s.amount,s.status,lastname+', '+firstname as fullname from employee_salary_management s left join employee_profile e on s.emp_code = e.emp_code
        where e.emp_status = :empStatus order by lastname asc";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $fname = "'".$result['fullname']."'";
                $onclick = 'onclick="editSalaryModal('.$empcd.','.$fname.')"' ;
                echo '
                <tr class="csor-pointer">
                <td '.$onclick.' >' . $result['fullname']. '</td>
                <td '.$onclick.'id="bt'.$result['emp_code'].'">' . $result['bank_type']. '</td>
                <td '.$onclick.' id="bn'.$result['emp_code'].'">' . $result['bank_no']. '</td> 
                <td '.$onclick.'id="pr'.$result['emp_code'].'">' . $result['pay_rate']. '</td> 
                <td '.$onclick.' id="am'.$result['emp_code'].'" hidden>'.round($result['amount'],3).'</td>
                <td '.$onclick.' id="amtn'.$result['emp_code'].'">₱ ' . number_format($result['amount'],2,'.',',').'</td>
                <td '.$onclick.' id="st'.$result['emp_code'].'">' . $result['status'] . '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editSalaryModal('.$empcd.','.$fname.')" title="Update Salary">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="viewSalaryLogs('.$empcd.')" title="Salary Logs">
                                <i class="fas fa-history"></i>
                            </button>
                            </td>';
                             
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="6" class="text-center">No Results Found</td></tr></tfoot>'; 
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
      </div> ';
    }


}

?>

