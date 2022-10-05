
<?php

Class NewEmpEnt{

public function InsertNewEmpEnt($emp_code,$emp_id,$emp_pic_loc,$positiontitle,$department,$howtoapply,$referredby,$firstname,$middlename,$lastname,$maidenname,$emp_address,$emp_address2,$telno,$telno1,$celno,$celno1,$emailaddress,$emailaddress1,$birthdate,$birthplace,$nationality,$tin_no,$sss_no,$phil_no,$pagibig_no,$tax_status,$married_dependents,$sex,$marital_status,$spousename,$spousebirthdate,$spouseoccupation,$spousecompany,$fathername,$fatheroccupation,$fatherbirthdate,$mothername,$motheroccupation,$motherbirthdate,$companyrelatives,$contactpersonname,$contactpersonno,$contactpersonaddress)
{
global $connL;

$query = "INSERT INTO employee_profile (emp_code,badgeno,emp_id,emp_pic_loc,position,department,howtoapply,referredby,firstname,middlename,lastname,maidenname,emp_address,emp_address2,telno,telno1,celno,celno1,emailaddress,emailaddress1,birthdate,birthplace,nationality,tin_no,sss_no,phil_no,pagibig_no,tax_status,married_dependents,sex,marital_status,spousename,spousebirthdate,spouseoccupation,spousecompany,fathername,fatheroccupation,fatherbirthdate,mothername,motheroccupation,motherbirthdate,companyrelatives,contactpersonname,contactpersonno,contactpersonaddress,datehired,reporting_to,audituser,auditdate) 

VALUES(:emp_code,:badgeno,:emp_id,:emp_pic_loc,:position,:department,:howtoapply,:referredby,:firstname,:middlename,:lastname,:maidenname,:emp_address,:emp_address2,:telno,:telno1,:celno,:celno1,:emailaddress,:emailaddress1,:birthdate,:birthplace,:nationality,:tin_no,:sss_no,:phil_no,:pagibig_no,:tax_status,:married_dependents,:sex,:marital_status,:spousename,:spousebirthdate,:spouseoccupation,:spousecompany,:fathername,:fatheroccupation,:fatherbirthdate,:mothername,:motheroccupation,:motherbirthdate,:companyrelatives,:contactpersonname,:contactpersonno,:contactpersonaddress,:datehired,:reporting_to,:audituser,:auditdate)";

                $stmt =$connL->prepare($query);

                $param = array(   
                ":emp_code"=> 'PCH'."".$emp_code,
                ":badgeno"=> $emp_code,
                ":emp_id"=> $emp_id,
                ":emp_pic_loc"=> $emp_pic_loc,
                ":position"=> $positiontitle,
                ":department"=> $department,
                ":howtoapply"=> $howtoapply,
                ":referredby"=> $referredby,
                ":firstname"=> $firstname,
                ":middlename"=> $middlename,
                ":lastname"=> $lastname,
                ":maidenname"=> $maidenname,
                ":emp_address"=> $emp_address,
                ":emp_address2"=> $emp_address2,
                ":telno"=> $telno,
                ":telno1"=> $telno1,
                ":celno"=> $celno,
                ":celno1"=> $celno1,
                ":emailaddress"=> $emailaddress,
                ":emailaddress1"=> $emailaddress1,
                ":birthdate"=> $birthdate,
                ":birthplace"=> $birthplace,
                ":nationality"=> $nationality,
                ":tin_no"=> $tin_no,
                ":sss_no"=> $sss_no,
                ":phil_no"=> $phil_no,
                ":pagibig_no"=> $pagibig_no,
                ":tax_status"=> $tax_status,
                ":married_dependents"=> $married_dependents,
                ":sex"=> $sex,
                ":marital_status"=> $marital_status,
                ":spousename"=> $spousename,
                ":spousebirthdate"=> $spousebirthdate,
                ":spouseoccupation"=> $spouseoccupation,
                ":spousecompany"=> $spousecompany,
                ":fathername"=> $fathername,
                ":fatheroccupation"=> $fatheroccupation,
                ":fatherbirthdate"=> $fatherbirthdate,
                ":mothername"=> $mothername,
                ":motheroccupation"=> $motheroccupation,
                ":motherbirthdate"=> $motherbirthdate,
                ":companyrelatives"=> $companyrelatives,
                ":contactpersonname"=> $contactpersonname,
                ":contactpersonno"=> $contactpersonno,
                ":contactpersonaddress"=> $contactpersonaddress,
                ":datehired"=> null,
                ":reporting_to"=> $emp_code,   
                ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                ":auditdate"=>date('m-d-Y H:i:s')                                          
                );

            $result = $stmt->execute($param);

            echo $result;

    }



public function InsertNewEmpDep($depname,$depbirthdate,$deprelationship,$firstname,$middlename,$lastname)
    {
        global $connL;

         $query = "INSERT INTO employee_dependents (emp_code,depname,depbirthdate,deprelationship,audituser,auditdate) 

                    VALUES(:emp_code,:depname,:depbirthdate,:deprelationship,:audituser,:auditdate)";
        
                    $stmt =$connL->prepare($query);

                    $param = array(   
                    ":emp_code"=> substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":depname"=> $depname,
                    ":depbirthdate"=> $depbirthdate,
                    ":deprelationship"=> $deprelationship, 
                    ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                    );

            $result = $stmt->execute($param);

            echo $result;


    }

public function InsertNewEmpSib($sibname,$sibrelationship,$firstname,$middlename,$lastname)
    {
        global $connL;

                 $query = "INSERT INTO employee_siblings (emp_code,sibname,sibrelationship,audituser,auditdate) 

                    VALUES(:emp_code,:sibname,:sibrelationship,:audituser,:auditdate)";
        
                    $stmt =$connL->prepare($query);

                    $param = array(   
                    ":emp_code"=> substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":sibname"=> $sibname,
                    ":sibrelationship"=> $sibrelationship,
                    ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                    );

            $result = $stmt->execute($param);

            echo $result;


    }    

// public function InsertNewEmpCon($conname,$conoccupation,$concompany,$conconviction,$firstname,$middlename,$lastname)
//     {
//         global $connL;


//                  $query = "INSERT INTO employee_convictions (emp_code,conname,conoccupation,concompany,conconviction,audituser,auditdate) 

//                     VALUES(:emp_code,:conname,:conoccupation,:concompany,:conconviction,:audituser,:auditdate)";
        
//                     $stmt =$connL->prepare($query);

//                     $param = array(   
//                     ":emp_code"=> substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
//                     ":conname"=> $conname,
//                     ":conoccupation"=> $conoccupation,
//                     ":concompany"=> $concompany,
//                     ":conconviction"=> $conconviction,
//                     ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
//                     ":auditdate"=>date('m-d-Y H:i:s')                                          
//                     );

//             $result = $stmt->execute($param);

//             echo $result;


//     }  

public function InsertNewEmpEdu($schoolname,$schoolfrom,$schoolto,$coursename,$certificatedegree,$firstname,$middlename,$lastname)
    {
        global $connL;

                 $query = "INSERT INTO employee_educations (emp_code,schoolname,schoolfrom,schoolto,coursename,certificatedegree,audituser,auditdate) 

                    VALUES(:emp_code,:schoolname,:schoolfrom,:schoolto,:coursename,:certificatedegree,:audituser,:auditdate)";
        
                    $stmt =$connL->prepare($query);

                    $param = array(   
                    ":emp_code"=> substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":schoolname"=> $schoolname,
                    ":schoolfrom"=> $schoolfrom,
                    ":schoolto"=> $schoolto,
                    ":coursename"=> $coursename,
                    ":certificatedegree"=> $certificatedegree,                   
                    ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                    );

            $result = $stmt->execute($param);

            echo $result;        


    }    


public function InsertNewEmpJob($startingposition,$mostrecentposition,$jobfrom,$jobto,$notypeemployees,$employername,$employeraddress,$supervisorname,$supervisortitle,$duties,$reasonforleaving,$firstname,$middlename,$lastname)

    {
        global $connL;

                 $query = "INSERT INTO employee_employments (emp_code,startingposition,mostrecentposition,jobfrom,jobto,notypeemployees,employername,employeraddress,supervisorname,supervisortitle,duties,reasonforleaving,audituser,auditdate) 

                    VALUES(:emp_code,:startingposition,:mostrecentposition,:jobfrom,:jobto,:notypeemployees,:employername,:employeraddress,:supervisorname,:supervisortitle,:duties,:reasonforleaving,:audituser,:auditdate)";
        
                    $stmt =$connL->prepare($query);

                    $param = array(   
                    ":emp_code"=> substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":startingposition"=> $startingposition,
                    ":mostrecentposition"=> $mostrecentposition,
                    ":jobfrom"=> $jobfrom,
                    ":jobto"=> $jobto,
                    ":notypeemployees"=> $notypeemployees,  
                    ":employername"=> $employername,
                    ":employeraddress"=> $employeraddress,
                    ":supervisorname"=> $supervisorname,
                    ":supervisortitle"=> $supervisortitle,
                    ":duties"=> $duties,  
                    ":reasonforleaving"=> $reasonforleaving,                                      
                    ":audituser" => substr($firstname,0,1)."".substr($middlename,0,1)."".$lastname,
                    ":auditdate"=>date('m-d-Y H:i:s')                                          
                    );

            $result = $stmt->execute($param);

            echo $result;          


    }     

}

?>