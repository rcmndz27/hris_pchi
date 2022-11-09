<?php

Class MfholidayEnt{

public function InsertMfholidayEnt($eMplogName,$holidaydate,$holidaytype,$holidaydescs,$holidayterm,$expired_date,$status)
    {
        global $connL;

            $query = "INSERT INTO mf_holiday (holidaydate,holidaytype,holidaydescs,holidayterm,expired_date,status,audituser,auditdate) 
            VALUES(:holidaydate,:holidaytype,:holidaydescs,:holidayterm,:expired_date,:status,:audituser,:auditdate)";
    
                $stmt =$connL->prepare($query);

                $param = array(
                    ":holidaydate"=> $holidaydate,
                    ":holidaytype" => $holidaytype,
                    ":holidaydescs" => $holidaydescs,
                    ":holidayterm" => $holidayterm,
                    ":expired_date" => $expired_date,
                    ":status" => 'Active',
                    ":audituser" => $eMplogName,
                    ":auditdate" => date('Y-m-d'),                                       
                );

            $result = $stmt->execute($param);

            echo $result;

    }


}

?>