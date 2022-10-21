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
            include("../loans/loanslist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allloansList = new LoansList(); 
            $mf = new MasterFile();
            $dd = new DropDown();


        if ($empUserType == 'Admin' || $empUserType == 'Finance' || $empUserType == 'Group Head')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        } 

    }    
?>
<link rel="stylesheet" type="text/css" href="../loans/all_view.css">
<script type="text/javascript" src="../loans/loans_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<body onload="javascript:generateEmpStatus();">
<div class="container">
    <div class="section-title">
          <h1>ALL LOANS LIST</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-bill-wave fa-fw mr-1'>
                        </i>Loans Management List</li>
            </ol>
          </nav>
    <div class="form-row">
        <div class='col-sm-1'>
            <label for="payroll_period" class="col-form-label pad">Status:</label>
        </div>
            <div class='col-md-2' >
              <select class="form-select" id="empStatus" name="empStatus" value="">
                <option value="Active">Active</option>
                <option value="Resigned">Resigned</option>
                <option value="Terminated">Terminated</option>
                <option value="Separated">Separated</option>
              </select>    
          </div>
        <div class='col-md-4' >          
            <button type="button" id="search" class="btn btn-primary text-white mr-1" onclick="generateEmpStatus();">
              <i class="fas fa-search-plus"></i> Generate                      
            </button>  
        <button type="button" class="btn btn-secondary" id="loansEntry"><i class="fas fa-plus-circle"></i> Add New Employee Loan </button>                                              
        </div>
      <div class="col-md-1">
            <select class="form-select" name="state" id="maxRows">
                <option value="5000">ALL</option>                
                 <option value="5">5</option>
                 <option value="10">10</option>
                 <option value="15">15</option>
                 <option value="20">20</option>
                 <option value="50">50</option>
                 <option value="70">70</option>
                 <option value="100">100</option>
            </select> 
        </div>          
        <div class='col-md-4' >     
            <input type="text" id="myInput" class="form-control" onkeyup="myFunction()" placeholder="Search for employee salary..." title="Type in employee name">
        </div>                                              
    </div>  

    <div class="pt-1">
        <div class="row">
            <div class="col-md-12">
                    <div id='contents'></div>  
            </div>
        </div>
    </div>

    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-check mr-1"></i> Loans Entry</h5>
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
                                        <label class="control-label" for="empcode">Employee Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("emp_code", $mf->GetEmployeeNames("allempnames")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="loanname">Loan Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("loandec_id", $mf->GetAllEmployeeDeduction("dedlist")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="loandate">Loan Date<span class="req">*</span>
                                    </label>                                        
                                        <input type="date" class="form-control inputtext" name="loan_date"
                                            id="loan_date" value="<?php  echo date('Y-m-d'); ?>" onkeydown="return false">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amount">Loan Amount<span class="req">*</span></label>
                                        <input class="form-control inputtext" type="number"  id="loan_amount" name="loan_amount" step=".01">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Balance<span class="req">*</span></label>
                                        <input class="form-control inputtext" type="number"  id="loan_balance" name="loan_balance" step=".01">
                                    </div>
                                </div>    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Total Payment<span class="req">*</span></label>
                                        <input class="form-control inputtext" type="number"  id="loan_totpymt" name="loan_totpymt" step=".01">
                                    </div>
                                </div>      
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Amortization<span class="req">*</span></label>
                                        <input class="form-control inputtext" type="number"  id="loan_amort" name="loan_amort" step=".01">
                                    </div>
                                </div>
                                <input type="text" name="eMplogName" id="eMplogName" value="<?php echo $empName ?>" hidden>
                            </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" id="Submit"  ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    <div class="modal fade" id="updateLn" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-check mr-1"></i>Update Loans</h5>
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
                                        <label class="control-label" for="bank_type">Employee Name</label>
                                        <input type="text" class="form-control" name="lnid" id="lnid" hidden>
                                        <input type="text" class="form-control" name="empcode" id="empcode" hidden>
                                        <input type="text" class="form-control" name="empname" id="empname" readonly>  
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="loanname">Loan Name</label>
                                        <?php $dd->GenerateSingleDropDown("loandecid", $mf->GetAllEmployeeDeduction("dedlist")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="loandate">Loan Date
                                    </label>                                        
                                        <input type="date" class="form-control inputtext" name="loandate"
                                            id="loandate" value="<?php  echo date('Y-m-d'); ?>" onkeydown="return false">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amount">Loan Amount</label>
                                        <input class="form-control" type="number"  id="loanamount" name="loanamount" step=".01">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Balance</label>
                                        <input class="form-control" type="number"  id="loanbalance" name="loanbalance" step=".01">
                                    </div>
                                </div>    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Total Payment</label>
                                        <input class="form-control" type="number"  id="loantotpymt" name="loantotpymt" step=".01">
                                    </div>
                                </div>      
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="balance">Loan Amortization</label>
                                        <input class="form-control" type="number"  id="loanamort" name="loanamort" step=".01">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>                                
                            </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateLn()"  ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 

                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->     

<div class="modal fade" id="viewLnLogs" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i>View Loans Logs</h5>
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
</body>

<script type="text/javascript">

function generateEmpStatus()
    {
        document.getElementById("myDiv").style.display="block";
        var url = "../loans/loanslist_process.php";
        var empStatus = $('#empStatus').val();

        $.post (
            url,
            {   
                empStatus:empStatus
                
            },
            function(data) { 
                $("#contents").html(data).show();
                $("#allloansList").tableExport({
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
                    sheetname: 'LoanEmployees'
                });
            $(".fa-file-export").remove();
            $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');      
                document.getElementById("myDiv").style.display="none"; 
            }
            );
    }   


    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return swal({text:"Only numbers are allowed!",icon:"error"});;
        return true;
    }


function insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid) {

    $.post (url2,
    {
        emp_code:emp_code,
        column_name: column_name,
        new_data: new_data,
        old_data: old_data ,
        rowid:rowid
    });
}



 function editLnModal(empcd,lnid,fname){

   
        $('#updateLn').modal('toggle');
        document.getElementById('empcode').value =  empcd;  
        document.getElementById('empname').value =  fname;  
        document.getElementById('lnid').value =  lnid;           
        document.getElementById('loandecid').value =  document.getElementById('lnh'+lnid).innerHTML;  
        document.getElementById('loanamount').value =  document.getElementById('lah'+lnid).innerHTML;  
        document.getElementById('loanbalance').value =  document.getElementById('lbh'+lnid).innerHTML;  
        document.getElementById('loantotpymt').value =  document.getElementById('ltph'+lnid).innerHTML; 
        document.getElementById('loanamort').value =  document.getElementById('lamh'+lnid).innerHTML;  
        document.getElementById('loandate').value =  document.getElementById('ldt'+lnid).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+lnid).innerHTML;      

    }


function updateLn()
{

var url = "../loans/updateloans_process.php";
var url2 = "../loans/logsmfloans_process.php";
var emp_code = document.getElementById("empcode").value;
var loan_decid = document.getElementById("loandecid").value;
var lnid = document.getElementById("lnid").value;
var e = document.getElementById("loandecid");
var loandecid = e.options[e.selectedIndex].text;        
var loanamount = document.getElementById("loanamount").value;
var loan_amount = '₱ '+loanamount.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","); 
var loanbalance = document.getElementById("loanbalance").value;
var loan_balance = '₱ '+loanbalance.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");    
var loantotpymt = document.getElementById("loantotpymt").value;
var loan_totpymt = '₱ '+loantotpymt.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");    
var loanamort = document.getElementById("loanamort").value;
var loan_amort = '₱ '+loanamort.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");              
var loan_date = document.getElementById("loandate").value; 
var status = document.getElementById("stts").value; 

//old value
var old_loandecid =  document.getElementById('ln'+lnid).innerHTML;  
var old_loanamount =  document.getElementById('lah'+lnid).innerHTML;  
var old_loanbalance =  document.getElementById('lbh'+lnid).innerHTML;  
var old_loantotpymt =  document.getElementById('ltph'+lnid).innerHTML; 
var old_loanamort =  document.getElementById('lamh'+lnid).innerHTML;  
var old_loandate =  document.getElementById('ldt'+lnid).innerHTML;  
var old_status =  document.getElementById('st'+lnid).innerHTML;      


        swal({
          title: "Are you sure?",
          text: "You want to update this employee loans details?",
          icon: "success",
          buttons: true,
          dangerMode: true,
        })
        .then((updateLn) => {
          if (updateLn) {
                $.post (
                    url,
                    {
                        action: 1,
                        emp_code: emp_code ,
                        rowid : lnid,
                        loandec_id: loan_decid,
                        loan_amount: loanamount,
                        loan_balance: loanbalance,
                        loan_balance: loanbalance,
                        loan_totpymt: loantotpymt,
                        loan_amort: loanamort,               
                        loan_date: loan_date,
                        status: status 
                        
                    },
                function(data) { 
                    swal({
                        title: "Success!", 
                        text: "Successfully updated the employee loans details!", 
                        icon: "success",
                    }).then(function() {
                        $('#updateLn').modal('hide');
                        document.getElementById('ln'+lnid).innerHTML = loandecid;
                        document.getElementById('lnh'+lnid).innerHTML = loan_decid;
                        document.getElementById('lah'+lnid).innerHTML = loanamount;
                        document.getElementById('la'+lnid).innerHTML = loan_amount;
                        document.getElementById('lbh'+lnid).innerHTML = loanbalance;
                        document.getElementById('lb'+lnid).innerHTML = loan_balance; 
                        document.getElementById('ltph'+lnid).innerHTML = loantotpymt;
                        document.getElementById('ltp'+lnid).innerHTML = loan_totpymt;
                        document.getElementById('lamh'+lnid).innerHTML = loanamort;
                        document.getElementById('lam'+lnid).innerHTML = loan_amort;                        
                        document.getElementById('ldt'+lnid).innerHTML = loan_date;
                        document.getElementById('st'+lnid).innerHTML = status;
                        if(loandecid !== old_loandecid){
                        new_data = loandecid;
                        old_data =  old_loandecid;
                        column_name = 'Loan Name';
                        rowid = lnid;
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(loanamount !== old_loanamount){
                        new_data = loanamount;
                        old_data =  old_loanamount;
                        column_name = 'Loan Amount';
                        rowid = lnid;         
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(loanbalance !== old_loanbalance){
                        new_data = loanbalance;
                        old_data =  old_loanbalance;
                        column_name = 'Loan Balance';
                        rowid = lnid ;     
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(loantotpymt !== old_loantotpymt){
                        new_data = loantotpymt;
                        old_data =  old_loantotpymt;
                        column_name =  'Loan Total Payment'; 
                        rowid = lnid;      
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(loanamort !== old_loanamort){
                        new_data = loanamort;
                        old_data =  old_loanamort;
                        column_name =  'Loan Amortization';
                        rowid = lnid ;        
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(loan_date !== old_loandate){
                        new_data = loan_date;
                        old_data =  old_loandate;
                        column_name =  'Loan Date'; 
                        rowid = lnid;      
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }if(status !== old_status){
                        new_data = status;
                        old_data =  old_status;
                        column_name =  'Status'; 
                        rowid = lnid;        
                        insertMfLnLogs(url2,emp_code,column_name,new_data,old_data,rowid);
                        }   

                    }); 
                }
            );

      } else {
        swal({text:"You cancel the updating of employee loans details!",icon:"error"});
      }
    });

}
    

function viewLnLogs(empcd,rowid)
 {
     $('#viewLnLogs').modal('toggle');
     var url = "../loans/viewloanslogs_process.php";
     var emp_code = empcd;
     var rowid = rowid;

     $.post (
        url,
        {
            emp_code:emp_code,
            rowid:rowid        
        },
        function(data) { 
            $("#contents2").html(data).show(); 
            $("#ViewLnLogs").tableExport({
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
                sheetname: 'loans Logs'
            });
            $(".fa-file-export").remove();
            $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');                
        }
        );
 }    

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allloansList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1  || td[5].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}


getPagination('#allloansList');

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
    .val(10)
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
