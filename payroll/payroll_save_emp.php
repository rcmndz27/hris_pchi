<?php  

   function ApprovePayEmpView($empCode,$pfrom,$pto,$ppay,$badgeno)
    {
            global $connL;

            $query_pay = $connL->prepare('EXEC payroll_summary_emp :period_from,:period_to,:location, :emp_code,:badgeno');
            $query_pay->bindValue(':period_from',$pfrom);
            $query_pay->bindValue(':period_to',$pto);
            $query_pay->bindValue(':location','Makati');
            $query_pay->bindValue(':emp_code',$empCode);
            $query_pay->bindValue(':badgeno',$badgeno);
            $query_pay->execute();

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $pfrom,
                ":pay_to" => $pto,
                ":remarks" => 'GENERATED EMP',
                ":audituser" => $empCode,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $result = $stmt_ins->execute($params);

            echo $result;            

    }

   function ApprovePayEmpView30($empCode,$pfrom,$pto,$pfrom30,$pto30,$ppay,$badgeno)
    {
            global $connL;

            $query_pay = $connL->prepare('EXEC payroll_summary_30th_emp :period_from, :period_to , :period_from30, :period_to30 , :location,:emp_code,:badgeno');
            $query_pay->bindValue(':period_from',$pfrom);
            $query_pay->bindValue(':period_to',$pto);
            $query_pay->bindValue(':period_from30',$pfrom30);
            $query_pay->bindValue(':period_to30',$pto30);            
            $query_pay->bindValue(':location','Makati');
            $query_pay->bindValue(':emp_code',$empCode);
            $query_pay->bindValue(':badgeno',$badgeno);
            $query_pay->execute();

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $pfrom,
                ":pay_to" => $pto,
                ":remarks" => 'GENERATED EMP',
                ":audituser" => $empCode,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $result = $stmt_ins->execute($params);

            echo $result;              

    }    

 ?>