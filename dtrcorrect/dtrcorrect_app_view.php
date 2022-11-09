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
        include('../dtrcorrect/dtrcorrect_app.php');

        $dtrcorrectApp = new DtrCorrectApp(); 
        $dtrcorrectApp->SetdtrcorrectAppParams($empCode);

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

        $queryf = "SELECT dtrc_date from dbo.tr_dtrcorrect WHERE emp_code = :empcode and  status in (1,2)";
        $paramf = array(":empcode" => $_SESSION['userid']);
        $stmtf =$connL->prepare($queryf);
        $stmtf->execute($paramf);
        $rsf = $stmtf->fetch();

        if(!empty($rsf)){
            $totalVal = [];
            do { 
                array_push($totalVal,$rsf['dtrc_date']);
                
            } while ($rsf = $stmtf->fetch());                  
        }else{
            $totalVal = [];
        }
    }    
?>
<script type="text/javascript">


    function viewdtrcorrectModal(dtrcdate,timein,timeout,rmrks,stts,approver,attachment){

        $('#viewdtrcorrectModal').modal('toggle');
        document.getElementById('dtrcdate').value =  dtrcdate;   
        document.getElementById('timein').value =  timein;  
        document.getElementById('timeout').value =  timeout;  
        document.getElementById('rmrks').value =  rmrks;  
        document.getElementById('stts').value =  stts; 
        document.getElementById('approver').value =  approver;                          
        if(!attachment){
            $('#viewattachment').hide();
        }else{
            $('#viewattachment').show();
            document.getElementById('viewattachment').setAttribute('href','../uploads/'+attachment);
        }
    }

    function viewdtrcorrectHistoryModal(lvlogid)
    {
        $('#viewdtrcorrectHistoryModal').modal('toggle');
        var url = "../dtrcorrect/dtrcorrect_viewlogs.php";
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

function canceldtrcorrect(lvid,empcd)
{

    var url = "../dtrcorrect/canceldtrcorrectProcess.php";  
    var dtrcorrectid = lvid;   
    var emp_code = empcd;   
    swal({
        title: "Are you sure?",
        text: "You want to cancel this dtr correction?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((cnclDTR) => {
        if (cnclDTR) {
            $.post (
                url,
                {
                    choice: 1,
                    dtrcorrectid:dtrcorrectid,
                    emp_code:emp_code
                },
                function(data) { 
// console.log(data);
swal({
    title: "Oops!", 
    text: "Successfully cancelled dtr correction!", 
    type: "info",
    icon: "info",
}).then(function() {
    document.getElementById('st'+dtrcorrectid).innerHTML = 'CANCELLED';
    $('#clv'+dtrcorrectid).hide();
});  
}
);
        } else {
            swal({text:"You stop the cancellation of your dtr correction.",icon:"error"});
        }
    });
}

</script>
<link rel="stylesheet" type="text/css" href="../dtrcorrect/dtrc_view.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type='text/javascript' src='../dtrcorrect/dtrcorrect_app.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="../dtrcorrect/moment2.min.js"></script>
<script src="../dtrcorrect/moment-range.js"></script>
<div class="container">
    <div class="section-title">
          <h1>DTR CORRECTION APPLICATION</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-clock fa-fw mr-1'>
                        </i>DTR Correction Application</li>
            </ol>
          </nav>
<div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="applydtrcorrect"><i class="fas fa-plus-circle mr-1"></i> Apply DTR Correction</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $dtrcorrectApp->GetdtrcorrectAppHistory(); ?>
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

    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-clock mr-1"></i>DTR Correction Form</h5>
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
                                <label for="">DTR Date:</label>
                                <span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="date" id="dtrc_date" name="dtrc_date" class="form-control" 
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                    </div>
                    <div class="form-row align-items-center mb-2">
                           <div class="col-md-2 d-inline">
                                <label for="">DTR Type:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-4 d-inline">
                                <select class="form-select" id="dtrtype">
                                    <option value="Both">Both Time-In and Time-Out</option>                                    
                                    <option value="Time-in">Time-in Only</option>
                                    <option value="Time-out">Time-out Only</option>
                                </select>
                            </div>    
                    </div>
                    <div class="form-row align-items-center mb-2">     
                            <!-- time in  -->
                               <div class="col-md-2 d-inline" >
                                    <label id="ltimein">Time-In:<span class="req">*</span></label>
                                </div>
                                <div class="col-md-4 d-inline" id="itimein">
                                    <input type="time" id="time_in" name="time_in" class="form-control inputtext">
                                </div>
                        </div>                                                   
                            <!-- timeout -->
                    <div class="form-row align-items-center mb-2">                              
                                <div class="col-md-2 d-inline">
                                    <label id="ltimeout">Time-Out:<span class="req">*</span></label>
                                </div>
                                <div class="col-md-4 d-inline" id="itimeout">
                                    <input type="time" id="time_out" name="time_out" class="form-control inputtext">
                                </div>   
                    </div>                                                                  
                   
                    <div class="form-row align-items-center mb-2">
                           <div class="col-md-2 d-inline">
                                <label for="">Reason:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <input type="text" id="remarks" name="remarks" class="form-control inputtext">
                            </div>
                    </div>   
                     <div class="row pb-2">
                        <div class="col-md-2">
                            <label for="Attachment" id="LabelAttachment">Attachment:<span class="req">*</span></label>
                        </div>
                        <div class="col-md-10">
                            <input type="file" class="inputtext" name="attachment" id="attachment" accept=".pdf,.jpg,.png" onChange="GetAttachFile()">
                        </div>
                    </div>                                             
            </div>
        </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="Submit" onclick="uploadFile();" ><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>

<div class="modal fade" id="viewdtrcorrectModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-clock"></i> View DTR Correction</h5>
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
                             <!-- dtrcorrectdate,dtrcorrecttask,dtrcorrectoutput,dtrcorrectpercentage,dtrcorrectstats -->
                        <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="dtrcdate">DTR Date</label>
                                        <input type="text" id="dtrcdate" name="dtrcdate" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status</label>
                                        <input type="text" id="stts" name="stts" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="timein">Time-In</label>
                                        <input type="text" id="timein" name="timein" class="form-control" readonly>
                                    </div>
                                </div>                              
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="timeout">Time-Out</label>
                                        <input type="text" id="timeout" name="timeout" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="rmrks">Reason</label>
                                        <input type="text" id="rmrks" name="rmrks" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="approver">Approver</label>
                                        <input type="text" id="approver" name="approver" class="form-control" readonly>
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

<div class="modal fade" id="viewdtrcorrectHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"> <i class='fas fa-clock mr-1'></i> View DTR Correction Logs</h5>
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
    $('#dtrcorrectList').DataTable({
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
    });
} );    


    $('#dtrc_date').change(function(){

    var dte = $('#dtrc_date').val();
    var disableDates  =  <?php echo json_encode($totalVal) ;?>;
    var cst = $('#dtrc_date').val();
    var dte = cst+'T00:00';
 

    if(disableDates.includes(dte)){
        document.getElementById('dtrc_date').value = '';
    }

    document.getElementById('time_in').value = dte;               
    document.getElementById('time_out').value = dte;       

    });

    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("dtrcorrectList");
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
    
    getPagination('#dtrcorrectList');

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
