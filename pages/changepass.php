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

        $query = "SELECT * from employee_profile where emp_code = :empcode";
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
<link rel="stylesheet" type="text/css" href="../pages/cpass.css">
<script type='text/javascript' src='../js/changepass.js'></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>Change Password</h1>
    </div>
    <div class="main-body mbsda">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page"><b><i class='fas fa-id-card fa-fw mr-1'></i> Change Password</b></li>
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
                <div class="card-body cdh">
               <!--Section: Block Content-->
                  <section class="mb-5 text-center">

       <h4>SET A NEW PASSWORD</h4>

          <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
              </div>
              <input name="password" type="password" value="" class="input form-control" id="newpassword" placeholder="New Password" required="true" aria-label="password" aria-describedby="basic-addon1" />
              <div class="input-group-append">
                <span class="input-group-text" onclick="password_show_hide();">
                  <i class="fas fa-eye" id="show_eye"></i>
                  <i class="fas fa-eye-slash d-none" id="hide_eye"></i>
                </span>
              </div>
            </div>
          </div>


          <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
              </div>
              <input name="password" type="password" value="" class="input form-control" id="confirmpassword" placeholder="Confirm Password" required="true" aria-label="password" aria-describedby="basic-addon1" />
              <div class="input-group-append">
                <span class="input-group-text" onclick="confirmpassword_show_hide();">
                  <i class="fas fa-eye" id="cfshow_eye"></i>
                  <i class="fas fa-eye-slash d-none" id="cfhide_eye"></i>
                </span>
              </div>
            </div>
          </div>

                      <button id="empCode" value="<?php echo $result['emp_code']; ?>" hidden></button>
                      <button type="button" class="chngpass" id="Submit" onclick="chngPass();"><i class="fas fa-key"></i> Change Password</button>
                  </section>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
<script type="text/javascript">
function password_show_hide() {
  var x = document.getElementById("newpassword");
  var show_eye = document.getElementById("show_eye");
  var hide_eye = document.getElementById("hide_eye");
  hide_eye.classList.remove("d-none");
  if (x.type === "password") {
    x.type = "text";
    show_eye.style.display = "none";
    hide_eye.style.display = "block";
  } else {
    x.type = "password";
    show_eye.style.display = "block";
    hide_eye.style.display = "none";
  }
}

function confirmpassword_show_hide() {
  var xcf = document.getElementById("confirmpassword");
  var cfshow_eye = document.getElementById("cfshow_eye");
  var cfhide_eye = document.getElementById("cfhide_eye");
  cfhide_eye.classList.remove("d-none");
  if (xcf.type === "password") {
    xcf.type = "text";
    cfshow_eye.style.display = "none";
    cfhide_eye.style.display = "block";
  } else {
    xcf.type = "password";
    cfshow_eye.style.display = "block";
    cfhide_eye.style.display = "none";
  }
}

function chngPass(){


    var newp = $('#newpassword').val();
    var conf = $('#confirmpassword').val();
     
         if(newp === '' || conf === ''){
            swal({text:"Kindly fill up blank field!",icon:"warning"});
         }else{
                if(newp === conf){

                    param = {
                        "Action":"ChangePass",
                        "newpassword": $('#newpassword').val(),
                        "empCode": $('#empCode').val(),
                        "confirmpassword": $('#confirmpassword').val()
                    };
                    
                    param = JSON.stringify(param);

                             swal({
                                  title: "Are you sure?",
                                  text: "You want to change your password.",
                                  icon: "success",
                                  buttons: true,
                                  dangerMode: true,
                                })
                                .then((appEnt) => {
                                  if (appEnt) {
                                            $.ajax({
                                                type: 'POST',
                                                url: "../controller/changepassprocess.php",
                                                data: {
                                                    data: param
                                                },
                                                success: function (result) {
                                                    console.log('success: ' + result);
                                                    swal({
                                                    title: "Success!", 
                                                    text: "Successfully updated password!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        location.reload();
                                                    }); 
                                                },
                                                error: function (result) {
                                                    console.log('error: ' + result);
                                                }
                                            }); //ajax
                                  } else {
                                    swal({text:"You cancel the changing of your password!",icon:"error"});
                                  }
                                });
                     }else{
                      swal({text:"Password do not match!",icon:"error"});
                    }
    
    }

}    
</script>


<?php include('../_footer.php');  ?>
