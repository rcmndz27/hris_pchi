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
        include('../overtime/ot_app.php');

        $otApp = new OtApp(); 
        $otApp->SetOtAppParams($empCode);

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
        $e_appr = (isset($ar['emailaddress'])) ? $ar['emailaddress'] : 'n/a' ;
        $afname = (isset($ar['firstname'])) ? $ar['firstname'] : 'n/a' ; 
        $alname = (isset($ar['lastname'])) ? $ar['lastname'] : 'n/a' ;
        $n_appr = $afname.' '.$afname;    


        $queryf = "SELECT ot_date from dbo.tr_overtime WHERE emp_code = :empcode and  status in (1,2)";
        $paramf = array(":empcode" => $_SESSION['userid']);
        $stmtf =$connL->prepare($queryf);
        $stmtf->execute($paramf);
        $rsf = $stmtf->fetch();

        if(!empty($rsf)){
            $totalVal = [];
            do { 
                array_push($totalVal,$rsf['ot_date']);
                
            } while ($rsf = $stmtf->fetch());                  
        }else{
            $totalVal = [];
        }

    }    
?>

<script type="text/javascript">
    
    function viewOtModal(otdate,ottype,otstartdtime,otenddtime,remark,otreqhrs,otrenhrs,rejectreason,stats,approver,attachment){
        $('#viewOtModal').modal('toggle');
        document.getElementById('otdatev').value =  otdate;   
        document.getElementById('ottypev').value =  ottype;  
        document.getElementById('otstartdtimev').value =  otstartdtime;  
        document.getElementById('otenddtimev').value =  otenddtime;  
        document.getElementById('remarkv').value =  remark;  
        document.getElementById('otreqhrsv').value =  otreqhrs;  
        document.getElementById('otrenhrsv').value =  otrenhrs;  
        document.getElementById('rejectreasonv').value =  rejectreason;    
        document.getElementById('statsv').value =  stats;
        document.getElementById('approver').value =  approver;
                if(!attachment){
            $('#viewattachment').hide();
        }else{
            $('#viewattachment').show();
            document.getElementById('viewattachment').setAttribute('href','../uploads/'+attachment);
        }                                     
    }

    function viewOtHistoryModal(lvlogid)
    {
       $('#viewOtHistoryModal').modal('toggle');
        var url = "../overtime/ot_viewlogs.php";
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

     function cancelOvertime(lvid,empcd)
        {
         var url = "../overtime/cancelOvertimeProcess.php";  
         var otid = lvid;   
         var emp_code = empcd;   

            swal({
                  title: "Are you sure?",
                  text: "You want to cancel this overtime?",
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
                        otid:otid,
                        emp_code:emp_code
                    },
                    function(data) { 
                        // console.log(data);
                    swal({
                    title: "Oops!", 
                    text: "Successfully cancelled overtime!", 
                    type: "info",
                    icon: "info",
                    }).then(function() {
                        document.getElementById('st'+otid).innerHTML = 'CANCELLED';
                        $('#clv'+otid).hide();
                    });  
                    }
                );
          } else {
            swal({text:"You stop the cancellation of your overtime.",icon:"error"});
          }
        });

    }

</script>
<link rel="stylesheet" type="text/css" href="../overtime/ot_view.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type='text/javascript' src='../overtime/ot_app.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="../overtime/moment2.min.js"></script>
<script src="../overtime/moment-range.js"></script>
<div class="container">
    <div class="section-title">
          <h1><br></h1>
        </div>
    <div class="main-body mbt">

          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-hourglass fa-fw mr-1'>
                </i>Overtime Application</li>
            </ol>
          </nav>
   
    <div class="pt-3">

        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="applyOvertime"><i class="fas fa-plus-circle"></i> Apply Overtime</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $otApp->GetOtAppHistory(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-hourglass fa-fw mr-1">
                        </i>Overtime Application Form </h5>
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
                                        <label for="">OT Date:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="otdate" name="otdate" class="form-control inputtext">
                                    </div>
                            </div>
                        <div class="form-row align-items-center mb-2">
                            <div class="col-md-2 d-inline">
                                <label for="">OT Start Time:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="time" id="otstartdtime" name="otstartdtime" class="form-control inputtext" value="<?php echo date('h:i a');?>">
                            </div>
                            <div class="col-md-2 d-inline">
                                <label for="">OT End Time:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="time" id="otenddtime" name="otenddtime" class="form-control inputtext">
                            </div>
                        </div> 
                        <div class="form-row mb-2">
                            <div class="col-md-2 d-inline">
                                <label for='leaveDesc'>Remarks:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <textarea class="form-control inputtext" id="remarks" name="remarks" rows="4" cols="50" ></textarea>
                            </div>
                        </div>
                         <div class="row pb-2">
                            <div class="col-md-2">
                                <label for="Attachment" id="LabelAttachment">Attachment:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10">
                                <input type="file" name="attachment" id="attachment" class="inputtext" accept=".pdf,.jpg,.png" onChange="GetAttFile()">
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
<div class="modal fade" id="viewOtModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-hourglass fa-fw mr-1"></i> View Overtime</h5>
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
                            <!-- otdate,ottype,otstartdtime,otenddtime,remark,otreqhrs,otrenhrs,rejectreason -->
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="otdate">OT Date</label>
                                        <input type="text" id="otdatev" name="otdatev" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="ottypev">OT Type</label>
                                        <input type="text" id="ottypev" name="ottypev" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="otstartdtimev">Time In</label>
                                        <input type="text" id="otstartdtimev" name="otstartdtimev" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="otenddtimev">Time Out</label>
                                        <input type="text" id="otenddtimev" name="otenddtimev" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="otreqhrsv">Plan OT</label>
                                        <input type="text" id="otreqhrsv" name="otreqhrsv" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="otrenhrsv">Rendered OT</label>
                                        <input type="text" id="otrenhrsv" name="otrenhrsv" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="remarkv">Description</label>
                                <textarea class="form-control inputtext" id="remarkv" name="remarkv" rows="4" cols="50" disabled></textarea >                                        
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="remarkv">Approver</label>
                                        <input type="text" id="approver" name="approver" class="form-control" readonly>
                                    </div>
                                </div>                                
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="rejectreasonv">Reject Reason</label>
                                        <input type="text" id="rejectreasonv" name="rejectreasonv" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="statsv">Status</label>
                                        <input type="text" id="statsv" name="statsv" class="form-control" readonly>
                                    </div>
                                </div>                                
                            </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">
                            <?php   echo"<a title='Attachment' id='viewattachment' class='font-weight-bold' href='' style='color:#ffff;'  
                                target='popup'><button type='button' class='btn btn-primary'><i class='text-white fas fa-paperclip mr-1'></i>View Attachment</button></a>";   ?>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="viewOtHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-hourglass fa-fw mr-1"></i>View Overtime Logs</h5>
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
    $('#otList').DataTable({
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
    });
} );    


             $('#otdate').change(function(){

                var dte = $('#otdate').val();
                var disableDates  =  <?php echo json_encode($totalVal) ;?>;

                if(disableDates.includes(dte)){
                    document.getElementById('otdate').value = '';
                }

            });



             $('#otdateto').change(function(){

                var dte_to = $('#otdateto').val();
                var disableDates  =  <?php echo json_encode($totalVal) ;?>;


                if(disableDates.includes(dte_to)){
                    document.getElementById('otdateto').value = '';
                }

            });

        //     $('#otenddtime').change(function(){

        //         var dte = $('#otdate').val();
        //         var dte_to = $('#otdateto').val();
        //         var otstrt = $('#otstartdtime').val();
        //         var otend = $('#otenddtime').val();
        //         var dt = dte+' '+otstrt;
        //         var dtd = dte_to+' '+otend;
        //         var dt_input = new Date(dt);
        //         var dt_input2 = new Date(dtd);
        //         var dtstrt = dt_input.getHours();
        //         var dtend = dt_input2.getHours();
        //         var minutes = dt_input.getMinutes();
        //         var minutes2 = dt_input2.getMinutes();

        //         console.log(otstrt);
        //         console.log(otend);
        //         console.log(dt);
        //         console.log(dtd);
        //         console.log(dt_input);
        //         console.log(dt_input2);   
        //         console.log(dtstrt);
        //         console.log(dtend); 
        //         console.log(minutes);
        //         console.log(minutes2);                                                 

        //         return false;
        //     });




        // function myChangeFunction(input1) {
        //     var dte = $('#otdate').val();
        //     var dte_to = $('#otdateto').val();
        //     var otstrt = $('#otstartdtime').val();
        //     var dt = dte+' '+otstrt;
        //     var othrs = $('#otreqhrs').val();
        //     var dt_input = new Date(dt);
        //     var hr = parseFloat(othrs);

        //     var hours = dt_input.getHours();
        //     var minutes = dt_input.getMinutes();
        //     var ampm = hours < 12 || hours > 24 ? 'AM' : 'PM';
        //     hours = hours % 12;
        //     hours = hours ? hours : 12; // the hour '0' should be '12'
        //     minutes = minutes < 10 ? '0'+minutes : minutes;
        //     var strTime = hours + ':' + minutes + ' ' + ampm;
        //     var dt = moment(strTime, ["h:mm A"]).format("HH:mm");

        //     var input2 = document.getElementById('otenddtime');
        //     input2.value = dt;

        //     console.log(hours);
        //     return false;

        // }

        
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("otList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1  || td[5].innerHTML.toUpperCase().indexOf(filter) > -1  
        || td[6].innerHTML.toUpperCase().indexOf(filter) > -1  || td[7].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}




getPagination('#otList');

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


