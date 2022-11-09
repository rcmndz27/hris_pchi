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
        include("../salaryadjustment/salaryadjustmentlist.php");
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');
        $allSalaryList = new SalaryAdjList(); 
        $mf = new MasterFile();
        $dd = new DropDown();

        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President' || $empUserType == 'Finance'){
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }


    }    
?>
<link rel="stylesheet" href="../salaryadjustment/salaryadjustmentent.css">
<script type="text/javascript" src="../salaryadjustment/salaryadjustment_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<body onload="javascript:generateEmpStatus();">
<div class="container">
    <div class="section-title">
          <h1>ALL PAYROLL ADJUSTMENT MANAGEMENT LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-bill-wave fa-fw mr-1'> </i>Salary Adjustment Management List</li>
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
        <button type="button" class="btn btn-warning" id="salaryAdjEntry"><i class="fas fa-plus-circle"></i> Add New Salary Adjustment </button>                                              
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
                    <h5 class="modal-title bb" id="popUpModalTitle">SALARY ADJUSTMENT ENTRY <i class="fas fa-money-bill"></i></h5>
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
                                        <label class="control-label" for="description">Adjustment Category<span class="req">*</span>
                                        </label>
                                        <input class="form-control inputtext" type="text"  id="description" name="description" placeholder="ex.Salary,Allowances etc.">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="period_cutoff">Period Cutoff<span class="req">*</span>
                                        </label>
                                        <?php $dd->GenerateDropDown("ddcutoff", $mf->GetCutoffSalAdj("saladj")); ?>
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                    <label class="control-label" for="inc_decr"> &nbsp;
                                    </label> 
                                        <select type="select" class="form-select" id="inc_decr" name="inc_decr" >
                                            <option value="+">+</option>
                                            <option value="-">-<n/option>
                                        </select>                                                                           
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="amount">Adjustment Amount<span class="req">*</span></label>
                                        <input type="number" class="form-control inputtext" name="amount"
                                            id="amount"placeholder="Amount" maxlength="15">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="remarks">Remarks<span class="req">*</span></label>
                                        <input class="form-control" type="text"  id="remarks" name="remarks" placeholder="Remarks....">
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

    <div class="modal fade" id="updateSalAdj" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i>Update Salary Adjustment Entry </h5>
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
                                        <input type="text" class="form-control" name="saladjid" id="saladjid" hidden>
                                        <input type="text" class="form-control" name="empcode" id="empcode" readonly>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="descript">Adjustment Category<span class="req">*</span>
                                        </label>
                                        <input class="form-control inputtext" type="text"  id="descript" name="descript">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="ddcut">Period Cutoff<span class="req">*</span>
                                        </label>
                                        <?php $dd->GenerateDropDown("ddcut", $mf->GetCutoffSalAdj("saladj")); ?>
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                    <label class="control-label" for="inc_de"> &nbsp;
                                    </label> 
                                        <select type="select" class="form-select" id="inc_de" name="inc_de" >
                                            <option value="+">+</option>
                                            <option value="-">-<n/option>
                                        </select>                                                                           
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="amnt">Adjustment Amount<span class="req">*</span></label>
                                        <input type="number" class="form-control inputtext" name="amnt"
                                            id="amnt"placeholder="Amount" maxlength="15">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="remark">Remarks<span class="req">*</span></label>
                                        <input class="form-control" type="text"  id="remark" name="remark" placeholder="Remarks....">
                                    </div>
                                </div>                                                                                  
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateSalAdj()" ><i class="fas fa-check-circle"></i> Submit</button>
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
        var url = "../salaryadjustment/salaryadjustmentlist_process.php";
        var empStatus = $('#empStatus').val();

        $.post (
            url,
            {   
                empStatus:empStatus
                
            },
            function(data) { 
                $("#contents").html(data).show();
                $("#allSalaryAdjList").tableExport({
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
                    sheetname: 'SalaryAdjustmentEmployees'
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



    function editSalAdjModal(empcd,percutoff,descrip,amnts,rremark,inc,saladjid){
          
        $('#updateSalAdj').modal('toggle');

        var hidful = document.getElementById('empcode');
        hidful.value =  empcd;   

        var bnkt = document.getElementById('ddcut');
        bnkt.value =  percutoff;  

        var bno = document.getElementById('descript');
        bno.value =  descrip;  

        var pyrte = document.getElementById('amnt');
        pyrte.value =  amnts;  

        document.getElementById('remark').value =  rremark;
        document.getElementById('inc_de').value =  inc;  
        document.getElementById('saladjid').value =  saladjid;          
                            
    }


     function updateSalAdj()
    {

        $("body").css("cursor", "progress");
        var url = "../salaryadjustment/updatesalaryadjustment_process.php";
        var emp_code = document.getElementById("empcode").value;
        var ddcuts = $('#ddcut').children("option:selected").val();
        var ddcutoff = ddcuts.split(" - ");
        var period_from = ddcutoff[0];
        var period_to = ddcutoff[1];
        var description = document.getElementById("descript").value;
        var amount = document.getElementById("amnt").value;
        var inc_decr = document.getElementById("inc_de").value;
        var remarks = document.getElementById("remark").value;  
        var saladjid = document.getElementById("saladjid").value;  


        // console.log(saladjid);
        // return false;        

        $('#contents').html('');

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this employee salary adjustment details?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateSlAdj) => {
                          if (updateSlAdj) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        emp_code: emp_code ,
                                        period_from: period_from,
                                        period_to: period_to,
                                        description: description,
                                        amount: amount,
                                        inc_decr: inc_decr,               
                                        remarks: remarks,
                                        saladjid: saladjid 
                                        
                                    },
                                    function(data) { 
                                        swal({
                                        title: "Success!", 
                                        text: "Successfully updated employee salary adjustment detailss!", 
                                        type: "success",
                                        icon: "success",
                                        }).then(function() {
                                            location.href = '../salaryadjustment/salaryadjustmentlist_view.php';
                                        });

                                }
                                );

                          } else {
                            swal({text:"You cancel the updating of employee salary adjustment details!",icon:"error"});
                          }
                        });
   
                }
    
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allSalaryAdjList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1  || td[5].innerHTML.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}



getPagination('#allSalaryAdjList');

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
