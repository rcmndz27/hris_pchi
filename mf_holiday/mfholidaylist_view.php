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
            include("../mf_holiday/mfholidaylist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfholidayList = new MfholidayList(); 
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
<link rel="stylesheet" href="../mf_holiday/mfholiday.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript"  src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="../mf_holiday/mfholiday_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL HOLIDAY LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-calendar-alt mr-1'>
                        </i>Holiday List</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-warning" id="mfholidayEntry"><i class="fas fa-plus-circle"></i> Add New Holiday </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfholidayList->GetAllMfholidayList(); ?>
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
                    <h5 class="modal-title bb" id="popUpModalTitle">HOLIDAY ENTRY <i class="fas fa-calendar-alt"></i></h5>
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
                                        <label class="control-label" for="descs">Holiday Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="holidaydescs"
                                            id="holidaydescs" placeholder="Holiday Name....." > 
                                    </div>
                                </div>                            
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Holiday Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="holidaytype" name="holidaytype" >
                                            <option value="Regular Holiday">Regular Holiday</option>
                                            <option value="Special Holiday">Special Holiday</option>
                                        </select>    
                                    </div>
                                </div>   
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pstatus">Holiday Term<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="holidayterm" name="holidayterm" >
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                        </select>    
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="code">Holiday Date<span class="req">*</span></label>
                                        <input type="date" id="holidaydate" name="holidaydate" class="form-control">
                                    </div>
                                </div>                                                                                                  
                              <div class="col-lg-6" id="temp_id">
                                  <div class="form-group">
                                        <label class="control-label" for="code">Expiration Date<span class="req">*</span></label>
                                        <input type="date" id="expired_date" name="expired_date" class="form-control">
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

    <div class="modal fade" id="updateMfhol" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE HOLIDAY <i class="fas fa-calendar-alt"></i></h5>
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
                                        <label class="control-label" for="descs">Holiday Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="hdescs"
                                            id="hdescs" placeholder="Holiday Name....." > 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Holiday Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="htype" name="htype" >
                                            <option value="Regular Holiday">Regular Holiday</option>
                                            <option value="Special Holiday">Special Holiday</option>
                                        </select>    
                                    </div>
                                </div>   
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="hterm">Holiday Term<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="hterm" name="hterm" >
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                        </select>    
                                    </div>
                                </div>                                                                                              
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="hdate">Holiday Date<span class="req">*</span></label>
                                        <input type="date" id="hdate" name="hdate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6" id="tempid">
                                  <div class="form-group">
                                        <label class="control-label" for="edate">Expiration Date<span class="req">*</span></label>
                                        <input type="date" id="edate"edatename="edate" class="form-control">
                                    </div>
                                </div>                                                                                          
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status<span class="req">*</span></label>
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
                                    <button type="button" class="btn btn-success" onclick="updateMfhol()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->


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


$(document).ready( function () {

$('#allMfholidayList').DataTable({
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

$('#expired_date').change(function(){

if($('#expired_date').val() < $('#holidaydate').val()){

    swal({text:"Expired date must be greater than date from!",icon:"error"});

    var input2 = document.getElementById('expired_date');
    input2.value = '';               
}
});


$('#holidaydate').change(function(){

if($('#holidaydate').val() > $('#expired_date').val()){
    var input2 = document.getElementById('expired_date');
    document.getElementById("expired_date").min = $('#holidaydate').val();
    input2.value = '';
}
});



$('#hterm').change(function(){

var ht = $('#hterm').val();
var edt = $('#edate').val();


if(ht == 'Permanent'){
    $('#tempid').hide();
    if(edt == '1970-01-01'){
        document.getElementById('edate').value = $('#hdate').val();
    }else{
        document.getElementById('edate').value = edt;
    }
}else{
    $('#tempid').show();
    if(edt == '1970-01-01'){
        document.getElementById('edate').value = $('#hdate').val();
    }else{
        document.getElementById('edate').value = edt;
    }    
}

});

    function editMfholidayModal(id,edate){
          
        $('#updateMfhol').modal('toggle'); 
        document.getElementById('rowd').value =  id;   
        document.getElementById('hdate').value =  document.getElementById('hd'+id).innerHTML;   
        document.getElementById('htype').value =  document.getElementById('ht'+id).innerHTML;  
        document.getElementById('hdescs').value =  document.getElementById('hn'+id).innerHTML;  
        document.getElementById('hterm').value =  document.getElementById('htr'+id).innerHTML;  
        document.getElementById('edate').value =  document.getElementById('ed'+id).innerHTML;

        var ht = $('#hterm').val();

        if(ht == 'Permanent'){
            $('#tempid').hide();
        }else{
            $('#tempid').show();
        }
    }


    function updateMfhol()
    {

        var url = "../mf_holiday/updatemfholiday_process.php";
        var rowid = document.getElementById("rowd").value;
        var holidaydate = document.getElementById("hdate").value;
        var holidaytype = document.getElementById("htype").value;
        var holidaydescs = document.getElementById("hdescs").value;
        var holidayterm = document.getElementById("hterm").value;
        if(holidayterm == 'Permanent'){
            var expired_date = document.getElementById("hdate").value;;
        }else{
            var expired_date = document.getElementById("edate").value;
        }
        var status = document.getElementById("stts").value;       

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this holiday type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateMfhol) => {
                          if (updateMfhol) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        rowid: rowid ,
                                        holidaydate: holidaydate ,
                                        holidaytype: holidaytype ,
                                        holidaydescs: holidaydescs ,
                                        holidayterm: holidayterm ,
                                        expired_date: expired_date ,
                                        status: status                                       
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the holiday details!", 
                                            type: "success",
                                            icon: "success",
                                            }).then(function() {
                                                $('#updateMfhol').modal('hide');
                                                 document.getElementById('hd'+rowid).innerHTML = holidaydate;
                                                 document.getElementById('ht'+rowid).innerHTML = holidaytype;
                                                 document.getElementById('hn'+rowid).innerHTML = holidaydescs;
                                                 document.getElementById('htr'+rowid).innerHTML = holidayterm;
                                                 document.getElementById('st'+rowid).innerHTML = status;
                                            });  
                                });
                          } else {
                            swal({text:"You cancel the updating of holiday details!",icon:"error"});
                          }
                        });

                }

</script>


<?php include("../_footer.php");?>
