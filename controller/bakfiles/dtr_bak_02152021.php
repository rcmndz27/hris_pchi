<?php

    function GetAttendanceList($dateStart, $dateEnd, $empId = "", $empDept = "", $searchType = 0){

        $totalHoursWorked = 0;
        $totalLateHours = 0;
        $totalRegularOvertime = 0;
        $totalRestdayOvertime = 0;
        $totalRegularHolidayOvertime = 0;
        $totalSpecialHolidayOvertime = 0;
        $totalUndertime = 0;
        $totalNightDiff = 0;

        global $connL;

        $cmd = $connL->prepare('exec dbo.sp_att_details_temp :emp_code, :date_start, :date_end');
        $cmd->bindValue(':emp_code', trim($empId));
        $cmd->bindValue(':date_start', $dateStart);
        $cmd->bindValue(':date_end', $dateEnd);
        $cmd->execute();

        echo "
        <table id='dtrList' class='table table-dark table-striped table-sm'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Schedule</th>
                    <th>Day</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Work (Hrs)</th>
                    <th>Lates (Hrs)</th>
                    <th>Undertime (Hrs)</th>
                    <th>Regular Overtime (Hrs)</th>
                    <th>Rest Day Overtime (Hrs)</th>
                    <th>Regular Holiday Overtime (Hrs)</th>
                    <th>Special Holiday Overtime (Hrs)</th>
                    <th>Night Differential (Hrs)</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>";

            while($r = $cmd->fetch(PDO::FETCH_ASSOC))
            {
                echo "<tr>".
                    "<td>" . $r['Date'] . "</td>".
                    "<td>" . $r['Schedule'] . "</td>".
                    "<td>" . $r['Day'] . "</td>".
                    "<td>" . $r['Time In'] . "</td>".
                    "<td>" . $r['Time Out'] . "</td>".
                    "<td>" . round($r['Hours Worked'], 2) . "</td>".
                    "<td>" . round($r['Late (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Undertime (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Regular Overtime (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Rest Day Overtime (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Regular Holiday Overtime (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Special Holiday Overtime (Hrs)'], 2) . "</td>".
                    "<td>" . round($r['Night Differential (Hrs)'], 2) . "</td>".
                    "<td>" . $r['Remarks'] . "</td>".
                    "</tr>";

                $totalHoursWorked += round($r['Hours Worked'], 2);
                $totalLateHours += round($r['Late (Hrs)'], 2);
                $totalRegularOvertime += round($r['Regular Overtime (Hrs)'], 2);
                $totalRestdayOvertime += round($r['Rest Day Overtime (Hrs)'], 2);
                $totalRegularHolidayOvertime += round($r['Regular Holiday Overtime (Hrs)'], 2);
                $totalSpecialHolidayOvertime += round($r['Special Holiday Overtime (Hrs)'], 2);
                $totalUndertime += round($r['Undertime (Hrs)'], 2);
                $totalNightDiff += round($r['Night Differential (Hrs)'], 2);
            }

            echo"
            </tbody>
            <tfoot>
                <tr>".
                    "<td colspan='5' class='text-center bg-success'><b>Total</b></td>".
                    "<td class='bg-success'><b>" . $totalHoursWorked . "</b></td>".
                    "<td class='bg-success'><b>" . $totalLateHours . "</b></td>".
                    "<td class='bg-success'><b>" . $totalUndertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalRegularOvertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalRestdayOvertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalRegularHolidayOvertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalSpecialHolidayOvertime . "</b></td>".
                    "<td class='bg-success'><b>" . $totalNightDiff . "</b></td>".
                    "<td class='bg-success'></td>".
                "</tr>
            </tfoot>
        </table>";
    }

    



?>