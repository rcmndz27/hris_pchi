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
        include('../payroll/payroll_reg.php');
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');
        $empCode = $_SESSION['userid'];
        $empInfo->SetEmployeeInformation($_SESSION['userid']);
        $empUserType = $empInfo->GetEmployeeUserType();
        $empInfo = new EmployeeInformation();
        $mf = new MasterFile();
        $dd = new DropDown();
        $payrollApplication = new PayrollRegApplication();

            if($empUserType == 'Admin' || $empUserType == 'Group Head' || $empUserType == 'Finance' || $empUserType == 'Finance2') {

            }else{
                        echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
                        echo "window.location.href = '../index.php';";
                        echo "</script>";
            }
    }
        
?>
<link rel="stylesheet" type="text/css" href="../payroll/payroll_reg.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<div class="container">
    <div class="section-title">
          <h1>PAYROLL REGISTER VIEW</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-check fa-fw mr-1'>
                        </i>Payroll Register View</li>
            </ol>
          </nav>

      <div class="form-row mb-2">
        <label for="payroll_period" class="col-form-label pad">Payroll Period:</label>
        <div class='col-md-3'>
            <select class="form-control" id="empCode" name="empCode" value="" hidden>
                <option value="<?php echo $empCode ?>"><?php echo $empCode ?></option>
            </select>
           <?php $dd->GenerateDropDown("ddcutoff", $mf->GetAllPayCutoffReg("paycutreg")); ?>
        </div>           
        <button type="button" id="search" class="btn btn-danger mr-2" onmousedown="javascript:deletePayReg()">
            <i class="fas fa-backspace mr-1 text-white"></i> Delete                      
        </button> 
        <button class='btn btn-success' id='confirm' onclick='ConfirmPayRegView()'>
          <i class='fas fa-check-square'></i> 
        Confirm Payroll Register
        </button>                                             
        </div>
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control" id="empCode" name="empCode" value="" hidden>
                        <option value="<?php echo $empCode ?>"><?php echo $empCode ?></option>
                    </select>
                        <button type="button" id="search" hidden>Generate</button>
                            <?php $payrollApplication->GetPayrollRegList()?>
                </div>
            </div>
    </div>
</div>
<script>

$(document).ready( function () {

  var cutoff = $('#ddcutoff').children("option:selected").val();
  var dates = cutoff.split(" - ");

  $('#payrollRegList').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Payroll from'+dates[0]+' to '+dates[1], 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Payroll from'+dates[0]+' to '+dates[1], 
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


    var a = document.getElementById("ddcutoff").value;
    var b = document.getElementById("search");
    var c = document.getElementById("confirm");
    if (a == null || a == "") {
      document.getElementById('ddcutoff').disabled = true
      document.getElementById("search").disabled = true;
      document.getElementById("confirm").disabled = true;
      b.style.display = 'none';
      c.style.display = 'none';
    }else{

    }

function deletePayReg()
{
 
  $(".fa-file-export").remove();
  $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');    
  var empCode = $('#empCode').children("option:selected").val();
  var url = "../payroll/payrollRegViewProcess.php";
  var cutoff = $('#ddcutoff').children("option:selected").val();
  var dates = cutoff.split(" - ");  

  swal({
    title: "Are you sure?",
    text: "You want to delete this generated payroll?",
    icon: "info",
    buttons: true,
    dangerMode: true,
  })
  .then((delPayGen) => {
    if (delPayGen) {
      
      $.post (
        url,
        {
          choice: 2,
          empCode:empCode,
          date_from: dates[0],
          date_to: dates[1]
        },
        function(data) {
          // console.log(data);
          swal({
            title: "Success!", 
            text: "Successfully deleted generated payroll!", 
            type: "success",
            icon: "success",
          }).then(function() {
            location.href = '../payroll/payroll_view_register.php';
          });                                             

        }
        );
    } else {
      
      swal({text:"You cancel the deletion of generated payroll!",icon:"error"});
    }
  });         
}


function ConfirmPayRegView()
{
 
  var empCode = $('#empCode').children("option:selected").val();
  var url = "../payroll/payrollRegViewProcess.php";

  swal({
    title: "Are you sure?",
    text: "You want to confirm this payroll for approval?",
    icon: "info",
    buttons: true,
    dangerMode: true,
  })
  .then((savePayroll) => {
    if (savePayroll) {
      
      $.post (
        url,
        {
          choice: 1,
          emp_code: empCode
        },
        function(data) {
          console.log("success: "+ data);
          swal({
            title: "Success!", 
            text: "Successfully confirmed payroll register details!", 
            type: "success",
            icon: "success",
          }).then(function() {
            location.href = '../payroll/payroll_view_register.php';
          });                                             

        }
        );
    } else {
      
      swal({text:"You cancel the confirmation of payroll register!",icon:"error"});
    }
  });         
}

</script>
<?php include('../_footer.php');  ?>
