<?php

Class MfpyrollcoList{

    public function GetAllMfpyrollcoList(){
        global $connL;

        echo '<div class="form-row">  
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Payroll Period" title="Type in payroll cutoff details"> 
                        </div>                     
                </div>  
                                     
        <table id="allMfpyrollcoList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Payroll From</th>
                <th>Payroll To</th>
                <th>Payroll Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE WHEN co_type = 0 then 'Payroll 15th' else 'Payroll 30th' END) AS cotype,* from dbo.mf_pyrollco ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                echo '
                <tr>
                <td id="pcf'.$result['rowid'].'">'.date('Y-m-d',strtotime($result['pyrollco_from'])).'</td>
                <td id="pct'.$result['rowid'].'">'.date('Y-m-d',strtotime($result['pyrollco_to'])).'</td>
                <td id="cot'.$result['rowid'].'">'.$result['cotype'].'</td>
                <td id="cor'.$result['rowid'].'" hidden>'.$result['co_type'].'</td>
                <td id="st'.$result['rowid'].'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfpyrollcoModal('.$rowd.')">
                                <i class="fas fa-edit"></i> UPDATE
                            </button></td>';
                
                
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
      </div>';
    }
}

?>

