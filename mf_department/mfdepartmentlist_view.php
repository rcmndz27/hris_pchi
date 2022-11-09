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
            include("../mf_department/mfdepartmentlist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfdepartmentList = new MfdepartmentList(); 
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
<link rel="stylesheet" href="../mf_department/mfdepartment.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_department/mfdepartment_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL DEPARTMENT LIST</h1>
        </div>
    <div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-warehouse mr-1'>
                  </i>Department List</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfdepartmentEntry"><i class="fas fa-plus-circle"></i> Add New Department </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfdepartmentList->GetAllMfdepartmentList(); ?>
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
                    <h5 class="modal-title bb" id="popUpModalTitle">DEPARTMENT ENTRY <i class="fas fa-warehouse"></i></h5>
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
                                        <label class="control-label" for="code">Department Code<span class="req">*</span></label>
                                        <input type="text" style="text-transform:uppercase" class="form-control inputtext" name="code" id="code" placeholder="CMP....." maxlength="4" >
                                    </div>
                                </div> 
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="descs">Department Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="descs"
                                            id="descs" placeholder="Department Name....." > 
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

    <div class="modal fade" id="updateMfdep" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE DEPARTMENT <i class="fas fa-warehouse"></i></h5>
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
                                        <label class="control-label" for="cde">Department Code<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="cde"
                                            id="cde" maxlength="4" style="text-transform:uppercase">
                                    </div>
                                </div> 
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="dscs">Department Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="dscs"
                                            id="dscs"> 
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
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfdep()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

        <?php 
        $query = "SELECT * from dbo.mf_dept ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['code']);
                
            } while ($result = $stmt->fetch());

           ?>

<script>

$(document).ready( function () {

$('#allMfdepartmentList').DataTable({
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


            $('#code').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#code').val();
                var res = cd.toUpperCase();

                if(totalVal.includes(res)){
                    swal({text:"Duplicate Department Code!",icon:"error"});
                    var dbc = document.getElementById('code');
                    dbc.value = '';               
                }else{
                }

            });

                $('#cde').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#cde').val();
                var res = cd.toUpperCase();
                var hidb = $('#dscsbup').val();

                if(totalVal.includes(res)){
                        if(hidb === res){

                        }else{
                            swal({text:"Duplicate Department Code!",icon:"error"});
                            var dbc = document.getElementById('cde');
                            dbc.value = hidb;                            
                        }               
                }else{
                }

            });


    function editMfdepartmentModal(id,desc){
          
        $('#updateMfdep').modal('toggle');
        document.getElementById('rowd').value =  id;   
        document.getElementById('cde').value =  document.getElementById('dc'+id).innerHTML;   
        document.getElementById('dscs').value =  document.getElementById('dn'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                                                


    }


     function updateMfdep()
    {

        var url = "../mf_department/updatemfdepartment_process.php";
        var rowid = document.getElementById("rowd").value;
        var code = document.getElementById("cde").value;
        var descs = document.getElementById("dscs").value;
        var status = document.getElementById("stts").value;       

              swal({
                title: "Are you sure?",
                text: "You want to update this department type?",
                icon: "success",
                buttons: true,
                dangerMode: true,
              })
              .then((updateMfdep) => {
                if (updateMfdep) {
                      $.post (
                          url,
                          {
                              action: 1,
                              rowid: rowid ,
                              code: code ,
                              descs: descs ,
                              status: status                                        
                          },
                          function(data) { 
                                  swal({
                                  title: "Success!", 
                                  text: "Successfully updated the department details!", 
                                  type: "success",
                                  icon: "success",
                                  }).then(function() {
                                      $('#updateMfdep').modal('hide');
                                        document.getElementById('dc'+rowid).innerHTML = code;
                                        document.getElementById('dn'+rowid).innerHTML = descs;
                                        document.getElementById('st'+rowid).innerHTML = status;
                                  });  
                          }
                      );

                } else {
                  swal({text:"You cancel the updating of department details!",icon:"error"});
                }
              });

      }
  
</script>


<?php include("../_footer.php");?>
