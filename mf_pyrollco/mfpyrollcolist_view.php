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
            include("../mf_pyrollco/mfpyrollcolist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfpyrollcoList = new MfpyrollcoList(); 
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
<link rel="stylesheet" href="../mf_pyrollco/mfpyrollco.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_pyrollco/mfpyrollco_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL PAYROLL CUTOFF LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-calendar-alt mr-1'>
                </i>Payroll Cutoff List</li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfpyrollcoEntry"><i class="fas fa-plus-circle"></i> Add New Payroll Cutoff </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfpyrollcoList->GetAllMfpyrollcoList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">PAYROLL CUTOFF ENTRY <i class="fas fa-calendar-alt"></i></h5>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_from">Payroll From<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollco_from"
                                            id="pyrollco_from" onkeydown="return false"> 
                                    </div>
                                </div>                            
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_to">Payroll To<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollco_to"
                                            id="pyrollco_to" onkeydown="return false"> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="co_type">Payroll Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="co_type" name="co_type" >
                                            <option value="0">Payroll 15th</option>
                                            <option value="1">Payroll 30th</option>
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

    <div class="modal fade" id="updateMfPyco" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE PAYROLL CUTOFF <i class="fas fa-calendar-alt"></i></h5>
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
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollcofrom">Payroll From<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollcofrom"
                                            id="pyrollcofrom" onkeydown="return false"> 
                                    </div>
                                </div>                            
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_to">Payroll To<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollcoto"
                                            id="pyrollcoto" onkeydown="return false"> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cotype">Payroll Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="cotype" name="cotype" >
                                            <option value="0">Payroll 15th</option>
                                            <option value="1">Payroll 30th</option>
                                        </select>    
                                    </div>
                                </div>                                                          
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfPyco()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->
<script type="text/javascript">

$(document).ready( function () {

$('#allMfpyrollcoList').DataTable({
      pageLength : 12,
      lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
      dom: 'Bfrtip',
      buttons: [
          'pageLength',
          {
              extend: 'excel',
              title: 'Bank List', 
              text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
              init: function(api, node, config) {
                  $(node).removeClass('dt-button')
                  },
                  className: 'btn bg-transparent btn-sm'
          },
          {
              extend: 'pdf',
              title: 'Bank List', 
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

$('#pyrollco_to').change(function(){

if($('#pyrollco_to').val() < $('#pyrollco_from').val()){

swal({text:"Date to must be greater than date from!",icon:"error"});
document.getElementById('pyrollco_to').value = '';               
}
});


$('#pyrollco_from').change(function(){

if($('#pyrollco_from').val() > $('#pyrollco_to').val()){
var input2 = document.getElementById('pyrollco_to');
document.getElementById("pyrollco_to").min = $('#pyrollco_from').val();
input2.value = '';
}
});



    function editMfpyrollcoModal(id){
          
        $('#updateMfPyco').modal('toggle'); 
        document.getElementById('rowd').value =  id;   
        document.getElementById('pyrollcofrom').value =  document.getElementById('pcf'+id).innerHTML;   
        document.getElementById('pyrollcoto').value =  document.getElementById('pct'+id).innerHTML;  
        document.getElementById('cotype').value =  document.getElementById('cor'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
    }


    function updateMfPyco()
    {

        var url = "../mf_pyrollco/updatemfpyrollco_process.php";
        var rowid = document.getElementById("rowd").value;
        var pyrollco_from = document.getElementById("pyrollcofrom").value;
        var pyrollco_to = document.getElementById("pyrollcoto").value;
        var co_type = document.getElementById("cotype").value;
        if(co_type == 1){
            var ctype =  'Payroll 30th';
        }else{
            var ctype =  'Payroll 15th';
        }
        var status = document.getElementById("stts").value;


        swal({
            title: "Are you sure?",
            text: "You want to update this pyrollco type?",
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((updateMfPyco) => {
            if (updateMfPyco) {
                $.post (
                    url,
                    {
                        action: 1,
                        rowid: rowid ,
                        pyrollco_from: pyrollco_from,
                        pyrollco_to: pyrollco_to,
                        co_type: co_type,
                        status: status                                       
                    },
                    function(data) { 
                            swal({
                            title: "Success!", 
                            text: "Successfully updated the payroll cut-off details!", 
                            type: "success",
                            icon: "success",
                            }).then(function() {
                                $('#updateMfPyco').modal('hide');
                                    document.getElementById('pcf'+rowid).innerHTML = pyrollco_from;
                                    document.getElementById('pct'+rowid).innerHTML = pyrollco_to;
                                    document.getElementById('cot'+rowid).innerHTML = ctype;
                                    document.getElementById('st'+rowid).innerHTML = status;
                            });  
                });
            } else {
            swal({text:"You cancel the updating of payroll cut-off details!",icon:"error"});
            }
        });

}
    


</script>


<?php include("../_footer.php");?>
