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
            include("../leavebalance/leavebalancelist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allLeaveBalanceList = new LeaveBalanceList(); 
            $mf = new MasterFile();
            $dd = new DropDown();


        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        } 

    }    
?>
<link rel="stylesheet" type="text/css" href="../leavebalance/leavebalance_view.css">
<script type="text/javascript" src="../leavebalance/leavebalance_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL EMPLOYEE LEAVE BALANCE LIST</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw'>
                        </i>&nbsp;EMPLOYEE LEAVE BALANCE MANAGEMENT LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="LeaveBalanceEntry"><i class="fas fa-plus-circle"></i> ADD EMPLOYEE LEAVE BALANCE</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allLeaveBalanceList->GetAllLeaveBalanceList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">LEAVE BALANCE ENTRY <i class="fas fa-money-check"></i> </h5>
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
                                        <label class="control-label" for="emp_code">Employee Code/Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleDropDown("emp_code", $mf->GetAllEmployeeLeaveBalance("leavebalc")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earned_sl">Sick Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earned_sl" name="earned_sl" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earned_vl">Vacation Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earned_vl" name="earned_vl" placeholder="0">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earned_vl">Floating Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earned_fl" name="earned_fl" placeholder="0">
                                    </div>
                                </div>                                  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earned_sl_bank">Sick Leave Bank</label>
                                        <input class="form-control" type="number"  id="earned_sl_bank" name="earned_sl_bank" placeholder="0">
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

    <div class="modal fade" id="updateLvBal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE LEAVE BALANCE <i class="fas fa-money-check"></i> </h5>
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
                                <input type="text" class="form-control" name="empcode" id="empcode" hidden>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="empname">Employee Name</label>
                                        <input type="text" class="form-control" name="empname" id="empname" disabled>
                                    </div>
                                </div>                                  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earnedsl">Sick Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earnedsl" name="earnedsl" placeholder="0">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earnedvl">Vacation Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earnedvl" name="earnedvl" placeholder="0">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earnedvl">Floating Leave</label>
                                        <input class="form-control inputtext" type="number"  id="earnedfl" name="earnedfl" placeholder="0">
                                    </div>
                                </div>                                  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="earnedslbank">Sick Leave Bank</label>
                                        <input class="form-control" type="number"  id="earnedslbank" name="earnedslbank" placeholder="0">
                                    </div>
                                </div>    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status<span class="req">*</span></label>
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
                                <button type="button" class="btn btn-success" onclick="updateLvBal()"  ><i class="fas fa-check-circle"></i> Submit</button>
                            </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->        

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->


<script>


    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return swal({text:"Only numbers are allowed!",icon:"error"});;
        return true;
    }

 function editLvBalModal(empcd,empnm){

   
        $('#updateLvBal').modal('toggle');
        document.getElementById('empcode').value =  empcd;
        document.getElementById('empname').value =  empnm;   
        document.getElementById('earnedsl').value =  document.getElementById('sl'+empcd).innerHTML;  
        document.getElementById('earnedvl').value =  document.getElementById('vl'+empcd).innerHTML;   
        document.getElementById('earnedfl').value =  document.getElementById('fl'+empcd).innerHTML;   
        document.getElementById('earnedslbank').value =  document.getElementById('slb'+empcd).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+empcd).innerHTML;        
    }


     function updateLvBal()
    {

        var url = "../leavebalance/updateleavebalance_process.php";
        var emp_code = document.getElementById("empcode").value;
        var earned_sl = document.getElementById("earnedsl").value;
        var earned_vl = document.getElementById("earnedvl").value;
        var earned_fl = document.getElementById("earnedfl").value;
        var earned_sl_bank = document.getElementById("earnedslbank").value;
        var status = document.getElementById("stts").value;

     
                        swal({
                          title: "Are you sure?",
                          text: "You want to update this employee LeaveBalance details?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateLvBal) => {
                          if (updateLvBal) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        emp_code: emp_code ,
                                        earned_sl: earned_sl,
                                        earned_vl: earned_vl,
                                        earned_fl: earned_fl,
                                        earned_sl_bank: earned_sl_bank,               
                                        status: status 
                                        
                                    },
                                    function(data) { 
                                        swal({
                                            title: "Success!", 
                                            text: "Successfully updated the employee leave balance details!", 
                                            icon: "success",
                                        }).then(function() {
                                            $('#updateLvBal').modal('hide');
                                            document.getElementById('sl'+emp_code).innerHTML = earned_sl;
                                            document.getElementById('vl'+emp_code).innerHTML = earned_vl;
                                            document.getElementById('fl'+emp_code).innerHTML = earned_fl;
                                            document.getElementById('slb'+emp_code).innerHTML = earned_sl_bank;
                                            document.getElementById('st'+emp_code).innerHTML = status;
                                        }); 
                                    }
                                );


                          } else {
                            swal({text:"You cancel the updating of employee leave balance details!",icon:"error"});
                          }
                        });
   
                }
    

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allLeaveBalanceList");
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


getPagination('#allLeaveBalanceList');

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
