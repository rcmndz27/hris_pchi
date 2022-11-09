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
            include("../mf_bank/banklist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allBankList = new BankList(); 
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
<link rel="stylesheet" href="../mf_bank/bank.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_bank/bank_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL BANK TYPE LIST</h1>
        </div>
    <div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw mr-1'>
                  </i>Bank Type List</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="bankEntry"><i class="fas fa-plus-circle"></i> Add New Bank Type </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allBankList->GetAllBankList(); ?>

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
                    <h5 class="modal-title bb" id="popUpModalTitle">BANK TYPE ENTRY <i class="fas fa-money-bill"></i></h5>
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
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_type">Bank Code<span class="req">*</span></label>
                                        <input type="text" style="text-transform:uppercase" class="form-control inputtext" name="descsb"
                                            id="descsb" placeholder="Bank Code..." maxlength="4">
                                    </div>
                                </div> 
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_type">Bank Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="descsb_name"
                                            id="descsb_name" placeholder="Banco De Oro....." > 
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

    <div class="modal fade" id="updateBan" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE BANK TYPE <i class="fas fa-money-bill"></i></h5>
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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="dscsb">Bank Code<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" style="text-transform:uppercase" name="dscsb"
                                            id="dscsb" maxlength="4" placeholder="Bank Code...">
                                    </div>
                                </div> 
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="descsbname">Bank Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="descsbname"
                                            id="descsbname"> 
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>                                 
                                <input type="text" class="form-control" name="dscsbup" id="dscsbup" hidden> 
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 

                        </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">                                  
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateBan()" id="updateBanT"><i class="fas fa-check-circle"></i> Submit</button>                                      
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

        <?php 
        $query = "SELECT * from dbo.mf_banktypes ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['descsb']);
                
            } while ($result = $stmt->fetch());

           ?>

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

<script>

$(document).ready( function () {

$('#allBankList').DataTable({
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


            var totalVal = <?php echo json_encode($totalVal) ;?>;
            $('#descsb').change(function(){
                var cd = $('#descsb').val();
                var res = cd.toUpperCase();

                if(totalVal.includes(res)){
                    swal({text:"Duplicate Bank Code!",icon:"error"});
                    var dbc = document.getElementById('descsb');
                    dbc.value = '';               
                }else{
                }

            });

                $('#dscsb').change(function(){
                    var d = totalVal;
                    var rd = $('#rowd').val();
                    var cd = $('#dscsb').val();
                    var res = cd.toUpperCase();
                    var hidb = $('#dscsbup').val();

                console.log(d);

                if(d.includes(res)){
                        if(hidb === res){

                        }else{
                            swal({text:"Duplicate Bank Code!",icon:"error"});
                            var dbc = document.getElementById('dscsb');
                            dbc.value = hidb;                            
                        }               
                }else{
                }

            });

    function editBankModal(id,desc){
          
        $('#updateBan').modal('toggle');
        document.getElementById('rowd').value =  id;   
        document.getElementById('dscsb').value =  document.getElementById('bc'+id).innerHTML;   
        document.getElementById('descsbname').value =  document.getElementById('bn'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                                          
    }

            



     function updateBan()
    {

        var url = "../mf_bank/updatebank_process.php";
        var rowid = document.getElementById("rowd").value;
        var descsb = document.getElementById("dscsb").value.toUpperCase();
        var descsb_name = document.getElementById("descsbname").value;
        var status = document.getElementById("stts").value;     


                        swal({
                          title: "Are you sure?",
                          text: "You want to update this bank type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateBan) => {
                          if (updateBan) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        rowid: rowid ,
                                        descsb: descsb ,
                                        descsb_name: descsb_name ,
                                        status: status 
                                        
                                    },
                                    function(data) {                                             
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the bank details!", 
                                            type: "success",
                                            icon: "success",
                                        }).then(function() {
                                            $('#updateBan').modal('hide');
                                             document.getElementById('bc'+rowid).innerHTML = descsb;
                                             document.getElementById('bn'+rowid).innerHTML = descsb_name;
                                             document.getElementById('st'+rowid).innerHTML = status;
                                        }); 
                                    }
                                );

                          } else {
                            swal({text:"You cancel the updating of bank details!",icon:"error"});
                          }
                        });
   
                }

</script>


<?php include("../_footer.php");?>
