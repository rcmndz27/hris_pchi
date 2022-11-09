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
            include("../mf_sched/mfschedlist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfschedList = new MfschedList(); 
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
<link rel="stylesheet" href="../mf_sched/mfsched.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_sched/mfsched_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL SCHEDULE LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-calendar-alt mr-1'>
                        </i>All Schedule List</li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfschedEntry"><i class="fas fa-plus-circle mr-1"></i> Add New Schedule</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfschedList->GetAllMfschedList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">New Schedule <i class="fas fa-calendar-alt"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
        <fieldset class="fieldset-border">
          <div class="modal-body">
              <div class="row">
                <input type="text" name="empcode" id="empcode" value="<?php echo $empCode ?>" hidden>
                <div class='col-md-12 form-group'>
                  Schedule Name
                  <input type="text" name='schedule_name' id='schedule_name' class="form-control" placeholder="Schedule Name/Type" required>
                </div>
              </div>
              <div class="row border text-center">
                <div class='col-md-3 border'>
                    Days
                </div>
                <div class='col-md-3 border'>
                  Start Time
                </div>
                <div class='col-md-3 border'>
                  End Time
                </div>
                <div class='col-md-3 border'>
                  Total working hours <br> <i>(including breaktime hours)</i>
                </div>
              </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Sunday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="sunCheck" id="sunCheck" checked class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_sun" id="time_in_from_sun" class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_sun" id="time_in_to_sun" class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_sun" id="time_out_from_sun" class="form-control form-control-sm"  readonly>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_sun"  id="time_out_to_sun" class="form-control form-control-sm"  readonly>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                    <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_sun'  name='working_hours_sun' step='.5' placeholder="0.00" readonly>
                </div>
            </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Monday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="monCheck" id="monCheck" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_mon" id="time_in_from_mon" class="form-control form-control-sm" value='07:00'  required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_mon"  id="time_in_to_mon" class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_mon" id="time_out_from_mon" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_mon" id="time_out_to_mon" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_mon' name='working_hours_mon' step='.5' placeholder="0.00" required>
              </div>
            </div>
              <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Tuesday <br>
                  <label class="form-check-label">
                    <input type="checkbox" name="tueCheck" id="tueCheck" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_tue" id="time_in_from_tue" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_tue"  id="time_in_to_tue" class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_tue" id="time_out_from_tue" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_tue" id="time_out_to_tue" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_tue' name='working_hours_tue' step='.5' placeholder="0.00" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Wednesday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="wedCheck" id="wedCheck" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_wed" id="time_in_from_wed" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_wed" id="time_in_to_wed" class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_wed" id="time_out_from_wed" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_wed" id="time_out_to_wed" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_wed' name='working_hours_wed' step='.5' placeholder="0.00" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Thursday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="thuCheck" id="thuCheck" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_thu" id="time_in_from_thu" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_thu" id="time_in_to_thu"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_thu" id="time_out_from_thu" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_thu" id="time_out_to_thu" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_thu' name='working_hours_thu' step='.5' placeholder="0.00" required>
              </div>
            </div>
            <div class="row border text-center">
                <div class='col-md-3 align-self-center'>
                  Friday <br>
                  <label class="form-check-label ">
                    <input type="checkbox" name="friCheck" id="friCheck" class="form-check-input">
                    Restday
                </label>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_from_fri" id="time_in_from_fri" class="form-control form-control-sm" value='07:00' required >
                    </div>
                  </div>
                  <div class="row">
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_in_to_fri" id="time_in_to_fri"  class="form-control form-control-sm" value='10:00' required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3 border'>
                  <div class="row mt-1 ">
                    <label  class="col-sm-2 col-form-label align-self-center">From</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_from_fri" id="time_out_from_fri" class="form-control form-control-sm" value='17:30'  required>
                    </div>
                    <label  class="col-sm-2 col-form-label align-self-center">To</label>
                    <div class="col-sm-10">
                      <input type="time" name="time_out_to_fri" id="time_out_to_fri" class="form-control form-control-sm"  value='20:30'  required>
                    </div>
                  </div>
                </div>
                <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_fri' name='working_hours_fri' step='.5' placeholder="0.00" required>
              </div>
            </div>
            <div class="row border text-center">
              <div class='col-md-3 align-self-center'>
                Saturday <br>
                <label class="form-check-label ">
                  <input type="checkbox" name="satCheck" id="satCheck" checked class="form-check-input">
                  Restday
              </label>
              </div>
              <div class='col-md-3 border'>
                <div class="row mt-1 ">
                  <label  class="col-sm-2 col-form-label align-self-center">From</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_in_from_sat" id="time_in_from_sat" class="form-control form-control-sm"  readonly>
                  </div>
                </div>
                <div class="row">
                  <label  class="col-sm-2 col-form-label align-self-center">To</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_in_to_sat" id="time_in_to_sat" class="form-control form-control-sm"  readonly>
                  </div>
                </div>
              </div>
              <div class='col-md-3 border'>
                <div class="row mt-1 ">
                  <label  class="col-sm-2 col-form-label align-self-center">From</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_out_from_sat" id="time_out_from_sat" class="form-control form-control-sm"  readonly>
                  </div>
                  <label  class="col-sm-2 col-form-label align-self-center">To</label>
                  <div class="col-sm-10">
                    <input type="time" name="time_out_to_sat" id="time_out_to_sat" class="form-control form-control-sm"  readonly>
                  </div>
                </div>
              </div>
              <div class='col-md-3  align-self-center'>
                  <input type='number' class='form-control form-control-sm align-self-center' id='working_hours_sat' name='working_hours_sat' step='.5' placeholder="0.00" readonly>
              </div>
            </div>
          </div>
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

    <div class="modal fade" id="updateMfSched" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">Edit Schedule <i class="fas fa-calendar-alt"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
  <fieldset class="fieldset-border">
    <div class="modal-body">
        <div class="row">
          <input type="text" name="rowd" id="rowd" hidden>
          <div class='col-md-12 form-group'>
            Schedule Name
            <input type="text" name='schedulename' id='schedulename' class="form-control" placeholder="Schedule Name/Type" required>
          </div>
        </div>
        <div class="row">
          <div class='col-md-12 form-group'>
            Status
            <select type="select" class="form-select" id="stts" name="stts" >
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>    
          </div>
    </div>              
</fieldset> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" onclick="updateMfSched()"  ><i class="fas fa-check-circle"></i> Submit</button>
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

$('#allMfschedList').DataTable({
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

           $('#sched_to').change(function(){

                if($('#sched_to').val() < $('#sched_from').val()){

                    swal({text:"Date to must be greater than date from!",icon:"error"});
                    document.getElementById('sched_to').value = '';               
                }
            });


            $('#sched_from').change(function(){

                if($('#sched_from').val() > $('#sched_to').val()){
                    var input2 = document.getElementById('sched_to');
                    document.getElementById("sched_to").min = $('#sched_from').val();
                    input2.value = '';
                }
            });



    function editMfschedModal(id){
          
        $('#updateMfSched').modal('toggle'); 
        document.getElementById('rowd').value =  id;   
        document.getElementById('schedulename').value =  document.getElementById('sn'+id).innerHTML;
        document.getElementById('stts').value =  document.getElementById('stts'+id).innerHTML;
    }


    function updateMfSched()
    {

        var url = "../mf_sched/updatemfsched_process.php";
        var rowid = document.getElementById("rowd").value;
        var schedule_name = document.getElementById("schedulename").value;
        var status = document.getElementById("stts").value;

          swal({
            title: "Are you sure?",
            text: "You want to update this schedule type?",
            icon: "success",
            buttons: true,
            dangerMode: true,
          })
          .then((updateMfSched) => {
            if (updateMfSched) {
                  $.post (
                      url,
                      {
                          action: 1,
                          rowid: rowid ,
                          schedule_name: schedule_name,
                          status: status
                                     
                      },
                      function(data) { 
                              swal({
                              title: "Success!", 
                              text: "Successfully updated the schedule details!", 
                              type: "success",
                              icon: "success",
                              }).then(function() {
                                location.reload();
                              });  
                  });
            } else {
              swal({text:"You cancel the updating of schedule details!",icon:"error"});
            }
          });

  }


</script>


<?php include("../_footer.php");?>
