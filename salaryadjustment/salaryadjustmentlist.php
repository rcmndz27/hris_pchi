<?php

Class SalaryAdjList{

    public function GetAllSalaryAdjList($empStatus){
        global $connL;

        echo '
        <table id="allSalaryAdjList" class="table table-sm">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Period From</th>
                <th>Period To</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Emp Status</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.emp_code,lastname+', '+firstname as fullname,period_to,period_from,description,remarks,amount,
        inc_decr,salaryadj_id,b.emp_status from dbo.employee_salaryadj_management a left join employee_profile b
        on a.emp_code = b.emp_code where b.emp_status = :empStatus and a.status = 'Active' ORDER by period_from DESC ";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
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
                $salaryadjid = "'".$result['salaryadj_id']."'"; 
                $onclick = 'onclick="editSalAdjModal('.$empcd.','.$percutoff.','.$descript.','.$amnt.','.$remark.','.$incdecr.','.$salaryadjid.')"';              
                echo '
                <tr class="csor-pointer">
                <td '.$onclick.'>' . $result['fullname']. '</td>
                <td '.$onclick.'>' . date('m/d/Y', strtotime($result['period_from'])) . '</td>
                <td '.$onclick.'>' . date('m/d/Y', strtotime($result['period_to'])) . '</td>
                <td '.$onclick.'>' . $result['description']. '</td>
                <td '.$onclick.'>' . round($result['amount'],2).'</td>                
                <td '.$onclick.'>' . $result['remarks']. '</td>
                <td '.$onclick.'>' . $result['emp_status']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" title="Edit/Update" onclick="editSalAdjModal('.$empcd.','.$percutoff.','.$descript.','.$amnt.','.$remark.','.$incdecr.','.$salaryadjid.')">
                                <i class="fas fa-edit"></i>
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

