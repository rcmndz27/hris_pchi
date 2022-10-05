<?php
    session_start();

    if (empty($_SESSION['userid']))
    {
        include_once('../loginfirst.php');
        exit();
    }
    else
    {
        include('../_header.php');
        include('../leave/leaveApplication.php');

        $leaveApp = new LeaveApplication(); 
        $leaveApp->SetLeaveApplicationParams($empCode,$empType);

        $query = 'SELECT * FROM dbo.employee_profile WHERE emp_code = :empcode ';
        $param = array(":empcode" => $_SESSION['userid']);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $r = $stmt->fetch();
        $e_req = $r['emailaddress'];
        $n_req = $r['firstname'].' '.$r['lastname'];


        $aquery = 'SELECT * FROM dbo.employee_profile WHERE emp_code = :empcode ';
        $aparam = array(":empcode" => $r['reporting_to']);
        $astmt =$connL->prepare($aquery);
        $astmt->execute($aparam);
        $ar = $astmt->fetch();
        $e_appr = $ar['emailaddress'];
        $n_appr = $ar['firstname'].' '.$ar['lastname'];        

        $querys = 'SELECT * FROM dbo.employee_leave WHERE emp_code = :empcode ';
        $params = array(":empcode" => $_SESSION['userid']);
        $stmts =$connL->prepare($querys);
        $stmts->execute($params);
        $rs = $stmts->fetch();

        $queryf = "SELECT date_from from dbo.tr_leave WHERE emp_code = :empcode and  approved in (1,2)";
        $paramf = array(":empcode" => $_SESSION['userid']);
        $stmtf =$connL->prepare($queryf);
        $stmtf->execute($paramf);
        $rsf = $stmtf->fetch();

        if(!empty($rsf)){
            $totalVal = [];
            do { 
                array_push($totalVal,$rsf['date_from']);
                
            } while ($rsf = $stmtf->fetch());
        }else{
            $totalVal = [];
        }

    }    
?>



<script type="text/javascript">



    function viewLeaveModal(datefl,leavedesc,leavetyp,datefr,dateto,remark,appdays,appr_oved,actlcnt,appr_over){

        $('#viewLeaveModal').modal('toggle');
        document.getElementById('datefl').value =  datefl;   
        document.getElementById('leavedesc').value =  leavedesc;  
        document.getElementById('leavetyp').value =  leavetyp;  
        document.getElementById('datefr').value =  datefr;  
        document.getElementById('dateto').value =  dateto;   
        document.getElementById('remark').value =  remark;  
        document.getElementById('appdays').value =  appdays;  
        document.getElementById('appr_oved').value =  appr_oved;  
        document.getElementById('actlcnt').value =  actlcnt;        
        document.getElementById('appr_over').value =  appr_over;        
}

    function viewLeaveHistoryModal(lvlogid)
    {
       $('#viewLeaveHistoryModal').modal('toggle');
        var url = "../leave/leave_viewlogs.php";
        var lvlogid = lvlogid;

        $.post (
            url,
            {
                _action: 1,
                lvlogid: lvlogid             
            },
            function(data) { $("#contents2").html(data).show(); }
        );
    }

        function myFunction() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("leaveList");
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

function cancelLeave(lvid,empcd)
{

 var url = "../leave/cancelLeaveProcess.php";  
 var leaveid = lvid;   
 var emp_code = empcd;   
    swal({
          title: "Are you sure?",
          text: "You want to cancel this leave?",
          icon: "success",
          buttons: true,
          dangerMode: true,
        })
        .then((cnclLv) => {
          if (cnclLv) {
            $.post (
                    url,
                    {
                        choice: 1,
                        leaveid:leaveid,
                        emp_code:emp_code

                    },
                    function(data) { 
                            swal({
                            title: "Oops!", 
                            text: "Successfully cancelled leave!", 
                            type: "info",
                            icon: "info",
                            }).then(function() {
                                document.getElementById('st'+leaveid).innerHTML = 'CANCELLED';
                                document.querySelector('#clv').remove();
                            });  
                    }
                );
          } else {
            swal({text:"You stop the cancellation of your leave.",icon:"error"});
          }
        });

}

</script>
<link rel="stylesheet" type="text/css" href="../leave/leave_view.css">
<script type='text/javascript' src='../leave/leaveApplication.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="../leave/moment2.min.js"></script>
<script src="../leave/moment-range.js"></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
          <h1>LEAVE APPLICATION</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-suitcase fa-fw'>
                        </i>&nbsp;LEAVE APPLICATION</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $leaveApp->GetLeaveSummary(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="applyLeave"><i class="fas fa-plus-circle"></i> APPLY LEAVE</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $leaveApp->GetLeaveHistory(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD LEAVE  -->
    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title bb" id="popUpModalTitle">APPLY LEAVE <i class="fas fa-suitcase ">
                        </i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                             <div class="row">
                                <div class=col-md-2>
                                    <label for="">Approver:</label><span class="req">*</span>
                                </div>
                                <div class="col-md-10">                 
                                    <h5><?php  echo $n_appr; ?></h5>
                                </div>
                            </div> 
                <input type="text" name="e_req" id="e_req" value="<?php echo $e_req; ?>" hidden>  
                <input type="text" name="n_req" id="n_req" value="<?php echo $n_req; ?>" hidden>
                <input type="text" name="e_appr" id="e_appr" value="<?php echo $e_appr; ?>" hidden>
                <input type="text" name="n_appr" id="n_appr" value="<?php  echo $n_appr; ?>" hidden>
                     <?php $leaveApp->GetLeaveType(); ?>
                        <div id="leavepay">                           
                            <div class="row">
                                <div class=col-md-2>
                                    <label for="">Leave Pay:</label><span class="req">*</span>
                                </div>
                                <div class="col-md-10">                                
                                        <div class="form-check form-check-inline" id="wpay" id="wpay">
                                            <input class="form-check-input" type="radio" name="leavepay"
                                                id="leave_pay1" value="WithPay" checked>
                                            <label class="form-check-label" for="withpay">With Pay</label>
                                        </div>
                                       <div class="form-check form-check-inline" id="woutpay">
                                        <input class="form-check-input" type="radio" name="leavepay"
                                            id="leave_pay2" value="WithoutPay">
                                        <label class="form-check-label" for="withoutpay">Without Pay</label>
                                        </div>
                                </div>
                            </div>
                        </div> 
                        <input type='text' id='emptype' name='emptype' class='form-control' value=<?php echo $r['emp_type']; ?> hidden>                                                                   
                                <?php 
                                $emp_type = $r['emp_type'];
                                $sl = isset($rs['earned_sl']) ? $rs['earned_sl'] : 0; 

                                if($emp_type == 'Regular'){
                                echo'<div id="sickleavebal">
                                            <div class="form-row align-items-center mb-2">
                                               <div class="col-md-2 d-inline">
                                                    <label for="">SL Balance:</label>
                                                </div>
                                                <div class="col-md-3 d-inline">
                                                        <input type="text" id="sick_leavebal" name="sickleavebal" class="form-control"
                                                        value='.$sl.' readonly>
                                                </div>
                                            </div>
                                    </div>';
                                }else{
                                }
                                 ?>                                            
                         <!-- sick leave advance filing  -->
                        <div id="advancefiling">
                            <div class="row">
                                <div class=col-md-2>
                                    <label for="">Advance Filing:</label>
                                </div>
                                <div class="col-md-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input advancesl" type="radio" name="advancesl"
                                            id="advancesl" value="yes">
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input advancesl" type="radio" name="advancesl"
                                            id="advancesl" value="no">
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <?php 
                        $emp_type = $r['emp_type'];
                        $vl =  isset($rs['earned_vl']) ? $rs['earned_vl'] : 0; 
                     
                       echo'<div id="vacleavebal">
                                    <div class="form-row align-items-center mb-2">
                                       <div class="col-md-2 d-inline">
                                            <label for="">VL Balance:</label>
                                        </div>
                                        <div class="col-md-3 d-inline">
                                                <input type="text" id="vac_leavebal" name="vacleavebal" class="form-control"
                                                value='.$vl.' readonly>
                                        </div>
                                    </div>
                                </div>';
                      

                        ?>

                    <div id="paternity">
                               <div class="form-row mb-2">
                                    <div class=col-md-2>
                                        <label for="">Civil Status:</label>
                                    </div>
                                    <div class="col-md-4">
                                          <div class="form-check form-check-inline">
                                        <?php 
                                            $cs = $r['civilstatus'];
                                            if($cs =='Single'){
                                           echo"<input type='text' id='civilstatus' name='civilstatus' class='form-control wr'
                                                value=".$cs." readonly>
                                                <h3 class='cstat'>*Please contact HRD to update your civil status*</h3>";

                                            }else{
                                                  echo"<input type='text' id='civilstatus' name='civilstatus' class='form-control'value=".$cs." readonly>";
                                            }
                                         ?>
                                    
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row align-items-center mb-2">
                                   <div class="col-md-2 d-inline">
                                        <label for="">Child Date of Birth:</label>
                                    </div>
                                        <div class="col-md-3 d-inline">
                                            <input type="date" id="dateBirth" name="dateBirth" class="form-control"
                                                value="<?php echo date('Y-m-d');?>">
                                        </div>
                                </div>
                     </div>

                           <div id="maternity">
                                <div class="form-row align-items-center mb-2">
                                   <div class="col-md-2 d-inline">
                                        <label for="">Delivery Date:</label>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="dateStartMaternity" name="dateStartMaternity" class="form-control"
                                            value="<?php echo date('Y-m-d');?>">
                                    </div>
                                </div>
                        </div>


                        <div id="specialviolence">
                                <div class="form-row align-items-center mb-2">
                                   <div class="col-md-2 d-inline">
                                        <label for="">Operation Date:</label>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="dateOfOperation" name="dateOfOperation" class="form-control"
                                            value="<?php echo date('Y-m-d');?>">
                                    </div>
                                </div>
                        </div>

                        <div class="form-row align-items-center mb-2">

                            <div class="col-md-2 d-inline">
                                <label for="">Leave From:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="date" id="dateFrom" name="dateFrom" class="form-control"
                                    >
                            </div>
                            <div class="col-md-1 d-inline">
                                <label for="">To:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="date" id="dateTo" name="dateTo" class="form-control"
                                    >
                            </div>
                            <div class="col-md-3 d-inline">
                                <div class="form-check" id="singleHalf">
                                    <input class="form-check-input" type="checkbox" id="halfDay">
                                        <label class="form-check-label " for="halfDay">Half Day</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-2 align-items-center" id="halfdayset">
                            <div class="col-md-2 d-inline"></div>

                            <div class="col-md-3 d-inline">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="lastDayHalfDay" name="lastDayHalfDay">
                                    <label class="form-check-label " for="lastDayHalfDay">Single Half Day</label>
                                </div>
                            </div>

                            <div class="col-md-3 d-inline">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="multiHalfDay" name="multiHalfDay">
                                    <label class="form-check-label " for="multiHalfDay">All Half Day</label>
                                </div>
                            </div>

                            <div class="col-md-4 d-inline">
                                <span id="errMsg"></span>
                            </div>

                        </div>

                        <div class="form-row mb-2">
                            <div class="col-md-2 d-inline">
                                <label for='leaveDesc'>Reason:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <textarea class="form-control inputtext" id="leaveDesc" name="leaveDesc" rows="4" cols="50" ></textarea>
                            </div>
                        </div>

                             <div class="row pb-2">
                                <div class="col-md-2">
                                    <label for="Attachment" id="LabelAttachment">Attachment:</label>
                                </div>
                                <div class="col-md-10">
                                    <input type="file" name="medicalfiles" id="medicalfiles" accept=".pdf" onChange="GetMedFile()">
                                </div>
                            </div>
                        

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="Submit" onclick="uploadFile();"  ><i class="fas fa-check-circle"></i> Submit</button>
                </div>
                </div> <!-- main body closing -->
            </div> <!-- modal body closing -->
        </div> <!-- modal content closing -->
    </div> <!-- modal dialog closing -->

<!-- edit leave  -->
<div class="modal fade" id="updateLeaveModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE LEAVE <i class="fas fa-suitcase"></i> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
         <div class="modal-body">
                        <?php $leaveApp->EditLeaveType(); ?>
                        <div id="eleavepay">
                            <div class="row">
                                <div class=col-md-2>
                                    <label for="">Leave Pay:</label>
                                </div>
                                <div class="col-md-10">                                
                                        <div class="form-check form-check-inline" id="wpay" id="wpay">
                                            <input class="form-check-input" type="radio" name="leavepay"
                                                id="lpay1" value="WithPay" checked>
                                            <label class="form-check-label" for="withpay">With Pay</label>
                                        </div>
                                       <div class="form-check form-check-inline" id="woutpay">
                                        <input class="form-check-input" type="radio" name="leavepay"
                                            id="lpay2" value="WithoutPay" >
                                        <label class="form-check-label" for="withoutpay">Without Pay</label>
                                        </div>
                                </div>
                            </div>
                        </div> 

                        <!-- edit leave -->

                </div> <!-- main body closing -->
            </div> <!-- modal body closing -->

                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->        

    <div class="modal fade" id="viewLeaveModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">VIEW LEAVE <i class="fas fa-suitcase fa-fw"></i></h5>
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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="datefl">Date Filed</label>
                                        <input type="text" id="datefl" name="datefl" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="leavetyp">Leave Type</label>
                                        <input type="text" id="leavetyp" name="leavetyp" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="datefr">Date From</label>
                                        <input type="text" id="datefr" name="datefr" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="dateto">Date To</label>
                                        <input type="text" id="dateto" name="dateto" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="actlcnt">Leave Count</label>
                                        <input type="text" id="actlcnt" name="actlcnt" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="appdays">Approved/Rejected (Days)</label>
                                        <input type="text" id="appdays" name="appdays" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="leavedesc">Description</label>
                                        <input type="text" id="leavedesc" name="leavedesc" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="leavedesc">Approver</label>
                                        <input type="text" id="appr_over" name="appr_over" class="form-control" readonly>
                                    </div>
                                </div>                                
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="remark">Remarks</label>
                                        <input type="text" id="remark" name="remark" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="appr_oved">Status</label>
                                        <input type="text" id="appr_oved" name="appr_oved" class="form-control" readonly>
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

<div class="modal fade" id="viewLeaveHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">VIEW LEAVE LOGS   <i class="fas fa-suitcase"></i></h5>
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



    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

<script type="text/javascript">

             $('#dateFrom').change(function(){

                var dte = $('#dateFrom').val();
                var disableDates  =  <?php echo json_encode($totalVal) ;?>;

                if(disableDates.includes(dte)){
                    document.getElementById('dateFrom').value = '';
                }

            });

             $('#dateTo').change(function(){

                var dte_to = $('#dateTo').val();
                var disableDates  =  <?php echo json_encode($totalVal) ;?>;


                if(disableDates.includes(dte_to)){
                    document.getElementById('dateTo').value = '';
                }

            });


     function updateLeaveModal(leaveid){

   
        $('#updateLeaveModal').modal('toggle');   
        var ltype = document.getElementById('lt'+leaveid).innerHTML;

        if(ltype == 'Vacation Leave without Pay') {
            document.getElementById('eleaveType').value =  'Vacation Leave';
            $("#lpay2").prop("checked", true);
            document.getElementById("eleavepay").style.display = "block";
        }else if(ltype == 'Sick Leave without Pay'){
            document.getElementById('eleaveType').value =  'Sick Leave';
            $("#lpay2").prop("checked", true);
            document.getElementById("eleavepay").style.display = "block";
        }else if(ltype == 'Sick Leave'){
            document.getElementById('eleaveType').value =  'Sick Leave';
            $("#lpay1").prop("checked", true);
            document.getElementById("eleavepay").style.display = "block";
        }else if(ltype == 'Vacation Leave'){
            document.getElementById('eleaveType').value =  'Vacation Leave';
            $("#lpay1").prop("checked", true);
            document.getElementById("eleavepay").style.display = "block";
        }else{
            document.getElementById('eleaveType').value  = ltype;
            document.getElementById("eleavepay").style.display = "none";
        }
    
        // document.getElementById('benefitid').value =  document.getElementById('bnr'+benfid).innerHTML;  
        // document.getElementById('periodcutoff').value =  document.getElementById('pc'+benfid).innerHTML;   
        // document.getElementById('amnt').value =  document.getElementById('am'+benfid).innerHTML;  
        // document.getElementById('effectivitydate').value =  document.getElementById('ed'+benfid).innerHTML;  
        // document.getElementById('stts').value =  document.getElementById('st'+benfid).innerHTML;      

    }

    

getPagination('#leaveList');

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');  
      // reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //  numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
                                  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
                                </li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');                  // add active class to the clicked
        limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
      limitPagging();
    })
    .val(5)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
    // alert($('.pagination li').length)

    if($('.pagination li').length > 7 ){
            if( $('.pagination li.active').attr('data-page') <= 3 ){
            $('.pagination li:gt(5)').hide();
            $('.pagination li:lt(5)').show();
            $('.pagination [data-page="next"]').show();
        }if ($('.pagination li.active').attr('data-page') > 3){
            $('.pagination li:gt(0)').hide();
            $('.pagination [data-page="next"]').show();
            for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                $('.pagination [data-page="'+i+'"]').show();

            }

        }
    }
}

</script>

<?php include("../_footer.php");?>
