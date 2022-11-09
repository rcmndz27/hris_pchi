<?php

Class LeaveBalanceList{

    public function GetAllLeaveBalanceList(){
        global $connL;

        echo '<table id="allLeaveBalanceList" class="table table-striped table-sm">
        <thead>

            <tr>
                <th>Employee Code</th>
                <th>Employee Name</th>
                <th>Sick Leave</th>
                <th>Vacation Leave</th>
                <th>Floating Leave</th>
                <th>Sick Leave Bank</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT c.lastname+', '+c.firstname as [fullname],a.emp_code,a.earned_sl,a.earned_vl,a.earned_fl,
        a.earned_sl_bank,a.status from dbo.employee_leave a left join dbo.employee_profile c  on a.emp_code = c.emp_code 
        where status = 'Active'
        ORDER by c.lastname ASC";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        

        if($result){
            do { 
                $empcd = "'".$result['emp_code']."'";
                $empnm = "'".$result['fullname']."'";
                 
                echo '
                <tr>
                <td>' . $result['emp_code']. '</td>
                <td>' . $result['fullname']. '</td>
                <td id="sl'.$result['emp_code'].'">' . $result['earned_sl']. '</td>
                <td id="vl'.$result['emp_code'].'">' . $result['earned_vl']. '</td>    
                <td id="fl'.$result['emp_code'].'">' . $result['earned_fl']. '</td>                
                <td id="slb'.$result['emp_code'].'">' . $result['earned_sl_bank']. '</td>
                <td id="st'.$result['emp_code'].'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" onclick="editLvBalModal('.$empcd.','.$empnm.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';                
                
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

