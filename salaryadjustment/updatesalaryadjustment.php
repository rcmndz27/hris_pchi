<?php 


function UpdateSalaryAdj($emp_code,$period_from,$period_to,$description,$inc_decr,$amount,$remarks,$saladjid)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.employee_salaryadj_management SET 
                period_from = :period_from,
                period_to = :period_to,
                description = :description,
                inc_decr = :inc_decr,
                amount = :amount,  
                remarks = :remarks
             where emp_code = :emp_code and salaryadj_id = :saladjid");
            $cmd->bindValue('emp_code',$emp_code);
            $cmd->bindValue('period_from',$period_from);
            $cmd->bindValue('period_to',$period_to);
            $cmd->bindValue('description',$description);
            $cmd->bindValue('inc_decr',$inc_decr);
            $cmd->bindValue('amount',$amount);
            $cmd->bindValue('remarks',$remarks);
            $cmd->bindValue('saladjid',$saladjid);
            $cmd->execute();
    }


?>
