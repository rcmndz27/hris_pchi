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
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');         

        $mf = new MasterFile();
        $dd = new DropDown();
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
        $e_appr = $ar['emailaddress'];
        $n_appr = $ar['firstname'].' '.$ar['lastname'];  

        
        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President' || $empUserType == 'Team Manager')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }  

    }    
?>

<script type="text/javascript">
    
    function viewOtModal(otdate,ottype,otstartdtime,otenddtime,remark,otreqhrs,otrenhrs,rejectreason,stats){
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
                                        document.querySelector('#clv').remove();
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type='text/javascript' src='../leave/viewapp.js'></script>
<div class="container">
    <div class="section-title">
          <h1>EMPLOYEES OVERTIME APPLICATION</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-hourglass fa-fw mr-1'>
                        </i>Employee Overtime Application View</li>
            </ol>
          </nav>
     <div class="form-row mb-2">
        <label for="from" class="col-form-label pad">From:</label>
        <div class="col-md-2">
            <input type="date" id="dateFrom" class="form-control" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>
        <label for="to" class="col-form-label pad">To:</label>
        <div class="col-md-2">
            <input type="date" id="dateTo" class="form-control" name="dateTo" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>        
            <?php if($empUserType == 'Team Manager'){   
                     echo '
                    <label for="status" class="col-form-label pad">NAME:</label><div class="col-md-3">';
                    $dd->GenerateSingleRepDropDown("empCode",$mf->GetAttEmployeeNamesRep("allemprepnames",$empCode));
                    echo '</div>
                    <button type="submit" id="searchOtRep" class="btn btn-warning"><i class="fas fa-search-plus"></i> Generate
                    </button>';
                }else{
                    echo '
                    <label for="status" class="col-form-label pad">Status:</label>
                    <div class="col-md-2">
                        <select class="form-select" id="status" name="status">
                            <option value="1">PENDING</option>
                            <option value="2">APPROVED</option>
                            <option value="3">REJECTED</option>
                            <option value="4">CANCELLED</option>
                        </select>
                    </div>
                    <button type="submit" id="searchOtApp" class="btn btn-warning"><i class="fas fa-search-plus"></i> Generate
                    </button>';
                }
            ?>                                                  
    </div>
            
    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <div id="OtListRepTabs" class="table-responsive-sm table-body"></div>
            </div>
        </div>
    </div>    


    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <div id="OtListTabs" class="table-responsive-sm table-body"></div>
            </div>
        </div>
    </div>    

<div class="modal fade" id="viewOtModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"> <i class="fas fa-hourglass fa-fw mr-1"></i>View Overtime</h5>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="remarkv">Description</label>
                                        <input type="text" id="remarkv" name="remarkv" class="form-control" readonly>
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-hourglass fa-fw"></i>View Overtime Logs</h5>
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

$('#dateTo').change(function(){

if($('#dateTo').val() < $('#dateFrom').val()){

    swal({text:"Date to must be greater than date from!",icon:"error"});

    var input2 = document.getElementById('dateTo');
    input2.value = '';               

}  

});

$('#dateFrom').change(function(){

if($('#dateFrom').val() > $('#dateTo').val()){
    var input2 = document.getElementById('dateTo');
    document.getElementById("dateTo").min = $('#dateFrom').val();
    input2.value = '';
}
});


function exportReportToExcel() {
  let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
  TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
    name: `export_ot.xlsx`, // fileName you could use any name
    sheet: {
      name: 'Overtime' // sheetName
    }
  });
} 

        
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("OtListTab");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1  || td[5].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[6].innerHTML.toUpperCase().indexOf(filter) > -1  || td[7].innerHTML.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}

            
function myFunctionRep() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("OtListRepTab");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1  || td[5].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[6].innerHTML.toUpperCase().indexOf(filter) > -1  || td[7].innerHTML.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }

 }

getPagination('#OtListTab');

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


getPagination('#OtListRepTab');

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
