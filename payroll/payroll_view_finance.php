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
    include('../payroll/payroll_finance.php');
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

    $queryp = 'EXEC xp_pending_forms :date_from,:date_to';
    $stmtp =$connL->prepare($queryp);
    $paramp = array(":date_from" => $rfrom,":date_to" => $rto);
    $stmtp->execute($paramp);
    $resultp = $stmtp->fetch();  

    $querypf = 'EXEC xp_pending_forms :date_from,:date_to';
    $stmtpf =$connL->prepare($querypf);
    $parampf = array(":date_from" => $rfrom,":date_to" => $rto);
    $stmtpf->execute($parampf);
    $resultpf = $stmtpf->fetch();            

    $querytk = 'SELECT remarks from logs_timekeep where pay_from = :dfrom and pay_to = :dto AND rowid = (SELECT MAX(rowid) from logs_timekeep)';
    $stmttk = $connL->prepare($querytk);
    $paramtk = array(":dfrom" => $rfrom,":dto" => $rto);
    $stmttk->execute($paramtk);
    $rtk = $stmttk->fetch();
    $tkstat = $rtk['remarks'];       


    if($empUserType == 'Admin' || $empUserType == 'Finance' || $empUserType == 'Finance2' || $empUserType == 'Group Head' || 
        $empUserType == 'Group Head') {

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
          <h1><br></h1>
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
            <?php $dd->GenerateDropDown("ddcutoff", $mf->GetAllCutoffPay("payview")); ?>
        </div>
        <div class='col-md-2' id="s30th">
            <?php $dd->GenerateDropDown("ddcutoff30", $mf->GetAllCutoffPay("payview")); ?>
        </div>                    
        <button type="button" id="search" class="btn btn-success" onmousedown="javascript:generatePayrll()">
            <i class="fas fa-search-plus"></i> GENERATE                      
        </button>
        &nbsp;&nbsp;
        <button type="button" class="btn btn-warning" id="usersEntry"><i class="fas fa-plus-circle"></i> ADD USER </button>
        &nbsp;&nbsp;

        <?php 
        if($tkstat == 'SAVED') {
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
<?php   
        $totalPendings = 0;
        echo "
        <table class='table table-striped table-sm'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Pending</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>";

            if($resultpf){
                do {
                    echo    "<tr>".
                                "<td>" . $resultpf['name']. "</td>".
                                "<td>" . round($resultpf['pending'],2) . "</td>".
                                "<td>" . $resultpf['remarks']. "</td>".
                            "</tr>";

                    $totalPendings += $resultpf['pending'];     

                } while ($resultpf = $stmtpf->fetch());     
            }else{

            }

        echo"
            </tbody>
            <tfoot>
                <tr>".
                    "<td class='text-right bg-success'><b>Total</b></td>".
                    "<td class='bg-success'><b>" . $totalPendings . "</b></td>".
                    "<td class='bg-success'><b></b></td>".
                "</tr>
            </tfoot>
        </table>";

?>
                        
                    </div> <!-- form row closing -->
            </fieldset> 

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                        </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
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
        document.getElementById("myDiv").style.display="block";
        var url = "../payroll/payrollrepfinance_process.php";
        var cutoff = $('#ddcutoff').children("option:selected").val();
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
