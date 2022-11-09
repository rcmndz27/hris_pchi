<?php

    class MasterFile
    {
        public function GetUserType()
        {

        }

        public function GetAllStaff($reportingto = null)
        {
            global $connL;

            try
            {
                $sql = "";
                $data = [];

                if ($reportingto == null || $reportingto == "")
                {
                    $sql = $connL->prepare(@"SELECT emp_code, Employee FROM dbo.view_employee ORDER BY Employee");
                }
                else
                {
                    $sql = $connL->prepare(@"SELECT emp_code, Employee FROM dbo.view_employee WHERE reporting_to = :rpt ORDER BY Employee");
                    $sql->bindParam(":rpt", $reportingto, PDO::PARAM_STR);
		$sql->execute();
                }
                
                $sql->execute();

                while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                {
                    array_push($data, array($r["emp_code"], $r["Employee"]));
                }

                return $data;
            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllCutoff($type)
        {
            global $connL;

            try
            {
                $data = [];

               

                $sql = $connL->prepare(@"SELECT * FROM dbo.payroll_generate ORDER by payroll_from, payroll_to DESC");
                $sql->execute();

                if ($type == "payroll")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["rowid"], date("m/d/Y", strtotime($r["payroll_from"])) . " - " . date("m/d/Y", strtotime($r["payroll_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetAllCutoffCO($type)
        {
            global $connL;

            try
            {
                $data = [];

               

                $sql = $connL->prepare(@"SELECT * FROM dbo.mf_pyrollco where status = 'Active' ORDER by pyrollco_from DESC");
                $sql->execute();

                if ($type == "payrollco")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["co_type"], date("m/d/Y", strtotime($r["pyrollco_from"])) . " - " . date("m/d/Y", strtotime($r["pyrollco_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetTKList($type)
        {
            global $connL;

            try
            {
                $data = [];


                $sql = $connL->prepare(@"SELECT location,period_from,period_to from att_summary WHERE period_from in (SELECT date_from from payroll) group by location,period_from,period_to ORDER BY period_to desc");
                $sql->execute();

                if ($type == "tkview")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                    array_push( $data, array($r["location"], date("m/d/Y", strtotime($r["period_from"])) . " - " . date("m/d/Y", strtotime($r["period_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllCutoffPay($type)
        {
            global $connL;

            try
            {
                $data = [];


                $sql = $connL->prepare(@"SELECT location,period_from,period_to from att_summary WHERE period_from not in (SELECT date_from from payroll) group by location,period_from,period_to ORDER BY period_to desc");
                $sql->execute();

                if ($type == "payview")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                    array_push( $data, array($r["location"], date("m/d/Y", strtotime($r["period_from"])) . " - " . date("m/d/Y", strtotime($r["period_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function UnGetAllCutoffPay($type)
        {
            global $connL;

            try
            {
                $data = [];

               

                $sql = $connL->prepare(@"select location,period_from,period_to from att_summary group by location,period_from,period_to order by period_from desc");
                $sql->execute();

                if ($type == "unpayview")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["location"], 
                            date("m/d/Y", strtotime($r["period_from"])) . " - " . date("m/d/Y", strtotime($r["period_to"]))));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetCutoffSalAdj($type)
        {
            global $connL;

            try
            {
                $data = [];

               

                $sql = $connL->prepare(@"select location,period_from,period_to from att_summary group by location,period_from,period_to");
                $sql->execute();

                if ($type == "saladj")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["location"], 
                            date("m/d/Y", strtotime($r["period_from"])) . " - " . date("m/d/Y", strtotime($r["period_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {         
                echo $e->getMessage();
            }
        }

        public function GetAllPayCutoff($type)
        {
            global $connL;

            try
            {
                $data = [];
        
                $sql = $connL->prepare(@"SELECT rowid,date_from,date_to FROM dbo.payroll where rowid = (select max(rowid) from dbo.payroll)");
                $sql->execute();

                if ($type == "paycut")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["rowid"], 
                            date("m/d/Y", strtotime($r["date_from"])) . " - " . date("m/d/Y", strtotime($r["date_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllPayCutoffReg($type)
        {
            global $connL;

            try
            {
                $data = [];
        
                $sql = $connL->prepare(@"SELECT rowid,date_from,date_to FROM dbo.payroll where payroll_status = 'R' and rowid = (select max(rowid) from dbo.payroll)");
                $sql->execute();

                if ($type == "paycutreg")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["rowid"], 
                            date("m/d/Y", strtotime($r["date_from"])) . " - " . date("m/d/Y", strtotime($r["date_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetAllPayLocCutoff($type)
        {
            global $connL;

            try
            {
                $data = [];
        
                $sql = $connL->prepare(@"SELECT rowid,date_from,date_to,location FROM dbo.payroll where rowid = (select max(rowid) from dbo.payroll)");
                $sql->execute();

                if ($type == "payloccut")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["rowid"], 
                            date("m/d/Y", strtotime($r["date_from"])) . " - " . date("m/d/Y", strtotime($r["date_to"])) . " - " .$r["location"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetPayLocation($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT * FROM dbo.mf_location ORDER by rowid ASC");
                $sql->execute();

                if ($type == "locpay")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push($data, array($r["rowid"],$r["location"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetPayCompany($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,code,descs FROM dbo.mf_company ORDER by rowid ASC");
                $sql->execute();

                if ($type == "compay")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["code"], $r["code"]." - ".$r["descs"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetNationality($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,nationality FROM dbo.mf_nationality ORDER by rowid ASC");
                $sql->execute();

                if ($type == "nation")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["nationality"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetJobPosition($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,position FROM dbo.mf_position ORDER by position ASC");
                $sql->execute();

                if ($type == "jobpos")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["position"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllBank($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,descsb FROM dbo.mf_banktypes ORDER by rowid ASC");
                $sql->execute();

                if ($type == "bankname")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["descsb"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllEmployeeLeaveBalance($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT a.rowid,a.emp_code,(a.lastname +','+a.firstname+' '+a.middlename) as fullname from employee_profile a where a.emp_status = 'Active' and NOT EXISTS (SELECT * FROM dbo.employee_leave b where a.emp_code = b.emp_code) order by a.lastname asc");
                $sql->execute();

                if ($type == "leavebalc")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetEmployeeSalary($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT a.rowid,a.emp_code,(a.lastname +','+a.firstname+' '+a.middlename) as fullname from employee_profile a where a.emp_status = 'Active' and NOT EXISTS (SELECT * FROM employee_salary_management b where a.emp_code = b.emp_code) order by a.lastname asc");
                $sql->execute();

                if ($type == "empsal")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["emp_code"]." - ".$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetEmployeeSalaryEmp($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT a.rowid,a.emp_code,(a.lastname +','+a.firstname+' '+a.middlename) as fullname from employee_profile a where a.emp_status = 'Active' and NOT EXISTS (SELECT * FROM employee_salary_management b where a.emp_code = b.emp_code) order by a.lastname asc");
                $sql->execute();

                if ($type == "empsalc")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }



        public function GetAllManpowerList($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,position FROM dbo.applicant_manpower where status = 'Open' ORDER by rowid ASC");
                $sql->execute();

                if ($type == "manpow")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["position"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetJobPlantilla($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,position FROM dbo.applicant_plantilla where status = 'Active' ORDER by rowid ASC");
                $sql->execute();

                if ($type == "jobpla")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["position"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetEmpJobType($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,empdesc FROM dbo.mf_employment_type ORDER by rowid ASC");
                $sql->execute();

                if ($type == "empjobtype")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["empdesc"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetJobPositionDesc($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,position FROM dbo.mf_position ORDER by rowid DESC");
                $sql->execute();

                if ($type == "jobposdesc")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["position"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllDepartment($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,descs FROM dbo.mf_dept ORDER by rowid ASC");
                $sql->execute();

                if ($type == "alldep")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["descs"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetDeptForJob($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,descs,code FROM dbo.mf_dept where status = 'Active' ORDER by rowid ASC");
                $sql->execute();

                if ($type == "depwid")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["code"].' - '.$r["descs"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetEmployeeNames($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,emp_code,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_status = 'Active' ORDER by lastname ASC");
                $sql->execute();

                if ($type == "allempnames")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetActEmployeeNames($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,emp_code,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_status = 'Active' ORDER by lastname ASC");
                $sql->execute();

                if ($type == "allempnames")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAttEmployeeNames($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,badgeno,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_status = 'Active' ORDER by lastname ASC");
                $sql->execute();

                if ($type == "allempnames")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["badgeno"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAttEmployeeNamesCode($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,emp_code,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_status = 'Active' and emp_code IN (select emp_code from payroll where payroll_status = 'R') ORDER by lastname ASC");
                $sql->execute();

                if ($type == "allempcode")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }        

        public function GetAttEmployeeNamesRep($type,$empCode)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,badgeno,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_status = 'Active' and reporting_to = :empCode ORDER by lastname ASC");
                $param = array(":empCode" => $empCode);
                $sql->execute($param );

                if ($type == "allemprepnames")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["badgeno"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }        

        public function GetUserAccntNames($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,emp_code,(lastname +','+firstname+' '+middlename) as fullname FROM dbo.employee_profile where emp_code not in (SELECT emp_code from employee_profile a right join mf_user b on a.emp_code = b.userid where emp_code is not null) and emp_status = 'Active' ORDER BY lastname asc");
                $sql->execute();

                if ($type == "allusracnt")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["fullname"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }



        public function GetAllEmployeeDeduction($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,deduction_name FROM dbo.mf_deductions ORDER by rowid ASC");
                $sql->execute();

                if ($type == "dedlist")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["deduction_name"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function GetAllEmployeeAllowances($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,benefit_name FROM dbo.mf_benefits ORDER by rowid ASC");
                $sql->execute();

                if ($type == "benlist")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["benefit_name"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function  GetAllEmployeePay($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT a.rowid,a.emp_code,date_from,date_to,lastname+','+firstname as [name] FROM dbo.payroll a left join employee_profile b on a.emp_code = b.emp_code ORDER by lastname ASC");
                $sql->execute();

                if ($type == "emppay")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["emp_code"],$r["name"]. " - " . 
                   date("m/d/Y", strtotime($r["date_from"])) . " - " . date("m/d/Y", strtotime($r["date_to"]))));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }


        public function  GetUnGenPayrollCutoff($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT pyrollco_from,pyrollco_to,rowid from mf_pyrollco where pyrollco_from not in (select period_from from att_summary) and pyrollco_from not in (select period_to from att_summary) and status = 'Active' ORDER BY pyrollco_from DESC");
                $sql->execute();

                if ($type == "ungenpco")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],date("m/d/Y", strtotime($r["pyrollco_from"])) . " - " . date("m/d/Y", strtotime($r["pyrollco_to"]))));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }



        public function GetAllEmployeeLevelWrds($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT level_id,level_code,level_description FROM dbo.employee_user_level ORDER by level_description ASC");
                $sql->execute();

                if ($type == "emp_levelwrds")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["level_code"],$r["level_description"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetWorkSched($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT rowid,schedule_name FROM schedules ORDER by rowid ASC");
                $sql->execute();

                if ($type == "schedtype")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["rowid"],$r["schedule_name"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }        

        public function GetAllEmployeeLevel($type)
        {
            global $connL;

            try
            {
                $data = [];
               

                $sql = $connL->prepare(@"SELECT level_id,level_code,level_description FROM dbo.employee_level ORDER by level_id ASC");
                $sql->execute();

                if ($type == "emp_level")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                       array_push( $data, array($r["level_id"],$r["level_description"]));
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetAllCutoffP($type)
        {
            global $connL;

            try
            {
                $data = [];

                $payql = $connL->prepare(@"SELECT * FROM dbo.payroll");
                $payql->execute();
                $pql = $payql->fetch();

                if($pql == false) {
                    $sql = $connL->prepare(@"SELECT * FROM dbo.payroll_generate ORDER by payroll_from, payroll_to DESC");
                    $sql->execute();
                }else{
                    $sql = $connL->prepare(@"SELECT rowid,payroll_from,payroll_to FROM dbo.payroll_generate WHERE payroll_from <> (SELECT date_from from 
                        dbo.payroll where rowid = (select max(rowid) from dbo.payroll)) ORDER by payroll_from, payroll_to DESC");
                    $sql->execute();
                }

                if ($type == "payroll")
                {
                    while ($r = $sql->fetch(PDO::FETCH_ASSOC))
                    {
                        array_push( $data, array($r["rowid"], date("m/d/Y", strtotime($r["payroll_from"])) . " - " . date("m/d/Y", strtotime($r["payroll_to"]))) );
                    }
                }

                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }

        public function GetUnlockedCutOff()
        {
            global $connL;

            try
            {
                $data = [];

                $sql = $connL->prepare(@"SELECT MIN(payroll_from), MAX(payroll_to) FROM dbo.payroll_generate where locked = 0");
                $sql->execute();

                while ($r = $sql->fetch(PDO::FETCH_BOTH))
                {
                    array_push($data, $r[0]);
                    array_push($data, $r[1]);
                }
                
                return $data;
            }
            catch (Exception $e)
            {
                echo $e->getMessage();
            }
        }
    }

?>