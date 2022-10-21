<?php 


function UpdateLoans($rowid,$emp_code,$loandec_id,$loan_amount,$loan_balance,$loan_totpymt,$loan_amort,$loan_date,$status)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.employee_loans_management SET 
                loandec_id = :loandec_id,
                loan_amount = :loan_amount,
                loan_balance = :loan_balance,
                loan_totpymt = :loan_totpymt,
                loan_amort = :loan_amort,
                loan_date = :loan_date,
                status = :status
             where emp_code = :emp_code and loan_id = :rowid ");
            $cmd->bindValue('emp_code',$emp_code);
            $cmd->bindValue('rowid',$rowid);
            $cmd->bindValue('loandec_id',$loandec_id);
            $cmd->bindValue('loan_amount',$loan_amount);
            $cmd->bindValue('loan_balance',$loan_balance);
            $cmd->bindValue('loan_totpymt',$loan_totpymt);
            $cmd->bindValue('loan_amort',$loan_amort);
            $cmd->bindValue('loan_date',$loan_date);
            $cmd->bindValue('status',$status);
            $cmd->execute();
    }


?>
