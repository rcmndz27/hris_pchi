<?php 
              

function GenAttendance($eMplogName,$pyrollco_from,$pyrollco_to){

           
    global $connL;

    $cmd = $connL->prepare('EXEC dbo.insert_xp_attendance_portal :emp_name,:pay_from,:pay_to');
    $cmd->bindValue(':emp_name',$eMplogName);
    $cmd->bindValue(':pay_from',$pyrollco_from);
    $cmd->bindValue(':pay_to',$pyrollco_to);
    $cmd->execute();


    $query = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmt =$connL->prepare($query);

        $param = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Attendance',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d')                                          
        );

    $result = $stmt->execute($param);

    echo $result;

    $qins = 'INSERT INTO dbo.logs_timekeep (pay_from,pay_to,remarks,audituser,auditdate) 
    VALUES (:pay_from,:pay_to,:remarks,:audituser,:auditdate)';
    $stmt_ins =$connL->prepare($qins);                                 
    $params = array(
        ":pay_from" => $pyrollco_from,
        ":pay_to" => $pyrollco_to,
        ":remarks" => 'READY',
        ":audituser" => $eMplogName,
        ":auditdate" => date('Y-m-d H:i:s'),
    );                                
    $rins = $stmt_ins->execute($params);

    echo $rins;    
    
                                       
}


?>
