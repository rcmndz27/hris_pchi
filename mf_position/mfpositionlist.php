<?php

Class MfpositionList{

    public function GetAllMfpositionList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Position Name.." title="Type in position name"> 
                        </div>                     
                </div>  
        <table id="allMfpositionList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Job Position Name</th>
                <th>Department</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_position ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $pstn = "'".$result['position']."'";
                $queryd = "SELECT c.code,a.job_id,b.rowid,a.dept_id from mf_jobdept a 
                left join mf_position b on a.job_id = b.rowid
                left join mf_dept c on a.dept_id = c.rowid
                where a.job_id = ".$rowd."";
                $stmtd =$connL->prepare($queryd);
                $stmtd->execute();
                $resultd = $stmtd->fetch();
                $data = array();
                $data2 = array();
                echo '
                <tr>
                <td id="pst'.$result['rowid'].'">' . $result['position']. '</td>
                <td id="dptv'.$result['rowid'].'">';
                if($resultd){
                    do { 
                        array_push($data,$resultd['code']);
                        $cdde = $resultd['code'];   

                        array_push($data2,$resultd['dept_id']);
                        $depid = $resultd['dept_id'];                             
                    }
                    while ($resultd = $stmtd->fetch());
                            $codess = implode(",", $data);
                            $depidss = implode(",", $data2);
                            $depidtt = "'".$depidss."'";
                            echo $codess;
                    }
                echo'
                </td>
                <td id="dpt'.$result['rowid'].'" hidden>';
                            echo $depidss;

                echo'
                </td>                
                <td id="st'.$result['rowid'].'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfpositionModal('.$rowd.','.$pstn.')">
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

