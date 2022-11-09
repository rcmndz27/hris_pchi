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
            include("../mf_position/mfpositionlist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfpositionList = new MfpositionList(); 
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
<link rel="stylesheet" href="../mf_position/mfposition.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_position/mfposition_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL JOB POSITION LIST</h1>
        </div>
    <div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><b><i class='fas fa-users mr-1'>
                        </i>Job Position List</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfpositionEntry"><i class="fas fa-plus-circle"></i> Add New Job Position </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfpositionList->GetAllMfpositionList(); ?>

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
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-users mr-1"></i> Job Position Entry </h5>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="position">Job Position Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="position"
                                            id="position" placeholder="Position Name....." > 
                                    </div>
                                </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="control-label" for="depname">Department Name<span class="req">*</span></label>
                                    <?php $dd->GenerateMultipledDropDown("department", $mf->GetDeptForJob("depwid")); ?>  
                                     </div>                                  
                                 </div>                                 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="status" name="status" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
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

    <div class="modal fade" id="updateMfpos" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE POSITION <i class="fas fa-users"></i></h5>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="pstn">Job Position Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="pstn" id="pstn" placeholder="Job Position"> 
                                    </div>
                                </div> 
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="control-label" for="depment">Department Name<span class="req">*</span></label>
                                    <?php $dd->GenerateMultipledDropDown("depment", $mf->GetDeptForJob("depwid")); ?>
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
                              <form action=""></form>
                                <input type="text" class="form-control" name="dscsbup" id="dscsbup" hidden>
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfpos()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->
        <input type="text" class="form-control" name="empCode" id="empCode" value="<?php echo $empCode; ?>" hidden>
    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

        <?php 

        $query = "SELECT * from dbo.mf_position ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['position']);
                
            } while ($result = $stmt->fetch());



        $queryd = "SELECT * from dbo.mf_jobdept";
        $stmtd =$connL->prepare($queryd);
        $stmtd->execute();
        $resultd = $stmtd->fetch();

        $totalVal2 = [];
        if($resultd){
            do { 
                array_push($totalVal2,$resultd);
                
            } while ($resultd = $stmtd->fetch());
        }



               

        $sqldepartments = $connL->prepare("SELECT rowid,descs,code FROM dbo.mf_dept where status = 'Active' ORDER by rowid ASC");
        $sqldepartments->execute();
        $departments = [];
        while ($r = $sqldepartments->fetch(PDO::FETCH_ASSOC))
        {
           array_push($departments, $r);
        }
    
        ?>


<script>


$(document).ready( function () {

$('#allMfpositionList').DataTable({
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


    var totalVal3 = <?php echo json_encode($totalVal2) ;?>;
    var depart = <?php echo json_encode($departments) ;?>;

    $('#position').change(function(){
    var totalVal = <?php echo json_encode($totalVal) ;?>;
    var cd = $('#position').val();

    if(totalVal.includes(cd)){
        swal({text:"Duplicate Position Name!",icon:"error"});
        var dbc = document.getElementById('position');
        dbc.value = '';               
    }else{
    }

});

    $('#pstn').change(function(){
    var totalVal = <?php echo json_encode($totalVal) ;?>;
    var cd = $('#pstn').val();
    var hidb = $('#dscsbup').val();

    if(totalVal.includes(cd)){
            if(hidb === cd){

            }else{
                swal({text:"Duplicate Position Name!",icon:"error"});
                var dbc = document.getElementById('pstn');
                dbc.value = hidb;                            
            }               
    }else{
    }

});




    function editMfpositionModal(id,desc){

          
        $('#updateMfpos').modal('toggle');
        $("#depment").val([]);
        $.each(totalVal3, function(i){
            if(totalVal3[i]['job_id'] == id)
            {
                $("#depment option[value='" +totalVal3[i]['dept_id']+ "']").prop("selected", true);
            }
        });                          
        document.getElementById('rowd').value =  id;   
        document.getElementById('pstn').value =  document.getElementById('pst'+id).innerHTML;   
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                                   

    }


     function updateMfpos()
    {

        var url = "../mf_position/updatemfposition_process.php";
        var rowid = document.getElementById("rowd").value;
        var position = document.getElementById("pstn").value; 
        var status = document.getElementById("stts").value; 
        var empCode = document.getElementById("empCode").value; 
        var department =  $('#depment').val();

        swal({
            title: "Are you sure?",
            text: "You want to update this position type?",
            icon: "success",
            buttons: true,
            dangerMode: true,
        })
        .then((updateMfpos) => {
            if (updateMfpos) {
                $.post (
                    url,
                    {
                        rowid: rowid ,
                        position: position ,
                        status : status,
                        department:department,
                        empCode: empCode
                        
                    },
                    function(data) { 
                    swal({
                    title: "Success!", 
                    text: "Successfully updated the job position details!", 
                    type: "success",
                    icon: "success",
                    }).then(function(e) {
                        totalVal3 = totalVal3.filter(jobId => jobId.job_id != rowid);  
                            $.each(department, function(i){
                                    var da = {};
                                    da.job_id = rowid;
                                    da.dept_id = department[i];
                                    totalVal3.push(da);
                                });  
                            var dep = ""; 
                            var countDep = department.length;
                            var cDept = 0;
                            $.each(depart, function(i){

                                var dddd = department.includes(depart[i].rowid);
                                if(dddd == true)
                                {
                                cDept = cDept +1;
                                if(countDep == cDept)
                                    {
                                        dep = dep + depart[i].code;
                                    }
                                    else
                                    {
                                        dep = dep + depart[i].code+',';
                                    }
                                }
                                });  
                            
                        $('#updateMfpos').modal('hide');
                            document.getElementById('pst'+rowid).innerHTML = position;
                            document.getElementById('dptv'+rowid).innerHTML = dep; 
                            document.getElementById('st'+rowid).innerHTML = status;
                    });
                    }
                );
            } else {
            swal({text:"You cancel the updating of job position details!",icon:"error"});
            }
        });

}

</script>


<?php include("../_footer.php");?>
