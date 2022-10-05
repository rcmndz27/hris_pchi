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
    include('../payslip/payslips.php');
    include('../elements/DropDown.php');
    include('../controller/MasterFile.php');

    $mf = new MasterFile();
    $dd = new DropDown();
    $empCode = $_SESSION['userid'];
    $empInfo = new EmployeeInformation();
    $empInfo->SetEmployeeInformation($_SESSION['userid']);
}

?>
<link rel="stylesheet" type="text/css" href="../payslip/payslip.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
      <h1>PAYSLIP VIEW</h1>
  </div>
  <div class="main-body mbt">

      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="main-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw'>
          </i>&nbsp;PAYSLIP VIEW</b></li>
      </ol>
  </nav>

  <div class="form-row pt-3">
    <select class="form-control" id="empCode" name="empCode" value="" hidden>
        <option value="<?php echo $empCode ?>"><?php echo $empCode ?></option>
    </select>
    <select class="form-control" id="empName" name="empName" value="" hidden>
        <option value="<?php echo $empName ?>"><?php echo $empName ?></option>
    </select>
    <label for="payroll_period" class="col-form-label pad">PAYROLL PERIOD:</label>

    <div class='col-md-3'>
        <?php $dd->GenerateDropDown("ddcutoff", $mf->GetTKList("tkview")); ?>
    </div>

    <div class="col-md-3 d-flex">
        <button type="button" id="search" class="btn btn-secondary" onclick="genePayrl()" ><i class="fas fa-search-plus"></i>GENERATE                    
        </button>
        <a href='javascript:generatePDF()'><img src="../img/expdf.png" height="40" class="pdfimg" id='expdf'></a>
    </div>
</div>
<div class="row pt-4">
    <div class="col-md-12 mbot d-flex justify-content-center">
        <div id='contents'></div>
    </div>
</div>
</div>
</div>

<script type="text/javascript">

    var a = document.getElementById("ddcutoff").value;
    var b = document.getElementById("search");
    console.log(a);
    if (a == null || a == "") {
      document.getElementById('ddcutoff').disabled = true
      document.getElementById("search").disabled = true;
      b.style.display = 'none';
    }else{

    }

    $('#expdf').hide();


    function genePayrl()
    {

        $('#expdf').show();
        document.getElementById("myDiv").style.display="block";
        var url = "../payslip/payslips_process.php";
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var dates = cutoff.split(" - ");
        var empCode = $('#empCode').children("option:selected").val();

        $.post (
            url,
            {
                _action: 1,
                _from: dates[0],
                _to: dates[1],
                _empCode: empCode
                
            },
            function(data) { $("#contents").html(data).show(); 
            document.getElementById("myDiv").style.display="none";}

            );
    }

    function generatePDF() {

            var cutoff = $('#ddcutoff').children("option:selected").val();
            var dates = cutoff.split(" - ");
            html2canvas(document.getElementById('payslipsList'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("PaySlip"+dates[0]+"to"+dates[1]+".pdf");
            }
        });
    }
</script>

<?php include('../_footer.php');  ?>
