<?php

            date_default_timezone_set('Asia/Manila');
    session_start();

    if (empty($_SESSION['userid']))
    {
        include_once('../loginfirst.php');
        exit();
    }
    else
    {
            include('../_header.php');
            include("../salary/salarylist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allSalaryList = new SalaryList(); 
            $mf = new MasterFile();
            $dd = new DropDown();

        if ($empUserType == 'Admin' || $empUserType == 'Group Head' || $empUserType == 'President' || $empUserType == 'Finance')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }             

   

    }    
?>

<link rel="stylesheet" type="text/css" href="../salary/sal_view.css">
<script type="text/javascript" src="../salary/salary_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<body onload="javascript:generateEmpStatus();">
<div class="container">
    <div class="section-title">
          <h1>ALL SALARY LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-bill-wave fa-fw mr-1'>
                        </i>Salary Management List</li>
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
        <button type="button" class="btn btn-secondary" id="salaryEntry"><i class="fas fa-plus-circle"></i> Add New Employee Salary </button>                                              
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i> Salary Entry</h5>
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
                                        <label class="control-label" for="bank_type">Employee Code/Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("emp_code", $mf->GetEmployeeSalaryEmp("empsalc")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_type">Bank Name<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("bank_type", $mf->GetAllBank("bankname")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_no">Bank Account No.</label>
                                        <input type="text" class="form-control inputtext" name="bank_no"
                                            id="bank_no" onkeypress="return onlyNumberKey(event)" placeholder="Account No...." maxlength="15">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="pay_rate">Payment Type<span class="req">*</span>
                                    </label>                                        
                                        <select type="select" class="form-select" id="pay_rate" name="pay_rate" >
                                            <option value="Monthly">Monthly</option>
                                            <option value="Daily">Daily</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amount">Payment Rate<span class="req">*</span></label>
                                        <input type="number" class="form-control inputtext" name="amount"
                                            id="amount" placeholder="000000.00" maxlength="15">
                                    </div>
                                </div> 

                                <div class="col-lg-6" hidden>
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="status"
                                            id="status" value="Active" readonly>                                        
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

    <div class="modal fade" id="updateSal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i>Update Salary Entry</h5>
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
                                        <label class="control-label" for="empcode">Employee Code<span class="req">*</span></label>
                                    <input type="text" class="form-control" name="empcode" id="empcode" hidden>
                                    <input type="text" class="form-control" name="empname" id="empname" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="banktype">Bank Name<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("banktype", $mf->GetAllBank("bankname")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="bankno">Bank Account No.<span class="req">*</span></label>
                                        <input type="text" class="form-control" name="bankno"
                                            id="bankno" onkeypress="return onlyNumberKey(event)" placeholder="Account No...." maxlength="15">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="pay_rate">Payment Type<span class="req">*</span>
                                    </label>                                        
                                        <select type="select" class="form-select" id="payrate" name="payrate" >
                                            <option value="Monthly">Monthly</option>
                                            <option value="Daily">Daily</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amnt">Payment Rate<span class="req">*</span></label>
                                        <input type="number" class="form-control inputtext" name="amnt"
                                            id="amnt" >
                                    </div>
                                </div> 
                                 <div class="col-lg-6">
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
                                    <button type="button" class="btn btn-success" onclick="updateSal()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="viewSalaryLogs" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i>View Salary Logs</h5>
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
        var url = "../salary/salarylist_process.php";
        var empStatus = $('#empStatus').val();

        $.post (
            url,
            {   
                empStatus:empStatus
                
            },
            function(data) { 
                $("#contents").html(data).show();
                $("#allSalaryList").tableExport({
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
                    sheetname: 'SalaryEmployees'
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



    function editSalaryModal(empcd,fname){
          
        $('#updateSal').modal('toggle');
        document.getElementById('empcode').value =  empcd;   
        document.getElementById('empname').value =  fname;   
        document.getElementById('banktype').value =  document.getElementById('bt'+empcd).innerHTML;  
        document.getElementById('bankno').value =  document.getElementById('bn'+empcd).innerHTML;  
        document.getElementById('payrate').value =  document.getElementById('pr'+empcd).innerHTML;  
        document.getElementById('amnt').value =  document.getElementById('am'+empcd).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+empcd).innerHTML;  

}

function insertMfSalLogs(url2,emp_code,column_name,new_data,old_data) {

    $.post (url2,
    {
        emp_code:emp_code,
        column_name: column_name,
        new_data: new_data,
        old_data: old_data
    });
}

     function updateSal()
    {


        var url = "../salary/updatesalary_process.php";
        var url2 = "../salary/logmfsalary_process.php";
        var emp_code = document.getElementById("empcode").value;
        var bank_type = document.getElementById("banktype").value;
        var bank_no = document.getElementById("bankno").value;
        var pay_rate = document.getElementById("payrate").value;
        var amount = document.getElementById("amnt").value;
        var status = document.getElementById("stts").value;      
        var amtn = '₱ '+amount.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        var old_bank_type =  document.getElementById('bt'+emp_code).innerHTML;  
        var old_bank_no =  document.getElementById('bn'+emp_code).innerHTML;  
        var old_pay_rate =  document.getElementById('pr'+emp_code).innerHTML;  
        var old_amount  = document.getElementById('am'+emp_code).innerHTML;  
        var old_status =  document.getElementById('st'+emp_code).innerHTML;            

swal({                     
  title: "Are you sure?",
  text: "You want to update this employee salary details?",
  icon: "success",
  buttons: true,
  dangerMode: true,
})
    .then((verifyApp) => {
      if (verifyApp) {                      
        $.post (
            url,
            {
                action: 1,
                emp_code: emp_code ,
                bank_type: bank_type ,
                bank_no: bank_no ,
                pay_rate: pay_rate ,
                amount: amount ,               
                status: status 
                
            },
            function(data) { 
                console.log(data);
                swal({
                    title: "Success!", 
                    text: "Successfully updated the employee salary details!", 
                    icon: "success",
                }).then(function() {
                    $('#updateSal').modal('hide');
                        document.getElementById('bt'+emp_code).innerHTML =  bank_type;
                        document.getElementById('bn'+emp_code).innerHTML = bank_no;
                        document.getElementById('pr'+emp_code).innerHTML =  pay_rate;
                        document.getElementById('am'+emp_code).innerHTML =  amount;
                        document.getElementById('amtn'+emp_code).innerHTML =  amtn;
                        document.getElementById('st'+emp_code).innerHTML =   status;

                        if(bank_type !== old_bank_type){
                        new_data = bank_type;
                        old_data =  old_bank_type;
                        column_name =  'Bank Type';
                        insertMfSalLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(bank_no !== old_bank_no){
                        new_data = bank_no;
                        old_data =  old_bank_no;
                        column_name =  'Bank No.';         
                        insertMfSalLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(pay_rate !== old_pay_rate){
                        new_data = pay_rate;
                        old_data =  old_pay_rate;
                        column_name =  'Pay Type';         
                        insertMfSalLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(amount !== old_amount){
                        new_data = amount;
                        old_data =  old_amount;
                        column_name =  'Salary Rate';         
                        insertMfSalLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(status !== old_status){
                        new_data = status;
                        old_data =  old_status;
                        column_name =  'Status';         
                        insertMfSalLogs(url2,emp_code,column_name,new_data,old_data);
                        }                                                
                 }); 
            }
        );

  } else {
    swal({text:"You cancel the updating of employee salary details!",icon:"error"});
  }
});

}


function viewSalaryLogs(empcd)
 {
     $('#viewSalaryLogs').modal('toggle');
     var url = "../salary/viewsalarylogs_process.php";
     var emp_code = empcd;

     $.post (
        url,
        {
            emp_code: emp_code        
        },
        function(data) { 
            $("#contents2").html(data).show(); 
            $("#ViewSalaryLogs").tableExport({
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
                sheetname: 'Salary Logs'
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
  table = document.getElementById("allSalaryList");
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


getPagination('#allSalaryList');

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

      if (maxRows == 6000) {
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
    .val(20)
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
