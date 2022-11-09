<?php

    function GenerateOTApprov($action, $dtFrom, $dtTo, $rpt)
    {
        global $connL;

            $query = "SELECT a.ot_ren_hrs,a.ot_req_hrs,a.ot_apprv_hrs,a.remarks,a.ot_date,b.firstname,b.middlename,b.lastname FROM tr_overtime a left join employee_profile b on a.emp_code = b.emp_code 
            where a.ot_date between :dtfrom and :dtto and a.audituser  =  :rpt ";
            $stmt =$connL->prepare($query);
            $param = array(":rpt" => $rpt,":dtfrom" => $dtFrom,":dtto" => 
                $dtTo);
            $stmt->execute($param);
            $result = $stmt->fetch();

        echo "<div id='table-container'>
            <div class='row' id='tablecontents'>
                <table id='list' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th scope='col'>Employee</th>
                        <th scope='col'>Date</th>
                        <th scope='col'>OT Plan (Hrs)</th>
                        <th scope='col'>OT Rendered (Hrs)</th>
                        <th scope='col'>OT Approved (Hrs)</th>
                        <th scope='col'>Remarks</th>
                    </tr>
                </thead>
                <tbody>";
            if($result){
                do{
                     echo "
                        <tr>
                            <td>".$result['lastname'].",".$result['firstname']." ".$result['middlename']."</td>
                            <td>" . $result["ot_date"] . "</td>
                            <td>" . number_format($result["ot_req_hrs"], 2) . "</td>
                            <td>" . number_format($result["ot_ren_hrs"], 2) . "</td>
                            <td>" . number_format($result["ot_apprv_hrs"], 2) . "</td>
                            <td>" . $result["remarks"] . "</td>
                        </tr>
                    ";
                } while($result = $stmt->fetch());

                echo "</tbody>";
            }else{
                echo '<tfoot></tfoot>'; 
            }

            echo "</table>";
        }


?>