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
            include("../applicantprofile/applicantlist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allAppEnt = new ApplicantList(); 
            $mf = new MasterFile();
            $dd = new DropDown();

            $month = date('m');
            $day = date('d');
            $year = date('Y');

            $today = $year . '-' . $month . '-' . $day;

       if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }

    }    
?>
<link rel="stylesheet" type="text/css" href="../applicantprofile/app_view.css">
<script type="text/javascript" src="../applicantprofile/appent_emp.js"></script>
<script type="text/javascript" src="../applicantprofile/verify_appent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
        <div class="section-title">
          <h1>ALL APPLICANT LIST</h1>
        </div>
<div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-users fa-fw'>
                        </i>&nbsp;ALL APPLICANT LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="applicantEntry"><i class="fas fa-user-plus"></i> ADD NEW APPLICANT </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allAppEnt->GetAllApplicantList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">APPLICANT ENTRY &nbsp;<i class="fas fa-user-plus"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
                      <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                   Personal Information
                                </legend>
                             </div>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="familyname">Family Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="familyname"
                                            id="familyname" placeholder="Family Name">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="How you come to apply">How you come to apply?</label>
                                        <select type="select" class="form-select" id="howtoapply" name="howtoapply" >
                                            <option value="Walk-in">Walk-in</option>
                                            <option value="Ads">Ads</option>
                                            <option value="College Placement Office">College Placement Office</option>
                                            <option value="Referred By">Referred By</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="First Name">First Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="firstname"
                                            id="firstname" placeholder="First Name">
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="Referred By">Referred By</label>
                                        <div id="refby_show">
                                            <input type="text" class="form-control inputtext" name="referredby"
                                                id="referredby" placeholder="Referred by">
                                        </div>
                                        <div id="refby_dis">
                                            <input type="text" class="form-control inputtext" placeholder="Referred by" readonly>
                                        </div>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="ReferredDate">Referred Date</label>
                                        <div id="refdate_show">
                                            <input type="date" class="form-control inputtext" name="referreddate"
                                                id="referreddate">
                                        </div>
                                        <div id="refdate_dis">
                                            <input type="date" class="form-control inputtext" readonly>
                                        </div>                                        
                                    </div>
                                </div>                                 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="middlei">Middle Intial</label>
                                        <input type="text" class="form-control inputtext" name="middlei"
                                            id="middlei" placeholder="Middle Initial">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Position Applied (1st preference)<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("jobpos1", $mf->GetJobPosition("jobpos")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">House Number/Subdivision<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="houseno"
                                            id="houseno" placeholder="House Number/Subdivision">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Position Applied (2nd preference)<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("jobpos2", $mf->GetJobPosition("jobpos")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Street/Barangay<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="streetbrgy"
                                            id="streetbrgy" placeholder="Street/Barangay">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Contact Number 1<span class="req">*</span></label>
                                            <input class="form-control inputtext" type="text" onkeypress="return onlyNumberKey(event)"placeholder="0991234567" maxlength="11" size="50%" name="contactno1"
                                                                    id="contactno1" />
                                    </div>
                                </div>                                                                                    
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">City<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="city"
                                            id="city" placeholder="City">
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Contact Number 2</label>
                                        <input class="form-control inputtext" type="text" onkeypress="return onlyNumberKey(event)"placeholder="0991234567" maxlength="11" size="50%" name="contactno2"
                                                                id="contactno2" />
                                      </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Email Address<span class="req">*</span></label>
                                        <input type="email" class="form-control inputtext" name="emailadd"
                                            id="emailadd" placeholder="user@email.com">
                                    </div>
                                </div>                                                   
                            </div>       
                        </fieldset>

                        <fieldset class="fieldset-border">
                               <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    Highest Educational Attainment
                                </legend>
                             </div>
                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="Tertiary">Tertiary</label>
                                        <input type="text" class="form-control inputtext" id="tertiary"
                                            name="tertiary" placeholder="Tertiary">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="discipline">Discipline</label>
                                        <select type="select" class="form-select" id="discipline1" name="discipline1" >
                                            <option value="Agriculture">Agriculture</option>
                                            <option value="Architecture and Design">Architecture and Design</option>
                                            <option value="Businesse">Business</option>
                                            <option value="Education">Education</option>
                                            <option value="Engineering">Engineering</option>
                                            <option value="Formal Sciences">Formal Sciences</option>
                                            <option value="Health Sciences">Health Sciences</option>
                                            <option value="Media Communication">Media Communication</option>
                                            <option value="Natural Sciences">Natural Sciences</option>
                                            <option value="Public Administration">Public Administration</option>
                                            <option value="Social Sciences">Social Sciences</option>
                                            <option value="Transportation">Transportation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label"  for="School Name">School Name</label>
                                        <input type="text" class="form-control inputtext" name="schoolname1" id="schoolname1"
                                            placeholder="School Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="secondary">Secondary</label>
                                        <input type="text" class="form-control inputtext" id="secondary"
                                            name="secondary" placeholder="Secondary">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="discipline">Discipline</label>
                                        <select type="select" class="form-select" id="discipline1" name="discipline1" >
                                            <option value="Agriculture">Agriculture</option>
                                            <option value="Architecture and Design">Architecture and Design</option>
                                            <option value="Businesse">Business</option>
                                            <option value="Education">Education</option>
                                            <option value="Engineering">Engineering</option>
                                            <option value="Formal Sciences">Formal Sciences</option>
                                            <option value="Health Sciences">Health Sciences</option>
                                            <option value="Media Communication">Media Communication</option>
                                            <option value="Natural Sciences">Natural Sciences</option>
                                            <option value="Public Administration">Public Administration</option>
                                            <option value="Social Sciences">Social Sciences</option>
                                            <option value="Transportation">Transportation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label"  for="School Name">School Name</label>
                                        <input type="text" class="form-control inputtext" name="schoolname2" id="schoolname2"
                                            placeholder="School Name">
                                    </div>
                                </div>
                            </div>
                        </fieldset>   
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                <button type="button" class="btn btn-success" id="Submit" ><i class="fas fa-check-circle"></i> Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">VERIFY APPLICANT </h5>
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
                                <div class="col-lg-1">
                                </div>
                                <div class="col-lg-10">
                                    <div class="form-group">
                                        <label class="control-label" for="applicant_name">Applicant Name</label>
                                        <input type="text" class="form-control" id="applicant_name" name="applicant_name" 
                                         readonly>
                                    </div>
                                </div> 
                                <div class="col-lg-1">
                                <input type="text" class="form-control inputtext" id="rowid" name="rowid" hidden>
                                </div>   
                                <div class="col-lg-1">
                                </div>                                
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="control-label" for="Relevant">Relevant</label>
                                        <input type="text" class="form-control inputtext"
                                        onkeypress="return onlyNumberKey(event)" placeholder="0000" maxlength="4" id="relevant" name="relevant" required>
                                    </div>
                                </div> 
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="control-label" for="notRelevant">Not Relevant</label>
                                        <input type="text" class="form-control inputtext" onkeypress="return onlyNumberKey(event)" placeholder="0000" maxlength="4" id="not_relevant" name="not_relevant" required>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                </div> 
                                <div class="col-lg-1">
                                </div>                                
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="control-label" for="Verifiedby">Verified By</label>
                                        <input type="text" class="form-control inputtext" value="<?php echo $empName; ?>" readonly>
                                         <input type="text" class="form-control inputtext" id="verified_by" name="verified_by" value="<?php echo $empCode; ?>" hidden>
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="Verifieddate">Verified Date</label>
                                        <input type="date" class="form-control inputtext" id="verified_date" name="verified_date" value="<?php echo $today; ?>" min="<?php echo $today; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="action_taken">Action Taken</label>
                                        <select type="select" class="form-select" id="action_taken" name="action_taken" >
                                            <option value="Dead File">Dead File</option>
                                            <option value="Keep">Keep</option>
                                            <option value="Referred">Referred</option>
                                            <option value="Unverified">Unverified</option>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="col-lg-1">
                                </div> 
                                <div class="col-lg-1">
                                </div>                                
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <label class="control-label" for="referred_to">Referred To</label>
                                           <div id="ref_to">
                                                <?php $dd->GenerateDropDown("referred_to", $mf->GetAllDepartment("alldep")); ?> 
                                            </div>
                                            <div id="ref_to_dis">
                                                <input type="text" class="form-control inputtext" id="referred_to" name="referred_to" placeholder="Referred To" readonly>
                                            </div>
                                    </div>
                                </div> 
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="control-label" for="referral_date">Referral Date</label>
                                            <div id="ref_date">
                                                <input type="date" class="form-control inputtext" id="referral_date" name="referral_date"  value="<?php echo $today; ?>">
                                            </div>
                                            <div id="ref_date_dis">
                                                <input type="date" class="form-control inputtext" id="referral_date" name="referral_date" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- form row closing -->
                            </fieldset> 
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="verifyAppli()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div>                            
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE APPLICANT &nbsp; <i class="fas fa-edit"></i></h5>
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
                                <input type="text" class="form-control inputtext" id="uprowid" name="uprowid" hidden>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="applicant_name">Applicant Name</label>
                                        <input type="text" class="form-control" id="applicant_names" name="applicant_names" 
                                         readonly>
                                    </div>
                                </div>  
                        <input type="text" class="form-control inputtext" id="rowid" name="rowid" hidden>
                        <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="manpow">Manpower Request</label>
                                           <div id="ref_to">
                                                <?php $dd->GenerateSingleDropDown("manpow", $mf->GetAllManpowerList("manpow")); ?>
                                            </div>
                                    </div>
                                </div>                                                         
                        </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateAppli()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div>                            
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->




<script>

    function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return swal({text:"Only numbers are allowed!",icon:"error"});;
        return true;
    }

    function verifyEntryModal(verid,name){

        $('#verifyModal').modal('toggle');
        document.getElementById('applicant_name').value = name;
        document.getElementById('rowid').value = verid;

    }

    function updateEntryModal(verid,name){

        $('#updateModal').modal('toggle');
        document.getElementById('applicant_names').value = name;
        document.getElementById('uprowid').value = verid;        

    }


 function verifyAppli()
{

    if($('#action_taken').val() === 'Referred'){
        var appent_status = 1;
        var status = 'Active';
    }else{
        var appent_status = 0;
        var status = 'Inactive';
    }
    var url = "../applicantprofile/verify_appent_process.php";
    var relevant = document.getElementById("relevant").value;
    var not_relevant = document.getElementById("not_relevant").value;
    var verified_by = document.getElementById("verified_by").value;
    var verified_date = document.getElementById("verified_date").value;
    var action_taken = document.getElementById("action_taken").value;
    var referred_to = document.getElementById("referred_to").value;
    var referral_date = document.getElementById("referral_date").value;  
    var rowid = document.getElementById("rowid").value;
    var applicant_names = document.getElementById("applicant_name").value;    
    var appname = "'"+applicant_names+"'";    

    // console.log("'"+applicant_names+"'");     
    // return false;

    
    if(relevant == '' || not_relevant == ''){
              swal({text:"Kindly fill up the blank fields.",icon:"error"});
    }else{
                    swal({
                      title: "Are you sure?",
                      text: "You want to verify this applicant?",
                      icon: "success",
                      buttons: true,
                      dangerMode: true,
                    })
                    .then((verifyApp) => {
                      if (verifyApp) {
                            $.post (
                                url,
                                {
                                    action: 1,
                                    relevant: relevant ,
                                    not_relevant: not_relevant ,
                                    verified_by: verified_by ,
                                    verified_date: verified_date ,
                                    action_taken: action_taken ,               
                                    referred_to: referred_to ,
                                    referral_date: referral_date ,
                                    appent_status: appent_status,
                                    rowid: rowid 
                                },
                    function(data) {
                            swal({
                            title: "Success!", 
                            text: "Successfully verified applicant details!", 
                            type: "success",
                            icon: "success",
                            }).then(function(e) {
                                $('#verifyModal').modal('hide');
                                document.getElementById('st'+rowid).innerHTML = status;

                            if(status == 'Active'){
                                document.getElementById('upd'+rowid).innerHTML = '<button type="button" class="uptv" onclick="updateEntryModal('+rowid+','+appname+')"><i class="fas fa-edit"></i> UPDATE</button>';
                            }else{
                                document.getElementById('upd'+rowid).innerHTML = '<button type="button" class="btn btn-info btn-sm" onclick="verifyEntryModal('+rowid+','+appname+')"><i class="fas fa-user-check"></i> VERIFY</button>'; 
                            }
                            });                                     

                    }
                            );
                      } else {
                        swal({text:"You cancel the verification of applicant!",icon:"error"});
                      }
            });
     }                        

}
    


     function updateAppli()
    {
        

        var url = "../applicantprofile/update_appent_process.php";
        var man_pow = document.getElementById("manpow").value;   
        var uprowid = document.getElementById("uprowid").value; 

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this applicant?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateApp) => {
                          if (updateApp) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        man_pow: man_pow,
                                        rowid: uprowid
                                    },
                                    function(data) { 
                                        swal({
                                        title: "Success!", 
                                        text: "Successfully verified applicant details!", 
                                        type: "success",
                                        icon: "success",
                                        }).then(function(e) {
                                            $('#updateModal').modal('hide');
                                            document.getElementById('st'+uprowid).innerHTML = 'Active';
                                            document.getElementById('upd'+uprowid).innerHTML = 'HIRED';
                                        }); 
                                }
                                );
                          } else {
                            swal({text:"You cancel the updating of applicant!",icon:"error"});
                          }
                        });

    }


    function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allAppEnt");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}



getPagination('#allAppEnt');

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
