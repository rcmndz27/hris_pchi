<?php

Class UsersAtt{

public function InsertUsersAtt($bdno,$name,$pfrom,$pto,$loct,$logname)
    {
        global $connL;

            $query = "INSERT INTO att_summary (company,badge_no,employee,location,period_from,period_to,audituser,auditdate) 

                VALUES(:company,:badge_no,:employee,:location,:period_from,:period_to,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":company"=> 'PCH',
                    ":badge_no" => $bdno,
                    ":employee" => $name,
                    ":location"=> $loct,
                    ":period_from"=> $pfrom,
                    ":period_to"=> $pto,
                    ":audituser" => $logname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                );

            $result = $stmt->execute($param);

            echo $result;

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $pfrom,
                ":pay_to" => $pto,
                ":remarks" => 'READY',
                ":audituser" => $logname,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $rins = $stmt_ins->execute($params);

            echo $rins;              

    }

public function InsertUsersAttEmp($bdno,$name,$pfrom,$pto,$loct,$logname)
    {
        global $connL;

            $query = "INSERT INTO att_summary (company,badge_no,employee,location,period_from,period_to,audituser,auditdate) 

                VALUES(:company,:badge_no,:employee,:location,:period_from,:period_to,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":company"=> 'PCH',
                    ":badge_no" => $bdno,
                    ":employee" => $name,
                    ":location"=> $loct,
                    ":period_from"=> $pfrom,
                    ":period_to"=> $pto,
                    ":audituser" => $logname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                );

            $result = $stmt->execute($param);

            echo $result;

            $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
            VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
            $stmt_ins =$connL->prepare($qins);                                 
            $params = array(
                ":pay_from" => $pfrom,
                ":pay_to" => $pto,
                ":remarks" => 'READY EMP',
                ":audituser" => $logname,
                ":auditdate" => date('Y-m-d H:i:s'),
            );                                
            $rins = $stmt_ins->execute($params);

            echo $rins;              

    }



}

?>