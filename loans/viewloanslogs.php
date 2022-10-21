<?php

 function ViewLnLogs($emp_code,$rowid){
        
        global $connL;

        echo '
        <table id="ViewLnLogs" class="table table-striped table2">
        <thead>
            <tr>
                <th colspan="11" class ="text-center">Loan Logs</th>
            </tr>
            <tr>
                <th>Emp Code</th>
                <th>Emp Name</th>
                <th>Column Type</th>
                <th>Old Data</th>
                <th>New Data</th>
                <th>Audit Name</th>
                <th>Audit Date</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * FROM logs_mfloan where emp_code = :emp_code and loan_id = :rowid ORDER BY auditdate ASC";
        $param = array(':emp_code' => $emp_code,':rowid' => $rowid);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 
                echo '
                <tr>
                <td>' . $result['emp_code'] . '</td>
                <td>' . $result['emp_name'] . '</td>
                <td>' . $result['column_name'] . '</td>
                <td>' . $result['old_data'] . '</td>
                <td>' . $result['new_data'] . '</td>
                <td>' . $result['audituser'] . '</td>
                <td>' . date('m-d-y h:i A', strtotime($result['auditdate'])) . '</td>';
            } while ($result = $stmt->fetch());
            echo '</tr></tbody>';
        }else { 
            echo '<tfoot><tr><td colspan="11" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>';
    } 

?>