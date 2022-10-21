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
            include("../allowances/allowanceslist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allAllowancesList = new AllowancesList(); 
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
<link rel="stylesheet" type="text/css" href="../allowances/all_view.css">
<script type="text/javascript" src="../allowances/allowances_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<body onload="javascript:generateEmpStatus();">
<div class="container">
    <div class="section-title">
          <h1>ALL ALLOWANCE LIST</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-bill-wave fa-fw mr-1'>
                        </i>Allowance Management List</li>
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
        <button type="button" class="btn btn-secondary" id="allowancesEntry"><i class="fas fa-plus-circle"></i> Add New Employee Allowance </button>                                              
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-check mr-1"></i> Allowances Entry</h5>
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
                                        <?php $dd->GenerateSingleDropDown("emp_code", $mf->GetEmployeeNames("allempnames")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="benefit_id">Allowance Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("benefit_id", $mf->GetAllEmployeeAllowances("benlist")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="period_cutoff">Period Cutoff<span class="req">*</span>
                                        </label>
                                        <select type="select" class="form-select" id="period_cutoff" name="period_cutoff" >
                                            <option value="Both">Both</option>
                                            <option value="15th">15th</option>
                                            <option value="30th">30th</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="effectivity_date">Effectivity Date<span class="req">*</span>
                                    </label>                                        
                                        <input type="date" class="form-control inputtext" name="effectivity_date"
                                            id="effectivity_date" value="<?php  echo date('Y-m-d'); ?>" onkeydown="return false">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amount">Allowance Amount<span class="req">*</span></label>
                                        <input class="form-control" type="number"  id="amount" name="amount" step=".01">
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

    <div class="modal fade" id="updateAlw" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-check mr-1"></i>Update Allowances</h5>
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
                                        <label class="control-label" for="bank_type">Employee Code<span class="req">*</span></label>
                                        <input type="text" class="form-control" name="benfid" id="benfid" hidden>                                        
                                        <input type="text" class="form-control" name="empcode" id="empcode" hidden>
                                        <input type="text" class="form-control" name="empname" id="empname" readonly>  
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="benefitid">Allowance Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("benefitid", $mf->GetAllEmployeeAllowances("benlist")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="periodcutoff">Period Cutoff<span class="req">*</span>
                                        </label>
                                        <select type="select" class="form-select" id="periodcutoff" name="periodcutoff" >
                                            <option value="Both">Both</option>
                                            <option value="15th">15th</option>
                                            <option value="30th">30th</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="effectivitydate">Effectivity Date<span class="req">*</span>
                                    </label>                                        
                                        <input type="date" class="form-control" name="effectivitydate"
                                            id="effectivitydate">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="amnt">Allowance Amount<span class="req">*</span></label>
                                        <input class="form-control" type="number"  id="amnt" name="amnt" step=".01">
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
                                    <button type="button" class="btn btn-success" onclick="updateAlw()"  ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 

                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->     

<div class="modal fade" id="viewAlwLogs" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i>View Allowances Logs</h5>
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
<script>

    function generateEmpStatus()
    {
        document.getElementById("myDiv").style.display="block";
        var url = "../allowances/allowanceslist_process.php";
        var empStatus = $('#empStatus').val();

        $.post (
            url,
            {   
                empStatus:empStatus
                
            },
            function(data) { 
                $("#contents").html(data).show();
                $("#allAllowancesList").tableExport({
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
                    sheetname: 'AllowancesEmployees'
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


function insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data) {

    $.post (url2,
    {
        emp_code:emp_code,
        column_name: column_name,
        new_data: new_data,
        old_data: old_data
    });
}



 function editAlwModal(empcd,benfid,fname){

   
        $('#updateAlw').modal('toggle');
        document.getElementById('empcode').value =  empcd;  
        document.getElementById('empname').value =  fname;  
        document.getElementById('benfid').value =  benfid;           
        document.getElementById('benefitid').value =  document.getElementById('bnr'+benfid).innerHTML;  
        document.getElementById('periodcutoff').value =  document.getElementById('pc'+benfid).innerHTML;   
        document.getElementById('amnt').value =  document.getElementById('am'+benfid).innerHTML;  
        document.getElementById('effectivitydate').value =  document.getElementById('ed'+benfid).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+benfid).innerHTML;      

    }


function updateAlw()
{

var url = "../allowances/updateallowances_process.php";
var url2 = "../allowances/logsmfallowances_process.php";
var emp_code = document.getElementById("empcode").value;
var benefit_id = document.getElementById("benefitid").value;
var benfid = document.getElementById("benfid").value;
var e = document.getElementById("benefitid");
var benefitid = e.options[e.selectedIndex].text;        
var period_cutoff = document.getElementById("periodcutoff").value;
var amount = document.getElementById("amnt").value;
var amtn = '₱ '+amount.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");        
var effectivity_date = document.getElementById("effectivitydate").value; 
var status = document.getElementById("stts").value; 

var old_benefitid =  document.getElementById('bn'+benfid).innerHTML;  
var old_period_cutoff =  document.getElementById('pc'+benfid).innerHTML;   
var old_amount =  document.getElementById('am'+benfid).innerHTML;  
var old_effectivity_date =  document.getElementById('ed'+benfid).innerHTML;  
var old_status =  document.getElementById('st'+benfid).innerHTML;    

        swal({
          title: "Are you sure?",
          text: "You want to update this employee allowances details?",
          icon: "success",
          buttons: true,
          dangerMode: true,
        })
        .then((updateAlw) => {
          if (updateAlw) {
                $.post (
                    url,
                    {
                        action: 1,
                        emp_code: emp_code ,
                        rowid : benfid,
                        benefit_id: benefit_id,
                        period_cutoff: period_cutoff,
                        amount: amount ,               
                        effectivity_date: effectivity_date,
                        status: status 
                        
                    },
                function(data) { 
                    swal({
                        title: "Success!", 
                        text: "Successfully updated the employee allowances details!", 
                        icon: "success",
                    }).then(function() {
                        $('#updateAlw').modal('hide');
                        document.getElementById('bn'+benfid).innerHTML = benefitid;
                        document.getElementById('bnr'+benfid).innerHTML = benefit_id;
                        document.getElementById('pc'+benfid).innerHTML = period_cutoff;
                        document.getElementById('am'+benfid).innerHTML = amount;
                        document.getElementById('amtn'+benfid).innerHTML = amtn;
                        document.getElementById('ed'+benfid).innerHTML = effectivity_date;
                        document.getElementById('st'+benfid).innerHTML = status;
                        if(benefitid !== old_benefitid){
                        new_data = benefitid;
                        old_data =  old_benefitid;
                        column_name =  'Allowances Name';
                        insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(period_cutoff !== old_period_cutoff){
                        new_data = period_cutoff;
                        old_data =  old_period_cutoff;
                        column_name =  'Period Cutoff';         
                        insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(effectivity_date !== old_effectivity_date){
                        new_data = effectivity_date;
                        old_data =  old_effectivity_date;
                        column_name =  'Effectivity Date';         
                        insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(amount !== old_amount){
                        new_data = amount;
                        old_data =  old_amount;
                        column_name =  'Allowances Amount';         
                        insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data);
                        }if(status !== old_status){
                        new_data = status;
                        old_data =  old_status;
                        column_name =  'Status';         
                        insertMfAlwLogs(url2,emp_code,column_name,new_data,old_data);
                        }                                                                          
                    }); 
                }
            );

      } else {
        swal({text:"You cancel the updating of employee allowances details!",icon:"error"});
      }
    });

}
    

function viewAlwLogs(empcd)
 {
     $('#viewAlwLogs').modal('toggle');
     var url = "../allowances/viewallowanceslogs_process.php";
     var emp_code = empcd;

     $.post (
        url,
        {
            emp_code: emp_code        
        },
        function(data) { 
            $("#contents2").html(data).show(); 
            $("#ViewAlwLogs").tableExport({
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
                sheetname: 'Allowances Logs'
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
  table = document.getElementById("allAllowancesList");
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


getPagination('#allAllowancesList');

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
