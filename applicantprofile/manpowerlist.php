<?php

Class ManpowerList{

    public function GetAllManpowerList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for manpower details.." title="Type in manpower details"> 
                        </div>                     
                </div>

        <table id="allManList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>MRF NO.</th>
                <th>Position</th>
                <th>Requirement</th>
                <th>Date Needed</th>
                <th>Status</th>
                <th>Applicant Name</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT a.rowid,a.position,a.req_ment,a.date_needed,a.status,b.familyname,b.firstname,(LEFT(b.middlei,1)+'.') as [middlename] from dbo.applicant_manpower a left join dbo.applicant_entry b on a.app_no = b.rowid ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        

        if($result){
            do { 
                echo '
                <tr>
                <td>' . 'MRF#'.$result['rowid'] . '</td>
                <td>' . $result['position'] . '</td>
                <td>' . $result['req_ment'] . '</td>
                <td>' . date('m/d/Y', strtotime($result['date_needed'])) . '</td>
                <td>' . $result['status'] . '</td>
                <td>' . ucwords($result['familyname']).','.ucwords($result['firstname']).' '.ucwords($result['middlename']). '</td>';
                

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
                      </div>         ';
    }

}

?>