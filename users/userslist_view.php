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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
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
              <li class="breadcrumb-item active bb" aria-current="page"><i class='fas fa-money-bill-wave fa-fw mr-1'>
                        </i>Users Management List</li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="usersEntry"><i class="fas fa-plus-circle"></i> Add New User Account</button>
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-minus-circle mr-1"></i>Users Entry</h5>
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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-minus-circle mr-1"></i>Update Users</h5>
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
$(document).ready( function () {

$('#allUsersList').DataTable({
                  pageLength : 12,
                  lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                  dom: 'Bfrtip',
                  buttons: [
                      'pageLength',
                      {
                          extend: 'excel',
                          title: 'UserList', 
                          text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                          init: function(api, node, config) {
                              $(node).removeClass('dt-button')
                              },
                              className: 'btn bg-transparent btn-sm'
                      },
                      {
                          extend: 'pdf',
                          title: 'UserList', 
                          text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                          init: function(api, node, config) {
                              $(node).removeClass('dt-button')
                              },
                              className: 'btn bg-transparent'
                      }
                  ] ,
                  "bPaginate": true,
                  "bLengthChange": false,
                  "bFilter": true,
                  "bInfo": true,
                  "bAutoWidth": false                       
              }); 
});    

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

</script>



<?php include("../_footer.php");?>
