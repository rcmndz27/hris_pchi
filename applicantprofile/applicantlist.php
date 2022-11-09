<?php

Class ApplicantList{

    public function GetAllApplicantList(){
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
                    <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for applicant.." title="Type in applicant details"> 
                        </div>                     
                </div>

        <table id="allAppEnt" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>How did you apply?</th>
                <th>Preferred Job 1</th>
                <th>Preferred Job 2</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT rowid,familyname,firstname,(LEFT(middlei,1)+'.') as [middlename],howtoapply,jobpos1,jobpos2, 
        (CASE WHEN appent_status = 1 then 'Active' 
             WHEN appent_status = 0 then 'Inactive' else 'Hired' END )as status from dbo.applicant_entry ORDER BY rowid desc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
       
        

        if($result){
            do { 
                $fullname = "'".ucwords($result['familyname']).",".ucwords($result['firstname'])." ".ucwords($result['middlename'])."'";
                $fulln = ucwords($result['familyname']).",".ucwords($result['firstname'])." ".ucwords($result['middlename']);
                echo '
                <tr>
                <td id="apl'.$result['rowid'].'">' .  $fulln .'</td>
                <td>' . ucwords($result['howtoapply']) . '</td>
                <td>' . ucwords($result['jobpos1']) . '</td>
                <td>' . ucwords($result['jobpos2']) . '</td>
                <td id="st'.$result['rowid'].'">' . ucwords($result['status']) . '</td>';
                if($result['status'] == 'Inactive'){
                echo '<td id="upd'.$result['rowid'].'"><button type="button" class="btn btn-info btn-sm" onclick="verifyEntryModal('.$result['rowid'].','.$fullname.')">
                                <i class="fas fa-user-check"></i> VERIFY
                            </button></td>';
                }else if($result['status'] == 'Active'){
                    echo '<td id="upd'.$result['rowid'].'"><button type="button" class="uptv" onclick="updateEntryModal('.$result['rowid'].','.$fullname.')">
                               <i class="fas fa-edit"></i> Update 
                            </button></td>';                  
                }else{
                    echo'<td id="upd'.$result['rowid'].'"><span>HIRED</span></td>';
                    
                }
    
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    }


}

?>

