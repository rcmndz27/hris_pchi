<?php

    Class EmployeeDTR{

        public function GetTime($dateTime){
            $formattedDateTime = new DateTime($dateTime);
            $time = $formattedDateTime->format('h:i:s A');
    
            return $time;
        }

        public function GetAttendanceList($dateStart, $dateEnd, $empcode){

        global $connL;
        global $dbConnection;

        $totalWork = 0;
        $totalLate = 0;
        $totalUndertime = 0;
        $totalOvertime = 0;

        $queryy = "SELECT * from employee_profile where emp_code = :empcode";
        $stmty =$connL->prepare($queryy);
        $paramy = array(":empcode" => $empcode);
        $stmty->execute($paramy);
        $resulty = $stmty->fetch();
        $cmp = $resulty['company'];
        $subemp = strlen($cmp);
        
        $query = 'EXEC dbo.xp_attendance_portal :emp_code,:startDate,:endDate';
        $param = array(":emp_code" => substr($empcode,$subemp), ":startDate" => $dateStart, ":endDate" => $dateEnd );
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $result = $stmt->fetch();
        $nm = (isset($result['name'])) ? $result['name'] : '' ;
        // $name=str_replace('.',', ',$nm);
        echo "
        <button id='btnExport' onclick='exportReportToExcel(this)' class='btn btn-primary'><i class='fas fa-file-export'></i> Export </button>
            <table id='dtrList' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='8' class='text-center'>My Attendance</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Work (Hrs)</th>
                        <th>Late</th>
                        <th>Undertime</th>
                        <th>Overtime</th>
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
    
                        echo    "<tr >
                                <td class=".$class.">" . date('F d, Y', strtotime($result['punch_date'])) . "</td>".
                                "<td>" . $timeIn . "</td>".
                                "<td>" . $timeOut . "</td>".
                                "<td>" . $swork . "</td>".
                                "<td>" . $slate."</td>".
                                "<td>" . $sundertime."</td>".
                                "<td>" . $sovertime."</td>".
                                "<td>" . $result['remarks'] . "</td>".
                                "</tr>";
    
                        $totalWork += $result['workhours'];
                        $totalLate += round($result['late']*60);
                        $totalUndertime += round($result['undertime']*60);
                        $totalOvertime += $result['overtime'];
    
                    } while ($result = $stmt->fetch()); 	

                    echo"</tbody>";

                    echo "<tfoot>
                            <tr>".
                                "<td colspan='3' class='text-right bg-secondary'><b>Total</b></td>".
                                "<td class='bg-secondary'><b>" . $totalWork . "</b></td>".
                                "<td class='bg-secondary'><b>" . $totalLate . "</b></td>".
                                "<td class='bg-secondary'><b>" . $totalUndertime . "</b></td>".
                                "<td class='bg-secondary'><b>" . $totalOvertime . "</b></td>".
                                "<td class='bg-secondary'><b></b></td>".
                            "</tr>
                        </tfoot>";
                }else { 
                    echo '<tfoot><tr><td colspan="7" class="text-center">No Results Found</td></tr></tfoot>'; 
                }
    
            echo"
                
            </table>";
    

        }

        public function ListTeamMembers($reportingTo){

            global $connL;

            $query = 'SELECT emp_code, employee FROM view_employee WHERE reporting_to = :reporting_to ORDER BY employee';
            $param = array(":reporting_to" => $reportingTo);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();

            $teamMemberList = '';

            $teamMemberList.='<select id="memberList" class="form-control" >';
            $teamMemberList.= '<option value=""></option>';

            if($result){
                do {
                    $teamMemberList.= '<option value="'.$result['emp_code'].'">'.$result['employee'].'</option>';
                } while ($result = $stmt->fetch()); 	

                $teamMemberList.='</select>';
            }

            echo $teamMemberList;


        }

    }



?>