<?php  

 function GetApprovedForms($emp_code,$dateFrom,$dateTo){
        
        global $connL;

        echo '
        <table id="ApprovedForms" class="table table-striped table4">
        <thead>
            <tr>
                <th colspan="5" class ="text-center">Approved Forms</th>
            </tr>
            <tr>
                <th>Emp Name</th>
                <th>Form Type</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

        $query = "EXEC dbo.timekeeping_viewapprovedforms :emp_code,:pay_from,:pay_to";
        $param = array(':emp_code' => $emp_code,':pay_from' => $dateFrom,':pay_to' => $dateTo);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        if($result){
            do { 

                echo '
                <tr>
                <td>' . $result['fname'] . '</td>
                <td>' . $result['ftype'] . '</td>
                <td>' . date('m-d-Y', strtotime($result['fdate'])) . '</td>
                <td>' . $result['fremarks'] . '</td>
                <td>' . $result['fstatus'] . '</td>';

            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot><tr><td colspan="5" class="text-center">No Results Found</td></tr></tfoot>'; 
        }
        echo '</table>';
    } 

?>