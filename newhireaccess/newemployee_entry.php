<?php
        
    include('../config/db.php');
    include('../newemp_entry.php');
    include('../elements/DropDown.php');
    include('../controller/MasterFile.php');

    $mf = new MasterFile();
    $dd = new DropDown();

    $month = date('m');
    $day = date('d');
    $year = date('Y');

    $today = $year . '-' . $month . '-' . $day;

    $date = strtotime($today.'-18 year');
?>   
<link rel="stylesheet" type="text/css" href="../newhireaccess/newemp.css">
<script type="text/javascript" src="../newhireaccess/newemp.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>EMPLOYEE PROFILE - PERSONAL DATA SHEET</h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-users fa-fw'>
                        </i>&nbsp; EMPLOYEE PROFILE - PERSONAL DATA SHEET</b></li>
            </ol>
          </nav>
          <form id="applicantform" method="post">
                <ul class="nav nav-tabs tabrec" id="myTab" name="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="personal-tab" name="personal-tab" data-toggle="tab"
                            href="#personal" role="tab" aria-controls="personal" aria-selected="true">Personal</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="education-tab" name="education-tab" data-toggle="tab" href="#education"
                            role="tab" aria-controls="education" aria-selected="false">Education</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="job-tab" name="job-tab" data-toggle="tab" href="#job" role="tab"
                            aria-controls="job" aria-selected="false">Employment Record </a>
                    </li>

                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade active show" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                      <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    PERSONAL DATA SHEET
                                </legend>
                             </div>
                        <div class="form-row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                <label class="control-label" for="collegeCourse">Upload Photo </label>
                                <input class="d-block" type="file" name="empimgpic" id="empimgpic" accept="image/png, image/jpeg" onChange="GetEmpImgFile()">
                                </div>
                            </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="collegeCourse">INSTRUCTIONS:</label>
                                            <p>Please answer each question clearly and completely.</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="dateField">Date Filed:</label>
                                        <input type="text" class="form-control" name="dateFiled" id="dateFiled" value="<?php echo date("F d , Y"); ?>" readonly>  
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="newEmpCode">Employee Code:<span class="req">*</span></label>
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text">PCH</span>
                                            </div>
                                            <input type="text" class="form-control inputtext" id="emp_code" name="emp_code" placeholder="000999">
                                          </div> 
                                    </div>
                                </div>   

                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="employeeid">Employee ID:</label>
                                        <input type="text" class="form-control" name="emp_id"
                                            id="emp_id" placeholder="Employee ID">                                        
                                    </div>
                                </div>                                 

                                                             
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="positionList">Position Title:<span class="req">*</span></label>
                                        <?php $dd->GenerateDropDown("positiontitle", $mf->GetJobPosition("jobpos")); ?>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="department">Department:<span class="req">*</span></label>
                                    <?php $dd->GenerateDropDown("department", $mf->GetAllDepartment("alldep")); ?>                                             
                                    </div>
                                </div>                                                                                                
                            </div> 

                          <div class="form-row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="How you come to apply">Source of Application:</label>
                                        <select type="select" class="form-select" id="howtoapply" name="howtoapply" >
                                            <option value="Walk-in">Walk-in</option>
                                            <option value="Ads">Ads</option>
                                            <option value="College Placement Office">College Placement Office</option>
                                            <option value="LinkedIn">LinkedIn</option>
                                            <option value="Indeed">Indeed</option>
                                            <option value="JobStreet">JobStreet</option>
                                            <option value="Website">Website</option>
                                            <option value="Referred By">Referred By</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="Referred By">Remarks:</label>
                                        <div id="refby_show">
                                            <input type="text" class="form-control inputtext" name="referredby"
                                                id="referredby" placeholder="Remarks">
                                        </div>
                                        <div id="refby_dis">
                                            <input type="text" class="form-control inputtext" placeholder="Referred by" readonly>
                                        </div>                                        
                                    </div>
                                </div> 
                            </div><br>
                            <div class="form-row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="firstname">First Name:<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" id="firstname"
                                            name="firstname" place placeholder="First Name">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="middlename">Middle Name:</label>
                                        <input type="text" class="form-control inputtext" name="middlename"
                                            id="middlename" placeholder="Middle Name">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label"  for="lastname">Last Name:<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="lastname" id="lastname"
                                            placeholder="Last Name">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label class="control-label" for="maidenname">Maiden Name (if any):</label>
                                    <input type="text" class="form-control" name="maidenname" id="maidenname" placeholder="Maiden Name">
                                </div>
                            </div> <!-- firstname, middlename, lastname, maiden name -->

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="Address">(A) Present Address<span class="req">*</span></label>
                                        <label class="note">(indicate since when)</label>
                                        <input type="text" class="form-control" name="emp_address" id="emp_address" placeholder="Address">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="Permanent">(B) Permanent Address<span class="req">*</span></label>
                                        <label class="note"><input class="btn samea" id="perma" value="SAME IN (A)" onkeydown="return false;"></label>
                                        <input type="text" class="form-control" name="emp_address2" id="emp_address2" placeholder="Address">
                                    </div>
                                </div>
                            </div>      

                            <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                         <label class="control-label" for="telno">Telephone Number:</label>
                                        <input type="text" class="form-control" name="telno" id="telno" onkeypress="return onlyNumberKey(event)" maxlength="20" placeholder="02......">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="cellphone">Mobile Number:<span class="req">*</span></label>
                                        <input type="text" class="form-control" id="celno" onkeypress="return onlyNumberKey(event)" name="celno" maxlength="20"  placeholder="09..........">
                                    </div> 
                                    <div class="form-group">
                                        <label class="control-label" for="emailaddress">Email Address:<span class="req">*</span></label>
                                        <input type="email" class="form-control" id="emailaddress" name="emailaddress" placeholder="user@example.com">
                                    </div>                                    
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="telno1">Other Telephone Number:</label>
                                        <input type="text" class="form-control" id="telno1"  onkeypress="return onlyNumberKey(event)" name="telno1" maxlength="20" placeholder="02......">

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="celno1">Other Mobile Number:</label>
                                        <input type="text" class="form-control" id="celno1" onkeypress="return onlyNumberKey(event)" name="celno1" maxlength="20" placeholder="09..........">

                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="emailaddress1">Other Email Address:</label>
                                        <input type="email" class="form-control" id="emailaddress1" name="emailaddress1" placeholder="user@example.com">
                                    </div>                                      
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="fieldset-border">
                               <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    PERSONAL DATA
                                </legend>
                             </div>
                            <div class="form-row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="birthdate">Date of Birth:<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" id="birthdate"
                                            name="birthdate" max="<?php echo date('Y-m-d',$date); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="birthplace">Place of Birth:<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="birthplace"
                                            id="birthplace" placeholder="Birth Place">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label class="control-label" for="maidenname">Age:<span class="req">*</span></label>
                                     <label class="note">(as of last birthday)</label>
                                    <input type="text" class="form-control" name="age" id="age" placeholder="0" readonly>
                                </div>                                 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label"  for="nationality">Nationality:<span class="req">*</span></label>
                                    <?php $dd->GenerateDropDown("nationality", $mf->GetNationality("nation")); ?>
                                    </div>
                                </div>
                            
                            </div>

                            <div class="form-row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="tin_no">TIN:</label>
                                        <input type="text" class="form-control inputtext" name="tin_no" id="tin_no"
                                            placeholder="TIN" onkeypress="return onlyNumberKey(event)">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="sss_no">SSS No. :</label>
                                        <input type="text" class="form-control inputtext" name="sss_no" id="sss_no"
                                            placeholder="SSS No." onkeypress="return onlyNumberKey(event)">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="phil_no">Philhealth No. :</label>
                                        <input type="text" class="form-control inputtext" name="phil_no"
                                            id="phil_no" placeholder="Philhealth No." onkeypress="return onlyNumberKey(event)">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="pagibig_no">Pag-ibig No. :</label>
                                        <input type="text" class="form-control inputtext" name="pagibig_no" id="pagibig_no"
                                            placeholder="Pag-ibig No." onkeypress="return onlyNumberKey(event)">
                                    </div>
                                </div> 
                            </div>
                          <div class="form-row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="tax_status">Tax Status:</label>
                                        <select type="select" class="form-select" id="tax_status" name="tax_status" >
                                            <option value="Single">Single</option>
                                            <option value="Head of the Family">Head of the Family</option>
                                            <option value="Married">Married</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="married_dependents">no. of dependents</label>
                                        <div id="married_dependents_show">
                                            <input type="text" class="form-control inputtext" name="married_dependents"
                                                id="married_dependents" placeholder="00" onkeypress="return onlyNumberKey(event)" maxlength="2">
                                        </div>
                                        <div id="married_dependents_dis">
                                            <input type="text" class="form-control inputtext" placeholder="00" readonly>
                                        </div>                                        
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="sex">Sex:<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="sex" name="sex" >
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="marital_status">Marital Status:<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="marital_status" name="marital_status" >
                                            <option value="Single">Single</option>
                                            <option value="Widow(er)">Widow(er)</option>
                                            <option value="Married">Married</option>
                                            <option value="Separated">Separated</option>
                                            <option value="Single Parent">Single Parent</option>

                                        </select>
                                    </div>
                                </div>                                                                    
                            </div>

                                <div class="form-row">
                                        <div class="col-lg-5">
                                            <div class="form-group">
                                                <label class="control-label" for="depname">Dependent's Name:</label>
                                                <input type="text" class="form-control" name="depname[]" placeholder="Dependent's Name:">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="control-label" for="depbirthdate">Dependent's Date of Birth:</label>
                                                <input type="date" class="form-control" name="depbirthdate[]">
                                            </div>
                                        </div>  
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label class="control-label" for="deprelationship">Relationship</label>
                                                <input type="text" class="form-control inputtext" name="deprelationship[]" placeholder="Relationship">
                                            </div>
                                        </div>                                              
                                        <div class="col-lg-1">
                                            <div class="form-group"> 
                                                <label class="control-label" for="adddep">&nbsp;</label>
                                                <button type="button" name="add_dep" id="add_dep" class="btn btn-success adddep">+ &nbsp;Add More 
                                                </button> 
                                            </div>
                                        </div>                         
                                </div>
            <div id="dep_dynamic_field"></div>
                        <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="fathername">Father's Name:</label>
                                    <input type="text" class="form-control" name="fathername" id="fathername" placeholder="Father's Name">
                                    </div> 
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="fatheroccupation">Father's Occupation:</label>
                                        <input type="text" class="form-control inputtext" name="fatheroccupation" id="fatheroccupation" placeholder="Occupation....">                                            
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="fatherbirthdate">Father's Age:</label>
                                        <input type="text" class="form-control inputtext" name="fatherbirthdate"
                                            id="fatherbirthdate" onkeypress="return onlyNumberKey(event)" maxlength="">
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="mothername">Mother's Name:</label>
                                    <input type="text" class="form-control" name="mothername" id="mothername" placeholder="Mother's Name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="motheroccupation">Mother's Occupation:</label>
                                        <input type="text" class="form-control inputtext" name="motheroccupation"
                                            id="motheroccupation" placeholder="Occupation...">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="motherbirthdate">Mother's Age:</label>
                                        <input type="text" class="form-control inputtext" name="motherbirthdate" id="motherbirthdate" onkeypress="return onlyNumberKey(event)" maxlength="3"> 
                                    </div>
                                </div>                                                           
                                <div class="col-lg-6">
                                    <div class="form-group">
                                    <label class="control-label" for="spousename">Spouse Name:</label>
                                    <input type="text" class="form-control" name="spousename" id="spousename" placeholder="Spouse Name">
                                    </div>
                                </div>                                                              
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="spousebirthdate">Spouse Birthdate:</label>
                                        <input type="date" class="form-control inputtext" id="spousebirthdate"
                                            name="spousebirthdate" max="<?php echo date('Y-m-d',$date); ?>" >
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="spouseage">Spouse Age:
                                        </label>
                                        <input type="text" class="form-control inputtext" name="spouseage" id="spouseage"
                                            placeholder="00" readonly>
                                    </div>
                                </div>                              
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="spouseoccupation">Spouse Occupation:</label>
                                        <input type="text" class="form-control" name="spouseoccupation" id="spouseoccupation" placeholder="Occupation">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="spousecompany">Spouse Company Name and Address:</label>
                                  <textarea  type="text" class="form-control" id="spousecompany" name="spousecompany" rows="1"
                                    cols="90" placeholder="Company Name and Address....."></textarea>
                                    </div>
                                </div>
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <label class="control-label" for="sibname">Name of Siblings:</label>
                                            <input type="text" class="form-control inputtext" name="sibname[]" placeholder="Name...">
                                        </div>                                                 
                                    </div>  
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="control-label" for="sibrelationship">Relationship:</label>
                                            <input type="text" class="form-control inputtext" name="sibrelationship[]" placeholder="Relationship...">
                                        </div>  
                                    </div>   
                                    <div class="col-lg-1">
                                                <div class="form-group"> 
                                                    <label class="control-label" for="adddep">&nbsp;</label>
                                                    <button type="button" name="add_sib" id="add_sib" class="btn btn-success adddep">+ &nbsp;Add More 
                                                </button> 
                                         </div>
                                    </div> 
                            </div> 
                            <div id="sib_dynamic_field"></div>   
                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="companyrelatives">Have you any near relatives working in another company like PCHI ?</label>
                                        <label class="note">If so, give name, relationship, and organization.</label>
                                        <textarea class="form-control" id="companyrelatives" name="companyrelatives" rows="4"
                                        cols="90" placeholder="Have you any near relatives working in another company like PCHI ?"></textarea>
                                    </div>
                                </div> 
                                <div class="col-lg-12">
                                    <label class="control-label" for="contactperson">Contact person in case of emergency:</label>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="contactpersonname">Name:</label>
                                        <input type="text" class="form-control inputtext" name="contactpersonname"
                                        id="contactpersonname" placeholder="Name....">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="c">Contact Number:</label>
                                        <input type="text" class="form-control inputtext" name="contactpersonno"
                                        id="contactpersonno" placeholder="09........" onkeypress="return onlyNumberKey(event)" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="contactpersonaddress">Address:</label>
                                        <textarea class="form-control" id="contactpersonaddress" name="contactpersonaddress" rows="4"
                                        cols="90" placeholder="Address...."></textarea>
                                    </div>
                                </div>   
<!--                                 <div class="col-lg-12">
                                    <label class="control-label" for="presentAddress">Legal Convictions:</label>
                                    <label class="note">Have you ever been charged or found guilty of the violation of any law (other than minor traffic violations), give details/particulars.</label>
                                  </div>  

                             <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="legalconvictioncharge">Charge:</label>
                                        <input type="text" class="form-control inputtext" name="legalconvictioncharge"
                                            id="legalconvictioncharge" placeholder="Charge...">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="legalconvictiondate">Date:</label>
                                        <input type="date" class="form-control inputtext" name="legalconvictiondate"
                                            id="legalconvictiondate">
                                    </div>
                                </div>   
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="legalconvictionwhere">Where Tried:</label>
                                        <input type="text" class="form-control inputtext" name="legalconvictionwhere"
                                            id="legalconvictionwhere" placeholder="Where...">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="legalconviction">Conviction:</label>
                                        <input type="text" class="form-control inputtext" name="legalconviction"
                                            id="legalconviction" placeholder="Conviction...">
                                    </div>
                                </div>   
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="civilcase">Have you been involved in any administrative or civil cases?</label>
                                        <label class="note" for="philhealth">If yes, state nature</label>
                                        <input type="text" class="form-control inputtext" name="civilcase"
                                            id="civilcase" placeholder="Have you been involved in any administrative or civil cases?">
                                    </div>
                                </div>   -->
                            </div> 
                             
               
<!--                                 <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="note" for="philhealth">May we seek references from your present/most recent employer and your former employers and supervisors? If not, specify exclusions. You  may also add more references here including name, occupation, relationship to you, address, telephone and fax numbers. We would also ask you to be responsible in notifying the people you have submitted as your references.</label>
                                    </div>
                                </div> -->
                                   <!-- legal conviction contact -->
<!--              <div class="form-row">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label" for="conname">Name:</label>
                                            <input type="text" class="form-control inputtext" name="conname[]" placeholder="Name...">
                                        </div>                                                 
                                    </div>  
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label class="control-label" for="conoccupation">Occupation:</label>
                                            <input type="text" class="form-control inputtext" name="conoccupation[]" placeholder="Occupation...">
                                        </div>  
                                    </div>  
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label" for="concompany">Company:</label>
                                            <input type="text" class="form-control inputtext" name="concompany[]" placeholder="Company...">
                                        </div>  
                                    </div>   
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <label class="control-label" for="conconviction">Conviction:</label>
                                            <input type="text" class="form-control inputtext" name="conconviction[]" placeholder="Conviction...">
                                        </div>  
                                    </div>                                        
                                    <div class="col-lg-1">
                                        <div class="form-group"> 
                                                <label class="control-label" for="adddep">&nbsp;</label>
                                                <button type="button" name="add_con" id="add_con" class="btn btn-success adddep">+ &nbsp;Add More 
                                                </button> 
                                        </div>
                                    </div>
            </div> -->
       
<!--             <div id="con_dynamic_field"></div>            
             <div class="form-row">
                <div class="col-lg-12">
                            <div class="form-group">
                                        <label class="control-label" for="rightsemployee">Will you have or can you arrange return rights to your present employer?</label>
                                        <input type="text" class="form-control inputtext" name="rightsemployee"
                                            id="rightsemployee" placeholder="Will you have or can you arrange return rights to your present employer?">                                        
                            </div>
                    </div>
                </div> 
 -->
                            <div class="d-flex justify-content-center">
                                    <!-- -- Next, Go to Education Tab -- -->
                                    <button type="button" class="btn btn-success adddep" onclick='nextPTabAct();'>  NEXT >>
                                    </button>    
                             </div>                

                        </fieldset>
                </div>

                    <div class="tab-pane fade" id="education" role="tabpanel" aria-labelledby="education-tab">
                        <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    EDUCATION
                                </legend>
                             </div>

            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="note2" for="philhealth">Give details in chronological order from High Scohol. Include short courses and postgraduate studies in your professional/occupation and related fields.</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="schoolfrom">From:</label><br><br>
                                        <input type="date" class="form-control" name="schoolfrom[]">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="schoolto">To:</label><br><br>
                                        <input type="date" class="form-control" name="schoolto[]"> 
                                    </div>
                                </div>                                
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="schoolname">Name of  School/College/University:</label><br><br>
                                        <input type="text" class="form-control" name="schoolname[]" placeholder="School Name...">                                            
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="coursename">Course and Specialization:</label>
                                        <input type="text" class="form-control" name="coursename[]" placeholder="Course Name...">                                            
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="certificatedegree">Certificate/Diploma/Degree obtained:</label>
                                        <input type="text" class="form-control" name="certificatedegree[]" placeholder="Certificate/Diploma">                                            
                                    </div>
                                </div>                                       
                                    <div class="col-lg-1">
                                        <div class="form-group"> 
                                                <label class="control-label" for="adddep">&nbsp;</label><br><br>
                                                <button type="button" name="add_edu" id="add_edu" class="btn btn-success adddep">+ &nbsp;Add More 
                                                </button> 
                                        </div>
                                    </div>
            </div>
       
                        <div id="edu_dynamic_field"></div>  

                            <div class="d-flex justify-content-center">
                                    <!-- -- Next, Go to Employment Tab -- -->
                                    <button type="button" class="btn btn-success adddep" onclick='prevETabAct();'> << PREV 
                                    </button>&nbsp;
                                    <button type="button" class="btn btn-success adddep" onclick='nextETabAct();'>  NEXT >>
                                    </button>
                             </div>
                </fieldset>
            </div>

                    <div class="tab-pane fade" id="job" role="tabpanel" aria-labelledby="job-tab">
                        <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                    EMPLOYMENT RECORD ( <span id="empcnt">1</span> )
                                </legend>                              
                             </div>
                             
                            <div class="form-row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="note2" for="philhealth">Starting with your present or most recent post, list in reverse order positions held. Attached additional pages if needed.</label>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="philhealth">Present or Most Recent Employment</label>
                                    </div>
                                </div>                                
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="Period">Period</label>
                                    </div>
                                </div> 
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="Positions">Positions you have handled in the same organization before:</label>
                                    </div>
                                </div>                                 
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="jobfrom">From:</label>
                                        <input type="date" class="form-control inputtext" name="jobfrom[]">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <label class="control-label" for="jobto">To:</label>
                                        <input type="date" class="form-control inputtext" name="jobto[]">
                                    </div>
                                </div>
<!--                                 <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="startingposition">Starting:</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="startingposition[]" placeholder="Starting...">
                                    </div>
                                </div> -->
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label class="control-label" for="mostrecentposition">Most Recent Position:</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="mostrecentposition[]" placeholder="Most Recent...">
                                    </div>
                                </div>                                                                                   
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="notypeemployees">Number and type of employees supervised by you, (if any):</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="notypeemployees[]" placeholder="Required">
                                    </div>
                                </div>
                                 <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="employername">Name of Employer:</label>
                                        <input type="text" class="form-control inputtext"
                                            name="employername[]" placeholder="Employer's Name...">
                                    </div>
                                </div>                               
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="employeraddress">Address of Employer:</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="employeraddress[]" placeholder="Employer's Address...">
                                    </div>
                                </div>
<!--                                 <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="supervisorname">Name  of Supervisor:</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="supervisorname[]" placeholder="Supervisor's Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="supervisortitle">Title of Supervisor:</label>
                                        <input type="text" class="form-control inputtext" 
                                            name="supervisortitle[]" placeholder="Supervisor's Title">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="duties">Brief description of your duties and responsibilities:</label>
                                        <input type="text" class="form-control inputnumber" name="duties[]"
                                            placeholder="Duties....">
                                    </div>
                                </div>-->

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="reasonforleaving">Reason for leaving:</label>
                                        <input type="text" class="form-control inputnumber" 
                                            name="reasonforleaving[]" placeholder="Reason for leaving...">
                                    </div>
                                </div> 

                <div id="job_dynamic_field"></div> 
                                 <div class="col-lg-4">
                                </div>
                                 <div class="col-lg-3">
                                </div>
                                 <div class="col-lg-3">
                                </div>
                                <div class="col-lg-2">
                                        <div class="form-group"> 
                                                <button type="button" name="add_job" id="add_job" class="btn btn-success addjob">+ &nbsp;Add More Employment
                                                </button> 
                                        </div>
                                </div>                                
                            </div> 

                            <div class="d-flex justify-content-center">
                                    <!-- -- Next, Go to Employment Tab -- -->
                                    <button type="button" class="btn btn-success adddep" onclick='prevJTabAct();'> << PREV 
                                    </button>&nbsp;
                                </div> 
                          
                                                                      
                            <div class="mt-3 d-flex justify-content-center">
                                <button type="button" class="btn btn-success" id="Submit"><i class="fas fa-check-circle"></i> Submit</button>
                            </div>       
                               
                        </fieldset><!-- JobHistory  -->

                    </div>

            </form>

    </div>
</div>
<script type="text/javascript">
    
        function onlyNumberKey(evt) {
          
        // Only ASCII character in that range allowed
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return swal({text:"Only numbers are allowed!",icon:"error"});;
        return true;
    }

   
    function nextPTabAct() {   
        document.getElementById("education-tab").click();
}

    function prevJTabAct() {   
        document.getElementById("education-tab").click();
}

    function nextETabAct() {   
        document.getElementById("job-tab").click();
}

    function prevETabAct() {   
        document.getElementById("personal-tab").click();
}
</script>

 <script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add_dep').click(function(){  
           i++;  
            $('#dep_dynamic_field').append('<div class="form-row" id="row'+i+'"><div class="col-lg-5" ><div class="form-group"><input type="text" class="form-control inputtext" name="depname[]" placeholder="Dependent&#39 Name" ></div></div><div class="col-lg-3"><div class="form-group"><input type="date" class="form-control inputtext" name="depbirthdate[]"></div></div><div class="col-lg-3"><div class="form-group"><input type="text" class="form-control inputtext" name="deprelationship[]" placeholder="Relationship" ></div></div><div class="col-lg-1"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">x</button></div></div></div>');

      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });   
 });  
 </script>

  <script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add_sib').click(function(){  
           i++;  
            $('#sib_dynamic_field').append('<div class="form-row" id="row'+i+'"><div class="col-lg-7"><div class="form-group"><input type="text" class="form-control inputtext" name="sibname[]" placeholder="Name..."></div></div><div class="col-lg-4"><div class="form-group"><input type="text" class="form-control inputtext" name="sibrelationship[]" placeholder="Relationship..."></div></div><div class="col-lg-1"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">x</button></div></div></div>');

      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });   
 });  
 </script>


<!--   <script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add_con').click(function(){  
           i++;  
            $('#con_dynamic_field').append('<div class="form-row" id="row'+i+'"><div class="col-lg-3"><div class="form-group"><input type="text" class="form-control inputtext" name="conname[]" placeholder="Name..."></div></div><div class="col-lg-2"><div class="form-group"><input type="text" class="form-control inputtext" name="conoccupation[]" placeholder="Occupation..."></div></div><div class="col-lg-3"><div class="form-group"><input type="text" class="form-control inputtext" name="concompany[]" placeholder="Company..."></div></div><div class="col-lg-3"><div class="form-group"><input type="text" class="form-control inputtext" name="conconviction[]" placeholder="Conviction..."></div></div><div class="col-lg-1"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">x</button></div></div></div>');

      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });   
 });  
 </script> -->

   <script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add_edu').click(function(){  
           i++;  
            $('#edu_dynamic_field').append('<div class="form-row" id="row'+i+'"><div class="col-lg-2"><div class="form-group"><input type="date" class="form-control"  name="schoolfrom[]"></div></div><div class="col-lg-2"><div class="form-group"><input type="date" class="form-control" name="schoolto[]"></div></div> <div class="col-lg-3"><div class="form-group"><input type="text" class="form-control"  name="schoolname[]" placeholder="School Name...."></div></div><div class="col-lg-2"><div class="form-group"><input type="text" class="form-control"  name="coursename[]" placeholder="Course Name..."> </div></div><div class="col-lg-2"><div class="form-group"><input type="text" class="form-control"  name="certificatedegree[]" placeholder="Certificate/Diploma..."></div></div><div class="col-lg-1"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">x</button></div></div></div>');
      });  

      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });   
 });  
 </script>

<script>  
 $(document).ready(function(){  
      var i=1;  

      $('#add_job').click(function(){  
            var empCount = document.getElementById("empcnt").innerText;
            var parseEmpC = parseInt(empCount);
            document.getElementById("empcnt").innerText = parseEmpC + 1;
           i++;  
            $('#job_dynamic_field').append('<div class="form-row" id="row'+i+'"><div class="col-lg-12"><hr></div><div class="col-lg-12"><div class="form-group"></div></div><div class="col-lg-4"><div class="form-group"><label class="control-label" for="Period">Period</label></div></div> <div class="col-lg-8"><div class="form-group"><label class="control-label" for="Positions">Positions you have handled in the same organization before:</label></div></div><div class="col-lg-2"><div class="form-group"><label class="control-label" for="jobfrom">From:</label><input type="date" class="form-control inputtext" name="jobfrom[]"></div></div><div class="col-lg-2"><div class="form-group"><label class="control-label" for="jobto">To:</label><input type="date" class="form-control inputtext"  name="jobto[]"></div></div><div class="col-lg-4"><div class="form-group"><label class="control-label" for="stratingposition">Starting:</label><input type="text" class="form-control inputtext" name="startingposition[]" placeholder="Starting..."></div></div><div class="col-lg-4"><div class="form-group"><label class="control-label" for="mostrecentposition">Most Recent:</label><input type="text" class="form-control inputtext" name="mostrecentposition[]" placeholder="Most Recent..."></div></div><div class="col-lg-12"><div class="form-group"><label class="control-label" for="notypeemployees">Number and type of employees supervised by you, (if any):</label><input type="text" class="form-control inputtext" name="notypeemployees[]" placeholder="Required"></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label" for="employername">Name of Employer:</label><input type="text" class="form-control inputtext" name="employername[]" placeholder="Employer&#39s Name..."></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label" for="employeraddress">Address of Employer:</label><input type="text" class="form-control inputtext" name="employeraddress[]" placeholder="Employer&#39s Address..."></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label" for="supervisorname">Name  of Supervisor:</label><input type="text" class="form-control inputtext" name="supervisorname[]" placeholder="Supervisor&#39s Name"></div></div><div class="col-lg-6"><div class="form-group"><label class="control-label" for="supervisortitle">Title of Supervisor:</label><input type="text" class="form-control inputtext" name="supervisortitle[]" placeholder="Supervisor&#39s Title"></div></div><div class="col-lg-12"><div class="form-group"><label class="control-label" for="duties">Brief description of your duties and responsibilities:</label><input type="text" class="form-control inputnumber"  name="duties[]" placeholder="Duties...."></div></div><div class="col-lg-12"><div class="form-group"><label class="control-label" for="reasonforleaving">Reason for leaving:</label><input type="text" class="form-control inputnumber" name="reasonforleaving[]" placeholder="Required"></div></div><div class="col-lg-10"></div><div class="col-lg-2"><div class="form-group"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove rememp">(x) Remove Employment</button></div></div></div>');

      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
            var empCount = document.getElementById("empcnt").innerText;
            var parseEmpC = parseInt(empCount);
            document.getElementById("empcnt").innerText = parseEmpC - 1;           
      });   
 });  
 </script>





                                
     
<?php include('../_footer.php');  ?>
