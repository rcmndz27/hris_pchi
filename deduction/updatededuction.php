<?php

date_default_timezone_set('Asia/Manila'); 


function UpdateDeduction($emp_code,$deduction_id,$period_cutoff,$amount,$effectivity_date,$end_date,$status,$dedcid)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.employee_deduction_management SET 
                deduction_id = :deduction_id,
                period_cutoff = :period_cutoff,
                amount = :amount,
                effectivity_date = :effectivity_date,
                end_date = :end_date,
                status = :status
             where emp_code = :emp_code and deduction_emp_id = :dedcid");
            $cmd->bindValue('emp_code',$emp_code);
            $cmd->bindValue('deduction_id',$deduction_id);
            $cmd->bindValue('period_cutoff',$period_cutoff);
            $cmd->bindValue('amount',$amount);
            $cmd->bindValue('effectivity_date',$effectivity_date);
            $cmd->bindValue('end_date',$end_date);
            $cmd->bindValue('status',$status);
            $cmd->bindValue('dedcid',$dedcid);
            $cmd->execute();
    }


?>
