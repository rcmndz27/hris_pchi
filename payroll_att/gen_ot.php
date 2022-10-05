<?php 
              

function GenOt($eMplogName,$pyrollco_from,$pyrollco_to){

           
    global $connL;

    $cmd2 = $connL->prepare('EXEC dbo.GenerateActualOtRendered :pay_from,:pay_to');
    $cmd2->bindValue(':pay_from',$pyrollco_from);
    $cmd2->bindValue(':pay_to',$pyrollco_to);
    $cmd2->execute();

    $cmd = $connL->prepare('EXEC dbo.GenerateOTToAttendance :pay_from,:pay_to');
    $cmd->bindValue(':pay_from',$pyrollco_from);
    $cmd->bindValue(':pay_to',$pyrollco_to);
    $cmd->execute();


    $query = "INSERT INTO logs_gen_script (pay_from,pay_to,remarks,audituser,auditdate) VALUES(:pay_from,:pay_to,:remarks,:audituser,:auditdate)";

        $stmt =$connL->prepare($query);

        $param = array(
            ":pay_from"=> $pyrollco_from,
            ":pay_to"=> $pyrollco_to,
            ":remarks"=> 'Overtime',
            ":audituser" => $eMplogName,
            ":auditdate"=>date('Y-m-d h:i:s')                                          
        );

    $result = $stmt->execute($param);

    echo $result;
    
                                       
}


?>
