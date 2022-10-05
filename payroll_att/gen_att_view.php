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
    include('../payroll_att/gen_att.php');
    include('../elements/DropDown.php');
    include('../controller/MasterFile.php');

    $mf = new MasterFile();
    $dd = new DropDown();
    $empCode = $_SESSION['userid'];

    if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President')
    {

    }else{
        echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
        echo "window.location.href = '../index.php';";
        echo "</script>";
    }

}

?>

<link rel="stylesheet" type="text/css" href="../newhireaccess/newemp.css">
<div id = "myDiv" style="display:none;" class="loader"></div>
<div class="container">
    <div class="section-title">
          <h2></h2>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-users fa-fw'>
                        </i>&nbsp; GENERATE SCRIPTS MODULE</b></li>
            </ol>
          </nav>

                <ul class="nav nav-tabs tabrec" id="myTab" name="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="attendance-tab" name="attendance-tab" data-toggle="tab"
                            href="#attendance" role="tab" aria-controls="attendance" aria-selected="true">Attendance</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="wfhome-tab" name="wfhome-tab" data-toggle="tab" href="#wfhome"
                            role="tab" aria-controls="wfhome" aria-selected="false">WORK FROM HOME</a>
                    </li>    
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="offb-tab" name="offb-tab" data-toggle="tab" href="#offb"
                            role="tab" aria-controls="offb" aria-selected="false">OFFICIAL BUSINESS</a>
                    </li>                                            
<!--                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="correct-tab" name="correct-tab" data-toggle="tab" href="#correct"
                            role="tab" aria-controls="correct" aria-selected="false">DTR Correction</a>
                    </li>    -->                 
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="leave-tab" name="leave-tab" data-toggle="tab" href="#leave"
                            role="tab" aria-controls="leave" aria-selected="false">Leave</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="overtime-tab" name="overtime-tab" data-toggle="tab" href="#overtime" role="tab"
                            aria-controls="overtime" aria-selected="false">Overtime</a>
                    </li>             
                </ul>
               
                <!-- ATTENDANCE -->
                <div class="tab-content" id="myTabContent">
                    <input type="text" name="eMplogName" id="eMplogName" value="<?php  echo $empName; ?>" hidden>
                    <div class="tab-pane fade active show" id="attendance" role="tabpanel" aria-labelledby="attendance-tab">
                      <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    Generate Attendance For Payroll
                                </legend>
                             </div>
                             <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   

                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <?php $dd->GenerateDropDown("ungenpco", $mf->GetUnGenPayrollCutoff("ungenpco")); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" id="search" class="btn btn-secondary" onclick="genAttPay();">
                                            <i class="fas fa-search-plus"></i>GENERATE                       
                                        </button> 
                                    </div>
                                </div>
                            </div>      
                    </fieldset>
                </div>
                <!-- WORK FROM HOME -->
                <div class="tab-pane fade" id="wfhome" role="tabpanel" aria-labelledby="wfhome-tab">
                    <fieldset class="fieldset-border">
                        <div class="d-flex justify-content-center">
                            <legend class="fieldset-border pad">
                                Generate Work From Home for Payroll
                            </legend>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $dd->GenerateDropDown("ungendtrc", $mf->UnGetAllCutoffPay("unpayview")); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <button type="button" id="search" class="btn btn-secondary" onclick="genWfhPay();">
                                        <i class="fas fa-search-plus"></i>GENERATE                       
                                    </button> 
                                </div>
                            </div>
                        </div>      
                    </fieldset>
                </div>
                <!-- OB -->
                <div class="tab-pane fade" id="offb" role="tabpanel" aria-labelledby="offb-tab">
                    <fieldset class="fieldset-border">
                        <div class="d-flex justify-content-center">
                            <legend class="fieldset-border pad">
                                Generate Official Business for Payroll
                            </legend>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   

                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $dd->GenerateDropDown("ungendtrc", $mf->UnGetAllCutoffPay("unpayview")); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <button type="button" id="search" class="btn btn-secondary" onclick="genObPay();">
                                        <i class="fas fa-search-plus"></i>GENERATE                       
                                    </button> 
                                </div>
                            </div>
                        </div>      
                    </fieldset>
                </div>                                    
                <!-- DTRC -->
<!--                 <div class="tab-pane fade" id="correct" role="tabpanel" aria-labelledby="correct-tab">
                    <fieldset class="fieldset-border">
                        <div class="d-flex justify-content-center">
                            <legend class="fieldset-border pad">
                                Generate DTR Correction for Payroll
                            </legend>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   

                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $dd->GenerateDropDown("ungendtrc", $mf->UnGetAllCutoffPay("unpayview")); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <button type="button" id="search" class="btn btn-secondary" onclick="genDtrcPay();">
                                        <i class="fas fa-search-plus"></i>GENERATE                       
                                    </button> 
                                </div>
                            </div>
                        </div>      
                    </fieldset>
                </div>  -->               
                <!-- leave -->
                <div class="tab-pane fade" id="leave" role="tabpanel" aria-labelledby="leave-tab">
                    <fieldset class="fieldset-border">
                        <div class="d-flex justify-content-center">
                            <legend class="fieldset-border pad">
                                Generate Leave for Payroll
                            </legend>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   

                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <?php $dd->GenerateDropDown("ungenleave", $mf->UnGetAllCutoffPay("unpayview")); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <button type="button" id="search" class="btn btn-secondary" onclick="genLeavePay();">
                                        <i class="fas fa-search-plus"></i>GENERATE                       
                                    </button> 
                                </div>
                            </div>
                        </div>      
                    </fieldset>
                </div>
                <!-- OVERTIME -->
                    <div class="tab-pane fade" id="overtime" role="tabpanel" aria-labelledby="overtime-tab">
                        <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    Generate Overtime for Payroll
                                </legend>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label for="employeepaylist" class="col-form-label pad">PAYROLL PERIOD:</label>   

                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <?php $dd->GenerateDropDown("ungenot", $mf->UnGetAllCutoffPay("unpayview")); ?>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <button type="button" id="search" class="btn btn-secondary" onclick="genOtPay();">
                                            <i class="fas fa-search-plus"></i>GENERATE                       
                                        </button> 
                                    </div>
                                </div>
                            </div>      
                        </fieldset>
                    </div>

                <!-- dtr -->
                    <div class="tab-pane fade" id="dtr" role="tabpanel" aria-labelledby="dtr-tab">
                        <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    Generate DTR from Biometrics
                                </legend>
                            </div>
                        <div class="d-flex justify-content-center">
                            <div class="form-row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <button type="button" id="search" class="btn btn-secondary" onclick="genDTR();">
                                                <i class="fas fa-search-plus"></i>GENERATE                       
                                            </button> 
                                        </div>
                                    </div>
                                  </div>
                            </div>      
                        </fieldset>
                    </div>                    
    </div>
</div>

<script type="text/javascript">

    function genAttPay()
    {

        var url = "../payroll_att/gen_att_process.php";
        var date = $('#ungenpco').children("option:selected").val();
        var dates = date.split(" - ");
        var eMplogName = $('#eMplogName').val();

        // console.log(dates[0]);  
        // console.log(dates[1]);  
        // console.log(eMplogName);
        // return false;

        swal({
          title: "Are you sure?",
          text: "You want to generate this attendance?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((genAttPay) => {
          document.getElementById("myDiv").style.display="block";
          if (genAttPay) {
            $.post (
                url,
                {
                    action: 1,
                    pyrollco_from: dates[0] ,
                    pyrollco_to: dates[1] ,
                    eMplogName: eMplogName                           
                },
                function(data) {                   
                    swal({
                        title: "Success!", 
                        text: "Successfully generated attendance!", 
                        type: "success",
                        icon: "success",
                    }).then(function() {                        
                        location.href = '../payroll_att/gen_att_view.php';
                    });  
                });
        } else {
            document.getElementById("myDiv").style.display="none";
            swal({text:"You cancel the generation of attendance!",icon:"error"});
        }
    });

    }


    function genWfhPay()
    {

        var url = "../payroll_att/gen_wfh_process.php";
        var date = $('#ungendtrc').children("option:selected").val();
        var dates = date.split(" - ");
        var eMplogName = $('#eMplogName').val();

        // console.log(dates[0]);  
        // console.log(dates[1]);  
        // console.log(eMplogName);
        // return false;

        swal({
          title: "Are you sure?",
          text: "You want to generate this work from home to attendance?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((genAttPay) => {
          document.getElementById("myDiv").style.display="block";
          if (genAttPay) {
            $.post (
                url,
                {
                    action: 1,
                    pyrollco_from: dates[0] ,
                    pyrollco_to: dates[1] ,
                    eMplogName: eMplogName                           
                },
                function(data) {                 
                    swal({
                        title: "Success!", 
                        text: "Successfully generated work from home to attendance!", 
                        type: "success",
                        icon: "success",
                    }).then(function() {                        
                        location.href = '../payroll_att/gen_att_view.php';
                    });  
                });
        } else {
            document.getElementById("myDiv").style.display="none";
            swal({text:"You cancel the generation of work from home to attendance!",icon:"error"});
        }
    });

    }


    // function genDtrcPay()
    // {

    //     var url = "../payroll_att/gen_dtrc_process.php";
    //     var date = $('#ungendtrc').children("option:selected").val();
    //     var dates = date.split(" - ");
    //     var eMplogName = $('#eMplogName').val();

    //     // console.log(dates[0]);  
    //     // console.log(dates[1]);  
    //     // console.log(eMplogName);
    //     // return false;

    //     swal({
    //       title: "Are you sure?",
    //       text: "You want to generate this dtr correction to attendance?",
    //       icon: "success",
    //       buttons: true,
    //       dangerMode: true,
    //   })
    //     .then((genAttPay) => {
    //       document.getElementById("myDiv").style.display="block";
    //       if (genAttPay) {
    //         $.post (
    //             url,
    //             {
    //                 action: 1,
    //                 pyrollco_from: dates[0] ,
    //                 pyrollco_to: dates[1] ,
    //                 eMplogName: eMplogName                           
    //             },
    //             function(data) {                 
    //                 swal({
    //                     title: "Success!", 
    //                     text: "Successfully generated dtr correction to attendance!", 
    //                     type: "success",
    //                     icon: "success",
    //                 }).then(function() {                        
    //                     location.href = '../payroll_att/gen_att_view.php';
    //                 });  
    //             });
    //     } else {
    //         document.getElementById("myDiv").style.display="none";
    //         swal({text:"You cancel the generation of dtr correction to attendance!",icon:"error"});
    //     }
    // });

    // }


     function genObPay()
    {

        var url = "../payroll_att/gen_ob_process.php";
        var date = $('#ungenot').children("option:selected").val();
        var dates = date.split(" - ");
        var eMplogName = $('#eMplogName').val();

        // console.log(dates[0]);  
        // console.log(dates[1]);  
        // console.log(eMplogName);
        // return false;

        swal({
          title: "Are you sure?",
          text: "You want to generate this official business to attendance?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((genAttPay) => {
          document.getElementById("myDiv").style.display="block";
          if (genAttPay) {
            $.post (
                url,
                {
                    action: 1,
                    pyrollco_from: dates[0] ,
                    pyrollco_to: dates[1] 
                    ,eMplogName: eMplogName                           
                },
                function(data) {                 
                    swal({
                        title: "Success!", 
                        text: "Successfully generated official business to attendance!", 
                        type: "success",
                        icon: "success",
                    }).then(function() {                        
                        location.href = '../payroll_att/gen_att_view.php';
                    });  
                });
        } else {
            document.getElementById("myDiv").style.display="none";
            swal({text:"You cancel the generation of official business to attendance!",icon:"error"});
        }
    });

    }
    function genLeavePay()
    {

        var url = "../payroll_att/gen_leave_process.php";
        var date = $('#ungenleave').children("option:selected").val();
        var dates = date.split(" - ");
        var eMplogName = $('#eMplogName').val();

        // console.log(dates[0]);  
        // console.log(dates[1]);  
        // console.log(eMplogName);
        // return false;

        swal({
          title: "Are you sure?",
          text: "You want to generate this leave to attendance?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((genAttPay) => {
          document.getElementById("myDiv").style.display="block";
          if (genAttPay) {
            $.post (
                url,
                {
                    action: 1,
                    pyrollco_from: dates[0] ,
                    pyrollco_to: dates[1] ,
                    eMplogName: eMplogName                           
                },
                function(data) {                 
                    swal({
                        title: "Success!", 
                        text: "Successfully generated attendance!", 
                        type: "success",
                        icon: "success",
                    }).then(function() {                        
                        location.href = '../payroll_att/gen_att_view.php';
                    });  
                });
        } else {
            document.getElementById("myDiv").style.display="none";
            swal({text:"You cancel the generation of leave to attendance!",icon:"error"});
        }
    });

    }


     function genOtPay()
    {

        var url = "../payroll_att/gen_ot_process.php";
        var date = $('#ungenot').children("option:selected").val();
        var dates = date.split(" - ");
        var eMplogName = $('#eMplogName').val();

        // console.log(dates[0]);  
        // console.log(dates[1]);  
        // console.log(eMplogName);
        // return false;

        swal({
          title: "Are you sure?",
          text: "You want to generate this overtime to attendance?",
          icon: "success",
          buttons: true,
          dangerMode: true,
      })
        .then((genAttPay) => {
          document.getElementById("myDiv").style.display="block";
          if (genAttPay) {
            $.post (
                url,
                {
                    action: 1,
                    pyrollco_from: dates[0] ,
                    pyrollco_to: dates[1] 
                    ,eMplogName: eMplogName                           
                },
                function(data) {                 
                    swal({
                        title: "Success!", 
                        text: "Successfully generated overtime to attendance!", 
                        type: "success",
                        icon: "success",
                    }).then(function() {                        
                        location.href = '../payroll_att/gen_att_view.php';
                    });  
                });
        } else {
            document.getElementById("myDiv").style.display="none";
            swal({text:"You cancel the generation of overtime to attendance!",icon:"error"});
        }
    });

    }

    // function genDTR()
    // {

    //     var url = "../payroll_att/gen_dtr_process.php";

    //     swal({
    //       title: "Are you sure?",
    //       text: "You want to generate dtr ?",
    //       icon: "success",
    //       buttons: true,
    //       dangerMode: true,
    //   })
    //     .then((genAttPay) => {
    //       document.getElementById("myDiv").style.display="block";
    //       if (genAttPay) {
    //         $.post (
    //             url,
    //             {
    //                 action: 1                         
    //             },
    //             function(data) {  
    //             // console.log(data);               
    //                 swal({
    //                     title: "Success!", 
    //                     text: "Successfully generated DTR!", 
    //                     type: "success",
    //                     icon: "success",
    //                 }).then(function() {                        
    //                     location.href = '../pages/admin.php';
    //                 });  
    //             });
    //     } else {
    //         document.getElementById("myDiv").style.display="none";
    //         swal({text:"You cancel the generation of dtr!",icon:"error"});
    //     }
    // });

    // }

</script>
<?php include('../_footer.php');  ?>
