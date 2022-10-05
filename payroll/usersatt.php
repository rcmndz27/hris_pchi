<?php

Class UsersAtt{

public function InsertUsersAtt($bdno,$name,$pfrom,$pto,$loct,$logname)
    {
        global $connL;

            $query = "INSERT INTO att_summary (company,badge_no,employee,location,period_from,period_to,audituser,auditdate) 

                VALUES(:company,:badge_no,:employee,:location,:period_from,:period_to,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":company"=> 'PCHI',
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

    }


}

?>