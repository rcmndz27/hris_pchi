<?php

Class NewHireAccess{

    public function GetAllEmpHistory($empStatus){

        global $connL;

        echo '
        <div class="form-row">  
                <div class="col-lg-8">
                </div>                               
                <div class="col-lg-4">        
                        </div>                     
                </div>  
        <table id="allEmpList" class="table empp table-striped table-sm">
        <thead>
            <tr>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Location</th>
                <th>Employee Type</th>
                <th>Employee Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.employee_profile where emp_status = :empStatus ORDER BY lastname asc ";
        $param = array(":empStatus" => $empStatus);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();
        if($result){
            do { 
                $fullname =  $result['lastname'].','.$result['firstname'];
                $empcd = "'".$result['emp_code']."'";
                $emppicloc = "'".$result['emp_pic_loc']."'";
                 $day[] = $result;
                echo '
                <tr>
                <td>' . $result['emp_code'] . '</td>
                <td>' . $fullname . '</td>
                <td>' . $result['position'] . '</td>
                <td>' . $result['department'] . '</td>
                <td>' . $result['location'] . '</td>
                <td>' . $result['emp_type'] . '</td>
                <td>' . $result['emp_status'] . '</td>';
                echo '<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="viewEmpModal('.$empcd.','.$emppicloc.')" title="View Employee Profile"><i class="fas fa-binoculars"></i>
                            </button><button type="button" class="btn btn-warning btn-sm" onclick="updateEmpModal('.$empcd.')" title="Update Employee Profile">
                                <i class="fas fa-edit"></i>
                            </button></td>
                            ';

            } while ($result = $stmt->fetch());
            $name = json_encode($day);
            echo '</tr></tbody>';
            echo "
            <script type=\"text/javascript\">
                var name='$name';
                var nameD = JSON.parse(name);
          
                function updateEmpModal(result){

                $('#HireEmp').modal('toggle');

                for(var i=0;i<nameD.length;i++)
                {
                    if(nameD[i][0] == result)
                    {
                        document.getElementById('rowid').value = nameD[i][0];
                        document.getElementById('fnameg').value = nameD[i][2];
                        document.getElementById('mnameg').value = nameD[i][3];
                        document.getElementById('lnameg').value = nameD[i][4];
                        document.getElementById('emailaddg').value = nameD[i][36];
                        document.getElementById('telng').value = nameD[i][38];
                        document.getElementById('celng').value = nameD[i][40];
                        document.getElementById('department').value = nameD[i][24];
                        document.getElementById('position').value = nameD[i][27];
                        document.getElementById('location').value = nameD[i][22];
                        document.getElementById('emp_type').value = nameD[i][21];
                        document.getElementById('emp_level').value = nameD[i][89];
                        document.getElementById('work_sched_type').value = nameD[i][83];
                        document.getElementById('minimum_wage').value = nameD[i][84];
                        document.getElementById('pay_type').value = nameD[i][85];
                        document.getElementById('emp_status').value = nameD[i][20];
                        document.getElementById('reporting_to').value = nameD[i][23];
                        document.getElementById('emp_address').value = nameD[i][34];
                        document.getElementById('emp_address2').value = nameD[i][35];
                        document.getElementById('sss_no').value = nameD[i][77];
                        document.getElementById('phil_no').value = nameD[i][78];
                        document.getElementById('pagibig_no').value = nameD[i][79];
                        document.getElementById('tin_no').value = nameD[i][76];
                        document.getElementById('birthdate').value = nameD[i][32];
                        document.getElementById('datehired').value = nameD[i][31];
                        document.getElementById('birthplace').value = nameD[i][33];
                        document.getElementById('sex').value = nameD[i][28];
                        document.getElementById('marital_status').value = nameD[i][29];  
                        document.getElementById('emp_id').value = nameD[i][98];  
                        console.log(nameD[i][89]) ;
                        if(nameD[i][89] == 4){
                            document.getElementById('reporting_to').disabled = true;
                        }else{
                            document.getElementById('reporting_to').disabled = false;
                        }
                       
                        break;

                    }
                }
                
                        
            }
            </script>
        ";
        //console.log(nameD.length);
         // console.log(nameD[i]);
              // console.log(nameD);
        }else { 
            echo '<tfoot><tr><td colspan="8" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table><div class="pagination-container">
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


