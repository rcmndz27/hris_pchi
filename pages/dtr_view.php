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
        include('../controller/dtr.php');
        $employeedtr = new EmployeeDTR();
    }
        
?>
<link rel="stylesheet" type="text/css" href="../pages/dtr.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type='text/javascript' src='../js/dtr.js'></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
          <h1>MY ATTENDANCE</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-calendar fa-fw mr-1'>
                </i>My Attendance</b></li>
            </ol>
          </nav>
    <div class="form-row mb-3">
            <input type="text" name="empCode" id="empCode" value="<?php $empCode ?>" hidden>
                <label class="control-label pad" for="dateFrom">From:</label>
            <div class="col-md-2">
                <input type="date" id="dateFrom" class="form-control" name="dateFrom"
                    value="<?php echo date('Y-m-d'); ?>" onkeydown="return false">
            </div>
                <label class="control-label pad" for="dateTo">To:</label>
            <div class="col-md-2">
                <input type="date" id="dateTo" class="form-control" name="dateTo" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false">
            </div>
            <div class="col-md-2">
                <button type="submit" id="search" class="btn btn-warning mb-2" ><i class="fas fa-search-plus"></i> Generate
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="dtrViewList" class="table-responsive-sm table-body"></div>
                </div>
            </div>
        </div> 
    </div>
</div>
<script type="text/javascript">

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
