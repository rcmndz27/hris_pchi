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

//NO TIME-IN
$queryl = "SELECT lastname+','+firstname as [fullname],emp_pic_loc from employee_profile where ranking = 1 and emp_status = 'Active' and badgeno not in (SELECT a.emp_code from employee_attendance a left join employee_profile b on a.emp_code = b.badgeno where a.punch_date = CONVERT(date, GETDATE()) and b.emp_status = 'Active')";
$stmtl =$connL->prepare($queryl);
$stmtl->execute();
$resultl = $stmtl->fetch();    

//ANNOUNCEMENT
$queryan = "SELECT * from logs_events where status = 1 or (date_to >= DATEADD(dd, 0, DATEDIFF(dd, 0, GETDATE())) and status = 0) ORDER BY rowid desc";
$stmtan =$connL->prepare($queryan);
$stmtan->execute();
$resultan = $stmtan->fetch();    

//LATES TODAY
$queryp = 'EXEC dbo.xp_attendance_portal_late_admin';
$stmtp =$connL->prepare($queryp);
$stmtp->execute();
$resultp = $stmtp->fetch();    

//SCHED TODAY
$queryy = 'EXEC dbo.xp_attendance_portal_schedtoday';
$stmty =$connL->prepare($queryy);
$stmty->execute();
$resulty = $stmty->fetch();       

//GET COMPANY
$query = "SELECT * from employee_profile where emp_code = :empcode";
$stmt =$connL->prepare($query);
$param = array(":empcode" => $empCode);
$stmt->execute($param);
$result = $stmt->fetch();
$cmp = $result['company'];
$bdno = $result['badgeno'];
$subemp = strlen($cmp);

//GET LAST TIME IN
$yquery = "SELECT timein from employee_attendance where emp_code = :empcode and punch_date = :todate";
$ystmt =$connL->prepare($yquery);
$yparam = array(":empcode" => $bdno,":todate" => date('Y-m-d'));
$ystmt->execute($yparam);
$yresult = $ystmt->fetch();
$timeinf =  (isset($yresult['timein']) ? date('h:i A', strtotime($yresult['timein'])) : 'NO TIME-IN');
// $start = $yresult['timein'];
$timeoutf = (isset($yresult['timein']) ? date('h:i A',strtotime('+10 hour 30 minute',strtotime($yresult['timein']))): 'n/a');

// GET ACTIVE EMPLOYEES
$qry = "SELECT count(emp_code) as empcnt,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as empcntpct  from employee_profile where emp_status = 'Active'" ;
$stm =$connL->prepare($qry);
$stm->execute();
$resul = $stm->fetch();
$empcnt = (isset($resul['empcnt'])) ? $resul['empcnt'] : '0' ;
$empcntpct = (isset($resul['empcntpct'])) ? $resul['empcntpct'] : '0' ;

// GET INACTIVE EMPLOYEES
$qrys = "SELECT count(emp_code) as empcnti,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as empcntipct from employee_profile where emp_status <> 'Active'" ;
$stms =$connL->prepare($qrys);
$stms->execute();
$resuls = $stms->fetch();
$empcnti = (isset($resuls['empcnti'])) ? $resuls['empcnti'] : '0' ;
$empcntipct = (isset($resuls['empcntipct'])) ? $resuls['empcntipct'] : '0' ;

// MALES
$qryt = "SELECT count(emp_code) as male,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as malepct
  FROM employee_profile where sex = 'Male'" ;
$stmt =$connL->prepare($qryt);
$stmt->execute();
$result = $stmt->fetch();
$male = (isset($result['male'])) ? $result['male'] : '0' ;
$malepct = (isset($result['malepct'])) ? $result['malepct'] : '0' ;

// FEMALES
$qryst = "SELECT count(emp_code) as female,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as femalepct from employee_profile where sex = 'Female' " ;
$stmst =$connL->prepare($qryst);
$stmst->execute();
$resulst = $stmst->fetch();
$female = (isset($resulst['female'])) ? $resulst['female'] : '0' ;
$femalepct = (isset($resulst['femalepct'])) ? $resulst['femalepct'] : '0' ;

// REGULAR
$qrysta = "SELECT count(emp_code) as reg,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as regpct from employee_profile where emp_type = 'Regular' " ;
$stmsta =$connL->prepare($qrysta);
$stmsta->execute();
$resulsta = $stmsta->fetch();
$reg = (isset($resulsta['reg'])) ? $resulsta['reg'] : '0' ;
$regpct = (isset($resulsta['regpct'])) ? $resulsta['regpct'] : '0' ;

// PROBATIONARY
$qrystab = "SELECT count(emp_code) as prob,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as probpct from employee_profile where emp_type = 'Probationary' " ;
$stmstab =$connL->prepare($qrystab);
$stmstab->execute();
$resulstab = $stmstab->fetch();
$prob = (isset($resulstab['prob'])) ? $resulstab['prob'] : '0' ;
$probpct = (isset($resulstab['probpct'])) ? $resulstab['probpct'] : '0' ;


// PROJECT BASED
$rystab = "SELECT count(emp_code) as proj,round(count(emp_code) * 100 / (SELECT count(*) from employee_profile),0) as projpct from employee_profile where emp_type = 'Project Based' " ;
$tmstab =$connL->prepare($rystab);
$tmstab->execute();
$esulstab = $tmstab->fetch();
$proj = (isset($esulstab['proj'])) ? $esulstab['proj'] : '0' ;
$projpct = (isset($esulstab['projpct'])) ? $esulstab['projpct'] : '0' ;

// LEAVE
$rysta = "SELECT sum(actl_cnt) as leave from tr_leave" ;
$tmsta =$connL->prepare($rysta);
$tmsta->execute();
$esulsta = $tmsta->fetch();
$leave = (isset($esulsta['leave'])) ? $esulsta['leave'] : '0' ;

// OVERTIME
$frysta = "SELECT CAST(sum(ot_req_hrs) AS INT) as ot from tr_overtime" ;
$ftmsta =$connL->prepare($frysta);
$ftmsta->execute();
$fesulsta = $ftmsta->fetch();
$ot = (isset($fesulsta['ot'])) ? $fesulsta['ot'] : '0' ;

// WORK FROM HOME
$fryst = "SELECT CAST(count(wfh_date) AS INT) as wfh from tr_workfromhome" ;
$ftmst =$connL->prepare($fryst);
$ftmst->execute();
$fesulst = $ftmst->fetch();
$wfh = (isset($fesulst['wfh'])) ? $fesulst['wfh'] : '0' ;

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


//rank and file
$queryv = "SELECT count(emp_code) as cnt_rank from employee_profile where ranking = 1 and emp_status = 'Active' ";
$stmtv =$connL->prepare($queryv);
$stmtv->execute();
$resultv = $stmtv->fetch(); 
$cnt_rank = (isset($resultv['cnt_rank'])) ? $resultv['cnt_rank'] : 0 ;

//supervisory
$queryfl = "SELECT count(emp_code) as cnt_sup from employee_profile where ranking = 2 and emp_status = 'Active' ";
$stmtfl =$connL->prepare($queryfl);
$stmtfl->execute();
$resultfl = $stmtfl->fetch(); 
$cnt_sup = (isset($resultfl['cnt_sup'])) ? $resultfl['cnt_sup'] : 0 ;

//managerial
$querysw = "SELECT count(emp_code) as cnt_man from employee_profile where ranking = 3 and emp_status = 'Active'";
$stmtsw =$connL->prepare($querysw);
$stmtsw->execute();
$resultsw = $stmtsw->fetch();
$cnt_man = (isset($resultsw['cnt_man'])) ? $resultsw['cnt_man'] : 0 ;

//executive
$queryvw = "SELECT count(emp_code) as cnt_exe from employee_profile where ranking = 4 and emp_status = 'Active'";
$stmtvw =$connL->prepare($queryvw);
$stmtvw->execute();
$resultvw = $stmtvw->fetch();  
$cnt_exe = (isset($resultsw['cnt_exe'])) ? $resultsw['cnt_exe'] : 0 ;

$totperlvl = $cnt_rank+$cnt_sup+$cnt_man+$cnt_exe;
$pct_rank = round($cnt_rank*100/$totperlvl);
$pct_sup = round($cnt_sup*100/$totperlvl);
$pct_man = round($cnt_man*100/$totperlvl);
$pct_exe = round($cnt_exe*100/$totperlvl);

//active
$queryact = "SELECT count(emp_code) as cnt_act from employee_profile where emp_status = 'Active' ";
$stmtact =$connL->prepare($queryact);
$stmtact->execute();
$resultact = $stmtact->fetch(); 
$cnt_act = (isset($resultact['cnt_act'])) ? $resultact['cnt_act'] : 0 ;

//resigned
$queryres = "SELECT count(emp_code) as cnt_res from employee_profile where emp_status = 'Resigned' ";
$stmtres =$connL->prepare($queryres);
$stmtres->execute();
$resultres = $stmtres->fetch(); 
$cnt_res = (isset($resultres['cnt_res'])) ? $resultres['cnt_res'] : 0 ;

//Separated
$querysep = "SELECT count(emp_code) as cnt_sep from employee_profile where emp_status = 'Separated'";
$stmtsep =$connL->prepare($querysep);
$stmtsep->execute();
$resultsep = $stmtsep->fetch();
$cnt_sep = (isset($resultsep['cnt_sep'])) ? $resultsep['cnt_sep'] : 0 ;

//Terminated
$queryter = "SELECT count(emp_code) as cnt_ter from employee_profile where emp_status = 'Terminated'";
$stmtter =$connL->prepare($queryter);
$stmtter->execute();
$resultter = $stmtter->fetch();  
$cnt_ter = (isset($resultter['cnt_ter'])) ? $resultter['cnt_ter'] : 0 ;

$totperstat = $cnt_act+$cnt_res+$cnt_sep+$cnt_ter;
$pct_act = round($cnt_act*100/$totperstat);
$pct_res = round($cnt_res*100/$totperstat);
$pct_sep = round($cnt_sep*100/$totperstat);
$pct_ter = round($cnt_ter*100/$totperstat);



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

if($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President') {

}else{
echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
echo "window.location.href = '../index.php';";
echo "</script>";
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
location.href = '../pages/admin.php';
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
<script type='text/javascript' src='../pages/add_ancmnt.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<link rel="stylesheet" href="../css/fullcalendar/fullcalendar.min.css">
<link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
</head>
<body id="page-top" style="background-color: #f8f9fc;height:100%;overflow-y:auto;overflow-x: hidden;">

<!-- Page Wrapper -->
<div id="wrapper">

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
<div id="content">


<div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">ADD ANNOUNCEMENT <i class="fas fa-paste fa-fw">
                        </i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                <div class="modal-body">                    

                        <div class="form-row mb-2">
                            <div class="col-md-2 d-inline">
                                <label for='subject'>Subject:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <input class="form-control inputtext" id="description" type="text" placeholder="Memo/Announcement Subject">
                            </div>
                        </div>
                        <div class="form-row mb-2">
                            <div class="col-md-2 d-inline">
                                <label for='status'>Status:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-4 d-inline">
                                <select type="select" class="form-select" id="status" name="status" >
                                    <option value="1">Permanent</option>
                                    <option value="0">Temporary</option>
                                </select>   
                            </div>
                        </div>                        
                        <div class="form-row align-items-center mb-2" id="dateancmnt">
                            <div class="col-md-2 d-inline">
                                <label for="datefrom" id="dfrom">Date From:<span class="req">*</span></label>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="date" id="date_from" name="date_from" class="form-control inputtext" value="<?php echo date('h:i a');?>">
                            </div>
                            <div class="col-md-1 d-inline">
                                <label for="" id="dto"> To: <span class="req">*</span></label>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="date" id="date_to" name="date_to" class="form-control inputtext">
                            </div>
                        </div>
                         <div class="row pb-2">
                            <div class="col-md-2">
                                <label for="Attachment" id="LabelAttachment">Attachment:<span class="req">*</span></label>
                            </div>
                            <div class="col-md-10">
                                <input type="file" name="medicalfiles" id="medicalfiles" accept=".pdf" onChange="GetMedFile()">
                            </div>
                        </div>                                                                                            
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="Submit" onclick="uploadFile();"  ><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>

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
                </div>
            </div>
              <div class="row">
                <div class="col-md-12 pt-5">
                </div>
            </div>                    

           <div class="row">  
    <!-- All Act Emp-->
    <div class="col-xl-2 col-md-6 mb-4">
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
                    <!-- Content Row -->

<div class="row">


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">NO TIME-IN : <?php echo strtoupper(date("F d, Y")) ?> <i class="fas fa-clock"></i></h6>
        </div>
        <!-- Card Body -->

        <div class="card-body cdbody">
              <?php  
                if($resultl){
                    $ppic = (isset($resultl['emp_pic_loc'])) ? $resultl['emp_pic_loc'] : 'nophoto.jpg' ;
                    do { 
                echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/'.$ppic.'"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>
                      '.$resultl['fullname'].'</b>  
                    </div>
                  </div><hr style="margin:5;">  ';
                        
                        } while ($resultl = $stmtl->fetch());
                     }                                                        
                ?>            
        </div>
        </div>
    </div>      

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">SCHEDULE TODAY: <?php echo strtoupper(date("F d, Y")) ?> <i class="fas fa-calendar"></i>  </h6>
        </div>
        <!-- Card Body -->

        <div class="card-body cdbody">
              <?php  
                if($resulty){
                    do { 
                    $ppic = (isset($resulty['emp_pic_loc'])) ? $resulty['emp_pic_loc'] : 'nophoto.jpg' ;
                    $fname =$resulty['fullname'] ;                        
                echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/'.$ppic.'"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>
                     '.$fname.'</b><br>Schedule: '.$resulty['remarks'].'  
                    </div>
                  </div><hr style="margin:5;">  ';
                        
                        } while ($resulty = $stmty->fetch());
                     }else{
                    echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/iconx.png"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>No filed leave,work from home and ob.</b>
                    </div>
                  </div><hr style="margin:5;">  ';
                     }                                                        
                ?>                        
        </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">TOTAL LATES : <?php echo strtoupper(date("M Y")) ?> <i class="fas fa-calendar"></i>  </h6>
        </div>
        <!-- Card Body -->
        <div class="card-body cdbody">
              <?php  
                if($resultp){
                    $ppic = (isset($resultp['emppic'])) ? $resultp['emppic'] : 'nophoto.jpg' ;

                    do { 
                echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:30px;height:30px;" src="../img/'.$ppic.'"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>
                      '.$resultp['fullname'].'</b><br>Late: '.round($resultp['tot_late']*60).' Min/s 
                    </div>
                  </div><hr style="margin:5;">  ';
                        
                        } while ($resultp = $stmtp->fetch());
                     }else{
                    echo ' <div class="row">
                    <div class="col-sm-1">
                      <h6 class="mb-0"><img class="rounded-circle" style="width:25px;height:25px;" src="../img/iconx.png"></h6>
                    </div>
                    <div class="col-sm-9 text-secondary"><b>No lates recorded for this month.</b>
                    </div>
                  </div><hr style="margin:5;">  ';
                     } 
                                                        
                ?>            
        </div>
        </div>
    </div>
  

    <div class="col-xl-3 col-md-6 mb-4">
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
 </div> <!--end of row -->

 <div class="row">
    <div class="col-md-3">
    <div class="card">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold">Legend: 
                <div class="onsite"></div><i class="ilgnd">Onsite</i>
                <div class="wfhome"></div><i class="ilgnd">WFH</i>
                <div class="obsched"></div><i class="ilgnd">OB</i>
                <div class="leaveschd"></div><i class="ilgnd">Leave</i>
                <div class="holisched"></div><i class="ilgnd">Holiday</i>
                <div class="noinnout"></div><i class="ilgnd">No In/No Out</i>
            </h6>
              
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
            <h6 class="m-0 font-weight-bold text-primary">ANNOUNCEMENTS <?php echo strtoupper(date("Y")) ?> <i class="fas fa-paste"></i></h6>
            <button class="btn btn-primary btn-sm"  id="addAncmnt"><i class="fas fa-plus-circle"></i> Announcement</button>
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

<div class="col-md-3">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">NO. OF EMPLOYEES PER LEVEL <i class="fas fa-users"></i></h6>
        </div>
<div class="table-responsive mb-3 mb-md-0 mt-3">
  <table class="table table-borderless report-table">
    <tr>
      <td class="text-muted">Rank in File</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $pct_rank; ?>%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_rank; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Supervisory</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $pct_sup; ?>%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_sup; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Managerial</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $pct_man; ?>%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_man; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Executive</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-secondary" role="progressbar" style="width: <?php echo $pct_exe; ?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_exe; ?></h5></td>
    </tr>
  </table>
</div>
    </div>
</div>   

<div class="col-md-3">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">NO. OF EMPLOYEES PER STATUS <i class="fas fa-users"></i></h6>
        </div>
<div class="table-responsive mb-3 mb-md-0 mt-3">
  <table class="table table-borderless report-table">
    <tr>
      <td class="text-muted">Active</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $pct_act; ?>%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_act; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Resigned</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $pct_res; ?>%" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_res; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Separated</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pct_sep; ?>%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_sep; ?></h5></td>
    </tr>
    <tr>
      <td class="text-muted">Terminated</td>
      <td class="w-100 px-0">
        <div class="progress progress-md mx-4">
          <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $pct_ter; ?>%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </td>
      <td><h5 class="font-weight-bold mb-0"><?php echo $cnt_ter; ?></h5></td>
    </tr>
  </table>
</div>
    </div>
</div>   



</div><!--end of row -->

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->


<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<?php include('../_footer.php');  ?>


    <script type="text/javascript">  
     let holidays = <?php echo json_encode($totalVal) ;?>;
    </script>
    <script src="../js/moment/moment.min.js"></script>
    <script src="../js/fullcalendar/fullcalendar.min.js"></script>
    <script src="../js/calendar/calendar.js"></script>
    
</body>

</html>