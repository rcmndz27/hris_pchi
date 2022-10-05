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
        include("../users/userslist.php");
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');
        $allUsersList = new UsersList(); 
        $mf = new MasterFile(); 
        $dd = new DropDown();

        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }

    }    
?>
<link rel="stylesheet" href="../users/users.css">
<script type="text/javascript" src="../users/users_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<div class="container">
    <div class="section-title">
          <h1>ALL USERS LIST</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw'>
                        </i>&nbsp;USERS MANAGEMENT LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="usersEntry"><i class="fas fa-plus-circle"></i> ADD NEW USER ACCOUNT </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allUsersList->GetAllUsersList(); ?>
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
                    <h5 class="modal-title bb" id="popUpModalTitle">USERS ENTRY  <i class="fas fa-minus-circle"></i></h5>
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
                                        <label class="control-label" for="empcode name">Employee Code/Name<span class="req">*</span></label>
                                        <?php $dd->GenerateSingleGenDropDown("emp_code", $mf->GetUserAccntNames("allusracnt")); ?> 
                                    </div>
                                </div> 
<!--                               <div class="col-12">
                                <div class="form-group">
                                 <label class="control-label" for="users_id">User Password<span class="req">*</span>
                                 </label>
                                <div class="input-group mb-3">
                                  <input name="password" type="password"  class="form-control inputtext" id="password" nameplaceholder="Password" required="true" aria-label="password" aria-describedby="basic-addon1" />
                                  <div class="input-group-append">
                                    <span class="input-group-text" onclick="confirmpassword_show_hide();">
                                      <i class="fas fa-eye" id="cfshow_eye"></i>
                                      <i class="fas fa-eye-slash d-none" id="cfhide_eye"></i>
                                    </span>
                                  </div>
                                </div>
                                </div>
                              </div>  -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="emp_level">User Level<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("emp_level", $mf->GetAllEmployeeLevelWrds("emp_levelwrds")); ?> 
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status
                                            <span class="req">*</span></label>
                                        <select type="select" class="form-select" id="status" name="status" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                               
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

<div class="modal fade" id="updateUsrs" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE USERS   <i class="fas fa-minus-circle"></i></h5>
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
                                        <label class="control-label" for="empcode name">Employee Code/Name<span class="req">*</span></label>
                                  <input name="empcode" id="empcode" class="form-control" readonly />
                                  <input name="usrid" id="usrid" class="form-control" hidden />
                                    </div>
                                </div> 
<!--                               <div class="col-12">
                                <div class="form-group">
                                 <label class="control-label" for="password">User Password<span class="req">*</span>
                                 </label>
                                <div class="input-group mb-3">
                                  <input name="pssword" type="password"  class="form-control inputtext" id="pssword" nameplaceholder="Password" required="true" aria-label="password" aria-describedby="basic-addon1" />
                                  <div class="input-group-append">
                                    <span class="input-group-text" onclick="password_show_hide();">
                                      <i class="fas fa-eye" id="cfdshow_eye"></i>
                                      <i class="fas fa-eye-slash d-none" id="cfdhide_eye"></i>
                                    </span>
                                  </div>
                                </div>
                                </div>
                              </div>  -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="usrtyp">User Level<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("usrtyp", $mf->GetAllEmployeeLevelWrds("emp_levelwrds")); ?> 
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status
                                            <span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stats" name="stats" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                               
                                    </div>
                                </div>                                                 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateUsrs()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->        

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

<script>

function confirmpassword_show_hide() {
  var xcfd = document.getElementById("password");
  var cfdshow_eye = document.getElementById("cfdshow_eye");
  var cfdhide_eye = document.getElementById("cfdhide_eye");
  cfdhide_eye.classList.remove("d-none");
  if (xcfd.type === "password") {
    xcfd.type = "text";
    cfdshow_eye.style.display = "none";
    cfdhide_eye.style.display = "block";
  } else {
    xcfd.type = "password";
    cfdshow_eye.style.display = "block";
    cfdhide_eye.style.display = "none";
  }
}

function password_show_hide() {
  var xcf = document.getElementById("pssword");
  var cfshow_eye = document.getElementById("cfshow_eye");
  var cfhide_eye = document.getElementById("cfhide_eye");
  cfhide_eye.classList.remove("d-none");
  if (xcf.type === "password") {
    xcf.type = "text";
    cfshow_eye.style.display = "none";
    cfhide_eye.style.display = "block";
  } else {
    xcf.type = "password";
    cfshow_eye.style.display = "block";
    cfhide_eye.style.display = "none";
  }
}


    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return swal({text:"Only numbers are allowed!",icon:"error"});;
        return true;
    }

      function editUsrModal(empcd,name){

   
        $('#updateUsrs').modal('toggle');
        document.getElementById('empcode').value =  name;   
        document.getElementById('usrid').value =  empcd;                            
        document.getElementById('usrtyp').value = document.getElementById('usr'+empcd).innerHTML;
        document.getElementById('stats').value = document.getElementById('st'+empcd).innerHTML;
    }   


     function updateUsrs()
    {


        var url = "../users/updateusers_process.php";
        var emp_code = document.getElementById("usrid").value;
        var status = $('#stats').children("option:selected").val();
        var usertype = $('#usrtyp').children("option:selected").val();
        // var userpassword = document.getElementById("pssword").value;

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this employee users details?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateUsrsd) => {
                          if (updateUsrsd) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        emp_code: emp_code ,
                                        // userpassword: userpassword,
                                        status: status,
                                        usertype: usertype
                                        
                                    },
                                    function(data) { 
                                        swal({
                                            title: "Success!", 
                                            text: "Successfully updated the user account details!", 
                                            type: "success",
                                            icon: "success",
                                        }).then(function() {
                                            $('#updateUsrs').modal('hide');
                                            document.getElementById('st'+emp_code).innerHTML = status;
                                            document.getElementById('usr'+emp_code).innerHTML = usertype;
                                         }); 
                                    }
                                );


                          } else {
                            swal({text:"You cancel the updating of employee users details!",icon:"error"});
                          }
                        });
   
                }


    function deleteLogsModal(empcd)
    {

        var url = "../users/unblockusers_process.php";
        var emp_code = empcd;

                        swal({
                          title: "Are you sure?",
                          text: "You want to unblocked this user account?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((unblkusr) => {
                          if (unblkusr) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        emp_code: emp_code 
                                        
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully unlocked the user account!.", 
                                            icon: "success",
                                        }).then(function() {
                                            document.querySelector('#ub'+emp_code).remove();
                                        });
                                    }
                                );

                          } else {
                            swal({text:"You cancel the unblocking of the user account!",icon:"error"});
                          }
                        });
   
                }

function resetPassword(empcd)
        {

                 var url = "../users/resetPasswordProcess.php";    
                 var emp_code = empcd;   

                    swal({
                          title: "Are you sure?",
                          text: "You want to reset this user account password?",
                          icon: "warning",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((restPSS) => {
                          if (restPSS) {
                            $.post (
                                    url,
                                    {
                                        choice: 1,
                                        emp_code:emp_code
                                    },
                                    function(data) { 
                                        // console.log(data);
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully reset the password!", 
                                            type: "info",
                                            icon: "info",
                                            });  
                                    }
                                );
                          } else {
                            swal({text:"You stop the cancellation of password reset.",icon:"error"});
                          }
                        });
      
    }                

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allUsersList");
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



getPagination('#allUsersList');

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
