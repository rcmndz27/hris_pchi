<?php
    session_start();

    $empID = $_SESSION['userid'];


    if (empty($_SESSION['userid']))
    {
        include_once('../loginfirst.php');
        exit();
    }
    else
    {
        include('../_header.php');
        include('../payroll/payrollApproval.php');
        include('../payroll/payrollapp_reg.php');

        if ($empUserType == 'Admin' || $empUserType == 'Finance' ||  $empUserType == 'Group Head' || $empUserType == 'President')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        } 
    }
?>
<link rel="stylesheet" type="text/css" href="../payroll/payroll_app.css">
<link rel="stylesheet" type="text/css" href="../payroll/payroll_appreg.css">
<div class="container">
    <div class="section-title">
          <h1>PAYROLL LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-check fa-fw mr-1'></i>Payroll Register List</li>
            </ol>
          </nav>

            <div class="row pt-3">
                <div class="col-md-12">
                    <div class="panel-body">
                        <div id="contents" class="table-responsive-sm table-body">
                            <?php ShowAllPayroll(); ?>                             
                        </div>
                    </div>
                </div>
            </div>

    <div class="modal fade" id="viewPayReg" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-money-bill mr-1"></i> View Payroll Register</h5>
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


<script type='text/javascript'>


function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("payrollAppRegList");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}



function ViewPyReg(perfrom,perto,stats)
    {
        $('#viewPayReg').modal('toggle');

        var url = "../payroll/payrollapp_process.php";
        var period_from = perfrom;
        var period_to = perto;

        // console.log(period_from);
        // console.log(period_to);
        // console.log(payroll_status);
        // return false;

        $.post (
            url,
            {
                _action: 1,
                period_from: period_from,
                period_to: period_to        
            },
            function(data) { 
                $("#contents2").html(data).show(); 
                $("#payrollAppRegList").tableExport({
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
                    sheetname: 'Payroll Register View'
                });
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i> ');
            }
       );
    }

    function ApprovePayroll()
    {
                 var url = "../payroll/payrollApprovalProcess.php";     
                    swal({
                          title: "Are you sure?",
                          text: "You want to approve this payroll?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((appPyrll) => {
                          if (appPyrll) {
                            $.post (
                                    url,
                                    {
                                        choice: 1
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully approved payroll details!", 
                                            type: "success",
                                            icon: "success",
                                            }).then(function() {
                                                location.href = '../payroll/payrollApproval_view.php';
                                            });   
                                    }
                                );

                          } else {
                            swal({text:"You cancel the approval of payroll!",icon:"error"});
                          }
                        });
      
    }

    function RejectPayroll()
    {
                 var url = "../payroll/payrollApprovalProcess.php";     
                    swal({
                          title: "Are you sure?",
                          text: "You want to reject this payroll?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((appPyrll) => {
                          if (appPyrll) {
                            $.post (
                                    url,
                                    {
                                        choice: 2
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Oops!", 
                                            text: "Successfully rejected payroll details!", 
                                            type: "info",
                                            icon: "info",
                                            }).then(function() {
                                                location.href = '../payroll/payrollApproval_view.php';
                                            });  
                                    }
                                );
                            location.reload();
                          } else {
                            swal({text:"You cancel the rejection of payroll!",icon:"error"});
                          }
                        });
      
    }

    

</script>

<?php include('../_footer.php');?>