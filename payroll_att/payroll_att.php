<?php 


function GetPayrollAttList($action, $dtFrom, $dtTo,$location,$empCode){

           
            global $connL;

            $totalDaysAbsent = 0;
            $totalDaysWorked = 0;
            $lates = 0;
            $undertime = 0;
            $reg_ot = 0; 
            $rd_ot = 0;
            $rh_ot = 0;
            $sh_ot = 0;

            $qins = 'INSERT INTO dbo.payroll_period_logs (emp_code,period_from,period_to,location) 
            VALUES (:emp_code,:period_from,:period_to,:location)';
            $stmt_ins =$connL->prepare($qins);                                 
                                                $params = array(
                                                ":emp_code" => $empCode,
                                                ":period_from" => $dtFrom,
                                                ":period_to" => $dtTo,
                                                ":location" => $location,
                                                );                                
            $result = $stmt_ins->execute($params);


            $query = 'SELECT * FROM dbo.att_summary WHERE period_from = :period_from AND period_to = :period_to and location = :location ORDER BY employee ASC';
            $param = array(":period_from" => $dtFrom, ":period_to" => $dtTo, ":location" => $location );
            $stmt =$connL->prepare($query);
            $stmt->execute($param);
            $r = $stmt->fetch();


            echo "
            <input type='text' id='myInput' onkeyup='myFunction()' placeholder='Search for names..' title='Type in a name'>
            <table id='payrollAttList' class='table table-striped table-sm'>
                <thead>
                    <tr>
                        <th colspan='12' class='paytop'>Payroll Period of ".$location." from ".$dtFrom." to ".$dtTo."  </th>
                    </tr>
                    <tr>
                        <th>Employee Name</th>
                        <th>Cut-off From</th>
                        <th>Cut-off To</th>
                        <th>Total Days Absent</th>
                        <th>Total Days Worked</th>
                        <th>Lates (Hrs)</th>
                        <th>Undertime (Hrs)</th>
                        <th>Regular Overtime (Hrs)</th>
                        <th>Rest Day Overtime (Hrs)</th>
                        <th>Regular Holiday Overtime (Hrs)</th>
                        <th>Special Holiday Overtime (Hrs)</th>
                        <th>Action</th>            
                    </tr>
                </thead>
                <tbody>";

                 if($r){
                    do {
                            echo "<tr>".
                                    "<td>" . $r['employee'] . "</td>".
                                    "<td>" . date('m/d/Y', strtotime($r['period_from'])) . "</td>".
                                    "<td>" . date('m/d/Y', strtotime($r['period_to'])) . "</td>".                                            
                                    "<td>" . round($r['tot_days_absent'],2) . "</td>".
                                    "<td>" . round($r['tot_days_work'],2) . "</td>".
                                    "<td>" . round($r['tot_lates'],2) . "</td>".
                                    "<td>" . round($r['total_undertime'],2) . "</td>".
                                    "<td>" . round($r['tot_overtime_reg'],2) . "</td>".
                                    "<td>" . round($r['tot_overtime_rest'],2) . "</td>".
                                    "<td>" . round($r['tot_overtime_regholiday'],2) . "</td>".
                                    "<td>" . round($r['tot_overtime_spholiday'],2) . "</td>";
                                    echo'<td><button type="button" class="editcv" onclick="editAttendanceModal()">
                                    <i class="fas fa-edit"></i> EDIT
                                </button>';
                
                                $totalDaysAbsent += round($r['tot_days_absent'], 2);
                                $totalDaysWorked += round($r['tot_days_work'] , 2);
                                $lates += round($r['tot_lates'], 2);
                                $undertime += round($r['total_undertime'] , 2);
                                $reg_ot += round($r['tot_overtime_reg'], 2);
                                $rd_ot += round($r['tot_overtime_rest'] , 2);
                                $rh_ot += round($r['tot_overtime_regholiday'], 2);
                                $sh_ot += round($r['tot_overtime_spholiday'] , 2);
                
                               
                   } while($r = $stmt->fetch(PDO::FETCH_ASSOC));

                                        echo"</tbody>";
                                        echo "<tfoot>
                                        <tr>".
                                            "<td colspan='3' class='text-center bg-success'><b>Total</b></td>".
                                            "<td class='bg-success'><b>" . $totalDaysAbsent . "</b></td>".
                                            "<td class='bg-success'><b>" . $totalDaysWorked . "</b></td>".
                                            "<td class='bg-success'><b>" . $lates . "</b></td>".
                                            "<td class='bg-success'><b>" . $undertime . "</b></td>".
                                            "<td class='bg-success'><b>" . $reg_ot . "</b></td>".
                                            "<td class='bg-success'><b>" . $rd_ot . "</b></td>".
                                            "<td class='bg-success'><b>" . $rh_ot . "</b></td>".
                                            "<td class='bg-success'><b>" . $sh_ot . "</b></td>".
                                            "<td class='bg-success'></td>".
                                            "</tr><tr></tr></tfoot>";  
                                                                    
                }else { 
                    echo '<tfoot><tr><td colspan="12" class="paytop">No Results Found</td></tr></tfoot>'; 
                }
    
            echo"</table>"; 
                                          
} 

function UpdatePayAtt($tda,$tdw,$late,$ut,$regot,$resot,$reghot,$sphot,$rowid)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.att_summary SET tot_days_absent = :tda, tot_days_work = :tdw ,tot_lates = :late, 
            total_undertime = :ut, tot_overtime_reg = :regot ,tot_overtime_rest = :resot,
            tot_overtime_regholiday = :reghot, tot_overtime_spholiday = :sphot where rowid = :rowid");
            $cmd->bindValue('tda',$tda);
            $cmd->bindValue('tdw', $tdw);
            $cmd->bindValue('late', $late);
            $cmd->bindValue('ut', $ut);
            $cmd->bindValue('regot', $regot);
            $cmd->bindValue('resot', $resot);
            $cmd->bindValue('reghot', $reghot);
            $cmd->bindValue('sphot', $sphot);
            $cmd->bindValue('rowid', $rowid);
            $cmd->execute();
    }


?>
