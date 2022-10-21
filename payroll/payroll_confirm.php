<?php  

function ConfirmPayRegEmpView($date_from,$date_to,$empCode,$badgeno)
    {
            global $connL;

            $q_logs = 'SELECT * FROM dbo.payroll_period_logs WHERE rowid = (SELECT MAX(rowid) AS id from dbo.payroll_period_logs where emp_code = :emp_cd)';
            $paramq = array(":emp_cd" => $empCode);
            $stmt_q =$connL->prepare($q_logs);
            $stmt_q->execute($paramq);
            $rs = $stmt_q->fetch();
            
            $loc = $rs["location"];
        
            $cmd = $connL->prepare("UPDATE dbo.payroll SET payroll_status = 'N' where date_from = :date_from and date_to = :date_to and location = :location and emp_code = :badgeno");
            $cmd->bindValue('date_from',$date_from);
            $cmd->bindValue('date_to', $date_to);
            $cmd->bindValue('location', $loc);
            $cmd->bindValue('badgeno', $badgeno);
            $cmd->execute();

    }


function DeletePayReg($date_from,$date_to,$empCode)
    {
            global $connL;
        
            $cmd = $connL->prepare("DELETE from dbo.payroll where date_from = :date_from and date_to = :date_to");
            $cmd->bindValue('date_from',$date_from);
            $cmd->bindValue('date_to', $date_to);
            $cmd->execute();


            $squery = "SELECT lastname+', '+firstname as [fullname] FROM employee_profile WHERE emp_code = :empCode";
            $sparam = array(':empCode' => $empCode);
            $sstmt =$connL->prepare($squery);
            $sstmt->execute($sparam);
            $sresult = $sstmt->fetch();
            $sname = $sresult['fullname'];


        $query = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser, auditdate) 
            VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate) ";

            $stmt =$connL->prepare($query);

            $param = array(
                ":pay_from"=> $date_from,
                ":pay_to" => $date_to,
                ":remarks" => 'Delete PayReg',
                ":audituser" => $sname,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

        $result = $stmt->execute($param);

        echo $result;

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $date_from,
                ":pay_to" => $date_to,
                ":remarks" => 'DELETED',
                ":audituser" => $empCode,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $result = $stmt_ins->execute($params);

            echo $result; 

    }

    function DeletePayEmpReg($date_from,$date_to,$empCode,$emp_code)
    {
            global $connL;
        
            $cmd = $connL->prepare("DELETE from dbo.payroll where date_from = :date_from and date_to = :date_to and emp_code = :emp_code");
            $cmd->bindValue('date_from',$date_from);
            $cmd->bindValue('date_to', $date_to);
            $cmd->bindValue('emp_code', $emp_code);
            $cmd->execute();


            $squery = "SELECT lastname+', '+firstname as [fullname] FROM employee_profile WHERE emp_code = :empCode";
            $sparam = array(':empCode' => $empCode);
            $sstmt =$connL->prepare($squery);
            $sstmt->execute($sparam);
            $sresult = $sstmt->fetch();
            $sname = $sresult['fullname'];


        $query = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser, auditdate) 
            VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate) ";

            $stmt =$connL->prepare($query);

            $param = array(
                ":pay_from"=> $date_from,
                ":pay_to" => $date_to,
                ":remarks" => 'Delete PayReg',
                ":audituser" => $sname,
                ":auditdate"=>date('m-d-Y H:i:s')
            );

        $result = $stmt->execute($param);

        echo $result;

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $date_from,
                ":pay_to" => $date_to,
                ":remarks" => 'DELETED EMP',
                ":audituser" => $empCode,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $result = $stmt_ins->execute($params);

            echo $result; 

    }    

 ?>