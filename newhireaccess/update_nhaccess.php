<?php 

    function UpdateEmployeeLevel($department,$position,$location,$emp_type,$emp_level,$work_sched_type,$minimum_wage,$pay_type,$emp_status,$reporting_to,$lastname,$firstname,$middlename,$emailaddress,$telno,$celno,$emp_address,$emp_address2,$sss_no,$phil_no,$pagibig_no,$tin_no,$datehired,$birthdate,$birthplace,$sex,$marital_status,$emp_pic_loc,$rowid,$emp_id)
    {
            global $connL;

            $queryr  = "SELECT * from employee_profile where emp_code = :empcode";
            $stmtr =$connL->prepare($queryr);
            $paramr = array(":empcode" => $rowid);
            $stmtr->execute($paramr);
            $resultr = $stmtr->fetch();
            $oldemp_type = $resultr['emp_type'];


            $queryg = "SELECT * from employee_leave where emp_code = :empcode";
            $stmtg =$connL->prepare($queryg);
            $paramg = array(":empcode" => $rowid);
            $stmtg->execute($paramg);
            $resultg = $stmtg->fetch();
            $empc = (isset($resultg['emp_code'])) ? $resultg['emp_code'] : 'none' ;


            if($emp_type == 'Regular' and $oldemp_type == 'Probationary'){
            if($empc == 'none'){
            $query = "INSERT INTO dbo.employee_leave (emp_code,earned_vl,earned_sl,earned_sl_bank,earned_fl,status,audituser,auditdate) 
                VALUES(:emp_code,:earned_vl,:earned_sl,:earned_sl_bank,:earned_fl,:status,:audituser,:auditdate) ";
                $stmt =$connL->prepare($query);
                $param = array(
                    ":emp_code"=> $rowid,
                    ":earned_vl" => 10,
                    ":earned_sl" => 10,
                    ":earned_sl_bank"=> 0,
                    ":earned_fl"=> 0,
                    ":status"=> 'Active',
                    ":audituser" => 'system',
                    ":auditdate"=>date('m-d-Y H:i:s')
                );

                $result = $stmt->execute($param);

            }else{
                $cmf = $connL->prepare("UPDATE dbo.employee_leave SET earned_sl = :sl,earned_vl = :vl where emp_code = :emp_code ");
                $cmf->bindValue('sl',10);
                $cmf->bindValue('vl',10);
                $cmf->bindValue('emp_code', $rowid);
                $cmf->execute();                    
            }

        }else{

        }            

            $cmd = $connL->prepare("UPDATE dbo.employee_profile SET 
                lastname = :lastname,
                firstname = :firstname,
                middlename = :middlename,
                emailaddress = :emailaddress,
                telno = :telno,
                celno = :celno, 
                emp_address = :emp_address,
                emp_address2 = :emp_address2,
                sss_no = :sss_no,
                phil_no = :phil_no,
                pagibig_no = :pagibig_no,
                tin_no = :tin_no,
                birthdate = :birthdate,
                datehired = :datehired,
                birthplace = :birthplace,
                sex = :sex,
                marital_status = :marital_status,
                department = :department,
                position = :position,
                location = :location,
                emp_type = :emp_type,
                ranking = :emp_level,
                work_sched_type = :work_sched_type,
                minimum_wage = :minimum_wage,
                pay_type = :pay_type,
                emp_status = :emp_status,
                reporting_to = :reporting_to,
                emp_pic_loc = :emp_pic_loc,
                emp_id = :emp_id
                where emp_code = :emp_code ");

            $cmd->bindValue('lastname',$lastname);
            $cmd->bindValue('firstname',$firstname);
            $cmd->bindValue('middlename',$middlename);
            $cmd->bindValue('emailaddress',$emailaddress);
            $cmd->bindValue('telno',$telno);
            $cmd->bindValue('celno',$celno);
            $cmd->bindValue('emp_address',$emp_address);
            $cmd->bindValue('emp_address2',$emp_address2);            
            $cmd->bindValue('sss_no',$sss_no);
            $cmd->bindValue('phil_no',$phil_no);
            $cmd->bindValue('pagibig_no',$pagibig_no);
            $cmd->bindValue('tin_no',$tin_no);
            $cmd->bindValue('birthdate',$birthdate);
            $cmd->bindValue('datehired',$datehired);
            $cmd->bindValue('birthplace',$birthplace);
            $cmd->bindValue('sex',$sex);
            $cmd->bindValue('marital_status',$marital_status);
            $cmd->bindValue('department',$department);
            $cmd->bindValue('position',$position);
            $cmd->bindValue('location',$location);
            $cmd->bindValue('emp_type',$emp_type);
            $cmd->bindValue('emp_level',$emp_level);
            $cmd->bindValue('work_sched_type',$work_sched_type);
            $cmd->bindValue('minimum_wage',$minimum_wage);
            $cmd->bindValue('pay_type',$pay_type);
            $cmd->bindValue('emp_status',$emp_status);
            $cmd->bindValue('reporting_to',$reporting_to);
            $cmd->bindValue('emp_pic_loc',$emp_pic_loc);
            $cmd->bindValue('emp_code', $rowid);
            $cmd->bindValue('emp_id', $emp_id);
            $cmd->execute();

   
}


?>
