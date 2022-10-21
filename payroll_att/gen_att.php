<?php 
              

function GenAttendance($eMplogName,$pyrollco_from,$pyrollco_to){

           
    global $connL;

    // ATTENDANCE

    $cmd = $connL->prepare('EXEC insert_xp_attendance_portal :emp_name,:pay_from,:pay_to');
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

    // WORK FROM HOME

    $cmdwfh = $connL->prepare('EXEC GenerateWfhToAttendance :pay_from,:pay_to,:eMplogName');
    $cmdwfh->bindValue(':pay_from',$pyrollco_from);
    $cmdwfh->bindValue(':pay_to',$pyrollco_to);
    $cmdwfh->bindValue(':eMplogName',$eMplogName);
    $cmdwfh->execute();


    $querywfh = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmtwfh =$connL->prepare($querywfh);

        $paramwfh = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Work From Home',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d')                                          
        );

    $resultwfh = $stmtwfh->execute($paramwfh);

    echo $resultwfh;    

    // OFFIICIAL BUSINESS 

    $cmdob = $connL->prepare('EXEC GenerateOBToAttendance :pay_from,:pay_to,:eMplogName');
    $cmdob->bindValue(':pay_from',$pyrollco_from);
    $cmdob->bindValue(':pay_to',$pyrollco_to);
    $cmdob->bindValue(':eMplogName',$eMplogName);
    $cmdob->execute();


    $queryob = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmtob =$connL->prepare($queryob);

        $paramob = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Official Business',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d')                                          
        );

    $resultob = $stmtob->execute($paramob);

    echo $resultob;

    // LEAVE

    $cmdlv = $connL->prepare('EXEC GenerateLeaveToAttendance :pay_from,:pay_to,:eMplogName');
    $cmdlv->bindValue(':pay_from',$pyrollco_from);
    $cmdlv->bindValue(':pay_to',$pyrollco_to);
    $cmdlv->bindValue(':eMplogName',$eMplogName);
    $cmdlv->execute();


    $querylv = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) 
                                    VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmtlv =$connL->prepare($querylv);

        $paramlv = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Leave',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d h:i:s')                                          
        );

    $resultlv = $stmtlv->execute($paramlv);

    echo $resultlv;
    

    //OVERTIME 

    $cmdot2 = $connL->prepare('EXEC GenerateActualOtRendered :pay_from,:pay_to');
    $cmdot2->bindValue(':pay_from',$pyrollco_from);
    $cmdot2->bindValue(':pay_to',$pyrollco_to);
    $cmdot2->execute();

    $cmdot = $connL->prepare('EXEC GenerateOTToAttendance :pay_from,:pay_to');
    $cmdot->bindValue(':pay_from',$pyrollco_from);
    $cmdot->bindValue(':pay_to',$pyrollco_to);
    $cmdot->execute();


    $queryot = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmtot =$connL->prepare($queryot);

        $paramot = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Overtime',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d h:i:s')                                          
        );

    $resultot = $stmtot->execute($paramot);

    echo $resultot;    
    
                                       
}

function GenEmpAttendance($eMplogName,$pyrollco_from,$pyrollco_to){

           
    global $connL;

    $cmd = $connL->prepare('EXEC insert_xp_attendance_portal :emp_name,:pay_from,:pay_to');
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
