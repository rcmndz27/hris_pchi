<?php

Class DeductionEnt{

public function InsertDeductionEnt($eMplogName,$emp_code,$deduction_id,$period_cutoff,$effectivity_date,$end_date,$amount)
    {
        global $connL;

            $query = "INSERT INTO employee_deduction_management (emp_code,deduction_id,period_cutoff,effectivity_date,end_date,amount,status,audituser,auditdate) 

                VALUES(:emp_code,:deduction_id,:period_cutoff,:effectivity_date,:end_date,:amount,:status,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $emp_code,
                    ":deduction_id" => $deduction_id,
                    ":period_cutoff" => $period_cutoff,
                    ":effectivity_date"=> $effectivity_date,
                    ":end_date"=> $end_date,
                    ":amount"=> $amount,
                    ":status"=> 'Active',
                    ":audituser" => $eMplogName,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                );

            $result = $stmt->execute($param);

            echo $result;

    }


}

?>