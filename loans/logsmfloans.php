<?php


function insertMfLnLogs($emp_code,$emp_name,$column_name,$new_data,$old_data,$empCode,$rowid)
    
    {    

        global $connL;

        // emp code salary
        $rquery = "SELECT firstname+' '+lastname as [fullname] FROM employee_profile 
        WHERE emp_code = :empcode";
        $rparam = array(':empcode' => $emp_code);
        $rstmt =$connL->prepare($rquery);
        $rstmt->execute($rparam);
        $rresult = $rstmt->fetch();
        $n_req = $rresult['fullname'];   

        // login emp
        $fquery = "SELECT firstname+' '+lastname as [fullname] FROM employee_profile 
        WHERE emp_code = :empcode";
        $fparam = array(':empcode' => $empCode);
        $fstmt =$connL->prepare($fquery);
        $fstmt->execute($fparam);
        $fresult = $fstmt->fetch();
        $f_req = $fresult['fullname'];               

        $querys = "INSERT INTO logs_mfloan (emp_code,loan_id,emp_name,column_name,new_data,old_data,audituser,auditdate) 
                VALUES(:emp_code,:loan_id,:emp_name,:column_name,:new_data,:old_data,:audituser,:auditdate)";
    
                $stmts =$connL->prepare($querys);
    
                $params = array(
                    ":emp_code"=> $emp_code,
                    ":loan_id"=> $rowid,
                    ":emp_name"=> $n_req,
                    ":column_name"=> $column_name,
                    ":new_data"=> $new_data,
                    ":old_data"=> $old_data,
                    ":audituser" => $f_req,
                    ":auditdate"=>date('m-d-Y H:i:s')
                );

            $results = $stmts->execute($params);

            echo $results;
    }


?>
