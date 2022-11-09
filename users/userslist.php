<?php

Class UsersList{

    public function GetAllUsersList(){
        global $connL;

        echo '<table id="allUsersList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th colspan="6" class ="text-center">List of All Employee Users</th>
            </tr>
            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Employee Type</th>
                <th>Employee Email</th>
                <th>Employee Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_user order by username ASC";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $empcd = "'".$result['userid']."'";
                $usrmail = "'".$result['useremail']."'";
                $usrtyp = "'".$result['usertype']."'";
                $bempcd = $result['userid'];
                $bname = $result['username'];
                $name = "'".$bempcd.' - '.$bname."'";
                $stts = "'".$result['status']."'";
              
                echo '
                <tr>
                <td >' . $result['userid']. '</td>
                <td>' . $result['username']. '</td>
                <td id="usr'.$result['userid'].'">' . $result['usertype']. '</td>
                <td>' . $result['useremail']. '</td>
                <td id="st'.$result['userid'].'">' . $result['status']. '</td>';

                if( $result['locked_acnt'] == 1){
                echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editUsrModal('. $empcd.','. $name.')" title="Update User/Change Password">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button id="ub'.$result['userid'].'" type="button" class="btn btn-warning btn-sm" onclick="deleteLogsModal('. $empcd.')" title="Unblocked User">
                                <i class="fas fa-lock-open"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="resetPassword('. $empcd.')" title="Reset Password">
                                <i class="fas fa-power-off"></i>
                            </button>                             
                            </td>';
                } else{
                    echo'<td><button type="button" class="btn btn-info btn-sm btn-sm" onclick="editUsrModal('. $empcd.','. $name.')" title="Update User/Change Password">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="resetPassword('. $empcd.')" title="Reset Password">
                                <i class="fas fa-power-off"></i>
                            </button>                            
                            </td>';
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

