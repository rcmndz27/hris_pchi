
<?php

$now = new DateTime(null, new DateTimeZone('Asia/Taipei'));

if (empty($_SESSION['userid']))
{
    echo '<script type="text/javascript">alert("Please login first!!");</script>';
    header( "refresh:1;url=../index.php" );
}
else
{
    include('../config/db.php');
    include('../config/dependencies.php');
    include('../controller/empInfo.php');
    include('../controller/indexProcess.php');
    $status = "OPEN";

    $empInfo = new EmployeeInformation();

    $empInfo->SetEmployeeInformation($_SESSION['userid']);
    
    $empCode = $empInfo->GetEmployeeCode();
    $empName = $empInfo->GetEmployeeName();
    $empDept = $empInfo->GetEmployeeDepartment();
    $empReportingTo = $empInfo->GetEmployeeReportingTo();
    $empDateHired = $empInfo->GetEmployeeDateHired();
    $empType = $empInfo->GetEmployeeType();
    $empUserType = $empInfo->GetEmployeeUserType();
    $empPost = $empInfo->GetEmployeePosition();
    $empRank = $empInfo->GetEmployeeRanking();

    global $connL;

    $query = "SELECT count(emp_code) as ot_count from tr_overtime where status = 1 and reporting_to = :empcode";
    $stmt =$connL->prepare($query);
    $param = array(":empcode" => $empCode);
    $stmt->execute($param);
    $result = $stmt->fetch();

    $uery = "SELECT count(emp_code) as ob_count from tr_offbusiness where status = 1 and ob_reporting = :empcode";
    $tmt =$connL->prepare($uery);
    $aram = array(":empcode" => $empCode);
    $tmt->execute($aram);
    $esult = $tmt->fetch();

    $buery = "SELECT count(emp_code) as dtrc_count from tr_dtrcorrect where status = 1 and reporting_to = :empcode";
    $btmt =$connL->prepare($buery);
    $baram = array(":empcode" => $empCode);
    $btmt->execute($baram);
    $besult = $btmt->fetch();    


    $querys = "SELECT count(emp_code) as lv_count from tr_leave where approved = 1 and approval = :empcode";
    $stmts =$connL->prepare($querys);
    $params = array(":empcode" => $empCode);
    $stmts->execute($params);
    $results = $stmts->fetch();

    $queryss = "SELECT count(emp_code) as wfh_count from tr_workfromhome where status = 1 and reporting_to = :empcode";
    $stmtss =$connL->prepare($queryss);
    $paramss = array(":empcode" => $empCode);
    $stmtss->execute($paramss);
    $resultss = $stmtss->fetch();

    $qry = "SELECT count(*) as pyrll_count from (SELECT company from payroll where payroll_status = 'N'
                group by company ) as sql";
    $smt =$connL->prepare($qry);
    $smt->execute();
    $rst = $smt->fetch();

    $qryf = "SELECT count(*) as pyrll_countf from (SELECT company from payroll where payroll_status = 'R'
                group by company ) as sqlf";
    $smtf =$connL->prepare($qryf);
    $smtf->execute();
    $rstf = $smtf->fetch();


    $tquery = "SELECT max(period_to) AS ptmax,max(period_from) as pfmax from att_summary";
    $tstmt =$connL->prepare($tquery);
    $tstmt->execute();
    $r = $tstmt->fetch();
    $rto = $r['ptmax'];
    $rfrom = $r['pfmax'];

    $querytk = 'SELECT remarks from logs_timekeep where pay_from = :dfrom and pay_to = :dto AND rowid = (SELECT MAX(rowid) from logs_timekeep)';
    $stmttk = $connL->prepare($querytk);
    $paramtk = array(":dfrom" => $rfrom,":dto" => $rto);
    $stmttk->execute($paramtk);
    $rtk = $stmttk->fetch();
    $tkstat = (isset($rtk['remarks'])) ? $rtk['remarks'] :'';     



    $zquery = "SELECT * from employee_profile where emp_code = :empcode";
    $zstmt =$connL->prepare($zquery);
    $zparam = array(":empcode" => $empCode);
    $zstmt->execute($zparam);
    $zresult = $zstmt->fetch();
    $rptto = $zresult['reporting_to'];
    $reportingto = ($rptto === false) ? 'none' : $rptto;

    if($reportingto == 'none'){
        $repname = 'n/a';
    }else{

    $zquerys = "SELECT * from employee_profile where emp_code = :reportingto";
    $zstmts =$connL->prepare($zquerys);
    $zparams = array(":reportingto" => $reportingto);
    $zstmts->execute($zparams);
    $zresults = $zstmts->fetch();
          if(isset($zresults['emp_code'])){
            $repname = $zresults['lastname'].",".$zresults['firstname']." ".$zresults['middlename'];
          }else{                       
            $repname = 'n/a';
          }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>PCHI</title>

  <meta content="" name="description">
  <meta content="" name="keywords">

<!-- Favicons -->
<link type='image/x-png' rel='icon' href='../img/pchi-logo.png'>
<link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
<link href="../css/bootstrap-icons.css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.gstatic.com" />
<link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;1,600&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,500;0,600;0,700;1,300;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,400;1,400&amp;display=swap" rel="stylesheet" />
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">
<!-- Vendor CSS Files -->
<link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<!-- Template Main CSS File -->
<link href="../assets/css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
<link type='text/css' rel='stylesheet' href="<?= constant('FONTAWESOME_CSS'); ?>">
<link rel="stylesheet" href="../css/header.css">
<link rel="stylesheet" href="../css/custom.css">
<link rel="stylesheet" href="../css/styles.css"/>
<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
rel="stylesheet">
<script type='text/javascript' src='../js/jquery.min.js'></script>
<script type='text/javascript' src="<?= constant('BOOTSTRAP_JS'); ?>"></script>
<script src="../js/sweetalert.min.js"></script>
<script src="<?= constant('NODE'); ?>xlsx/dist/xlsx.core.min.js"></script>
<script src="<?= constant('NODE'); ?>file-saverjs/FileSaver.min.js"></script>
<script src="<?= constant('NODE'); ?>tableexport/dist/js/tableexport.min.js"></script>
<!-- <script type="text/javascript" src='../js/script.js'></script> -->
  </head>
<body>
<div id = "myDiv" style="display:none;" class="loader"></div>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <img src='../img/pchi-logo.png'alt="" class="ob_logo"/>
      <nav id="navbar" class="navbar">
          <ul>
      <?php 
             $phpfile = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
                if($phpfile == 'admin.php' || $phpfile == 'employee.php'){
                  $admin = 'active';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = '';
                  $payslip_view = '';
                  $admintools = ''; 
                  $myprofile_view = '';
                }else if($phpfile == 'dtr_view.php'){
                  $admin = '';
                  $dtr_view = 'active';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = '';
                  $payslip_view = '';
                  $admintools = ''; 
                  $myprofile_view = '';
                }else if($phpfile == 'leaveApplication_view.php' || $phpfile == 'ot_app_view.php' || $phpfile == 'wfh_app_view.php'){
                  $leaveApplication_view = 'active';
                  $admin = '';
                  $dtr_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = '';
                  $payslip_view = ''; 
                  $admintools = '';   
                  $myprofile_view = '';                           
                }else if($phpfile == 'ot_app_view.php'){
                  $ot_app_view = 'active';
                  $admin = '';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $wfh_app_view = '';
                  $payslip_view = '';
                  $admintools = ''; 
                  $myprofile_view = '';
                }else if($phpfile == 'wfh_app_view.php'){
                  $wfh_app_view = 'active';
                  $admin = '';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $payslip_view = '';
                  $admintools = ''; 
                  $myprofile_view = '';
                }else if($phpfile == 'payslip_view.php'){
                  $payslip_view = 'active';
                  $admin = '';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = ''; 
                  $admintools = ''; 
                  $myprofile_view = '';                           
                }else if($phpfile == 'myprofile_view.php' || $phpfile == 'changepass.php'){
                  $payslip_view = '';
                  $admin = '';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = ''; 
                  $admintools = ''; 
                  $myprofile_view = 'active';                           
                }else{
                  $admin = '';
                  $dtr_view = '';
                  $leaveApplication_view = '';
                  $ot_app_view = '';
                  $wfh_app_view = '';
                  $payslip_view = '';
                  $myprofile_view = '';
                  $admintools = 'active';
                }

          if($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President'){
             echo'<li><a class="nav-link '.$admin.'" href="../pages/admin.php" onclick="show()"><i class="fas fa-home fa-fw"></i>  &nbsp; Home</a></li>';
            }else{
             echo'<li><a class="nav-link '.$admin.'" href="../pages/employee.php" onclick="show()"><i class="fas fa-home fa-fw" ></i> &nbsp;Home</a></li>';
           } 
             

          echo"<li><a class='nav-link ".$dtr_view."' href='../pages/dtr_view.php' onclick='show()'><i class='fas fa-calendar fa-fw'></i>&nbsp;MY Attendance</a></li>

             <li class='dropdown'><a href='#' class='".$leaveApplication_view."'><span><i class='fas fa-suitcase fa-fw'></i>FORMS</span> <i class='bi bi-chevron-down'></i></a>
                <ul>
                  <li><a href='../leave/leaveApplication_view.php' onclick='show()'><i class='fas fa-suitcase fa-fw'></i>Leave</a></li>
                  <li><a href='../overtime/ot_app_view.php' onclick='show()'><i class='fas fa-hourglass fa-fw'></i>Overtime</a></li>
                  <li><a href='../wfhome/wfh_app_view.php' onclick='show()'><i class='fas fa-warehouse fa-fw'></i>Work From Home</a></li>
                  <li><a href='../ob/ob_app_view.php' onclick='show()'><i class='fas fa-building'></i>Official Business</a></li>
                  <li><a href='../dtrcorrect/dtrcorrect_app_view.php' onclick='show()'><i class='fas fa-clock'></i>DTR Correction</a></li>                                                              
                </ul>
            </li>

 <li><a class='nav-link ".$payslip_view."' href='../payslip/payslip_view.php' onclick='show()'><i class='fas fa-money-bill-wave fa-fw'></i>
             &nbsp;Payslip</a></li>              
           ";

                $lv = (isset($results['lv_count'])) ? $results['lv_count'] : '0' ;
                $ot = (isset($result['ot_count'])) ? $result['ot_count'] : '0' ;
                $ob = (isset($esult['ob_count'])) ? $esult['ob_count'] : '0' ;
                $wfh = (isset($resultss['wfh_count'])) ? $resultss['wfh_count'] : '0' ;
                $dtrc = (isset($besult['dtrc_count'])) ? $besult['dtrc_count'] : '0' ;
                $pyrll = (isset($rst['pyrll_count'])) ? $rst['pyrll_count'] : '0' ;
                $pyrllf = (isset($rstf['pyrll_countf'])) ? $rstf['pyrll_countf'] : '0' ;

                if($tkstat == 'SAVED') {
                    $tkcnt = 1;
                    $tkappr = "&nbsp;<span class='badge badge-danger badge-counter'>1</span>";
                }else{   
                    $tkcnt = 0;  
                    $tkappr = '';     
                }


                $approval_adm = $lv + $ot + $wfh + $dtrc + $ob;
                $approval_tm = $lv + $ot + $wfh + $dtrc + $ob;
                $approval_tmf = $lv + $ot + $wfh + $dtrc + $ob + $tkcnt;
                $approval_f = $pyrllf;
                if ($approval_adm > 0) {
                  $apprm = "&nbsp;<span class='badge badge-danger badge-counter'>".$approval_adm."</span>";
                 }else{
                   $apprm = '';
                 }

                if($approval_tmf > 0 and $empUserType == 'Finance') {
                  $appr = "&nbsp;<span class='badge badge-danger badge-counter'>".$approval_tmf."</span>";
                 }else if($approval_tm > 0 and $empUserType <> 'Finance') {
                  $appr = "&nbsp;<span class='badge badge-danger badge-counter'>".$approval_tm."</span>";
                 }else{
                   $appr = '';
                 }  

                 if ($approval_f > 0) {
                  $apprf = "&nbsp;<span class='badge badge-danger badge-counter'>".$approval_f."</span>";
                 }else{
                   $apprf = '';
                 }   
                switch(trim($empUserType)){
                    case "Admin":
                    case "President":
                    case "Group Head":
                        echo "                      
                        <li class='dropdown'><a href='#' class='".$admintools."'><i class='fas fa-toolbox fa-fw'></i>&nbsp;ADMIN TOOLS ".$apprm."<i class='bi bi-chevron-down'></i></a>
                            <ul>
                              <li class='dropdown'><a href='#'><i class='fas fa-money-check fa-fw'></i><span>Payroll</span><i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../payroll/payroll_view.php' onclick='show()'>Payroll Timekeeping View</a></li>
                                  <li><a href='../payroll/payroll_view_register.php' onclick='show()'>Payroll Register View ".$apprf."</a></li>
                                  <li><a href='../payslip/payslip_viewall.php' onclick='show()'>Payslip All Employee</a></li>
                                  <li><a href='../salaryadjustment/salaryadjustmentlist_view.php' onclick='show()'>Salary Adjustment Management</a></li> 
                                  <li><a href='../payroll/payroll_tklist_view.php' onclick='show()'>Payroll Timekeeping List</a></li> 
                                    <li><a href='../payroll/payrollApproval_view.php' onclick='show()'>Payroll Register List</a></li> 
                                  </ul>
                              </li>
                              <li class='dropdown'><a href='#'><i class='fas fa-thumbs-up fa-fw'></i> <span>Approvals (".$approval_adm.")</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../leave/leaveApproval_view.php' onclick='show()'>Leave (".$lv.")</a></li>
                                  <li><a href='../overtime/overtime-approval-view.php' onclick='show()'>Overtime (".$ot.")</a></li>
                                  <li><a href='../wfhome/wfh-approval-view.php' onclick='show()'>Work From Home (".$wfh.")</a></li>
                                  <li><a href='../ob/ob-approval-view.php' onclick='show()'>Official Business (".$ob.")</a></li>
                                  <li><a href='../dtrcorrect/dtrcorrect-approval-view.php' onclick='show()'>DTR Correction (".$dtrc.")</a></li>                                              
                                </ul>
                              </li>
                              <li class='dropdown'><a href='#'><i class='fa fa-id-badge fa-fw'></i></i><span>HR Tools</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../newhireaccess/newhireaccess_view.php' onclick='show()'>201 Master File</a></li>
                                  <li><a href='../salary/salarylist_view.php' onclick='show()'>Salary Management</a></li>
                                  <li><a href='../deduction/deductionlist_view.php' onclick='show()'>Deduction Management</a></li>
                                  <li><a href='../allowances/allowanceslist_view.php' onclick='show()'>Allowances Management</a></li>
                                  <li><a href='../users/userslist_view.php' onclick='show()'>Users Management</a></li>                                                  
                                  <li><a href='../payroll_att/gen_att_view.php' onclick='show()'>Generate Scripts</a></li>
                                  <li><a href='../leavebalance/leavebalancelist_view.php' onclick='show()'>Employee Leave Balance</a></li>                                                                
                                </ul>
                              </li>
                                <li class='dropdown'><a href='#'><i class='fas fa-user'></i><span>Employees View</span> 
                                  <i class='bi bi-chevron-right'></i></a>
                                  <ul>                                                
                                    <li><a href='../dtr/index.php' onclick='show()'>Employee Attendance</a></li>
                                  <li><a href='../leave/view-approved-leave.php' onclick='show()'>Employee Leaves</a></li>
                                  <li><a href='../overtime/view-approved-overtime.php' onclick='show()'>Employee Overtime</a></li>
                                  <li><a href='../wfhome/view-approved-wfh.php' onclick='show()'>Employee WFH</a></li> 
                                 <li><a href='../ob/view-approved-ob.php' onclick='show()'>Employee OB</a></li>
                                   <li><a href='../dtrcorrect/view-approved-dtrc.php' onclick='show()'>Employee DTR Correction</a></li>  
                                  </ul>
                                </li>                                
                              <li class='dropdown'><a href='#'><i class='fas fa-file-archive'></i><span>Masterfile</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../mf_bank/banklist_view.php' onclick='show()'>Bank Type</a></li>
                                  <li><a href='../mf_allowances/mfallowanceslist_view.php' onclick='show()'>Allowances</a></li>
                                  <li><a href='../mf_company/mfcompanylist_view.php' onclick='show()'>Company</a></li>
                                  <li><a href='../mf_deduction/mfdeductionlist_view.php' onclick='show()'>Deduction</a></li>
                                  <li><a href='../mf_department/mfdepartmentlist_view.php' onclick='show()'>Department</a></li>
                                <li><a href='../mf_holiday/mfholidaylist_view.php' onclick='show()'>Holiday</a></li>  
                                <li><a href='../mf_pyrollco/mfpyrollcolist_view.php' onclick='show()'>Payroll Cut-Off</a></li>  
                                <li><a href='../mf_position/mfpositionlist_view.php' onclick='show()'>Job Position</a></li>
                                </ul>
                              </li>                                              
                             <li class='dropdown'><a href='#'><i class='fa fa-wrench fa-fw'></i></i><span>Recruitment Tools</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../applicantprofile/applicantlist_view.php' onclick='show()'>Applicant Module</a></li>
                                  <li><a href='../applicantprofile/plantillalist_view.php' onclick='show()'>Plantilla Module</a></li>
                                  <li><a href='../applicantprofile/manpowerlist_view.php' onclick='show()'>Manpower Module</a></li>
                                  <li><a href='../newhireaccess/newemployee_entry.php' target='_blank'>Add New Employee</a></li>
                                  <li><a href='../applicantprofile/applicant_entry.php' target='_blank'>Add New Applicant</a></li>                                                                    
                                </ul>
                              </li>
                            </ul>
                          </li>";  
                    break;
                    case "Finance":
                    case "Finance2":
                        echo "                      
                        <li class='dropdown'><a href='#' class='".$admintools."'><i class='fas fa-toolbox fa-fw'></i>&nbsp;FINANCE TOOLS ".$appr."<i class='bi bi-chevron-down'></i></a>
                            <ul>
                              <li class='dropdown'><a href='#'><i class='fa fa-id-badge fa-fw'></i></i><span>Finance Tools</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../salary/salarylist_view.php' onclick='show()'>Salary Management</a></li>
                                  <li><a href='../deduction/deductionlist_view.php' onclick='show()'>Deduction Management</a></li>
                                  <li><a href='../allowances/allowanceslist_view.php' onclick='show()'>Allowances Management</a></li> 
                                  <li><a href='../salaryadjustment/salaryadjustmentlist_view.php' onclick='show()'>Salary Adjustment Management</a></li>           
                                </ul>
                              </li>                            
                              <li class='dropdown'><a href='#'><i class='fas fa-money-check fa-fw'></i><span>Payroll ".$tkappr."</span><i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../payroll/payroll_view_finance.php' onclick='show()'>Payroll Timekeeping View ".$tkappr."</a></li>                                
                                  <li><a href='../payroll/payroll_view_register.php' onclick='show()'>Payroll Register View ".$apprf."</a></li>
                                  <li><a href='../payslip/payslip_viewall.php' onclick='show()'>Payslip All Employee</a></li>
                                    <li><a href='../payroll/payrollApproval_view.php' onclick='show()'>Payroll Register List</a></li> 
                                  </ul>
                              </li>
                              <li class='dropdown'><a href='#'><i class='fas fa-thumbs-up fa-fw'></i> <span>Approvals (".$approval_tmf.")</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../leave/leaveApproval_view.php' onclick='show()'>Leave (".$lv.")</a></li>
                                  <li><a href='../overtime/overtime-approval-view.php' onclick='show()'>Overtime (".$ot.")</a></li>
                                  <li><a href='../wfhome/wfh-approval-view.php' onclick='show()'>Work From Home (".$wfh.")</a></li>
                                  <li><a href='../ob/ob-approval-view.php' onclick='show()'>Official Business (".$ob.")</a></li>
                                  <li><a href='../dtrcorrect/dtrcorrect-approval-view.php' onclick='show()'>DTR Correction (".$dtrc.")</a></li>                                              
                                </ul>
                              </li>                                
                              <li class='dropdown'><a href='#'><i class='fas fa-file-archive'></i><span>Masterfile</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../mf_bank/banklist_view.php' onclick='show()'>Bank Type</a></li>
                                  <li><a href='../mf_allowances/mfallowanceslist_view.php' onclick='show()'>Allowances</a></li>
                                  <li><a href='../mf_deduction/mfdeductionlist_view.php' onclick='show()'>Deduction</a></li>
                                </ul>
                              </li>                                              
                            </ul>
                          </li>";  
                    break;                    
                    case "HR Generalist":
                    case "HR Manager":
                        echo "                      
                        <li class='dropdown'><a href='#' class='".$admintools."'><i class='fas fa-toolbox fa-fw'></i>&nbsp;HR TOOLS ".$apprm."<i class='bi bi-chevron-down'></i></a>
                            <ul>
                              <li class='dropdown'><a href='#'><i class='fas fa-money-check fa-fw'></i><span>Payroll</span><i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../payroll/payroll_view.php' onclick='show()'>Payroll Timekeeping View</a></li>
                                  <li><a href='../payroll/payroll_tklist_view.php' onclick='show()'>Payroll Timekeeping List</a></li> 
                                  </ul>
                              </li>
                              <li class='dropdown'><a href='#'><i class='fas fa-thumbs-up fa-fw'></i> <span>Approvals (".$approval_adm.")</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul>
                                  <li><a href='../leave/leaveApproval_view.php' onclick='show()'>Leave (".$lv.")</a></li>
                                  <li><a href='../overtime/overtime-approval-view.php' onclick='show()'>Overtime (".$ot.")</a></li>
                                  <li><a href='../wfhome/wfh-approval-view.php' onclick='show()'>Work From Home (".$wfh.")</a></li>
                                  <li><a href='../ob/ob-approval-view.php' onclick='show()'>Official Business (".$ob.")</a></li>
                                  <li><a href='../dtrcorrect/dtrcorrect-approval-view.php' onclick='show()'>DTR Correction (".$dtrc.")</a></li>                                              
                                </ul>
                              </li>
                              <li class='dropdown'><a href='#'><i class='fa fa-id-badge fa-fw'></i></i><span>Employee Tools</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../newhireaccess/newhireaccess_view.php' onclick='show()'>201 Master File</a></li>                                  <li><a href='../users/userslist_view.php' onclick='show()'>Users Management</a></li>                                                  
                                  <li><a href='../payroll_att/gen_att_view.php' onclick='show()'>Generate Scripts</a></li>
                                  <li><a href='../leavebalance/leavebalancelist_view.php' onclick='show()'>Employee Leave Balance</a></li>                                                                
                                </ul>
                              </li>
                                <li class='dropdown'><a href='#'><i class='fas fa-user'></i><span>Employees View</span> 
                                  <i class='bi bi-chevron-right'></i></a>
                                  <ul>                                                
                                    <li><a href='../dtr/index.php' onclick='show()'>Employee Attendance</a></li>
                                  <li><a href='../leave/view-approved-leave.php' onclick='show()'>Employee Leaves</a></li>
                                  <li><a href='../overtime/view-approved-overtime.php' onclick='show()'>Employee Overtime</a></li>
                                  <li><a href='../wfhome/view-approved-wfh.php' onclick='show()'>Employee WFH</a></li> 
                                 <li><a href='../ob/view-approved-ob.php' onclick='show()'>Employee OB</a></li>
                                   <li><a href='../dtrcorrect/view-approved-dtrc.php' onclick='show()'>Employee DTR Correction</a></li>  
                                  </ul>
                                </li>                                
                              <li class='dropdown'><a href='#'><i class='fas fa-file-archive'></i><span>Masterfile</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../mf_company/mfcompanylist_view.php' onclick='show()'>Company</a></li>
                                  <li><a href='../mf_department/mfdepartmentlist_view.php' onclick='show()'>Department</a></li>
                                <li><a href='../mf_holiday/mfholidaylist_view.php' onclick='show()'>Holiday</a></li>  
                                <li><a href='../mf_pyrollco/mfpyrollcolist_view.php' onclick='show()'>Payroll Cut-Off</a></li>  
                                <li><a href='../mf_position/mfpositionlist_view.php' onclick='show()'>Job Position</a></li>
                                </ul>
                              </li>                                              
                             <li class='dropdown'><a href='#'><i class='fa fa-wrench fa-fw'></i></i><span>Recruitment Tools</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../applicantprofile/applicantlist_view.php' onclick='show()'>Applicant Module</a></li>
                                  <li><a href='../applicantprofile/plantillalist_view.php' onclick='show()'>Plantilla Module</a></li>
                                  <li><a href='../applicantprofile/manpowerlist_view.php' onclick='show()'>Manpower Module</a></li>
                                  <li><a href='../newhireaccess/newemployee_entry.php' target='_blank'>Add New Employee</a></li>
                                  <li><a href='../applicantprofile/applicant_entry.php' target='_blank'>Add New Applicant</a></li>                                                                    
                                </ul>
                              </li>
                            </ul>
                          </li>";  
                    break;                    
             case "Team Manager":   
              echo "
                      <li class='dropdown'><a href='#' class='".$admintools."'><i class='fas fa-toolbox fa-fw'></i>&nbsp;MANAGER TOOLS ".$appr."<i class='bi bi-chevron-down'></i></a>
                          <ul>
                                  <li class='dropdown'><a href='#'><i class='fas fa-thumbs-up fa-fw'></i> <span>Approvals (".$approval_tm.")</span> 
                                    <i class='bi bi-chevron-right'></i></a>
                                    <ul>
                                      <li><a href='../leave/leaveApproval_view.php' onclick='show()'>Leave (".$lv.")</a></li>
                                      <li><a href='../overtime/overtime-approval-view.php' onclick='show()'>Overtime (".$ot.")</a></li>
                                      <li><a href='../wfhome/wfh-approval-view.php' onclick='show()'>Work From Home (".$wfh.")</a></li>
                                       <li><a href='../ob/ob-approval-view.php' onclick='show()'>Official Business (".$ob.")</a></li>
                                    <li><a href='../dtrcorrect/dtrcorrect-approval-view.php' onclick='show()'>DTR Correction (".$dtrc.")</a></li>                                                     
                                    </ul>
                                  </li>
                                <li class='dropdown'><a href='#'><i class='fa fa-id-badge fa-fw'></i></i><span>My Employee's View</span> 
                                  <i class='bi bi-chevron-right'></i></a>
                                  <ul>                                                
                                    <li><a href='../dtr/index.php' onclick='show()'>Employee Attendance</a></li>
                                  <li><a href='../leave/view-approved-leave.php' onclick='show()'>Employee Leaves</a></li>
                                  <li><a href='../overtime/view-approved-overtime.php' onclick='show()'>Employee Overtime</a></li>
                                  <li><a href='../wfhome/view-approved-wfh.php' onclick='show()'>Employee WFH</a></li> 
                                 <li><a href='../ob/view-approved-ob.php' onclick='show()'>Employee OB</a></li>
                                   <li><a href='../dtrcorrect/view-approved-dtrc.php' onclick='show()'>Employee DTR Correction</a></li>  
                                  </ul>
                                </li>                                                                                              
                    </ul>
                 </li>";
                      break;                    
                                }
                            ?> 
                 <!-- MY PROFILE TOOLS -->

          <?php  
              $sex = $zresult['sex'];
              $up_avatar = $zresult['up_avatar'];
              $up_sign = $zresult['up_sign'];

              if($sex == 'Male' AND empty($up_avatar)){
                  $avatar = 'avatar2.png';
              }else if($sex == 'Female' AND empty($up_avatar)){
                  $avatar = 'avatar8.png';
              }else if($sex == 'Male' AND !empty($up_avatar)){
                  $avatar = $up_avatar;
              }else if($sex == 'Female' AND !empty($up_avatar)){
                  $avatar = $up_avatar;
              }else{
                  $avatar = 'nophoto.png';
              }

              if(empty($up_sign)){
                $signpic = '../uploads/employees/nosign.png';
              }else{
                $signpic = '../uploads/employees/'.$up_sign;
              }
           ?>                 

          <li class="dropdown"><a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img class="rounded-circle" style="width:34px;height:34px;" src="../uploads/employees/<?php echo $avatar; ?>" >
                </a>
            <ul>
              <li><a href="../pages/myprofile_view.php" onclick="show()"><i class='fas fa-id-card fa-fw'></i>MY PROFILE</a></li>
              <li><a href="../pages/changepass.php" onclick="show()"><i class="fas fa-cogs fa-fw"></i>CHANGE PASSWORD</a></li>
              <li><a href="../controller/logout.php" onclick="show()"><i class="fas fa-sign-out-alt fa-fw"></i>LOG-OUT</a></li>
            </ul>
          </li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><!-- End Header -->
  <br>
 <!-- <script src="../assets/js/main.js"></script> -->
</body>

<!--                              <li class='dropdown'><a href='#'><i class='fas fa-flag fa-fw'></i><span>Reports</span> 
                                <i class='bi bi-chevron-right'></i></a>
                                <ul> 
                                  <li><a href='../pages/otApprovalReport_view.php' onclick='show()'>OT Approval Report</a></li>
                                  <li><a href='../pages/dashboard_view.php' onclick='show()'>Demographic Report</a></li>
                                  <li><a href='../att_report/att_rep_view.php' onclick='show()'>Attendance Report</a></li>
                                </ul>
                              </li> -->

  <script type="text/javascript">

         function show() {
            document.getElementById("myDiv").style.display="block";
        }

    var urpath = window.location.pathname;

    if(($('#lv').val() ==! 0 || $('#ot').val() ==! 0 || $('#wfh').val() ==! 0) && (urpath === '/webportal-beta/pages/admin.php')){

    const el = document.createElement('div')
    el.innerHTML = "<?php    echo "You have <a href='../leave/leaveApproval_view.php'>".$lv."</a> leave approval.<br>";
                             echo "You have <a href='../overtime/overtime-approval-view.php'>".$ot."</a> overtime approval.<br>";
                             echo "You have <a href='../wfhome/wfh-approval-view.php'>".$wfh."</a> work from home approval.";
                    ?>"

    swal({
      title: "Pending Approval:",
      content: el ,
      icon: "warning"
    })

    }else{
      // swal ('error');
    }
    
    
</script>
