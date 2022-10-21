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
        include('../leave/leaveApproval.php');

        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'Team Manager' || $empUserType == 'President' || $empUserType == 'Finance'){
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }
    }
?>
<script type='text/javascript' src='../leave/leaveApplication.js'></script>
<link rel="stylesheet" type="text/css" href="../leave/leaveapp.css">
<div class="container">
    <div class="section-title">
          <h1>LEAVE APPROVAL</h1>
    </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-suitcase fa-fw mr-1'></i>Leave Approval</li>
            </ol>
          </nav>

         <div class="row">
            <input type="text" name="empCode" id="empCode" value="<?php  echo $empCode; ?>" hidden>
            <div class="col-md-12 pt-3">
                <div class="panel-body" id="pendingList">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 pt-3">
                <div class="panel-body" id="summaryList">
                </div>
            </div>
        </div>

        <div class="modal fade" id="remarksModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="popUpModalTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="rejectReason">Remarks</label>
                            <input type="text" name="remarks" id="remarks" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                        <button type="button" class="btn btn-success btnRemarks" id="submit"><i class="fas fa-check-circle"></i> Submit</button>
                    </div>
                </div>
            </div>
        </div>       
    </div>
</div>



<?php  include('../_footer.php');?>

<!-- <script type="text/javascript">
    $(document).ready(function(){

});
</script> -->
