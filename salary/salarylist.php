<?php

            date_default_timezone_set('Asia/Manila');

Class SalaryList{

    public function GetAllSalaryList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for employee salary.." title="Type in employee details"> 
                        </div>                     
                </div> 
        <table id="allSalaryList" class="table table-striped table-sm">
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

        $query = "SELECT s.emp_code,s.bank_type,s.bank_no,s.bank_no,s.pay_rate,s.amount,s.status,lastname+', '+firstname as fullname from employee_salary_management s left join employee_profile e on s.emp_code = e.emp_code order by lastname asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $fname = "'".$result['fullname']."'";

                echo '
                <tr>
                <td>' . $result['fullname']. '</td>
                <td id="bt'.$result['emp_code'].'">' . $result['bank_type']. '</td>
                <td id="bn'.$result['emp_code'].'">' . $result['bank_no']. '</td> 
                <td id="pr'.$result['emp_code'].'">' . $result['pay_rate']. '</td> 
                <td id="am'.$result['emp_code'].'" hidden>'.round($result['amount'],3).'</td>
                <td id="amtn'.$result['emp_code'].'">₱ ' . number_format($result['amount'],2,'.',',').'</td>
                <td id="st'.$result['emp_code'].'">' . $result['status'] . '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" onclick="editSalaryModal('.$empcd.','.$fname.')" title="Update Salary">
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

