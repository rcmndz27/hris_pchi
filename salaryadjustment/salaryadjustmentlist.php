<?php

Class SalaryAdjList{

    public function GetAllSalaryAdjList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for employee details.." title="Type in employee details"> 
                        </div>                     
                </div>

        <table id="allSalaryAdjList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Period From</th>
                <th>Period To</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.emp_code,lastname+', '+firstname as fullname,period_to,period_from,description,remarks,amount,
        inc_decr from dbo.employee_salaryadj_management a left join employee_profile b
        on a.emp_code = b.emp_code ORDER by period_from DESC ";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $perfrom = date('m/d/Y', strtotime($result['period_from']));
                $perto = date('m/d/Y', strtotime($result['period_to']));
                $percutoff = "'".$perfrom.' - '.$perto."'";
                $descript = "'".$result['description']."'";
                $amnt = "'".round($result['amount'],3)."'";
                $remark = "'".$result['remarks']."'";   
                $incdecr = "'".$result['inc_decr']."'";               
                echo '
                <tr>
                <td>' . $result['fullname']. '</td>
                <td>' . date('m/d/Y', strtotime($result['period_from'])) . '</td>
                <td>' . date('m/d/Y', strtotime($result['period_to'])) . '</td>
                <td>' . $result['description']. '</td>
                <td>' . $result['amount'].'</td>                
                <td>' . $result['remarks']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" onclick="editSalAdjModal('.$empcd.','.$percutoff.','.$descript.','.$amnt.','.$remark.','.$incdecr.')">
                                <i class="fas fa-edit"></i> UPDATE
                            </button></td>';
                
                

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="7" class="text-center">No Results Found</td></tr></tfoot>'; 
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

