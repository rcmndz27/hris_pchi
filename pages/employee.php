<?php
session_start();
class data
{
    public $start;
    public $title;
}
if (empty($_SESSION['userid']))
{
    include_once('../loginfirst.php');
    exit();
}
else
{
  include_once('../_header.php');

$empInfo = new EmployeeInformation();
$empInfo->SetEmployeeInformation($_SESSION['userid']);
$empCode = $empInfo->GetEmployeeCode();

global $connL;
global $connL;

//ANNOUNCEMENT
$queryan = "SELECT * from logs_events where status = 1 or (date_to >= DATEADD(dd, 0, DATEDIFF(dd, 0, GETDATE())) and status = 0) ORDER BY rowid desc";
$stmtan =$connL->prepare($queryan);
$stmtan->execute();
$resultan = $stmtan->fetch();    


//GET HOLIDAYS
$queryq = "SELECT * from mf_holiday where YEAR(holidaydate) = YEAR(GETDATE())";
$stmtq =$connL->prepare($queryq);
$stmtq->execute();
$resultq = $stmtq->fetch();
$totalVal = array();
if(!empty($resultq)){
 
$i = 0;
do { 
$d = new data();
$d->start = date('Y-m-d',strtotime($resultq['holidaydate']));
$d->title = $resultq['holidaydescs'];
$d->color = '#FFE800';
$d->textColor = '#FFFFFF';
array_push($totalVal,$d);  
$i++;
} while ($resultq = $stmtq->fetch());                     
}else{
}

//GET LEAVE
$queryg = "SELECT * from tr_leave where approved = 2 and emp_code = :empcode";
$stmtg =$connL->prepare($queryg);
$paramg = array(":empcode" => $empCode);
$stmtg->execute($paramg);
$resultg = $stmtg->fetch();

if(!empty($resultg)){
do { 
$d = new data();
$d->start = date('Y-m-d',strtotime($resultg['date_from']));
$d->title = $resultg['leavetype'];
$d->color = '#0C47FF';
$d->textColor = '#FFFFFF';
array_push($totalVal,$d);  
$i++;
} while ($resultg = $stmtg->fetch());                     
}else{
}

//GET OB
$queryob = "SELECT * from tr_offbusiness where status = 2 and emp_code = :empcode";
$stmtob =$connL->prepare($queryob);
$paramob = array(":empcode" => $empCode);
$stmtob->execute($paramob);
$rsob = $stmtob->fetch();

if(!empty($rsob)){
do { 
$d = new data();
$d->start = date('Y-m-d',strtotime($rsob['ob_date']));
$d->title = $rsob['ob_purpose'];
$d->color = '#FD0A84';
$d->textColor = '#FFFFFF';
array_push($totalVal,$d);  
$i++;
} while ($rsob = $stmtob->fetch());                     
}else{
}

//GET ATTENDANCE
$queryd = "exec xp_attendance_portal_admin :empcode";
$stmtd =$connL->prepare($queryd);
$paramd = array(":empcode" => substr($empCode,3));
$stmtd->execute($paramd);
$resultd = $stmtd->fetch();

if(!empty($resultd)){
do { 
$rmrks = $resultd['remarks'];
$tin = (isset($resultd['timein'])) ? date('h:i A',strtotime($resultd['timein'])) : 'NO IN';
$tout = (isset($resultd['timeout'])) ? date('h:i A',strtotime($resultd['timeout'])) : 'NO OUT';
$d = new data();
$d->start = date('Y-m-d',strtotime($resultd['punch_date']));
$d->title = $tin." - ".$tout;
if($tin == 'NO IN' or $tout == 'NO OUT'){
    $d->color = '#FD0A27';
    $d->textColor = '#FFFFFF';    
}else if($rmrks  == 'ONSITE'){
    $d->color = '#FD9D0A';
    $d->textColor = '#FFFFFF';    
}else if($rmrks  == 'WORK FROM HOME'){
    $d->color = '#0AFDC9';
    $d->textColor = '#FFFFFF';    
}else{
    $d->color = '#FD0A84';
    $d->textColor = '#FFFFFF';  
}
array_push($totalVal,$d);  
$i++;
} while ($resultd = $stmtd->fetch());                     
}else{
}

//BIRTHDAY CELEBRANTS
$queryu = "SELECT * from employee_profile where emp_status = 'Active' AND month(birthdate) = month(GETDATE())";
$stmtu =$connL->prepare($queryu);
$stmtu->execute();
$resultu = $stmtu->fetch();


//GET COMPANY
$query = "SELECT * from employee_profile where emp_code = :empcode";
$stmt =$connL->prepare($query);
$param = array(":empcode" => $empCode);
$stmt->execute($param);
$result = $stmt->fetch();
$cmp = $result['company'];
$bdno = $result['badgeno'];
$subemp = strlen($cmp);

//GET TOTAL LATES
$bquery = "EXEC xp_attendance_portal_late_emp :empcode";
$bstmt =$connL->prepare($bquery);
$bparam = array(":empcode" => $empCode);
$bstmt->execute($bparam);
$bresult = $bstmt->fetch();
$totlate = (isset($bresult['tot_late'])) ? round($bresult['tot_late']*60) : 0 ;

//GET TOTAL OT
$otquery = "SELECT SUM(ot_apprv_hrs) as tot_ot from tr_overtime where emp_code = :empcode 
and month(ot_date) = MONTH(GETDATE()) 
and YEAR(ot_date) = YEAR(GETDATE())";
$otstmt =$connL->prepare($otquery);
$otparam = array(":empcode" => $empCode);
$otstmt->execute($otparam);
$otresult = $otstmt->fetch();
$totot = (isset($otresult['tot_ot'])) ? round($otresult['tot_ot'],2) : 0 ;

//GET TOTAL UT
$utquery = "EXEC xp_attendance_portal_totut :empcode";
$utstmt =$connL->prepare($utquery);
$utparam = array(":empcode" => substr($empCode,3));
$utstmt->execute($utparam);
$utresult = $utstmt->fetch();
$totut = (isset($utresult['tot_ut'])) ? round($utresult['tot_ut']*60) : 0 ;

//GET TOTAL WORK
$twquery = "EXEC xp_attendance_portal_totwork :empcode";
$twstmt =$connL->prepare($twquery);
$twparam = array(":empcode" => substr($empCode,3));
$twstmt->execute($twparam);
$twresult = $twstmt->fetch();
$totwork = (isset($twresult['tot_work'])) ? $twresult['tot_work'] : 0 ;


//GET LAST TIME IN
$yquery = "SELECT timein from employee_attendance where emp_code = :empcode and punch_date = :todate";
$ystmt =$connL->prepare($yquery);
$yparam = array(":empcode" => $bdno,":todate" => date('Y-m-d'));
$ystmt->execute($yparam);
$yresult = $ystmt->fetch();
$timeinf =  (isset($yresult['timein']) ? date('h:i A', strtotime($yresult['timein'])) : 'NO TIME-IN');
$timeoutf =  (isset($yresult['timein']) ? date('h:i A',strtotime('+10 hour 30 minute',strtotime($yresult['timein']))): 'n/a');

//LEAVE BALANCES IN PROFILE EMP
$jquery = "SELECT a.earned_sl,a.earned_vl,a.earned_fl FROM employee_leave a where a.emp_code =:empCode";
$jstmt =$connL->prepare($jquery);
$jparam = array(":empCode" => $empCode);
$jstmt->execute($jparam);
$jresult = $jstmt->fetch();  

// PENDING FLEAVE
$querypf = "SELECT count(actl_cnt) as cnt_pf from tr_leave where approved = 1 and emp_code = :empCode 
and leavetype in ('Floating Leave')";
$stmtpf =$connL->prepare($querypf);
$parampf = array(":empCode" => $empCode);
$stmtpf->execute($parampf);
$resultpf = $stmtpf->fetch();

// PENDING SL
$queryps = "SELECT count(actl_cnt) as cnt_ps from tr_leave where approved = 1 and emp_code  = :empCode 
and leavetype in ('Sick Leave')";
$stmtps =$connL->prepare($queryps);
$paramps = array(":empCode" => $empCode);
$stmtps->execute($paramps);
$resultps = $stmtps->fetch();

//PENDING VL
$querypv = "SELECT count(actl_cnt) as cnt_pv from tr_leave where approved = 1 and emp_code  = :empCode 
and leavetype in ('Vacation Leave','Emergency Leave')";
$stmtpv =$connL->prepare($querypv);
$parampv = array(":empCode" => $empCode);
$stmtpv->execute($parampv);
$resultpv = $stmtpv->fetch();     

$queryxc = "SELECT count(actl_cnt) as cnt_sl from tr_leave where approved = 2 and emp_code  = :empCode 
and leavetype in ('Sick Leave')";
$stmtxc =$connL->prepare($queryxc);
$paramxc = array(":empCode" => $empCode);
$stmtxc->execute($paramxc);
$resultxc = $stmtxc->fetch();

$queryv = "SELECT count(actl_cnt) as cnt_vl from tr_leave where approved = 2 and emp_code  = :empCode 
and leavetype in ('Vacation Leave','Emergency Leave')";
$stmtv =$connL->prepare($queryv);
$paramv = array(":empCode" => $empCode);
$stmtv->execute($paramv);
$resultv = $stmtv->fetch(); 

$queryfl = "SELECT count(actl_cnt) as cnt_fl from tr_leave where approved = 2 and emp_code  = :empCode 
and leavetype in ('Floating Leave')";
$stmtfl =$connL->prepare($queryfl);
$paramfl = array(":empCode" => $empCode);
$stmtfl->execute($paramfl);
$resultfl = $stmtfl->fetch(); 

$querysw = "SELECT count(actl_cnt) as cnt_slw from tr_leave where approved = 2 and emp_code  = :empCode 
and leavetype in ('Sick Leave without Pay')";
$stmtsw =$connL->prepare($querysw);
$paramsw = array(":empCode" => $empCode);
$stmtsw->execute($paramsw);
$resultsw = $stmtsw->fetch();

$queryvw = "SELECT count(actl_cnt) as cnt_vlw from tr_leave where approved = 2 and emp_code  = :empCode 
and leavetype in ('Vacation Leave without Pay')";
$stmtvw =$connL->prepare($queryvw);
$paramvw = array(":empCode" => $empCode);
$stmtvw->execute($paramvw);
$resultvw = $stmtvw->fetch();                       

$used_vl = $resultv['cnt_vl'];
$used_sl = $resultxc['cnt_sl'];
$used_fl = $resultfl['cnt_fl'];        
$used_vlw = $resultvw['cnt_vlw'];
$used_slw = $resultsw['cnt_slw'];   
$pending_vl = (isset($resultpv['cnt_pv']) ? round($resultpv['cnt_pv'],2) : 0);
$pending_sl = (isset($resultps['cnt_ps']) ? round($resultps['cnt_ps'],2) : 0);
$pending_fl = (isset($resultpf['cnt_pf']) ? round($resultpf['cnt_pf'],2) : 0); 
$earned_vl = (isset($jresult['earned_vl'])) ? round($jresult['earned_vl'],2) : 0 ;
$earned_sl = (isset($jresult['earned_sl'])) ? round($jresult['earned_sl'],2) : 0 ; 
$earned_fl = (isset($jresult['earned_fl'])) ? round($jresult['earned_fl'],2) : 0 ; 

// GET CUT OFF
$qry = "SELECT * from payroll_cutoff WHERE GETDATE() between cutoff_from and cutoff_to" ;
$stm =$connL->prepare($qry);
$stm->execute();
$resul = $stm->fetch();
$cutoff_from = (isset($resul['cutoff_from'])) ? $resul['cutoff_from'] : date("Y/m/d") ;;
$cutoff_to = (isset($resul['cutoff_to'])) ? $resul['cutoff_to'] : date("Y/m/d") ;


// LEAVE BALANCE
$query = "SELECT  earned_vl,earned_sl,round(earned_vl * 100 / 10,0) as vlpct,round(earned_sl * 100 / 10,0) as slpct from employee_leave where emp_code = :empcode" ;
$stmt =$connL->prepare($query);
$param = array(":empcode" => $empCode);
$stmt->execute($param);
$result = $stmt->fetch();
// $earned_vl = (isset($result['earned_vl'])) ? round($result['earned_vl'],2) : 0 ;
// $earned_sl = (isset($result['earned_sl'])) ? round($result['earned_sl'],2) : 0 ;
$vlpct = (isset($result['vlpct'])) ? round($result['vlpct'],2) : 0 ;
$slpct = (isset($result['slpct'])) ? round($result['slpct'],2) : 0 ;


// TOTAL APPLIED LEAVE
$querys = "SELECT sum(app_days) as app_leave from tr_leave where emp_code = :empcode and
date_from between :cutoff_from and :cutoff_to and
date_to between :cutoff_from2 and :cutoff_to2" ;
$stmts =$connL->prepare($querys);
$params= array(":empcode" => $empCode,":cutoff_from" => $cutoff_from,":cutoff_to" => $cutoff_to,
":cutoff_from2" => $cutoff_from,":cutoff_to2" => $cutoff_to);
$stmts->execute($params);
$results = $stmts->fetch();
$app_leave =  (isset($results['app_leave'])) ? $results['app_leave'] : 0 ;


// TOTAL WORK HRS
$queryrenot = 'EXEC xp_attendance_portal_work :emp_code,:startDate,:endDate';
$paramrenot = array(":emp_code" => substr($empCode,$subemp), ":startDate" => date("Y-01-01"), ":endDate" => date("Y-12-31") );
$stmtrenot =$connL->prepare($queryrenot);
$stmtrenot->execute($paramrenot);
$resultrenot = $stmtrenot->fetch();
$workhrs = (isset($resultrenot['workhrs'])) ? round($resultrenot['workhrs'],2) : 0 ;

//laudot 
$querylaudot = 'EXEC xp_attendance_portal_sum :emp_code,:startDate,:endDate';
$paramlaudot = array(":emp_code" => substr($empCode,$subemp), ":startDate" => date("Y-01-01"), ":endDate" => date("Y-12-31") );
$stmtlaudot =$connL->prepare($querylaudot);
$stmtlaudot->execute($paramlaudot);
$resultlaudot = $stmtlaudot->fetch();
$latepct = (isset($resultlaudot['latepct'])) ? round($resultlaudot['latepct'],2) : 0 ;
$udpct =  (isset($resultlaudot['udpct'])) ? round($resultlaudot['udpct'],2) : 0 ;
$otpct = (isset($resultlaudot['otpct'])) ? round($resultlaudot['otpct'],2) : 0 ;

//wfh login
$spquery = "SELECT (CASE when status = 1 then 'PENDING'
when   status = 2 then 'APPROVED'
when   status = 3 then 'REJECTED'
when   status = 4 then 'CANCELLED' ELSE 'N/A' END) as stats,a.rowid  as wfhid,a.emp_code as empcd,b.rowid as attid,* 
FROM dbo.tr_workfromhome a
left join employee_attendance b
on RIGHT(A.emp_code, LEN(A.emp_code) - 3) = b.emp_code
and a.wfh_date = b.punch_date where a.emp_code = :emp_code and a.wfh_date = :wfh_date and status = 2 ORDER BY wfh_date DESC";
$spparam = array(':emp_code' => $empCode,':wfh_date' => date('Y-m-d'));
$spstmt =$connL->prepare($spquery);
$spstmt->execute($spparam);
$spresult = $spstmt->fetch();
$wfhd =  (isset($spresult['wfh_date'])) ? "'".$spresult['wfh_date']."'" : null ;
$wfhid = (isset($spresult['wfhid'])) ? "'".$spresult['wfhid']."'" : '' ;
$wfhempcd = (isset($spresult['empcd'])) ? "'".$spresult['empcd']."'" : '' ;
$attid = (isset($spresult['attid'])) ? "'".$spresult['attid']."'" : '' ;
}

?>
<script type="text/javascript">

    function timeInModal(lvid,empcd){

        $('#timeInModal').modal('toggle');
        document.getElementById('empcd').value =  empcd;   
        document.getElementById('lvid').value =  lvid;   

    }

    function timeIn()
    {

        var url = "../wfhome/timeInProcess.php";  
        var wfhid = document.getElementById("lvid").value; 
        var emp_code = document.getElementById("empcd").value;
        var wfh_output = document.getElementById("wfh_output").value;  

        if(wfh_output){
            swal({
              title: "Are you sure?",
              text: "You want to time in now?",
              icon: "success",
              buttons: true,
              dangerMode: true,
          })
            .then((timeIn) => {
              if (timeIn) {
                $.post (
                    url,
                    {
                        choice: 1,
                        wfhid:wfhid,
                        emp_code:emp_code,
                        wfh_output:wfh_output
                    },
                    function(data) { 
                        console.log(data);
                        swal({
                            title: "Success!", 
                            text: "Successfully time in!", 
                            type: "info",
                            icon: "info",
                        }).then(function() {
                            location.href = '../pages/employee.php';
                        });  
                    }
                    );
            } else {
                swal({text:"You cancel your time in!",icon:"warning"});
            }
        });
        }else{
            swal({text:"Kindly fill up blank details!",icon:"warning"});
        }


    }    

    function timeOutModal(lvid,empcd,attid){

        $('#timeOutModal').modal('toggle');
        document.getElementById('empcd').value =  empcd;   
        document.getElementById('lvid').value =  lvid;   
        document.getElementById('attid').value =  attid;   

    }

    function timeOut()
    {

        var url = "../wfhome/timeInProcess.php";  
        var wfhid = document.getElementById("lvid").value; 
        var emp_code = document.getElementById("empcd").value;
        var wfh_output2 = document.getElementById("wfh_output2").value;  
        var wfh_percentage = document.getElementById("wfh_percentage").value;  
        var attid = document.getElementById("attid").value;  


        if(!wfh_output2 || !wfh_percentage){
          swal({text:"Kindly fill up blank details!",icon:"warning"});  
      }else{
        swal({
          title: "Are you sure?",
          text: "You want to time out now?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((timeIn) => {
          if (timeIn) {
            $.post (
                url,
                {
                    choice: 2,
                    wfhid:wfhid,
                    emp_code:emp_code,
                    wfh_output2:wfh_output2,
                    wfh_percentage:wfh_percentage,
                    attid:attid

                },
                function(data) { 
                    console.log(data);
                    swal({
                        title: "Success!", 
                        text: "Successfully time out!", 
                        type: "info",
                        icon: "info",
                    }).then(function() {
                        location.href = '../wfhome/wfh_app_view.php';
                    });  
                }
                );
        } else {
            swal({text:"You cancel your time out!",icon:"warning"});
        }
    });                
    }

}    
</script>
<link rel="stylesheet" href="../css/fullcalendar/fullcalendar.min.css">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<body id="page-top" style="background-color: #f8f9fc;overflow: auto;">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                <div class="modal fade" id="timeInModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title bb" id="popUpModalTitle">Time-In <i class="fas fa-play"></i></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times; </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div>                  

                                <input type="text" name="lvid" id="lvid" hidden>
                                <input type="text" name="empcd" id="empcd" hidden>
                                <div class="form-row align-items-center mb-2">
                                    <div class="col-md-2 d-inline">
                                        <label for="">Expected Output:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-10 d-inline">
                                     <textarea class="form-control inputtext" id="wfh_output" name="wfh_output" rows="4" cols="50" ></textarea> 
                                 </div>
                             </div>

                         </div>
                     </div>


                     <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                        <button type="button" class="btn btn-success" id="Submit" onclick="timeIn()" ><i class="fas fa-check-circle"></i> Submit</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- end timein wfh -->

        <!-- start time out wfh  -->

        <div class="modal fade" id="timeOutModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">Time-Out <i class="fas fa-hand-paper"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                <div class="modal-body">
                    <div>                  
                        <input type="text" name="lvid" id="lvid" hidden>
                        <input type="text" name="empcd" id="empcd" hidden>
                        <input type="text" name="attid" id="attid" hidden>
                        <div class="form-row align-items-center mb-2">
                            <div class="col-md-2 d-inline">
                                <label for="">Output:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <textarea class="form-control inputtext" id="wfh_output2" name="wfh_output2" rows="4" cols="50" ></textarea>                                        
                            </div>
                        </div>
                        <div class="form-row align-items-center mb-2">
                           <div class="col-md-2 d-inline">
                            <label for="">Percentage:</label><span class="req">*</span>
                        </div>
                        <div class="col-md-2 d-inline">
                            <input type="number" id="wfh_percentage" name="wfh_percentage" class="form-control inputtext" min="10" max="100" step="10" onkeypress="return false" placeholder="0">
                        </div>
                        <div class="col-md-1 d-inline">
                            <label for="">%</label>
                        </div> 
                    </div>                            

                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                <button type="button" class="btn btn-success" id="Submit" onclick="timeOut()" ><i class="fas fa-check-circle"></i> Submit</button>
            </div>

        </div>
    </div>
</div>

<!-- end time out wfh -->
<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 pt-5">
     <h1><br></h1>
 </div>
</div>            

<!-- Content Row -->
<div class="row">  
    <!-- All Act Emp-->
    <div class="col-xl-2 col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-capitalize mb-1">EMPLOYEE HANDBOOK
                            <?php echo date("Y") ?> </div>
                            <div class="row no-gutters align-items-center">
                                <a href="../uploads/COD_HANDBOOK.pdf" target="_blank">
                                    <button type="button" class="btn btn-outline-primary">View Handbook
                                    </button>
                                </a>
                                <span class="text-muted small pt-2 ps-1">Handbook updated 08.05.22</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>                        

           <!--Total Work Days-->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-capitalize mb-1">Total Work Days <br><?php echo date("F Y") ?> 
                        </div>
                        <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totwork; ?> day/s</div>
                        </div>
                    </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

              <!--Total OT-->
            <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-capitalize mb-1">Total Overtime <br><?php echo date("F Y") ?> 
                        </div>
                        <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totot; ?> hr/s</div>
                        </div>
                    </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!--UNDERTIME-->
        <div class="col-xl-2 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-capitalize mb-1">Total Undertime <br><?php echo date("F Y") ?> 
                        </div>
                        <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totut; ?> min/s</div>
                        </div>
                    </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-bell-slash fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Male-->
    <div class="col-xl-2 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-capitalize mb-1">Total Lates <br><?php echo date("F Y") ?> 
                    </div>
                    <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totlate; ?> min/s</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-stopwatch fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- time in -->
<div class="col-xl-2 col-md-6 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-capitalize mb-1">Today's Time-In: <br><?php echo date("F d, Y");  if(isset($wfhd)){echo': WFH';}   ?> 
                </div>
                <div class="row no-gutters align-items-center">
                    <div class="col-auto">
                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $timeinf; ?>
                        <?php  

                        if(isset($wfhd)){
                            if(empty($spresult['timein']) && empty($spresult['timeout'])){
                                echo'
                                <button type="button"  class="btn btn-primary" onclick="timeInModal('.$wfhid.','.$wfhempcd.')" title="Time In">
                                <i class="fas fa-play"> Time-In </i>
                                </button>                            
                                </td>';
                            }else if(!empty($spresult['timein']) && empty($spresult['timeout'])){
                                echo'<button type="button"  class="btn btn-danger" onclick="timeOutModal('.$wfhid.','.$wfhempcd.','.$attid.')" title="Time Out">
                                <i class="fas fa-hand-paper"> Time-Out </i>
                                </button>                            
                                </td>';

                            }
                        }else{
                        }   
                        ?>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-auto py-2">
            <i class="fas fa-clock fa-2x text-gray-300"></i>
        </div>
    </div>
                <span class="text-muted small pt-2 ps-1">Estimated Time-Out:</span>
                <?php 

                if($timeinf <> 'NO TIME-IN' && date('H', strtotime($yresult['timein'])) < 7 ){
                    echo'<span class="text-success small pt-1 fw-bold">05:30 PM</span> ';
                }else if($timeinf <> 'NO TIME-IN' && date('H', strtotime($yresult['timein'])) > 10 ){
                    echo'<span class="text-success small pt-1 fw-bold">08:30 PM</span> ';
                }else{
                    echo'<span class="text-success small pt-1 fw-bold">'.$timeoutf.'</span> ';
                }
                
                ?>
            </div>
        </div>
    </div>   
</div>     
<!-- Content Row -->

<div class="row">
    <div class="col-md-4">
    <div class="card">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <h5 class="m-0 font-weight-bold">Legend: 
                <div class="onsite"></div><i class="ilgnd">Onsite</i>
                <div class="wfhome"></div><i class="ilgnd">WFH</i>
                <div class="obsched"></div><i class="ilgnd">OB</i>
                <div class="leaveschd"></div><i class="ilgnd">Leave</i>
                <div class="holisched"></div><i class="ilgnd">Holiday</i>
                <div class="noinnout"></div><i class="ilgnd">No In/No Out</i>
            </h5>
              
        </div>
    </div>
      <div class="card mt-2">
        <div class="card-body">
          <div id="calendar" class="full-calendar"></div>
        </div>
      </div>
    </div>
  <div class="col-md-3">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">EMPLOYEE'S LEAVE <i class="fas fa-suitcase fa-fw">
                        </i></h6>
        </div>
        <div class="card-body cdbody">
                  <div class="table-responsive">
                    <table class="table table-borderless">
                      <thead>
                        <tr>
                          <th class="ps-0  pb-2 border-bottom">Leave Type</th>
                          <th class="border-bottom pb-2">Balance</th>
                          <th class="border-bottom pb-2">Pending</th>
                          <th class="border-bottom pb-2">Used</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="ps-0">Vacation Leave</td>
                          <td><p class="mb-0"><span class="font-weight-bold me-2"><?php echo $earned_vl;?></span></p></td>
                          <td class="text-muted"><?php echo $pending_vl; ?></td>
                          <td class="text-muted"><?php echo $used_vl?>: with Pay<br>
                                                <?php echo $used_vlw?>: without Pay<br></td>
                        </tr>
                        <tr>
                          <td class="ps-0">Sick Leave</td>
                          <td><p class="mb-0"><span class="font-weight-bold me-2"><?php echo $earned_sl;?></span></p></td>
                          <td class="text-muted"><?php echo $pending_sl; ?></td>
                          <td class="text-muted"><?php echo $used_sl?> : with Pay<br>
                                                <?php echo $used_slw?> : without Pay<br></td>
                        </tr>
                        <tr>
                          <td class="ps-0">Floating Leave</td>
                          <td><p class="mb-0"><span class="font-weight-bold me-2"><?php echo $earned_fl;?></span></p></td>
                          <td class="text-muted"><?php echo $pending_fl; ?></td>
                          <td class="text-muted"><?php echo $used_fl?> : with Pay<br></td>
                        </tr>                        
                      </tbody>
                    </table>
                  </div>
                </div>
    </div>
</div>      
    <div class="col-md-3">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">BIRTHDAY CELEBRANTS : <?php echo strtoupper(date("M Y")) ?>  <i class="fas fa-birthday-cake"></i>  </h6>
        </div>
        <!-- Card Body -->
        <div class="card-body cdbody">
              <?php  
                if($resultu){
                    $ppic = (isset($resultu['emp_pic_loc'])) ? $resultu['emp_pic_loc'] : 'nophoto.jpg' ;
                    do { 
                echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:30px;height:30px;" src="../img/'.$ppic.'"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>
                      '.$resultu['lastname'].', '.$resultu['firstname'].'</b><br>'.$resultu['department'].'<br>'.date('F d', strtotime($resultu['birthdate'])).'  
                    </div>
                  </div><hr style="margin:5;">  ';                        
                        } while ($resultu = $stmtu->fetch());
                     }else{
                    echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/iconx.png"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>No birthday celebrant this month.</b>
                    </div>
                  </div><hr style="margin:5;">  ';
                     }
                ?>             
            </div>
        </div>
    </div>
<div class="col-md-2">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">ANNOUNCEMENTS <?php echo strtoupper(date("Y")) ?> <i class="fas fa-paste"></i></h6>
        </div>
        <div class="card-body cdbody">
              <?php  
                if($resultan){
                    do { 
                echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><a href="../uploads/'.$resultan['filename'].'" target="_blank"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/expdf.png"></a></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><a href="../uploads/'.$resultan['filename'].'" target="_blank"><b>
                      '.$resultan['description'].'</b></a>  
                    </div>
                  </div><hr style="margin:5;">  ';
                        
                        } while ($resultan = $stmtan->fetch());
                     }else{
                    echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/iconx.png"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>No announcement recorded.</b>
                    </div>
                  </div><hr style="margin:5;">  ';
                     }                                                        
                ?>            
        </div>
    </div>
</div>     
                     
                    </div>

                </div>

            </div>

        </div>

    </div>


    <?php include('../_footer.php');  ?>
    <script type="text/javascript">  
     let holidays = <?php echo json_encode($totalVal) ;?>;
    </script>
    <script src="../js/moment/moment.min.js"></script>
    <script src="../js/fullcalendar/fullcalendar.min.js"></script>
    <script src="../js/calendar/calendar.js"></script>
    
</body>

</html>