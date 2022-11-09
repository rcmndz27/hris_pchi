<?php

Class OvertimeAdjList{

    public function GetAllOvertimeAdjList(){
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

        <table id="allOvertimeAdjList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>OT Date</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.employee_overtimeadj_management ORDER by overtimeadj_id DESC ";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $otadjdate =  "'".date('Y-m-d', strtotime($result['otadj_date']))."'";
                $descript = "'".$result['description']."'";
                $amnt = "'".round($result['amount'],3)."'";
                $remark = "'".$result['remarks']."'";   
                $incdecr = "'".$result['inc_decr']."'";               
                echo '
                <tr>
                <td>' . $result['emp_code']. '</td>
                <td>' . date('m/d/Y', strtotime($result['otadj_date'])) . '</td>
                <td>' . $result['description']. '</td>
                <td>' . substr(hash('sha256', $result['amount']),50). '</td>                
                <td>' . $result['remarks']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" onclick="editOtAdjModal('.$empcd.','.$otadjdate.','.$descript.','.$amnt.','.$remark.','.$incdecr.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';
                
                

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
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

