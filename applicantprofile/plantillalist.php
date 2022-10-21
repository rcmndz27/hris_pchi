<?php

Class PlantillaList{

    public function GetAllPlantillaList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for plantilla.." title="Type in plantilla details"> 
                        </div>                     
                </div>

        <table id="allPlaList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Entry Date</th>
                <th>Department</th>
                <th>Position</th>
                <th>Reporting To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.applicant_plantilla ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        

        if($result){
            do { 
                echo '
                <tr>
                <td>' . date('m/d/Y', strtotime($result['entry_date'])) . '</td>
                <td>' . $result['department'] . '</td>
                <td>' . $result['position'] . '</td>
                <td>' . $result['reporting_to'] . '</td>
                <td id="st'.$result['rowid'].'">' . $result['status'] . '</td>
                ';

                if($result['status'] === 'Open' or $result['status'] === 'De-Activated'){
                echo '<td id="act'.$result['rowid'].'"><button type="button" class="btn btn-info btn-sm" onclick="activatePlant('.$result['rowid'].')">
                                <i class="fas fa-check-circle"></i> ACTIVATE
                            </button></td>';
                }else{
                    echo '<td id="act'.$result['rowid'].'"><button type="button" class="debtn btn-info btn-sm" onclick="deactivatePlant('.$result['rowid'].');">
                               <i class="fas fa-times-circle"></i> DE-ACTIVATE
                            </button></td>';
                }
                
                

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