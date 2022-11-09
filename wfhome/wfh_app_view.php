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
        include('../wfhome/wfh_app.php');

        $wfhApp = new WfhApp(); 
        $wfhApp->SetWfhAppParams($empCode);

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

        $queryf = "SELECT wfh_date from dbo.tr_workfromhome WHERE emp_code = :empcode and  status in (1,2)";
        $paramf = array(":empcode" => $_SESSION['userid']);
        $stmtf =$connL->prepare($queryf);
        $stmtf->execute($paramf);
        $rsf = $stmtf->fetch();

        if(!empty($rsf)){
            $totalVal = [];
            do { 
                array_push($totalVal,$rsf['wfh_date']);
                
            } while ($rsf = $stmtf->fetch());                     
        }else{
            $totalVal = [];
        }
    }    
?>
<script type="text/javascript">
    

        function viewWfhModal(wfhdate,wfhtask,wfhoutput,wfhoutput2,wfhpercentage,wfhstats,rjrson,approver,attachment){
   
        $('#viewWfhModal').modal('toggle');
        document.getElementById('wfhdates').value =  wfhdate;   
        document.getElementById('wfhtask').value =  wfhtask;  
        document.getElementById('wfhoutput').value =  wfhoutput;  
        document.getElementById('wfhoutput2').value =  wfhoutput2;  
        document.getElementById('wfhpercentage').value =  wfhpercentage;  
        document.getElementById('wfhstats').value =  wfhstats;  
        document.getElementById('rejectreason').value =  rjrson;  
        document.getElementById('approver').value =  approver;   
        if(!attachment){
            $('#viewattachment').hide();
        }else{
            $('#viewattachment').show();
            document.getElementById('viewattachment').setAttribute('href','../uploads/'+attachment);
        }                       
    }

    function viewWfhHistoryModal(lvlogid)
    {
       $('#viewWfhHistoryModal').modal('toggle');
        var url = "../wfhome/wfh_viewlogs.php";
        var lvlogid = lvlogid;

        // console.log(lvlogid);
        // return false;

        $.post (
            url,
            {
                _action: 1,
                lvlogid: lvlogid             
            },
            function(data) { $("#contents2").html(data).show(); }
        );
    }

    function cancelWfh(lvid,empcd) {

     var url = "../wfhome/cancelWfhProcess.php";  
     var wfhid = lvid;   
     var emp_code = empcd;  
     
        swal({
              title: "Are you sure?",
              text: "You want to cancel this work from home?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((cnclOT) => {
              if (cnclOT) {
                $.post (
                url,
                {
                    choice: 1,
                    wfhid:wfhid,
                    emp_code:emp_code
                },
                function(data) { 
                    // console.log(data);
                        swal({
                        title: "Oops!", 
                        text: "Successfully cancelled work from home!", 
                        type: "info",
                        icon: "info",
                        }).then(function() {
                            document.getElementById('st'+wfhid).innerHTML = 'CANCELLED';
                             $('#clv'+wfhid).hide();
                             $('#tin'+wfhid).hide();
                             $('#tout'+wfhid).hide();
                        });  
                }
            );
              } else {
                swal({text:"You stop the cancellation of your work from home.",icon:"error"});
              }
            });
      
    }

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
                                    location.href = '../wfhome/wfh_app_view.php';
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
<link rel="stylesheet" type="text/css" href="../wfhome/wfh_view.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type='text/javascript' src='../wfhome/wfh_app.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="../overtime/moment2.min.js"></script>
<script src="../overtime/moment-range.js"></script>
<div class="container">
    <div class="section-title">
          <h1>WORK FROM HOME APPLICATION</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><b><i class='fas fa-warehouse fa-fw mr-1'></i>Work From Home Application</b></li>
            </ol>
          </nav>
<div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="applyWfh"><i class="fas fa-plus-circle mr-1"></i> Apply Work From Home </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $wfhApp->GetWfhAppHistory(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="popUpModalTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 class="text-danger text-center"><label for="" id="modalText"></label></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
 <!-- apply wfh start -->
    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-warehouse mr-1"></i>Work From Home Application Form </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
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
                    <div>
                      
                            <div class="form-row align-items-center mb-2">
                                   <div class="col-md-2 d-inline">
                                        <label for="">WFH Date:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="wfhdate" name="wfhdate" class="form-control" 
                                            value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                            </div>                    
                            <div class="form-row align-items-center mb-2">
                                   <div class="col-md-2 d-inline">
                                        <label for="">Task:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-10 d-inline">
                                         <textarea class="form-control inputtext" id="wfh_task" name="wfh_task" rows="4" cols="50" ></textarea> 
                                    </div>
                            </div>
                         <div class="row pb-2">
                            <div class="col-md-2">
                                <label for="Attachment" id="LabelAttachment">Attachment:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10">
                                <input type="file" name="attachment" id="attachment" class="inputtext" accept=".pdf,.jpg,.png" onChange="GetAttFile()" >
                            </div>
                        </div>                            
                    
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="Submit" onclick="uploadFile();"><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>

    <!-- end of apply wfh -->


    <!-- start time in wfh  -->

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



    <!-- start view wfh  -->
<div class="modal fade" id="viewWfhModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-warehouse mr-1"></i>View Work From Home</h5>
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
                             <!-- wfhdate,wfhtask,wfhoutput,wfhpercentage,wfhstats -->
                        <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhdates">WFH Date</label>
                                        <input type="text" id="wfhdates" name="wfhdates" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhtask">Task</label>
                                        <input type="text" id="wfhtask" name="wfhtask" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhpercentage">Percentage %</label>
                                        <input type="text" id="wfhpercentage" name="wfhpercentage" class="form-control" readonly >
                                    </div>
                                </div>                              
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhoutput">Expected Output</label>
                                        <input type="text" id="wfhoutput" name="wfhoutput" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhstats">Status</label>
                                        <input type="text" id="wfhstats" name="wfhstats" class="form-control" readonly>
                                    </div>
                                </div> 
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhoutput2">Output</label>
                                        <input type="text" id="wfhoutput2" name="wfhoutput2" class="form-control" readonly>                                        
                                    </div>
                                </div>  
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="Approver">Approver</label>
                                        <input type="text" id="approver" name="approver" class="form-control" readonly>                                        
                                    </div>
                                </div>                                                                        
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="rejectreason">Reject Reason</label>
                                        <input type="text" id="rejectreason" name="rejectreason" class="form-control" readonly>
                                    </div>
                                </div>                                 
                            </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">
                            <?php   echo"<a title='Attachment' id='viewattachment' class='font-weight-bold' href='' style='color:#ffff;'  
                                target='popup'><button type='button' class='btn btn-primary'><i class='text-white fas fa-paperclip mr-1'></i>View Attachment</button></a>"; ?>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="viewWfhHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title bb" id="popUpModalTitle"><i class='fas fa-warehouse mr-1'></i>View Work From Home Logs</h5>
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

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

<script type="text/javascript">

$(document).ready( function () {
    $('#wfhList').DataTable({
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
    });
} );    



     $('#wfhdate').change(function(){

        var dte = $('#wfhdate').val();
        var disableDates  =  <?php echo json_encode($totalVal) ;?>;

        if(disableDates.includes(dte)){
            document.getElementById('wfhdate').value = '';
        }

    });

     $('#wfhdateto').change(function(){

        var dte_to = $('#wfhdateto').val();
        var disableDates  =  <?php echo json_encode($totalVal) ;?>;


        if(disableDates.includes(dte_to)){
            document.getElementById('wfhdateto').value = '';
        }

    });    

    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("wfhList");
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
    
    getPagination('#wfhList');

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
