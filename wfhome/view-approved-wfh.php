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
        include('../wfhome/wfh_app.php');
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');        

        $mf = new MasterFile();
        $dd = new DropDown();
        $wfhApp = new WfhApp(); 
        $wfhApp->SetWfhAppParams($empCode);

        $query = 'SELECT * FROM dbo.employee_profile WHERE emp_code = :empcode ';
        $param = array(":empcode" => $_SESSION['userid']);
        $stmt =$connL->prepare($query);
        $stmt->execute($param);
        $r = $stmt->fetch();
        $e_req = $r['emailaddress'];
        $n_req = $r['firstname'].' '.$r['lastname'];


        $aquery = 'SELECT * FROM dbo.employee_profile WHERE emp_code = :empcode ';
        $aparam = array(":empcode" => $r['reporting_to']);
        $astmt =$connL->prepare($aquery);
        $astmt->execute($aparam);
        $ar = $astmt->fetch();
        $e_appr = $ar['emailaddress'];
        $n_appr = $ar['firstname'].' '.$ar['lastname'];    

         
        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President' || $empUserType == 'Team Manager')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        } 


    }    
?>
<script type="text/javascript">
    

function viewWfhModal(wfhdate,wfhtask,wfhoutput,wfhpercentage,wfhstats,rjrson,attachment){
   
        $('#viewWfhModal').modal('toggle');
        document.getElementById('wfhdates').value =  wfhdate;   
        document.getElementById('wfhtask').value =  wfhtask;  
        document.getElementById('wfhoutput').value =  wfhoutput;  
        document.getElementById('wfhpercentage').value =  wfhpercentage;  
        document.getElementById('wfhstats').value =  wfhstats;   
        document.getElementById('rejectreason').value =  rjrson;   
        if(!attachment){
            $('#viewattachment').hide();
        }else{
            $('#viewattachment').show();
            document.getElementById('viewattachment').setAttribute('href','../uploads/'+attachment);
        }                                
    }

    function viewWfhHistoryModal(lvlogid)
    {
       $('#viewWfhHistoryModal').modal('toggle');
        var url = "../wfhome/wfh_viewlogs.php";
        var lvlogid = lvlogid;

        $.post (
            url,
            {
                _action: 1,
                lvlogid: lvlogid             
            },
            function(data) { $("#contents2").html(data).show(); }
        );
    }

</script>
<link rel="stylesheet" type="text/css" href="../wfhome/wfh_view.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type='text/javascript' src='../leave/viewapp.js'></script>
<div class="container">
    <div class="section-title">
          <h1>EMPLOYEES WORK FROM HOME APPLICATION</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-warehouse fa-fw mr-1'>
                    </i>Employee Work From Home Application View</li>
            </ol>
          </nav>

    <div class="form-row mb-2">
        <label for="from" class="col-form-label pad">From:</label>
        <div class="col-md-2">
            <input type="date" id="dateFrom" class="form-control" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>
        <label for="to" class="col-form-label pad">To:</label>
        <div class="col-md-2">
            <input type="date" id="dateTo" class="form-control" name="dateTo" value="<?php echo date('Y-m-d'); ?>" onkeydown="return false" required>
        </div>        
            <?php if($empUserType == 'Team Manager'){   
                     echo '
                    <label for="status" class="col-form-label pad">NAME:</label><div class="col-md-3">';
                    $dd->GenerateSingleRepDropDown("empCode",$mf->GetAttEmployeeNamesRep("allemprepnames",$empCode));
                    echo '</div>
                    <button type="submit" id="searchWfhRep" class="btn btn-warning"><i class="fas fa-search-plus"></i> Generate
                    </button>';
                }else{
                    echo '
                    <label for="status" class="col-form-label pad">Status:</label>
                    <div class="col-md-2">
                        <select class="form-select" id="status" name="status">
                            <option value="1">PENDING</option>
                            <option value="2">APPROVED</option>
                            <option value="3">REJECTED</option>
                            <option value="4">CANCELLED</option>
                        </select>
                    </div>
                    <button type="submit" id="searchWfhApp" class="btn btn-warning"><i class="fas fa-search-plus"></i> Generate
                    </button>';
                }
            ?>                                                  
    </div>
        

    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <div id="WfhListRepTabs" class="table-responsive-sm table-body"></div>
            </div>
        </div>
    </div>    


    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                <div id="WfhListTabs" class="table-responsive-sm table-body"></div>
            </div>
        </div>
    </div>     

    <!-- start view wfh  -->
<div class="modal fade" id="viewWfhModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class="fas fa-warehouse mr-1"></i>View Work From Home</h5>
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
                             <!-- wfhdate,wfhtask,wfhoutput,wfhpercentage,wfhstats -->
                        <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhdates">WFH Date</label>
                                        <input type="text" id="wfhdates" name="wfhdates" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhtask">Task</label>
                                        <input type="text" id="wfhtask" name="wfhtask" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhpercentage">Percentage %</label>
                                        <input type="text" id="wfhpercentage" name="wfhpercentage" class="form-control" readonly >
                                    </div>
                                </div>                              
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhoutput">Expected Output</label>
                                        <input type="text" id="wfhoutput" name="wfhoutput" class="form-control" readonly>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="wfhstats">Status</label>
                                        <input type="text" id="wfhstats" name="wfhstats" class="form-control" readonly>
                                    </div>
                                </div> 
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="rejectreason">Reject Reason</label>
                                        <input type="text" id="rejectreason" name="rejectreason" class="form-control" readonly>
                                    </div>
                                </div>                                          
                            </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">
                                <?php   echo"<a title='Attachment' id='viewattachment' class='font-weight-bold' href='' style='color:#ffff;'  
                                target='popup'><button type='button' class='btn btn-primary'><i class='text-white fas fa-paperclip mr-1'></i>View Attachment</button></a>"; ?>                                  
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="viewWfhHistoryModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle"><i class='fas fa-warehouse mr-1'></i>View Work From Home Logs</h5>
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
                            <div class="row pt-3">
                                <div class="col-md-12">
                                    <div class="panel-body">
                                        <div id="contents2" class="table-responsive-sm table-body">
                                            <button type="button" id="search" hidden>GENERATE</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                                               
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->   

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

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
<?php include("../_footer.php");?>
