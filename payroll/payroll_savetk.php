<?php  

   function SaveTk($empCode,$pfrom,$pto,$ppay)
    {
            global $connL;


                $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
                VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
                $stmt_ins =$connL->prepare($qins);                                 
                $params = array(
                    ":pay_from" => $pfrom,
                    ":pay_to" => $pto,
                    ":remarks" => 'SAVED',
                    ":audituser" => $empCode,
                    ":auditdate" => date('Y-m-d H:i:s'),
                );                                
                $result = $stmt_ins->execute($params);

                echo $result;
    }


   function SaveEmpTk($empCode,$pfrom,$pto,$ppay)
    {
            global $connL;


                $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
                VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
                $stmt_ins =$connL->prepare($qins);                                 
                $params = array(
                    ":pay_from" => $pfrom,
                    ":pay_to" => $pto,
                    ":remarks" => 'SAVED EMP',
                    ":audituser" => $empCode,
                    ":auditdate" => date('Y-m-d H:i:s'),
                );                                
                $result = $stmt_ins->execute($params);

                echo $result;
    }    

 ?>