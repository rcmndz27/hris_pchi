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
                <button type="button" class="btn btn-primary" id="mfschedEntry"><i class="fas fa-plus-circle"></i>  Add New Schedule</button>
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


    </div> <!-- main body mbt closing -->
</div><!-- container closing -->
<script type="text/javascript">

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



function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allMfschedList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}

    function editMfschedModal(id){
          
        $('#updateMfSched').modal('toggle'); 
        document.getElementById('rowd').value =  id;   
        document.getElementById('schedfrom').value =  document.getElementById('pcf'+id).innerHTML;   
        document.getElementById('schedto').value =  document.getElementById('pct'+id).innerHTML;  
        document.getElementById('cotype').value =  document.getElementById('cor'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
    }


    function updateMfSched()
    {

        var url = "../mf_sched/updatemfsched_process.php";
        var rowid = document.getElementById("rowd").value;
        var sched_from = document.getElementById("schedfrom").value;
        var sched_to = document.getElementById("schedto").value;
        var co_type = document.getElementById("cotype").value;
        if(co_type == 1){
            var ctype =  'Payroll 30th';
        }else{
            var ctype =  'Payroll 15th';
        }
        var status = document.getElementById("stts").value;

        // console.log(sched_from);
        // console.log(sched_to);       
        // console.log(co_type);
        // console.log(status);
        // console.log(ctype);
        // return false;


                        swal({
                          title: "Are you sure?",
                          text: "You want to update this sched type?",
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
                                        sched_from: sched_from,
                                        sched_to: sched_to,
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
                                                $('#updateMfSched').modal('hide');
                                                 document.getElementById('pcf'+rowid).innerHTML = sched_from;
                                                 document.getElementById('pct'+rowid).innerHTML = sched_to;
                                                 document.getElementById('cot'+rowid).innerHTML = ctype;
                                                 document.getElementById('st'+rowid).innerHTML = status;
                                            });  
                                });
                          } else {
                            swal({text:"You cancel the updating of payroll cut-off details!",icon:"error"});
                          }
                        });

                }
    

getPagination('#allMfschedList');

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');  
      // reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //  numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
                                  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
                                </li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');                  // add active class to the clicked
        limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
      limitPagging();
    })
    .val(10)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
    // alert($('.pagination li').length)

    if($('.pagination li').length > 7 ){
            if( $('.pagination li.active').attr('data-page') <= 3 ){
            $('.pagination li:gt(5)').hide();
            $('.pagination li:lt(5)').show();
            $('.pagination [data-page="next"]').show();
        }if ($('.pagination li.active').attr('data-page') > 3){
            $('.pagination li:gt(0)').hide();
            $('.pagination [data-page="next"]').show();
            for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                $('.pagination [data-page="'+i+'"]').show();

            }

        }
    }
}

</script>


<?php include("../_footer.php");?>
