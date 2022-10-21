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

        if ($empUserType == 'Admin' || $empUserType == 'Finance' || $empUserType == 'Group Head' || $empUserType == 'President' 
            || $empUserType == 'HR Generalist')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }

    }
        
?>

<link rel="stylesheet" type="text/css" href="../payslip/payslip.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
          <h1>PAYROLL REGISTER VIEW</h1>
        </div>
    <div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-money-check fa-fw mr-1'>
                        </i>Payslip All Employee Viewing</li>
            </ol>
          </nav>

      <div class="form-row pt-3">
                <label for="employeepaylist" class="col-form-label pad">Employee:</label>
                <div class="col-md-3">      
                        <?php $dd->GenerateSingleGenDropDown("emppay", $mf->GetAttEmployeeNames("allempnames")); ?>
                </div>

                <label for="payroll_period" class="col-form-label pad">Pay-Period:</label>

                <div class='col-md-3'>
                    <?php $dd->GenerateDropDown("ddcutoff", $mf->GetAllPayCutoff("paycut")); ?>
                </div>                


                <div class="col-md-2 d-flex">
                        <button type="button" id="search" class="btn btn-primary" onclick="filterAtt()">
                            <i class="fas fa-search-plus mr-1"></i>Generate                       
                        </button>
                        <a href='javascript:generatePDF()'><img src="../img/expdf.png" height="40" class="pdfimg" id='expdf'></a>                        
                </div>

        </div>

    <div class="row pt-4">
        <div class="col-md-12 mbot">
            <div class="d-flex justify-content-center">
                <div id='contents'></div>     
            </div>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
    $('#expdf').hide();
    function filterAtt()
    {

        $('#expdf').show();
        document.getElementById("myDiv").style.display="block";
        var url = "../payslip/payslips_process.php";
        var dt = 'PCH'+$('#emppay').val();
        var cutoff = $('#ddcutoff').children("option:selected").val();
        var data = cutoff.split(" - ");
        $('#expdf').show();

        // console.log(dt);
        // console.log(data[0]);
        // console.log(data[1]);

        // return false;

        $.post (
            url,
            {
                _action: 1,
                _empCode: dt,
                _from: data[0],
                _to: data[1]
                
            },
            function(data) { $("#contents").html(data).show(); 
            document.getElementById("myDiv").style.display="none";
        }
        );
    }

    function generatePDF() {
        var dt = $('#emppay').val();
        var cfta = document.getElementById(dt).innerHTML;
        var data = cfta.split(" - ");
        var stringToReplace = data[0];
        var desired = cfta.replace(/[^\w\s]/gi, '');

        html2canvas(document.getElementById('payslipsList'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("PaySlips"+desired+".pdf");
            }
        });
    }
</script>
<?php include('../_footer.php');  ?>
