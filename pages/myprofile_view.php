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

        global $connL;

        $query = "SELECT * from employee_profile a left join employee_level b on a.ranking = b.level_id where emp_code = :empcode";
        $stmt =$connL->prepare($query);
        $param = array(":empcode" => $empCode);
        $stmt->execute($param);
        $result = $stmt->fetch();
        $rptto = $result['reporting_to'];
        $reportingto = ($rptto === false) ? 'none' : $rptto;

        if($reportingto == 'none'){
            $repname = 'n/a';
        }else{

        $querys = "SELECT * from employee_profile where emp_code = :reportingto";
        $stmts =$connL->prepare($querys);
        $params = array(":reportingto" => $reportingto);
        $stmts->execute($params);
        $results = $stmts->fetch();
              if(isset($results['emp_code'])){
                $repname = $results['lastname'].",".$results['firstname']." ".$results['middlename'];
              }else{                       
                $repname = 'n/a';
              }
        }
     
    }
        
?>
<link rel="stylesheet" type="text/css" href="../pages/myprof.css">
<script type='text/javascript' src='../pages/up_signavatar.js'></script>
<body>
<div class="container">
    <div class="section-title">
          <h1>MY PROFILE</h1>
    </div>
    <div class="main-body mbsda">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-id-card fa-fw mr-1'></i>My Profile</b></li>
            </ol>
          </nav>
          <?php  
              $sex = $result['sex'];
              $up_avatar = $result['up_avatar'];
              $up_sign = $result['up_sign'];

              if($sex == 'Male' AND empty($up_avatar)){
                  $avatar = 'avatar2.png';
              }else if($sex == 'Female' AND empty($up_avatar)){
                  $avatar = 'avatar8.png';
              }else if($sex == 'Male' AND !empty($up_avatar)){
                  $avatar = $up_avatar;
              }else if($sex == 'Female' AND !empty($up_avatar)){
                  $avatar = $up_avatar;
              }else{
                  $avatar = 'nophoto.png';
              }

              if(empty($up_sign)){
                $signpic = '../uploads/employees/nosign.png';
              }else{
                $signpic = '../uploads/employees/'.$up_sign;
              }
           ?>
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <?php 
                    echo'<img src="../uploads/employees/'.$avatar.'" alt="Admin" title="Primary Picture" class="rounded-circle" width="150">';
                     ?>
                    
                    <div class="mt-3">
                      <h4><?php echo $empName; ?></h4>
                      <input type="text" class="form-control" name="empcode" id="empcode" hidden value="<?php echo $empCode; ?>">
                      <p class="text-secondary mb-1"><?php echo $result['position']; ?></p>
                      <p class="text-muted font-size-sm"><?php echo $empCode.'-'.$result['emp_type']; ?></p>
                      <img class="border" src="<?php echo $signpic ?>" height="100px;" width="225px;">                      
                      <button class="btn btn-primary btn-sm mt-3" id="upavatar">Upload Avatar</button>
                      <button class="btn btn-outline-primary btn-sm mt-3" id="upsign">Upload Signature</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card mt-3">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0">
                      <i class='fas fa-table fa-fw'></i></svg>Date Hired:</h6>
                    <span class="text-secondary"><?php echo date('m/d/Y', strtotime($result['datehired'])); ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><i class='fas fa-birthday-cake fa-fw'></i></svg>Birthdate:</h6>
                    <span class="text-secondary"><?php echo date('m/d/Y', strtotime($result['birthdate'])); ?></span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0"><i class='fas fa-phone fa-fw'></i></svg>Phone:</h6>
                    <span class="text-secondary"><?php echo $result['celno1']; ?></span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Full Name:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $empName; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Email:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['emailaddress']; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Address:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['emp_address'].' '.$result['emp_address2']; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Department:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['department']; ?>
                    </div>
                  </div>
                  <hr>                  
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Job Title:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['position']; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Civil Status:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['marital_status']; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Reporting To:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $repname; ?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Employee Level:</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $result['level_description']; ?>
                    </div>                    
                  </div>
                </div>
              </div>
            </div>
          </div>

 <div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">Upload Avatar <i class="fas fa-images"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                 <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <img src="../uploads/employees/<?php echo $avatar; ?>" alt="Admin" title="Primary Picture" 
                  class="rounded-circle" width="150" id="tempava" onerror="this.src='../uploads/employees/<?php echo $avatar; ?>'">
                    
                    <div class="mt-3">
                        <div class="col-md-12 text-center">
                          <label title="Upload image file" for="up_avatar" class="btn btn-primary btn-sm ">
                              <input type="file" accept="image/*" name="up_avatar" id="up_avatar" style="display:none" onchange="GetAvatarFile(this)">
                              Change Avatar
                          </label>
                        </div>
                    </div>
                  </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="SubmitAvatar" onclick="uploadAvatar();" ><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>  

 <div class="modal fade" id="signModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-xs modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">Upload Signature <i class="fas fa-images"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
                 <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                  <img src="<?php echo $signpic; ?>" alt="Admin" title="Primary Signature" class="rounded-circle" width="150" id="tempsign" onerror="this.src='<?php echo $signpic; ?>'">
                     <div class="mt-3">
                        <div class="col-md-12 text-center">
                          <label title="Upload image file" for="up_sign" class="btn btn-outline-primary btn-sm ">
                              <input type="file" accept="image/*" name="up_sign" id="up_sign" style="display:none" onchange="GetSignFile(this)">
                              Change Signature
                          </label>
                        </div>
                    </div>
                  </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                    <button type="button" class="btn btn-success" id="SubmitSign" onclick="uploadSign();" ><i class="fas fa-check-circle"></i> Submit</button>
                </div>

            </div>
        </div>
    </div>    

      </div>
    </div>
</body>

<script type="text/javascript">


  
var pdfAvatar;
var pdfSign;


function GetAvatarFile(input) {

if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function(e) {
$('#tempava').attr('src', e.target.result);
}

reader.readAsDataURL(input.files[0]);
}  

  var selectedfile = document.getElementById("up_avatar").files;

  if (selectedfile.length > 0) {
    var uploadedFile = selectedfile[0];
    var fileReader = new FileReader();
    var fl = uploadedFile.name;

    fileReader.onload = function (fileLoadedEvent) {
      var srcData = fileLoadedEvent.target.result;
      pdfAvatar =  fl;
    }
    fileReader.readAsDataURL(uploadedFile);
  }
}


function uploadAvatar() {

   var files = document.getElementById("up_avatar").files;

   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();

      // Set POST method and ajax file path
      xhttp.open("POST", "ajax_avasign.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
           if(response == 1){
              // alert("Upload successfully.");
           }else{
              // alert("File not uploaded.");
           }
         }
      };

      // Send request with data
      xhttp.send(formData);

   }else{
      // swal({text:"Please upload a file!",icon:"warning"});
   }

      var url = "../pages/up_signavatar_process.php";
      var emp_code = document.getElementById("empcode").value;
      var up_avatar = pdfAvatar;

        // console.log(up_avatar);
        // console.log(emp_code);
        // return false;

            swal({
              title: "Are you sure?",
              text: "You want to update your avatar?",
              icon: "success",
              buttons: true,
              dangerMode: true,
            })
            .then((updateDedd) => {
              if (updateDedd) {
                    $.post (
                        url,
                        {
                            action: 1,
                            emp_code: emp_code ,
                            up_avatar: up_avatar                            
                        },
                        function(data) {   
                        console.log(data);                                      
                            swal({
                                title: "Success!", 
                                text: "Successfully updated avatar!", 
                                icon: "success",
                            }).then(function() {
                                location.href = '../pages/myprofile_view.php';
                            }); 
                        }
                    );

              } else {
                swal({text:"You cancel your updating of avatar!",icon:"error"});
              }
            });   

}

    

function GetSignFile(input) {

if (input.files && input.files[0]) {
var reader = new FileReader();

reader.onload = function(e) {
$('#tempsign').attr('src', e.target.result);
}

reader.readAsDataURL(input.files[0]);
}


    var selectedfile = document.getElementById("up_sign").files;
    if (selectedfile.length > 0) {
        var uploadedFile = selectedfile[0];
        var fileReader = new FileReader();
        var fl = uploadedFile.name;

        fileReader.onload = function (fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result;
            pdfSign =  fl;
        }
        fileReader.readAsDataURL(uploadedFile);
    }
}


function uploadSign() {

   var files = document.getElementById("up_sign").files;

   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();

      // Set POST method and ajax file path
      xhttp.open("POST", "ajax_avasign.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
           if(response == 1){
              // alert("Upload successfully.");
           }else{
              // alert("File not uploaded.");
           }
         }
      };

      // Send request with data
      xhttp.send(formData);

   }else{
      // swal({text:"Please upload a file!",icon:"warning"});
   }

        var url = "../pages/up_signavatar_process.php";
        var emp_code = document.getElementById("empcode").value;
        var up_sign = pdfSign;

        // console.log(up_sign);
        // console.log(emp_code);
        // return false;

            swal({
              title: "Are you sure?",
              text: "You want to update your signature?",
              icon: "success",
              buttons: true,
              dangerMode: true,
            })
            .then((updateDedd) => {
              if (updateDedd) {
                    $.post (
                        url,
                        {
                            action: 2,
                            emp_code: emp_code ,
                            up_sign: up_sign                            
                        },
                        function(data) {   
                        console.log(data);                                      
                            swal({
                                title: "Success!", 
                                text: "Successfully updated signature!", 
                                icon: "success",
                            }).then(function() {
                                location.href = '../pages/myprofile_view.php';
                            }); 
                        }
                    );

              } else {
                swal({text:"You cancel your updating of signature!",icon:"error"});
              }
            });

}


$(function(){

 $('#upavatar').click(function(e){
        e.preventDefault();
        $('#avatarModal').modal('toggle');
    });

 $('#upsign').click(function(e){
        e.preventDefault();
        $('#signModal').modal('toggle');
    }); 

});

</script>
<?php include('../_footer.php');  ?>
