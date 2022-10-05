<?php  
   function GetTime($dateTime){
        $formattedDateTime = new DateTime($dateTime);
        $time = $formattedDateTime->format('h:i:s A');

        return $time;
    }

 function GetPayAttViewLogs($emp_code, $dateFrom, $dateTo){

        $totalWork = 0;
        $totalLate = 0;
        $totalUndertime = 0;
        $totalOvertime = 0;

        global $connL;

        if(strlen($emp_code) === 0){

            $whereClause = " WHERE punch_date BETWEEN :startDate AND :endDate ";
            $param = array(":startDate" => $dateFrom, ":endDate" => $dateTo );
            
        }else{

            $whereClause = " WHERE (emp_pin = :emp_pin  OR emp_ssn = :emp_ssn) AND punch_date BETWEEN :startDate AND :endDate ";
            $param = array(":emp_ssn" => $emp_code, ":startDate" => $dateFrom, ":endDate" => $dateTo );
        }

        $query = 'EXEC dbo.xp_attendance_portal :emp_ssn,:startDate,:endDate';
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();

        echo "
        <table id='empDtrList' class='table table-striped table-sm'>
            <thead>
                <tr>
                    <th colspan='8' class='text-center'>".$result['name']."</th>
                </tr>
                <tr>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Work (Hrs)</th>
                    <th>Lates (Hrs)</th>
                    <th>Undertime (Hrs)</th>
                    <th>Overtime (Hrs)</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>";

            if($result){
                do {

                    $timeIn = (isset($result['timein']) ? GetTime($result['timein']): '');
                    $timeOut = (isset($result['timeout']) ? GetTime($result['timeout']) : '');

                    echo    "<tr>".
                                "<td>" . date('F d, Y', strtotime($result['punch_date'])) . "</td>".
                                "<td>" . $timeIn . "</td>".
                                "<td>" . $timeOut . "</td>".
                                "<td>" . round($result['workhours'],2) . "</td>".
                                "<td>" . round($result['late'],2) . "</td>".
                                "<td>" . round($result['undertime'],2) . "</td>".
                                "<td>" . round($result['overtime'],2) . "</td>".
                                "<td>" . $result['remarks']. "</td>".
                            "</tr>";

                    $totalWork += $result['workhours'];
                    $totalLate += $result['late'];
                    $totalUndertime += $result['undertime'];
                    $totalOvertime += $result['overtime'];        

                } while ($result = $stmt->fetch());     
            }

        echo"
            </tbody>
            <tfoot>
                <tr>".
                    "<td colspan='3' class='text-right bg-secondary'><b>Total</b></td>".
                    "<td class='bg-secondary'><b>" . $totalWork . "</b></td>".
                    "<td class='bg-secondary'><b>" . $totalLate . "</b></td>".
                    "<td class='bg-secondary'><b>" . $totalUndertime . "</b></td>".
                    "<td class='bg-secondary'><b>" . $totalOvertime . "</b></td>".
                    "<td class='bg-secondary'><b></b></td>".
                "</tr>
            </tfoot>
        </table>";
    }

?>