<?php

include('../config/db.php');

Class EmployeeAttendance{

    function GetTime($dateTime){
        $formattedDateTime = new DateTime($dateTime);
        $time = $formattedDateTime->format('h:i:s A');

        return $time;
    }

    function GetEmployeeAttendannce($empCodeParam, $dateFrom, $dateTo){

        $totalWork = 0;
        $totalLate = 0;
        $totalUndertime = 0;
        $totalOvertime = 0;

        global $connL;

        if($empCodeParam == 'All')
        {

            $param = array(":startDate" => $dateFrom, ":endDate" => $dateTo );
            $query = 'EXEC xp_all_attendance_portal :startDate,:endDate';
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();
        }else{


            $param = array(":emp_ssn" => $empCodeParam, ":startDate" => $dateFrom, ":endDate" => $dateTo );
            $query = 'EXEC xp_attendance_portal :emp_ssn,:startDate,:endDate';
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();
        }                

        echo "
        <table id='empDtrList' class='table table-striped table-sm'>
            <thead>
                <tr>
                    <th> Name</th>
                    <th>Date</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Work (Hrs)</th>
                    <th>Late</th>
                    <th>Undertime</th>
                    <th>Overtime</th>
                    <th>Day</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>";

            if($result){
                do {

                        $timeIn = (isset($result['timein']) ? $this->GetTime($result['timein']): '');
                        $timeOut = (isset($result['timeout']) ? $this->GetTime($result['timeout']) : '');
                        $late = ($result['late'] > 0 ? round($result['late']*60).' Min/s'  : 0);
                        $undertime = ($result['undertime'] > 0 ? round($result['undertime']*60).' Min/s': 0);
                        $overtime = ($result['overtime'] > 0 ? round($result['overtime'],2).' Hr/s': 0);
                        $class = (isset($result['timein']) || isset($result['timeout']) ?  '': "bg-danger");
                        $slate = (isset($result['timein']) || isset($result['timeout']) ?  $late : '');
                        $sundertime = (isset($result['timein']) || isset($result['timeout']) ?  $undertime : '');
                        $sovertime = (isset($result['timein']) || isset($result['timeout']) ?  $overtime : '');
                        $swork = (isset($result['timein']) || isset($result['timeout']) ?  round($result['workhours'],2): '');
    
                        echo    "<tr>".
                                "<td>" . $result['name'] . "</td>".
                                "<td class=".$class.">" . date('F d, Y', strtotime($result['punch_date'])) . "</td>".
                                "<td>" . $timeIn . "</td>".
                                "<td>" . $timeOut . "</td>".
                                "<td>" . $swork . "</td>".
                                "<td>" . $slate."</td>".
                                "<td>" . $sundertime."</td>".
                                "<td>" . $sovertime."</td>".
                                "<td>" . $result['wday'] . "</td>".
                                "<td>" . str_replace(';','<br>',$result['remarks']). "</td>".
                                "</tr>";
    
    
                        $totalWork += $result['workhours'];
                        $totalLate += round($result['late']*60);
                        $totalUndertime += round($result['undertime']*60);
                        $totalOvertime += $result['overtime'];
    
                    } while ($result = $stmt->fetch());     
	
            }

        echo"
            </tbody>
            <tfoot>
                <tr>".
                    "<td colspan='4' class='text-right bg-success'><b>Total</b></td>".
                    "<td class='bg-success'><b>" . $totalWork . "</b></td>".
                    "<td class='bg-success'><b>" . $totalLate . "</b></td>".
                    "<td class='bg-success'><b>" . $totalUndertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalOvertime . "</b></td>".
                    "<td class='bg-success'><b></b></td>".
                    "<td class='bg-success'><b></b></td>".
                "</tr>
            </tfoot>
        </table>";
    }
}

?>