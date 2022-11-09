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
            include("../mf_allowances/mfallowanceslist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfallowancesList = new MfallowancesList(); 
            $mf = new MasterFile();
            $dd = new DropDown();

       if ($empUserType == 'Admin' || $empUserType == 'Finance' || $empUserType == 'Group Head' || $empUserType == 'President')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }  

    }    
?>
<link rel="stylesheet" href="../mf_allowances/mfallowances.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_allowances/mfallowances_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL ALLOWANCE TYPE LIST</h1>
        </div>
    <div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw mr-1'>
                  </i>Allowance Type List</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfallowancesEntry"><i class="fas fa-plus-circle"></i> Add New Allowance Type </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfallowancesList->GetAllMfallowancesList(); ?>

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
                    <h5 class="modal-title bb" id="popUpModalTitle">ALLOWANCES TYPE ENTRY <i class="fas fa-money-bill"></i></h5>
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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="benefit_code">Allowances Code<span class="req">*</span></label>
                                        <input type="text" style="text-transform:uppercase" class="form-control inputtext" name="benefit_code" id="benefit_code" placeholder="ALW....." maxlength="3" >
                                    </div>
                                </div> 
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="benefit_name">Allowances Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="benefit_name"
                                            id="benefit_name" placeholder="Allowances Name....." > 
                                    </div>
                                </div>
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

    <div class="modal fade" id="updateMfalw" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE ALLOWANCE TYPE <i class="fas fa-money-bill"></i></h5>
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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="benefitcode">Allowances Code<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" style="text-transform:uppercase"  name="benefitcode"
                                            id="benefitcode" maxlength="3">
                                    </div>
                                </div> 
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="benefitname">Allowances Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="benefitname"
                                            id="benefitname"> 
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>
                            <input type="text" class="form-control" name="dscsbup" id="dscsbup" hidden>
                            <input type="text" class="form-control" name="rowd"id="rowd" hidden> 

                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">                                   
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfalw()" ><i class="fas fa-check-circle"></i> Submit</button>                                     
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->
        <?php 
        $query = "SELECT * from dbo.mf_benefits ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['benefit_code']);
                
            } while ($result = $stmt->fetch());

           ?>

<script>

$(document).ready( function () {

$('#allMfallowancesList').DataTable({
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

            $('#benefit_code').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#benefit_code').val();
                var res = cd.toUpperCase();

                if(totalVal.includes(res)){
                    swal({text:"Duplicate Allowances Code!",icon:"error"});
                    var dbc = document.getElementById('benefit_code');
                    dbc.value = '';               
                }else{
                }

            });

                $('#benefitcode').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#benefitcode').val();
                var res = cd.toUpperCase();
                var hidb = $('#dscsbup').val();

                if(totalVal.includes(res)){
                        if(hidb === res){

                        }else{
                            swal({text:"Duplicate Allowances Code!",icon:"error"});
                            var dbc = document.getElementById('benefitcode');
                            dbc.value = hidb;                            
                        }               
                }else{
                }

            });



    function editMfallowancesModal(id,desc){
          
        $('#updateMfalw').modal('toggle');
        document.getElementById('rowd').value =  id;   
        document.getElementById('benefitcode').value =  document.getElementById('ac'+id).innerHTML;   
        document.getElementById('benefitname').value =  document.getElementById('an'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                          
                            
    }


     function updateMfalw()
    {
        var url = "../mf_allowances/updatemfallowances_process.php";
        var rowid = document.getElementById("rowd").value;
        var benefit_code = document.getElementById("benefitcode").value.toUpperCase();
        var benefit_name = document.getElementById("benefitname").value;  
        var status = document.getElementById("stts").value;             

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this allowances type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateMfalw) => {
                          if (updateMfalw) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        rowid: rowid ,
                                        benefit_code: benefit_code ,
                                        benefit_name: benefit_name ,
                                        status: status
                                        
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the allowances details!", 
                                            type: "success",
                                            icon: "success",
                                        }).then(function() {
                                            $('#updateMfalw').modal('hide');
                                             document.getElementById('ac'+rowid).innerHTML = benefit_code;
                                             document.getElementById('an'+rowid).innerHTML = benefit_name;
                                             document.getElementById('st'+rowid).innerHTML = status;
                                        }); 
                                }
                                );
                          } else {
                            swal({text:"You cancel the updating of allowances details!",icon:"error"});
                          }
                        });
   
                }
                
</script>


<?php include("../_footer.php");?>
