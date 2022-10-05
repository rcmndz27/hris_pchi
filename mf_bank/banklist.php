<?php

Class BankList{

    public function GetAllBankList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for bank.." title="Type in bank name"> 
                        </div>                     
                </div> 

        <table id="allBankList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Bank Code</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_banktypes ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $dscsb = "'".$result['descsb']."'";;
                echo '
                <tr>
                <td id="bc'.$result['rowid'].'">'.$result['descsb'].'</td>
                <td id="bn'.$result['rowid'].'">'.$result['descsb_name'].'</td>
                <td id="st'.$result['rowid'].'">'.$result['status']. '</td>';
                echo'<td><button type="button"class="btn btn-info btn-sm" 
                onclick="editBankModal('.$rowd.','.$dscsb.')">
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

