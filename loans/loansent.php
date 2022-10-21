<?php

Class LoansEnt{

public function InsertLoansEnt($eMplogName,$emp_code,$loandec_id,$loan_date,$loan_amount,$loan_balance,$loan_totpymt,$loan_amort)
    {
        global $connL;

            $query = "INSERT INTO employee_loans_management (emp_code,loandec_id,loan_date,loan_amount,loan_balance,loan_totpymt,loan_amort,audituser,auditdate) 

                VALUES(:emp_code,:loandec_id,:loan_date,:loan_amount,:loan_balance,:loan_totpymt,:loan_amort,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":emp_code"=> $emp_code,
                    ":loandec_id" => $loandec_id,
                    ":loan_date" => $loan_date,
                    ":loan_amount"=> $loan_amount,
                    ":loan_balance"=> $loan_balance,
                    ":loan_totpymt"=> $loan_totpymt,
                    ":loan_amort"=> $loan_amort,
                    ":audituser" => $eMplogName,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                );

            $result = $stmt->execute($param);

            echo $result;

    }


}

?>