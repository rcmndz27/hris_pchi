<?php

    Class AttRepDTR{

        public function GetAttRepList($dateStart,$dateEnd){

            global $connL;

            $total_days = 0;

            $query = "SELECT count(a.punch_date) as [total_days],b.lastname+','+b.firstname as [fullname]
            ,(CASE WHEN count(a.punch_date) >= 20 then 'Perfect Attendance' else ' ' END) as remarks from employee_attendance a right join employee_profile b on a.emp_code = b.badgeno
            where punch_date between :startDate and :endDate and timein is not null and timeout is not null
            and b.emp_status = 'Active' GROUP by b.badgeno,b.lastname,b.firstname";
            $param = array(":startDate" => $dateStart, ":endDate" => $dateEnd);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();

            echo "
            <table id='attRepListTab' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='7' class='text-center'>Attendance from ".date('F d', strtotime($dateStart))." to ".date('F d, Y', strtotime($dateEnd))." </th>
                    </tr>
                    <tr>
                        <th>Days</th>
                        <th>Full Name</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>";

                if($result){
                    do { 
                        echo    "<tr>".
                                "<td>" . round($result['total_days'],2) . "</td>".
                                "<td>" . $result['fullname'] . "</td>".
                                "<td>" . $result['remarks'] . "</td>".
                                "</tr>";
    
                        $total_days += $result['total_days'];
    
                    } while ($result = $stmt->fetch()); 	

                    echo"</tbody>";

                    echo "<tfoot>
                            <tr>".
                                "<td class='bg-success'><b>" . $total_days . "</b></td>".
                                "<td class='bg-success'><b></b></td>".
                                "<td class='bg-success'><b></b></td>".
                            "</tr>
                        </tfoot>";
                }else { 
                    echo '<tfoot></tfoot>'; 
                }
    
            echo"
                
            </table>";
    

        }

        public function GetLateList($dateStart,$dateEnd){

            global $connL;

            $total_lates = 0;

            $query = 'EXEC xp_attendance_portal_late :startDate,:endDate';
            $param = array(":startDate" => $dateStart, ":endDate" => $dateEnd);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();            

            echo "
            <table id='lateListTab' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='7' class='text-center'>Lates from ".date('F d', strtotime($dateStart))." to ".date('F d, Y', strtotime($dateEnd))." </th>
                    </tr>
                    <tr>
                        <th>Lates (Hrs)</th>
                        <th>Full Name</th>
                    </tr>
                </thead>
                <tbody>";

                if($result){
                    do { 
                        echo    "<tr>".
                                "<td>" . round($result['tot_late'],2) . "</td>".
                                "<td>" . $result['fullname'] . "</td>".
                                "</tr>";
    
                        $total_lates += $result['tot_late'];
    
                    } while ($result = $stmt->fetch());     

                    echo"</tbody>";

                    echo "<tfoot>
                            <tr>".
                                "<td class='bg-success'><b>" . $total_lates . "</b></td>".
                                "<td class='bg-success'><b></b></td>".
                            "</tr>
                        </tfoot>";
                }else { 
                    echo '<tfoot></tfoot>'; 
                }
    
            echo"
                
            </table>";
    

        }

        public function GetUTList($dateStart,$dateEnd){

            global $connL;
            $total_ut = 0;

            $query = 'EXEC xp_attendance_portal_ut :startDate,:endDate';
            $param = array(":startDate" => $dateStart, ":endDate" => $dateEnd);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();            

            echo "
            <table id='utListTab' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='7' class='text-center'>Undertime from ".date('F d', strtotime($dateStart))." to ".date('F d, Y', strtotime($dateEnd))." </th>
                    </tr>
                    <tr>
                        <th>Undertime (Hrs)</th>
                        <th>Full Name</th>
                    </tr>
                </thead>
                <tbody>";

                if($result){
                    do { 
                        echo    "<tr>".
                                "<td>" . round($result['tot_ut'],2) . "</td>".
                                "<td>" . $result['fullname'] . "</td>".
                                "</tr>";
    
                        $total_ut += $result['tot_ut'];
    
                    } while ($result = $stmt->fetch());     

                    echo"</tbody>";

                    echo "<tfoot>
                            <tr>".
                                "<td class='bg-success'><b>" . $total_ut . "</b></td>".
                                "<td class='bg-success'><b></b></td>".
                            "</tr>
                        </tfoot>";
                }else { 
                    echo '<tfoot></tfoot>'; 
                }
    
            echo"
                
            </table>";
        }  

        public function GetNList($dateStart,$dateEnd){

            global $connL;
            $total_nologin = 0;
            $total_nologout = 0;

            $query = 'EXEC xp_attendance_portal_nologs :startDate,:endDate';
            $param = array(":startDate" => $dateStart, ":endDate" => $dateEnd);
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $result = $stmt->fetch();            

            echo "
            <table id='nListTab' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='7' class='text-center'>No Logs from ".date('F d', strtotime($dateStart))." to ".date('F d, Y', strtotime($dateEnd))." </th>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <th>No Login</th>
                        <th>No Logout</th>                                                
                    </tr>
                </thead>
                <tbody>";

                if($result){
                    do { 
                        echo    "<tr>".
                                "<td>" . $result['name'] . "</td>".
                                "<td>" . round($result['no_login'],2) . "</td>".
                                "<td>" . round($result['no_logout'],2) . "</td>".
                                "</tr>";
    
                        $total_nologin += $result['no_login'];
                        $total_nologout += $result['no_logout'];
    
                    } while ($result = $stmt->fetch());     

                    echo"</tbody>";

                    echo "<tfoot>
                            <tr>".
                                "<td class='bg-success'><b></b></td>".
                                "<td class='bg-success'><b>" . $total_nologin . "</b></td>".
                                "<td class='bg-success'><b>" . $total_nologout . "</b></td>".
                            "</tr>
                        </tfoot>";
                }else { 
                    echo '<tfoot></tfoot>'; 
                }
    
            echo"
                
            </table>";
        }              

    }



?>