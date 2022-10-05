<?php
session_start();

if (empty($_SESSION["userid"]))
{
    header( "refresh:1;url=../index.php" );
}
else
{
    include("../_header.php");
    include("../dtr/dtr-viewing.php");
    include('../elements/DropDown.php');
    include('../controller/MasterFile.php');
    $mf = new MasterFile();
    $dd = new DropDown();
    
        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' ||  $empUserType =='Team Manager')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        } 
}
?>

<link rel="stylesheet" type="text/css" href="../pages/dtr.css">
<script type="text/javascript" src="../dtr/dtr-viewing.js"></script>
<script src="<?= constant('NODE'); ?>xlsx/dist/xlsx.core.min.js"></script>
<script src="<?= constant('NODE'); ?>file-saverjs/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
          <h1>ALL EMPLOYEE DAILY TIME RECORD VIEWING</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-suitcase fa-fw'>
                        </i>&nbsp;ALL EMPLOYEE DAILY TIME RECORD VIEWING</b></li>
            </ol>
          </nav>
<form method="post">
        <div class="form-row pt-3">
            <label for="employee" class="col-form-label pad">EMPLOYEE:</label>
        <div class="col-md-3">
            <?php 

            if($empUserType == 'Team Manager'){
                $dd->GenerateSingleRepDropDown("empCode",$mf->GetAttEmployeeNamesRep("allemprepnames",$empCode));
            }else{
                $dd->GenerateSingleAttDropDown("empCode",$mf->GetAttEmployeeNames("allempnames")); 
            }
            
            ?>            
        </div>
        <label for="from" class="col-form-label pad">FROM:</label>
        <div class="col-md-2">
            <input type="date" id="dateFrom" class="form-control" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>
        <label for="to" class="col-form-label pad">TO:</label>
        <div class="col-md-2">
            <input type="date" id="dateTo" class="form-control" name="dateTo" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>
        <div class="col-md-2">
            <button type="submit" id="search" class="btn btn-secondary"><i class="fas fa-search-plus"></i> GENERATE
                </button>
        </div>
    </div>
</form>    

    <div id="dtrViewList" class="form-row pt-3">
    </div>
 </div>
</div>

<script type="text/javascript">
       
    function exportReportToExcel() {
  let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
  TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
    name: `export_attendance.xlsx`, // fileName you could use any name
    sheet: {
      name: 'Attendance' // sheetName
    }
  });
}
       
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
</script>




<?php include('../_footer.php');  ?>