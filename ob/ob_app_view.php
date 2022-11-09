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
        include('../ob/ob_app.php');

        $obApp = new ObApp(); 
        $obApp->SetObAppParams($empCode);

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

        $queryf = "SELECT ob_date from dbo.tr_offbusiness WHERE emp_code = :empcode and  status in (1,2)";
        $paramf = array(":empcode" => $_SESSION['userid']);
        $stmtf =$connL->prepare($queryf);
        $stmtf->execute($paramf);
        $rsf = $stmtf->fetch();

        if(!empty($rsf)){
            $totalVal = [];
            do { 
                array_push($totalVal,$rsf['ob_date']);
                
            } while ($rsf = $stmtf->fetch());                  
        }else{
            $totalVal = [];
        }

        //disable date ob

        $queryd = "EXEC disableddates_ob :empcode";
        $paramd = array(":empcode" => $_SESSION['userid']);
        $stmtd =$connL->prepare($queryd);
        $stmtd->execute($paramd);
        $rsd = $stmtd->fetch();

        if(!empty($rsd)){
            $disdate = [];
            do { 
                array_push($disdate,$rsd['punch_date']);
                
            } while ($rsd = $stmtd->fetch());
        }else{
            $disdate = [];
        }                  

    }    
?>

<script type="text/javascript">
    

    function viewObModal(obdestination,obdate,obtime,obpurpose,obpercmp,stats,approver,attachment){
            $('#viewObModal').modal('toggle');
            document.getElementById('obdestination').value =  obdestination;   
            document.getElementById('obdate').value =  obdate;  
            document.getElementById('obtime').value =  obtime;  
            document.getElementById('obpurpose').value =  obpurpose;  
            document.getElementById('obpercmp').value =  obpercmp;  
            document.getElementById('stats').value =  stats;   
            document.getElementById('approver').value =  approver; 
                    if(!attachment){
            $('#viewattachment').hide();
        }else{
            $('#viewattachment').show();
            document.getElementById('viewattachment').setAttribute('href','../uploads/'+attachment);
        }                                        
    }

    function viewObHistoryModal(lvlogid)
    {
       $('#viewObHistoryModal').modal('toggle');
        var url = "../ob/ob_viewlogs.php";
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

     function cancelOb(lvid,empcd)
        {

         var url = "../ob/cancelObProcess.php";  
         var obid = lvid;   
         var emp_code = empcd;   
         
            swal({
                  title: "Are you sure?",
                  text: "You want to cancel this work from home?",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((cnclOB) => {
                  if (cnclOB) {
                    $.post (
                            url,
                            {
                                choice: 1,
                                obid:obid,
                                emp_code:emp_code
                            },
                            function(data) { 
                                // console.log(data);
                                    swal({
                                    title: "Oops!", 
                                    text: "Successfully cancelled official business!", 
                                    type: "info",
                                    icon: "info",
                                    }).then(function() {
                                        document.getElementById('st'+obid).innerHTML = 'CANCELLED';
                                         $('#clv'+obid).hide();
                                    });  
                            }
                        );
                  } else {
                    swal({text:"You stop the cancellation of your official business.",icon:"error"});
                  }
                });
      
    }
</script>
<link rel="stylesheet" type="text/css" href="../ob/ob_view.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type='text/javascript' src='../ob/ob_app.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="../ob/moment2.min.js"></script>
<script src="../ob/moment-range.js"></script>
<div class="container">
    <div class="section-title">
          <h1>OFFICIAL BUSINESS APPLICATION</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-building mr-1'></i> Official Business Application</li>
            </ol>
          </nav>
   
    <div class="pt-3">

        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="applyOfBus"><i class="fas fa-plus-circle mr-1"></i> Apply Official Business</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $obApp->GetObAppHistory(); ?>
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class='fas fa-building mr-1'></i>Official Business Application Form 
                        </i></h5>
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
                                        <label for="">OB Date From:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="ob_from" name="ob_from" class="form-control"
                                            >
                                    </div>
                                    <div class="col-md-2 d-inline">
                                        <label for="">OB Date To:</label><span class="req">*</span>
                                    </div>
                                    <div class="col-md-3 d-inline">
                                        <input type="date" id="ob_to" name="ob_to" class="form-control"
                                            >
                                    </div>
                            </div>


                        <div class="form-row align-items-center mb-2">

                            <div class="col-md-2 d-inline">
                                <label for="">OB Time:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-3 d-inline">
                                <input type="time" id="ob_time" name="ob_time" class="form-control">
                            </div>
                            <div class="col-md-2 d-inline">
                                <label for="">OB Destination:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-5 d-inline">
                                <input type="text" id="ob_destination" name="ob_destination" class="form-control inputtext">
                            </div>
                        </div>

                                    <div class="form-row align-items-center mb-2">
                                       <div class="col-md-3 d-inline">
                                            <label for="ob_percmp">Person/Company to See:</label><span class="req">*</span>
                                        </div>
                                        <div class="col-md-9 d-inline">
                                            <input type="text" id="ob_percmp" name="ob_percmp" class="form-control inputtext">
                                        </div>
                                    </div>

                        <div class="form-row mb-2">
                            <div class="col-md-2 d-inline">
                                <label for='leaveDesc'>Purpose:</label><span class="req">*</span>
                            </div>
                            <div class="col-md-10 d-inline">
                                <textarea class="form-control inputtext" id="ob_purpose" name="ob_purpose" rows="4" cols="50" ></textarea>
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
                    <button type="button" class="btn btn-success" id="Submit" ><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>
<div class="modal fade" id="viewObModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"> <i class="fas fa-building mr-1"></i>View Official Business</h5>
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
                            <!-- ob_from,ottype,otstartdtime,otenddtime,remark,otreqhrs,otrenhrs,rejectreason -->
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="obdate">OB Date</label>
                                        <input type="text" id="obdate" name="obdate" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="obdestination">Destination</label>
                                        <input type="text" id="obdestination" name="obdestination" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="obtime">Time</label>
                                        <input type="text" id="obtime" name="obtime" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="obpercmp">Person/Company to See</label>
                                        <input type="text" id="obpercmp" name="obpercmp" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="stats">Status</label>
                                        <input type="text" id="stats" name="stats" class="form-control" readonly>
                                    </div>
                                </div>   
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="obpurpose">Purpose</label>
                                        <input type="text" id="obpurpose" name="obpurpose" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4">
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

<div class="modal fade" id="viewObHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"> <i class='fas fa-building mr-1'></i>View Official Business Logs</h5>
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
    $('#obList').DataTable({
        pageLength : 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
    });
} );    



        function CheckInput() {

        var inputValues = [];

        inputValues = [
            
            $('#ob_destination'),
            $('#ob_purpose'),
            $('#ob_percmp'),
            $('#attachment')
            
        ];

        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }


            $('#ob_to').change(function(){

                if($('#ob_to').val() < $('#ob_from').val()){

                    swal({text:"OB date TO must be greater than OB Date From!",icon:"error"});

                    var input2 = document.getElementById('ob_to');
                    input2.value = '';               

                }else{
                    // alert('Error');
                }   

            });


            $('#ob_from').change(function(){

                    var input2 = document.getElementById('ob_to');
                    document.getElementById("ob_to").min = $('#ob_from').val();
                    input2.value = '';

            });



$('#Submit').click(function(){


            var dte = $('#ob_from').val();
            var dte_to = $('#ob_to').val();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
            dateArr = []; //Array where rest of the dates will be stored

            //creating JS date objects
            var start = new Date(dte);
            var date = new Date(dte_to);
            var end = date.setDate(date.getDate() + 1);

            //Logic for getting rest of the dates between two dates("FromDate" to "EndDate")
            while(start < end){
               dateArr.push(moment(start).format('YYYY-MM-DD'));
               var newDate = start.setDate(start.getDate() + 1);
               start = new Date(newDate);  
            }

            var ite_date = dateArr.length === 0  ? dte : dateArr ;
            var disableDates  =  <?php echo json_encode($disdate) ;?>;

            var arr2 = Object.values(ite_date);
            var arr1 = Object.values(disableDates);

            arr2 = arr2.reduce(function (prev, value) {

                var isDuplicate = false;
                for (var i = 0; i < arr1.length; i++) {
                    if (value == arr1[i]) {
                        isDuplicate = true;
                        break;
                    }
                }
                  
                if (!isDuplicate) {
                    prev.push(value);
                }
                   
                return prev;
                    
            }, []);

            var itdate = arr2;            

            var e_req = $('#e_req').val();
            var n_req = $('#n_req').val();
            var e_appr = $('#e_appr').val();
            var n_appr = $('#n_appr').val();            


            if (CheckInput() === true) {

                param = {
                    "Action":"ApplyObApp",
                    "ob_date": itdate,
                    "ob_time": $('#ob_time').val(),
                    "ob_destination": $('#ob_destination').val(),
                    "ob_purpose": $('#ob_purpose').val(),
                    "ob_percmp": $('#ob_percmp').val(),
                    "e_req": e_req,
                    "n_req": n_req,
                    "e_appr": e_appr,
                    "n_appr": n_appr,
                    "attachment": attFile   
                };
                
                param = JSON.stringify(param);

                // console.log(param);
                // return false;

                            swal({
                              title: "Are you sure?",
                              text: "You want to apply this official business?",
                              icon: "success",
                              buttons: true,
                              dangerMode: true,
                            })
                            .then((applyOb) => {
                                document.getElementById("myDiv").style.display="block";
                              if (applyOb) {
                                        $.ajax({
                                        type: "POST",
                                        url: "../ob/ob_app_process.php",
                                        data: {data:param} ,
                                        success: function (data){
                                            console.log("success: "+ data);
                                                    swal({
                                                    title: "Success!", 
                                                    text: "Successfully added official business details!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        location.href = '../ob/ob_app_view.php';
                                                    });
                                        },
                                        error: function (data){
                                            // alert('error');
                                        }
                                    });//ajax

                              } else {
                                document.getElementById("myDiv").style.display="none";
                                swal({text:"You cancel your official business!",icon:"error"});
                              }
                            });
              
                }else{
                swal({text:"Kindly fill up blank fields!",icon:"error"});
            }
                              
    });

$('#ob_from').change(function(){

    var dte = $('#ob_from').val();
    var disableDates  =  <?php echo json_encode($totalVal) ;?>;

    if(disableDates.includes(dte)){
        document.getElementById('ob_from').value = '';
    }

});

 $('#ob_to').change(function(){

    var dte_to = $('#ob_to').val();
    var disableDates  =  <?php echo json_encode($totalVal) ;?>;


    if(disableDates.includes(dte_to)){
        document.getElementById('ob_to').value = '';
    }

}); 
      
      function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("obList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1 || td[5].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[6].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}
    
    getPagination('#obList');

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
