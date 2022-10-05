<?php


session_start();

if (empty($_SESSION['userid']))
{

    echo '<script type="text/javascript">alert("Please login first!!");</script>';
    header( "refresh:1;url=../index.php" );
}
else
{

    include('../_header.php');
    include('../payroll/payroll.php');
    include('../elements/DropDown.php');
    include('../controller/MasterFile.php');
    $empCode = $_SESSION['userid'];
    $empInfo->SetEmployeeInformation($_SESSION['userid']);
    $empUserType = $empInfo->GetEmployeeUserType();
    $empInfo = new EmployeeInformation();
    $mf = new MasterFile();
    $dd = new DropDown(); 

    $query = "SELECT max(period_to) AS ptmax,max(period_from) as pfmax from att_summary";
    $stmt =$connL->prepare($query);
    $stmt->execute();
    $r = $stmt->fetch();
    $rto = $r['ptmax'];
    $rfrom = $r['pfmax'];

    $queryp = 'EXEC dbo.xp_pending_forms :date_from,:date_to';
    $stmtp =$connL->prepare($queryp);
    $paramp = array(":date_from" => $rfrom,":date_to" => $rto);
    $stmtp->execute($paramp);
    $resultp = $stmtp->fetch();  

    $querypf = 'EXEC dbo.xp_pending_forms :date_from,:date_to';
    $stmtpf =$connL->prepare($querypf);
    $parampf = array(":date_from" => $rfrom,":date_to" => $rto);
    $stmtpf->execute($parampf);
    $resultpf = $stmtpf->fetch();   

    $querytk = 'SELECT remarks from logs_timekeep where pay_from = :dfrom and pay_to = :dto AND rowid = (SELECT MAX(rowid) from logs_timekeep)';
    $stmttk = $connL->prepare($querytk);
    $paramtk = array(":dfrom" => $rfrom,":dto" => $rto);
    $stmttk->execute($paramtk);
    $rtk = $stmttk->fetch();
    $tkstat = (isset($rtk['remarks'])) ? $rtk['remarks'] : 'n/a' ;         

    if($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head') {

    }else{
        echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
        echo "window.location.href = '../index.php';";
        echo "</script>";
    }
}

?>

<link rel="stylesheet" href="../payroll/payroll.css">
<script src="<?= constant('NODE'); ?>xlsx/dist/xlsx.core.min.js"></script>
<script src="<?= constant('NODE'); ?>file-saverjs/FileSaver.min.js"></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<body  onload="javascript:generatePayrll();">
    <div class="container-fluid">
        <div class="section-title">
          <h6>&nbsp;</h6>
      </div>
      <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-money-check fa-fw'>
              </i>&nbsp;PAYROLL TIMEKEEPING VIEW</b></li>
          </ol>
      </nav>

      <div class="form-row">
        <label for="payroll_period" class="col-form-label pad">PAYROLL PERIOD:</label>
        <input type="text" name="empCode" id="empCode" value="<?php echo $empCode; ?>" hidden>
        <div class='col-lg-1' id="slct">
            <select class="form-select" id="spay">
                <option value="15th">15th Payroll</option>
                <option value="30th">30th Payroll</option>
            </select>
        </div>
        <div class='col-md-2' id="s15th">
            <?php $dd->GenerateDropDown("ddcutoff", $mf->GetTKList("tkview")); ?>
        </div>
        
        <div class='col-md-2' id="s30th">
            <?php $dd->GenerateDropDown("ddcutoff30", $mf->GetTKList("tkview")); ?>
        </div>                    
        <button type="button" id="search" class="btn btn-success mr-2" onmousedown="javascript:generatePayrll()">
            <i class="fas fa-search-plus"></i> GENERATE                      
        </button>
        <button type="button" class="btn btn-warning mr-2" id="usersEntry"><i class="fas fa-plus-circle"></i> ADD USER </button>

        <?php 
        if($tkstat == 'READY' || $tkstat == 'DELETED') {
            echo '<button type="button" class="btn btn-primary" onclick="savetk()"><i class="fas fa-save"></i> SAVE TIMEKEEPING </button>';
        }else if($tkstat == 'SAVED' && $empUserType == 'Admin') {
            echo "<button class='btn btn-primary' onclick='ApprovePayView()'><i class='fas fa-save'></i> GENERATE PAYROLL</button>"; 

        }else{

        }
        ?>
        
  
    </div>
   
        <div class="row pt-5">
            <div class="col-md-12 mbot"><br> 
                <div class="d-flex justify-content-center">
                    <legend class="fieldset-border pad">
                       <div id="pyper">Payroll Period of </span> from <span id="pfromt"></span> to <span id="ptot"></span></div>
                   </legend>
               </div>
               <div id='contents'></div>   
           </div>
       </div>

       <div class="modal fade" id="updateAtt" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
       aria-hidden="true">
       <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title bb" id="popUpModalTitle">UPDATE EMPLOYEE ATTENDANCE <i class="fas fa-money-check fa-fw"></i></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times; </span>
                </button>
            </div>
            <div class="modal-body">
                <div class="main-body"> 
                    <fieldset class="fieldset-border editatt">
                        <div class="d-flex justify-content-center">
                            <legend class="fieldset-border pad">
                            </legend>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label class="control-label" for="dscsb">Code:</label>
                                    <input type="text" class="form-control" name="badge_no"
                                    id="badge_no" readonly>
                                    <input type="text" class="form-control" name="rowid"
                                    id="rowid" hidden>                                      
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label class="control-label" for="dscsb">Employee Name:</label>
                                    <input type="text" class="form-control" name="employee"
                                    id="employee" readonly> 
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="control-label" for="remarks">Remarks:</label>
                                    <input type="text" class="form-control" name="remarks"
                                    id="remarks" placeholder="Comments/Reasons..."> 
                                </div>
                            </div>   
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_days_absent">Total Days Absent:</label>
                                    <input type="number" class="form-control" name="tot_days_absent"
                                    id="tot_days_absent"> 
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_days_work">Total Days Worked:</label>
                                    <input type="number" class="form-control" name="tot_days_work"
                                    id="tot_days_work"> 
                                </div>
                            </div> 
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="total_undertime">Total Undertime:</label>
                                    <input type="number" class="form-control" name="total_undertime"
                                    id="total_undertime"> 
                                </div>
                            </div>   
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_lates">Total Lates:</label>
                                    <input type="number" class="form-control" name="tot_lates"
                                    id="tot_lates"> 
                                </div>
                            </div>                            
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_reg ">Regular Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_reg" id="tot_overtime_reg">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="night_differential">Regular Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="night_differential" id="night_differential">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="night_differential_ot">Regular Night Differential OT (Hrs):</label>
                                    <input type="number" class="form-control" name="night_differential_ot" id="night_differential_ot">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_regholiday">Regular Holiday (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_regholiday" id="tot_regholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_regholiday">Regular Holiday Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_regholiday" id="tot_overtime_regholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_regholiday_nightdiff">Regular Holiday Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_regholiday_nightdiff" id="tot_regholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_regholiday_nightdiff">Regular Holiday Night Differential Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_regholiday_nightdiff" id="tot_overtime_regholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_spholiday">Special Holiday (Hrs):</label><br><br>
                                    <input type="number" class="form-control" name="tot_spholiday" id="tot_spholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_spholiday">Special Holiday Overtime (Hrs):</label><br><br>
                                    <input type="number" class="form-control" name="tot_overtime_spholiday" id="tot_overtime_spholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_spholiday_nightdiff">Special Holiday Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_spholiday_nightdiff" id="tot_spholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_spholiday_nightdiff">Special Holiday Night Differential Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_spholiday_nightdiff" id="tot_overtime_spholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_rest">Rest Day (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_rest" id="tot_rest">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_rest">Rest Day Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_rest" id="tot_overtime_rest">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="night_differential_rest">Rest Day Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="night_differential_rest" id="night_differential_rest">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="night_differential_ot_rest      ">Rest Day Night Differential Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="night_differential_ot_rest" id="night_differential_ot_rest">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_rest_regholiday">Rest Day Regular Holiday Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_rest_regholiday" id="tot_overtime_rest_regholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="night_differential_rest_regholiday">Rest Day Regular Holiday Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="night_differential_rest_regholiday" id="night_differential_rest_regholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_night_diff_rest_regholiday">Rest Day Regular Holiday Night Differential Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_night_diff_rest_regholiday" id="tot_overtime_night_diff_rest_regholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_sprestholiday">Rest Day Special Holiday Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_sprestholiday" id="tot_overtime_sprestholiday">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_sprestholiday_nightdiff">Rest Day Special Holiday Night Differential (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_sprestholiday_nightdiff" id="tot_sprestholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="tot_overtime_sprestholiday_nightdiff">Rest Day Special Holiday Night Differential Overtime (Hrs):</label>
                                    <input type="number" class="form-control" name="tot_overtime_sprestholiday_nightdiff" id="tot_overtime_sprestholiday_nightdiff">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="workfromhome">Work From Home (Days):</label><br><br>
                                    <input type="number" class="form-control" name="workfromhome"
                                    id="workfromhome"> 
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="offbusiness">Official Business (Days):</label><br><br>
                                    <input type="number" class="form-control" name="offbusiness"
                                    id="offbusiness"> 
                                </div>
                            </div>                             
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="sick_leave">Sick Leave (Days):</label><br><br>
                                    <input type="number" class="form-control" name="sick_leave"
                                    id="sick_leave"> 
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="vacation_leave">Vacation Leave (Days):</label><br><br>
                                    <input type="number" class="form-control" name="vacation_leave"
                                    id="vacation_leave"> 
                                </div>
                            </div> 
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="sick_leave_nopay">Sick Leave No Pay(Days):</label><br><br>
                                    <input type="number" class="form-control" name="sick_leave_nopay"
                                    id="sick_leave_nopay"> 
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="vacation_leave_nopay">Vacation Leave No Pay(Days):</label><br><br>
                                    <input type="number" class="form-control" name="vacation_leave_nopay"
                                    id="vacation_leave_nopay"> 
                                </div>
                            </div>                             
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="control-label" for="total_adjstmenthrs">Adjustment (Hrs):</label><br><br>
                                    <input type="number" class="form-control" name="total_adjstmenthrs"
                                    id="total_adjstmenthrs"> 
                                </div>
                            </div>                             
                                                                                                                                             
                        </div> <!-- form row closing -->
                    </fieldset> 
                    <div class="modal-footer">                                  
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                        <button type="button" class="btn btn-success" onclick="updateAtt()" ><i class="fas fa-check-circle"></i> Submit</button>                                      
                    </div> 
                </div> <!-- main body closing -->
            </div> <!-- modal body closing -->
        </div> <!-- modal content closing -->
    </div> <!-- modal dialog closing -->
</div><!-- modal fade closing -->

<div class="modal fade" id="pendingModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">PENDING FORMS  <i class="fas fa-minus-circle"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
<div class="modal-body">
    <div class="main-body">
        <fieldset class="fieldset-border">
                <div class="form-row">
                        
                    </div> <!-- form row closing -->
            </fieldset> 

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> CLOSE</button>
                        </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">USERS ENTRY  <i class="fas fa-minus-circle"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                </legend>
                             </div>
                        <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="allempnames">Employee Code/Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleGenDropDown("allempnames", $mf->GetAttEmployeeNames("allempnames")); ?> 
                                    </div>
                                </div> 

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="allempnames">Payroll Period/Location<span class="req">*</span></label>
                                    <?php $dd->GenerateDropDown("ddcutoff", $mf->GetAllCutoffPay("payview")); ?>
                                    </div>
                                </div>  
                                <input type="text" name="eMplogName" id="eMplogName" value="<?php echo $empName ?>" hidden>
                            </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" id="Submit" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="viewAllAttendanceEmp" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle">VIEW ATTENDANCE LOGS   <i class="fas fa-suitcase"></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times; </span>
            </button>
        </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                    <div class="d-flex justify-content-center">
                        <legend class="fieldset-border pad">
                        </legend>
                    </div>
                    <div class="form-row">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div id="contents2" class="table-responsive-sm table-body">
                                        <button type="button" id="search" hidden>GENERATE</button>
                                    </div>
                                </div>
                            </div>
                        </div>                                               
                    </div> <!-- form row closing -->
                </fieldset> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> CLOSE</button>
                </div> 
            </div> <!-- main body closing -->
        </div> <!-- modal body closing -->
    </div> <!-- modal content closing -->
</div> <!-- modal dialog closing -->
</div><!-- modal fade closing -->   

<div class="modal fade" id="viewPayrollLogs" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle">VIEW ATTENDANCE PAYROLL LOGS  <i class="fas fa-money-bill"></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times; </span>
            </button>
        </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                    <div class="d-flex justify-content-center">
                        <legend class="fieldset-border pad">
                        </legend>
                    </div>
                    <div class="form-row">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div id="contents3" class="table-responsive-sm table-body">
                                        <button type="button" id="search" hidden>GENERATE</button>
                                    </div>
                                </div>
                            </div>
                        </div>                                               
                    </div> <!-- form row closing -->
                </fieldset> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> CLOSE</button>
                </div> 
            </div> <!-- main body closing -->
        </div> <!-- modal body closing -->
    </div> <!-- modal content closing -->
</div> <!-- modal dialog closing -->
</div><!-- modal fade closing -->   



<div class="modal fade" id="viewApprovedForms" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle">VIEW APPROVED FORMS  <i class="fas fa-money-bill"></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times; </span>
            </button>
        </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                    <div class="d-flex justify-content-center">
                        <legend class="fieldset-border pad">
                        </legend>
                    </div>
                    <div class="form-row">
                        <div class="row pt-3">
                            <div class="col-md-12">
                                <div class="panel-body">
                                    <div id="contents4" class="table-responsive-sm table-body">
                                        <button type="button" id="search" hidden>GENERATE</button>
                                    </div>
                                </div>
                            </div>
                        </div>                                               
                    </div> <!-- form row closing -->
                </fieldset> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> CLOSE</button>
                </div> 
            </div> <!-- main body closing -->
        </div> <!-- modal body closing -->
    </div> <!-- modal content closing -->
</div> <!-- modal dialog closing -->
</div><!-- modal fade closing -->   

</div>
</div>
</body>
<script type="text/javascript">
    
    $("#s30th").hide();
    $('#spay').change(function(){
        if($('#spay').val() == '15th'){
            $("#s30th").hide();
        }else{
            $("#s30th").show();
        }
    });

    $('#pendingEntry').click(function(e){
        e.preventDefault();
        $('#pendingModal').modal('toggle');

    });

    $('#usersEntry').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

    $('#Submit').click(function(){

        var bdno = $('#allempnames').val();
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var det = cutoff.split(" - ");
        var name =  document.getElementById(bdno).innerHTML;
        var logname = $('#eMplogName').val();

            param = {
                'Action': 'InsertUsersAtt',
                'bdno': bdno,
                'name': name,
                'pfrom': det[0],
                'pto': det[1],
                'loct': 'Makati',
                'logname': logname
              
            }
    
            param = JSON.stringify(param);

        // console.log(param);
        // return false;
    
                 swal({
                      title: "Are you sure?",
                      text: "You want to add this user to the payroll list?",
                      icon: "success",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((appEnt) => {
                      if (appEnt) {
                            $.ajax({
                                type: 'POST',
                                url: '../payroll/usersatt_process.php',
                                data: {
                                    data: param
                                },
                                success: function (result) {
                                    console.log('success: ' + result);
                                    swal({text:"Successfully added employee in payroll!",icon:"success"});
                                    location.reload();
                                },
                                error: function (result) {
                                    console.log('error: ' + result);
                                }
                            }); //ajax
                      } else {
                        swal({text:"You cancel the addition of your employee in payroll!",icon:"error"});
                      }
                    });

            });


    function show() {
        document.getElementById("myDiv").style.display="block";
    }


    function generatePayrll()
    {

        var cutoff = $('#ddcutoff').children("option:selected").val();
        if(typeof(cutoff) != "undefined" && cutoff !== null) {
            document.getElementById("myDiv").style.display="block";
            var url = "../payroll/payrollrep_process.php";
            var dates = cutoff.split(" - ");
            var empCode = $('#empCode').val();
            document.getElementById('pfromt').innerHTML = dates[0];
            document.getElementById('ptot').innerHTML = dates[1];
            $.post (
                url,
                {
                    _action: 1,
                    _from: dates[0],
                    _to: dates[1],
                    _location: 'Makati',
                    _empCode: empCode
                    
                },
                function(data) { 
                    $("#contents").html(data).show();
                    $("#payrollList").tableExport({
                        headers: true,
                        footers: true,
                        formats: ['xlsx'],
                        filename: 'id',
                        bootstrap: false,
                        exportButtons: true,
                        position: 'top',
                        ignoreRows: null,
                        ignoreCols: null,
                        trimWhitespace: true,
                        RTL: false,
                        sheetname: 'Payroll Attendance'
                    });
                    $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
                    document.getElementById("myDiv").style.display="none"; 
                }
                );
        }else{

               swal({
                    title: "Warning!", 
                    text: "No generated timekeeping logs.", 
                    icon: "warning",
                }).then(function() {
                    window.location.replace("../pages/admin.php"); 
                }); 

        }
    }

    function viewAllAttendanceEmp(bdno,pfrom,pto)
    {
     $('#viewAllAttendanceEmp').modal('toggle');
     var url = "../payroll/payatt_viewlogs.php";
     var emp_code = bdno;
     var dateFrom = pfrom;
     var dateTo = pto;

     $.post (
        url,
        {
            _action: 1,
            emp_code: emp_code,
            dateFrom: dateFrom,
            dateTo: dateTo             
        },
        function(data) { 
            $("#contents2").html(data).show(); 
            $("#empDtrList").tableExport({
                headers: true,
                footers: true,
                formats: ['xlsx'],
                filename: 'id',
                bootstrap: false,
                exportButtons: true,
                position: 'top',
                ignoreRows: null,
                ignoreCols: null,
                trimWhitespace: true,
                RTL: false,
                sheetname: 'Attendace_Logs'
            });
            $(".fa-file-export").remove();
            $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');                
        }
        );
 }

 function viewPayrollLogs(bdno,pfrom,pto)
 {
     $('#viewPayrollLogs').modal('toggle');
     var url = "../payroll/audit_payattviewlogs_process.php";
     var emp_code = bdno;
     var dateFrom = pfrom;
     var dateTo = pto;

     $.post (
        url,
        {
            _action: 1,
            emp_code: emp_code,
            dateFrom: dateFrom,
            dateTo: dateTo             
        },
        function(data) { 
            $("#contents3").html(data).show(); 
            $("#AuditPayAttViewLogs").tableExport({
                headers: true,
                footers: true,
                formats: ['xlsx'],
                filename: 'id',
                bootstrap: false,
                exportButtons: true,
                position: 'top',
                ignoreRows: null,
                ignoreCols: null,
                trimWhitespace: true,
                RTL: false,
                sheetname: 'Attendace_Audit_Logs'
            });
            $(".fa-file-export").remove();
            $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');                
        }
        );
 }

  function viewApprovedForms(bdno,pfrom,pto)
 {
     $('#viewApprovedForms').modal('toggle');
     var url = "../payroll/approvedforms_process.php";
     var emp_code = bdno;
     var dateFrom = pfrom;
     var dateTo = pto;

     $.post (
        url,
        {
            _action: 1,
            emp_code: emp_code,
            dateFrom: dateFrom,
            dateTo: dateTo             
        },
        function(data) { 
            $("#contents4").html(data).show();            
        }
        );
 }

 function editAttModal(empname,empcd,rwd){

    $('#updateAtt').modal('toggle');
    document.getElementById('remarks').value = '';
    document.getElementById('employee').value =  empname;
    document.getElementById('badge_no').value =  empcd; 
    document.getElementById('rowid').value =  rwd; 
    document.getElementById('tot_days_absent').value = document.getElementById('toa'+empcd).innerHTML;
    document.getElementById('tot_days_work').value =  document.getElementById('tow'+empcd).innerHTML;
    document.getElementById('tot_lates').value =  document.getElementById('tol'+empcd).innerHTML;
    document.getElementById('total_undertime').value =  document.getElementById('tou'+empcd).innerHTML;  
    document.getElementById('total_adjstmenthrs').value =  document.getElementById('toad'+empcd).innerHTML;                 
    document.getElementById('tot_overtime_reg').value = document.getElementById('reg_ot'+empcd).innerHTML;
    document.getElementById('night_differential').value = document.getElementById('reg_ns'+empcd).innerHTML;
    document.getElementById('night_differential_ot').value = document.getElementById('reg_ns_ot'+empcd).innerHTML;
    document.getElementById('tot_regholiday').value = document.getElementById('rh'+empcd).innerHTML;
    document.getElementById('tot_overtime_regholiday').value = document.getElementById('rh_ot'+empcd).innerHTML;
    document.getElementById('tot_regholiday_nightdiff').value = document.getElementById('rh_ns'+empcd).innerHTML;
    document.getElementById('tot_overtime_regholiday_nightdiff').value =  document.getElementById('rh_ns_ot'+empcd).innerHTML;
    document.getElementById('tot_spholiday').value = document.getElementById('sh'+empcd).innerHTML;
    document.getElementById('tot_overtime_spholiday').value = document.getElementById('sh_ot'+empcd).innerHTML;
    document.getElementById('tot_spholiday_nightdiff').value = document.getElementById('sh_ns'+empcd).innerHTML;
    document.getElementById('tot_overtime_spholiday_nightdiff').value = document.getElementById('sh_ns_ot'+empcd).innerHTML;
    document.getElementById('tot_rest').value = document.getElementById('rd'+empcd).innerHTML;
    document.getElementById('tot_overtime_rest').value = document.getElementById('rd_ot'+empcd).innerHTML;
    document.getElementById('night_differential_rest').value = document.getElementById('rd_ns'+empcd).innerHTML;
    document.getElementById('night_differential_ot_rest').value =  document.getElementById('rd_ns_ot'+empcd).innerHTML;
    document.getElementById('tot_overtime_rest_regholiday').value = document.getElementById('rd_rh_ot'+empcd).innerHTML;
    document.getElementById('night_differential_rest_regholiday').value = document.getElementById('rd_rh_ns'+empcd).innerHTML;
    document.getElementById('tot_overtime_night_diff_rest_regholiday').value = document.getElementById('rd_rh_ns_ot'+empcd).innerHTML;
    document.getElementById('tot_overtime_sprestholiday').value = document.getElementById('rd_sh_ot'+empcd).innerHTML;
    document.getElementById('tot_sprestholiday_nightdiff').value = document.getElementById('rd_sh_ns'+empcd).innerHTML;
    document.getElementById('tot_overtime_sprestholiday_nightdiff').value = document.getElementById('rd_sh_ns_ot'+empcd).innerHTML; 
    document.getElementById('workfromhome').value =   document.getElementById('wfh'+empcd).innerHTML;  
    document.getElementById('offbusiness').value =   document.getElementById('ob'+empcd).innerHTML;  
    document.getElementById('sick_leave').value =   document.getElementById('slh'+empcd).innerHTML;  
    document.getElementById('vacation_leave').value =   document.getElementById('vlh'+empcd).innerHTML;
    document.getElementById('sick_leave_nopay').value =   document.getElementById('slhnp'+empcd).innerHTML;  
    document.getElementById('vacation_leave_nopay').value =   document.getElementById('vlhnp'+empcd).innerHTML;    
}

function insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname) {

    $.post (url2,
    {
        action:action,
        badge_no: badge_no,
        column_name: column_name,
        pay_from: pay_from,
        pay_to: pay_to,
        new_data: new_data,
        old_data: old_data,
        remarks: remarks,
        emp_code: empcd, 
        emp_name: lname+','+fname 
    });
}

function updateAtt()
{

    // new data
    var url = "../payroll/updateAtt_process.php";
    var url2 = "../payroll/logspayroll_process.php";
    var badge_no = document.getElementById("badge_no").value;
    var rowid = document.getElementById("rowid").value;
    var tot_days_absent = document.getElementById("tot_days_absent").value;
    var tot_days_work = document.getElementById("tot_days_work").value;  
    var tot_lates = document.getElementById("tot_lates").value;       
    var total_undertime = document.getElementById("total_undertime").value;       
    var total_adjstmenthrs = document.getElementById("total_adjstmenthrs").value;  
    var tot_overtime_reg  = document.getElementById("tot_overtime_reg").value;
    var night_differential = document.getElementById("night_differential").value;
    var night_differential_ot = document.getElementById("night_differential_ot").value;
    var tot_regholiday = document.getElementById("tot_regholiday").value;
    var tot_overtime_regholiday = document.getElementById("tot_overtime_regholiday").value;
    var tot_regholiday_nightdiff = document.getElementById("tot_regholiday_nightdiff").value;
    var tot_overtime_regholiday_nightdiff = document.getElementById("tot_overtime_regholiday_nightdiff").value;
    var tot_spholiday = document.getElementById("tot_spholiday").value;
    var tot_overtime_spholiday = document.getElementById("tot_overtime_spholiday").value;
    var tot_spholiday_nightdiff = document.getElementById("tot_spholiday_nightdiff").value;
    var tot_overtime_spholiday_nightdiff = document.getElementById("tot_overtime_spholiday_nightdiff").value;
    var tot_rest = document.getElementById("tot_rest").value;
    var tot_overtime_rest = document.getElementById("tot_overtime_rest").value;
    var night_differential_rest = document.getElementById("night_differential_rest").value;
    var night_differential_ot_rest  = document.getElementById("night_differential_ot_rest").value;
    var tot_overtime_rest_regholiday = document.getElementById("tot_overtime_rest_regholiday").value;
    var night_differential_rest_regholiday = document.getElementById("night_differential_rest_regholiday").value;
    var tot_overtime_night_diff_rest_regholiday = document.getElementById("tot_overtime_night_diff_rest_regholiday").value;
    var tot_overtime_sprestholiday = document.getElementById("tot_overtime_sprestholiday").value;
    var tot_sprestholiday_nightdiff = document.getElementById("tot_sprestholiday_nightdiff").value;
    var tot_overtime_sprestholiday_nightdiff = document.getElementById("tot_overtime_sprestholiday_nightdiff").value;
    var workfromhome = document.getElementById("workfromhome").value;
    var offbusiness = document.getElementById("offbusiness").value;
    var sick_leave = document.getElementById("sick_leave").value;
    var vacation_leave = document.getElementById("vacation_leave").value;
    var sick_leave_nopay = document.getElementById("sick_leave_nopay").value;
    var vacation_leave_nopay = document.getElementById("vacation_leave_nopay").value;    
    var remarks = document.getElementById("remarks").value;


    // old data
    var lname = document.getElementById('ln'+badge_no).innerHTML ;
    var fname = document.getElementById('fn'+badge_no).innerHTML ;
    var pay_from = document.getElementById('pf'+badge_no).innerHTML ;
    var pay_to = document.getElementById('pt'+badge_no).innerHTML ;
    var empcd = document.getElementById('empc'+badge_no).innerHTML ;
    var old_tot_days_absent = document.getElementById('toa'+badge_no).innerHTML ;
    var old_tot_days_work = document.getElementById('tow'+badge_no).innerHTML ;
    var old_tot_lates = document.getElementById('tol'+badge_no).innerHTML ;
    var old_total_undertime = document.getElementById('toad'+badge_no).innerHTML ;
    var old_total_adjstmenthrs = document.getElementById('toad'+badge_no).innerHTML ;
    var old_tot_overtime_reg  = document.getElementById('reg_ot'+badge_no).innerHTML;
    var old_night_differential  = document.getElementById('reg_ns'+badge_no).innerHTML;
    var old_night_differential_ot  = document.getElementById('reg_ns_ot'+badge_no).innerHTML;
    var old_tot_regholiday  = document.getElementById('rh'+badge_no).innerHTML;
    var old_tot_overtime_regholiday  = document.getElementById('rh_ot'+badge_no).innerHTML;
    var old_tot_regholiday_nightdiff  = document.getElementById('rh_ns'+badge_no).innerHTML;
    var old_tot_overtime_regholiday_nightdiff  =  document.getElementById('rh_ns_ot'+badge_no).innerHTML;
    var old_tot_spholiday  = document.getElementById('sh'+badge_no).innerHTML;
    var old_tot_overtime_spholiday  = document.getElementById('sh_ot'+badge_no).innerHTML;
    var old_tot_spholiday_nightdiff  = document.getElementById('sh_ns'+badge_no).innerHTML;
    var old_tot_overtime_spholiday_nightdiff  = document.getElementById('sh_ns_ot'+badge_no).innerHTML;
    var old_tot_rest  = document.getElementById('rd'+badge_no).innerHTML;
    var old_tot_overtime_rest  = document.getElementById('rd_ot'+badge_no).innerHTML;
    var old_night_differential_rest  = document.getElementById('rd_ns'+badge_no).innerHTML;
    var old_night_differential_ot_rest  =  document.getElementById('rd_ns_ot'+badge_no).innerHTML;
    var old_tot_overtime_rest_regholiday  = document.getElementById('rd_rh_ot'+badge_no).innerHTML;
    var old_night_differential_rest_regholiday  = document.getElementById('rd_rh_ns'+badge_no).innerHTML;
    var old_tot_overtime_night_diff_rest_regholiday  = document.getElementById('rd_rh_ns_ot'+badge_no).innerHTML;
    var old_tot_overtime_sprestholiday  = document.getElementById('rd_sh_ot'+badge_no).innerHTML;
    var old_tot_sprestholiday_nightdiff  = document.getElementById('rd_sh_ns'+badge_no).innerHTML;
    var old_tot_overtime_sprestholiday_nightdiff  = document.getElementById('rd_sh_ns_ot'+badge_no).innerHTML; 
    var old_workfromhome = document.getElementById('wfh'+badge_no).innerHTML ;
    var old_offbusiness = document.getElementById('ob'+badge_no).innerHTML ;
    var old_sick_leave = document.getElementById('slh'+badge_no).innerHTML ;
    var old_vacation_leave = document.getElementById('vlh'+badge_no).innerHTML;
    var old_sick_leave_nopay = document.getElementById('slhnp'+badge_no).innerHTML ;
    var old_vacation_leave_nopay = document.getElementById('vlhnp'+badge_no).innerHTML;    

    // return false;
    
    swal({
        title: "Are you sure?",
        text: "You want to update this employee attendance details?",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((updateAtt) => {
        if (updateAtt) {
            $.post (
                url,
                {
                    action: 1,
                    badge_no: badge_no ,
                    rowid: rowid ,
                    tot_days_absent: tot_days_absent ,
                    tot_days_work: tot_days_work , 
                    tot_lates: tot_lates ,   
                    total_undertime: total_undertime ,  
                    total_adjstmenthrs: total_adjstmenthrs ,                                         
                    tot_overtime_reg  : tot_overtime_reg ,
                    night_differential : night_differential,
                    night_differential_ot : night_differential_ot,
                    tot_regholiday : tot_regholiday,
                    tot_overtime_regholiday : tot_overtime_regholiday,
                    tot_regholiday_nightdiff : tot_regholiday_nightdiff,
                    tot_overtime_regholiday_nightdiff : tot_overtime_regholiday_nightdiff,
                    tot_spholiday : tot_spholiday,
                    tot_overtime_spholiday : tot_overtime_spholiday,
                    tot_spholiday_nightdiff : tot_spholiday_nightdiff,
                    tot_overtime_spholiday_nightdiff : tot_overtime_spholiday_nightdiff,
                    tot_rest : tot_rest,
                    tot_overtime_rest : tot_overtime_rest,
                    night_differential_rest : night_differential_rest,
                    night_differential_ot_rest : night_differential_ot_rest,
                    tot_overtime_rest_regholiday : tot_overtime_rest_regholiday,
                    night_differential_rest_regholiday : night_differential_rest_regholiday,
                    tot_overtime_night_diff_rest_regholiday : tot_overtime_night_diff_rest_regholiday,
                    tot_overtime_sprestholiday : tot_overtime_sprestholiday,
                    tot_sprestholiday_nightdiff : tot_sprestholiday_nightdiff,
                    tot_overtime_sprestholiday_nightdiff : tot_overtime_sprestholiday_nightdiff,
                    workfromhome: workfromhome,
                    offbusiness: offbusiness,
                    sick_leave: sick_leave,
                    vacation_leave: vacation_leave,
                    sick_leave_nopay: sick_leave_nopay,
                    vacation_leave_nopay: vacation_leave_nopay                    
                },
                function(data) {   
    console.log(data);                                        
        swal({
            title: "Success!", 
            text: "Successfully updated the attendance details!", 
            type: "success",
            icon: "success",
        }).then(function() {
            $('#updateAtt').modal('hide');
            document.getElementById('toa'+badge_no).innerHTML = tot_days_absent;
            document.getElementById('tow'+badge_no).innerHTML = tot_days_work;
            document.getElementById('tol'+badge_no).innerHTML = tot_lates;
            document.getElementById('tou'+badge_no).innerHTML = total_undertime;
            document.getElementById('toad'+badge_no).innerHTML = total_adjstmenthrs;
            document.getElementById('reg_ot'+badge_no).innerHTML = tot_overtime_reg ;
            document.getElementById('reg_ns'+badge_no).innerHTML = night_differential;
            document.getElementById('reg_ns_ot'+badge_no).innerHTML = night_differential_ot;
            document.getElementById('rh'+badge_no).innerHTML = tot_regholiday;
            document.getElementById('rh_ot'+badge_no).innerHTML = tot_overtime_regholiday;
            document.getElementById('rh_ns'+badge_no).innerHTML = tot_regholiday_nightdiff;
            document.getElementById('rh_ns_ot'+badge_no).innerHTML = tot_overtime_regholiday_nightdiff;
            document.getElementById('sh'+badge_no).innerHTML = tot_spholiday;
            document.getElementById('sh_ot'+badge_no).innerHTML = tot_overtime_spholiday;
            document.getElementById('sh_ns'+badge_no).innerHTML = tot_spholiday_nightdiff;
            document.getElementById('sh_ns_ot'+badge_no).innerHTML = tot_overtime_spholiday_nightdiff;
            document.getElementById('rd'+badge_no).innerHTML = tot_rest;
            document.getElementById('rd_ot'+badge_no).innerHTML = tot_overtime_rest;
            document.getElementById('rd_ns'+badge_no).innerHTML = night_differential_rest;
            document.getElementById('rd_ns_ot'+badge_no).innerHTML = night_differential_ot_rest;
            document.getElementById('rd_rh_ot'+badge_no).innerHTML = tot_overtime_rest_regholiday;
            document.getElementById('rd_rh_ns'+badge_no).innerHTML = night_differential_rest_regholiday;
            document.getElementById('rd_rh_ns_ot'+badge_no).innerHTML = tot_overtime_night_diff_rest_regholiday;
            document.getElementById('rd_sh_ot'+badge_no).innerHTML = tot_overtime_sprestholiday;
            document.getElementById('rd_sh_ns'+badge_no).innerHTML = tot_sprestholiday_nightdiff;
            document.getElementById('rd_sh_ns_ot'+badge_no).innerHTML = tot_overtime_sprestholiday_nightdiff;
            document.getElementById('wfh'+badge_no).innerHTML = workfromhome;
            document.getElementById('ob'+badge_no).innerHTML = offbusiness;
            document.getElementById('slh'+badge_no).innerHTML = sick_leave;
            document.getElementById('vlh'+badge_no).innerHTML = vacation_leave;
            document.getElementById('slhnp'+badge_no).innerHTML = sick_leave_nopay;
            document.getElementById('vlhnp'+badge_no).innerHTML = vacation_leave_nopay;                        

            if(tot_days_absent !== old_tot_days_absent){
            action = 'Change';
            new_data = tot_days_absent;
            old_data =  old_tot_days_absent;
            column_name =  'Days Absent';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(tot_days_work !== old_tot_days_work){
            action = 'Change';
            new_data = tot_days_work;
            old_data =  old_tot_days_work;
            column_name =  'Days Worked';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(tot_lates !== old_tot_lates){
            action = 'Change';
            new_data = tot_lates;
            old_data =  old_tot_lates;
            column_name =  'Days Late';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(total_undertime !== old_total_undertime){
            action = 'Change';
            new_data = total_undertime;
            old_data =  old_total_undertime;
            column_name =  'Undertime (Hrs)';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(total_adjstmenthrs !== old_total_adjstmenthrs){
            action = 'Change';
            new_data = total_adjstmenthrs;
            old_data =  old_total_adjstmenthrs;
            column_name =  'Adjustment (Hrs)';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(tot_overtime_reg  !== old_tot_overtime_reg ){
            action = 'Change';
            new_data = tot_overtime_reg ;
            old_data = old_tot_overtime_reg ;
            column_name =  'Regular Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(night_differential !== old_night_differential){
            action = 'Change';
            new_data = night_differential;
            old_data = old_night_differential;
            column_name =  'Regular Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(night_differential_ot !== old_night_differential_ot){
            action = 'Change';
            new_data = night_differential_ot;
            old_data = old_night_differential_ot;
            column_name =  'Regular Night Differential OT (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_regholiday !== old_tot_regholiday){
            action = 'Change';
            new_data = tot_regholiday;
            old_data = old_tot_regholiday;
            column_name =  'Regular Holiday (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_regholiday !== old_tot_overtime_regholiday){
            action = 'Change';
            new_data = tot_overtime_regholiday;
            old_data = old_tot_overtime_regholiday;
            column_name =  'Regular Holiday Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_regholiday_nightdiff !== old_tot_regholiday_nightdiff){
            action = 'Change';
            new_data = tot_regholiday_nightdiff;
            old_data = old_tot_regholiday_nightdiff;
            column_name =  'Regular Holiday Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_regholiday_nightdiff !== old_tot_overtime_regholiday_nightdiff){
            action = 'Change';
            new_data = tot_overtime_regholiday_nightdiff;
            old_data = old_tot_overtime_regholiday_nightdiff;
            column_name =  'Regular Holiday Night Differential Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_spholiday !== old_tot_spholiday){
            action = 'Change';
            new_data = tot_spholiday;
            old_data = old_tot_spholiday;
            column_name =  'Special Holiday (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_spholiday !== old_tot_overtime_spholiday){
            action = 'Change';
            new_data = tot_overtime_spholiday;
            old_data = old_tot_overtime_spholiday;
            column_name =  'Special Holiday Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_spholiday_nightdiff !== old_tot_spholiday_nightdiff){
            action = 'Change';
            new_data = tot_spholiday_nightdiff;
            old_data = old_tot_spholiday_nightdiff;
            column_name =  'Special Holiday Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_spholiday_nightdiff !== old_tot_overtime_spholiday_nightdiff){
            action = 'Change';
            new_data = tot_overtime_spholiday_nightdiff;
            old_data = old_tot_overtime_spholiday_nightdiff;
            column_name =  'Special Holiday Night Differential Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_rest !== old_tot_rest){
            action = 'Change';
            new_data = tot_rest;
            old_data = old_tot_rest;
            column_name =  'Rest Day (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_rest !== old_tot_overtime_rest){
            action = 'Change';
            new_data = tot_overtime_rest;
            old_data = old_tot_overtime_rest;
            column_name =  'Rest Day Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(night_differential_rest !== old_night_differential_rest){
            action = 'Change';
            new_data = night_differential_rest;
            old_data = old_night_differential_rest;
            column_name =  'Rest Day Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(night_differential_ot_rest       !== old_night_differential_ot_rest      ){
            action = 'Change';
            new_data = night_differential_ot_rest      ;
            old_data = old_night_differential_ot_rest      ;
            column_name =  'Rest Day Night Differential Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_rest_regholiday !== old_tot_overtime_rest_regholiday){
            action = 'Change';
            new_data = tot_overtime_rest_regholiday;
            old_data = old_tot_overtime_rest_regholiday;
            column_name =  'Rest Day Regular Holiday Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(night_differential_rest_regholiday !== old_night_differential_rest_regholiday){
            action = 'Change';
            new_data = night_differential_rest_regholiday;
            old_data = old_night_differential_rest_regholiday;
            column_name =  'Rest Day Regular Holiday Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_night_diff_rest_regholiday !== old_tot_overtime_night_diff_rest_regholiday){
            action = 'Change';
            new_data = tot_overtime_night_diff_rest_regholiday;
            old_data = old_tot_overtime_night_diff_rest_regholiday;
            column_name =  'Rest Day Regular Holiday Night Differential Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_sprestholiday !== old_tot_overtime_sprestholiday){
            action = 'Change';
            new_data = tot_overtime_sprestholiday;
            old_data = old_tot_overtime_sprestholiday;
            column_name =  'Rest Day Special Holiday Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_sprestholiday_nightdiff !== old_tot_sprestholiday_nightdiff){
            action = 'Change';
            new_data = tot_sprestholiday_nightdiff;
            old_data = old_tot_sprestholiday_nightdiff;
            column_name =  'Rest Day Special Holiday Night Differential (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(tot_overtime_sprestholiday_nightdiff !== old_tot_overtime_sprestholiday_nightdiff){
            action = 'Change';
            new_data = tot_overtime_sprestholiday_nightdiff;
            old_data = old_tot_overtime_sprestholiday_nightdiff;
            column_name =  'Rest Day Special Holiday Night Differential Overtime (Hrs)';
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);}
            if(vacation_leave !== old_vacation_leave){
            action = 'Change';
            new_data = vacation_leave;
            old_data =  old_vacation_leave;
            column_name =  'Vacation Leave Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(sick_leave !== old_sick_leave){
            action = 'Change';
            new_data = sick_leave;
            old_data =  old_sick_leave;
            column_name =  'Sick Leave Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(vacation_leave_nopay !== old_vacation_leave_nopay){
            action = 'Change';
            new_data = vacation_leave_nopay;
            old_data =  old_vacation_leave_nopay;
            column_name =  'Vacation Leave No Pay Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(sick_leave_nopay !== old_sick_leave_nopay){
            action = 'Change';
            new_data = sick_leave_nopay;
            old_data =  old_sick_leave_nopay;
            column_name =  'Sick Leave No Pay Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(workfromhome !== old_workfromhome){
            action = 'Change';
            new_data = workfromhome;
            old_data =  old_workfromhome;
            column_name =  'Work From Home Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }if(offbusiness !== old_offbusiness){
            action = 'Change';
            new_data = offbusiness;
            old_data =  old_offbusiness;
            column_name =  'Official Business Days';         
            insertPayLogs(url2,badge_no,action,column_name,pay_from,pay_to,new_data,old_data,remarks,empcd,lname,fname);
            }else{
            action = 'NoChange';
            value = 0;
            old_value =  0;
            }
            });  //  end of swal
            }
            );
            } else {
            swal({text:"You cancel the updating of employee details!",icon:"error"});
            }
            });

            }


        function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("payrollList");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        if(td.length > 0){ // to avoid th
        if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
        tr[i].style.display = "";
        } else {
        tr[i].style.display = "none";
            }
        }
        }
    }


function savetk()
{   
    $("body").css("cursor", "progress");
    var empCode = $('#empCode').val();
    var url = "../payroll/payrollSaveTkProcess.php";
  
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var dates = cutoff.split(" - ");
        var ppay =  $('#spay').val();

            $('#contents').html('');
            swal({
              title: "Are you sure?",
              text: "You want to save this timekeeping for "+ppay+'?\n'+dates[0]+' to '+dates[1],
              icon: "info",
              buttons: true,
              dangerMode: true,
          })
            .then((savePayroll) => {
              if (savePayroll) {
                $.post (
                    url,
                    {
                        choice: 1,
                        emp_code: empCode,
                        pfrom:dates[0],
                        pto: dates[1],
                        ppay:ppay
                    },
                    function(data) {window.location.replace("../payroll/payroll_view.php"); }
                    );

            } else {
                swal({text:"You cancel the saving of payroll!",icon:"error"});
                location.reload();
            }
        });        
}




function ApprovePayView()
{   
    $("body").css("cursor", "progress");
    var empCode = $('#empCode').val();
    var url = "../payroll/payrollViewProcess.php";

    if($('#spay').val() == '15th'){    
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var dates = cutoff.split(" - ");
        var ppay =  $('#spay').val();

        // console.log(dates[0]);
        // console.log(dates[1]);
        // return false;

            $('#contents').html('');
            swal({
              title: "Are you sure?",
              text: "You want to save this payroll for "+ppay+'?\n'+dates[0]+' to '+dates[1],
              icon: "info",
              buttons: true,
              dangerMode: true,
          })
            .then((savePayroll) => {
              if (savePayroll) {
                $.post (
                    url,
                    {
                        choice: 1,
                        emp_code: empCode,
                        pfrom:dates[0],
                        pto: dates[1],
                        ppay:ppay
                    },
                    function(data) {window.location.replace("../payroll/payroll_view.php"); }
                    );

            } else {
                swal({text:"You cancel the saving of payroll!",icon:"error"});
            }
        });        
    }else{ 
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var dates = cutoff.split(" - ");
        var cutoff30 = $('#ddcutoff30').children("option:selected").val();
        var dates30 = cutoff30.split(" - ");
        var ppay =  $('#spay').val();

        // console.log(dates[0]);
        // console.log(dates[1]);
        // console.log(dates30[0]);
        // console.log(dates30[1]);
        // return false;

            $('#contents').html('');
            swal({
              title: "Are you sure?",
              text: "You want to generate this payroll for "+ppay+'?\n'+dates30[0]+' to '+dates30[1],
              icon: "info",
              buttons: true,
              dangerMode: true,
          })
            .then((savePayroll) => {
              if (savePayroll) {
                $.post (
                    url,
                    {
                        choice: 2,
                        emp_code: empCode,
                        pfrom:dates30[0],
                        pto: dates30[1],
                        pfrom30:dates[0],
                        pto30: dates[1],                        
                        ppay:ppay
                    },
                    function(data) {
                        window.location.replace("../payroll/payroll_view.php"); 
                    }
                    );

            } else {
                swal({text:"You cancel the saving of payroll!",icon:"error"});
            }
        });        
    }

    
}



</script>


<?php include('../_footer.php');  ?>
