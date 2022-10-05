<?php 
session_start(); 
include('config/db.php');
include('class/userClass.php');
// include('controller/indexProcess.php');

$userClass = new userClass();

$errorMsgReg='';
$errorMsgLogin='';



if (!empty($_POST['loginSubmit']))


{

    $query = 'SELECT userid FROM dbo.mf_user WHERE useremail =:email';
    $param = array(":email" => $_POST['userid']);
    $stmt =$connL->prepare($query);
    $stmt->execute($param);
    $r = $stmt->fetch();

    $userid = isset($r['userid']) ? $r['userid'] : 'PCHI00000';
    $password = $_POST['password'];

    $date1 = date("Y-m-d");
    $date2 = date("Y-m-d", strtotime($date1 . " +1 day"));
    $dateatt = date("Y-m-d H:i:s");
    $action_f = "LOGIN -FAILED";
    $action_s = "LOGIN -SUCCESS";


    // echo $date1.", ".$date2.", ".$dateatt.", ".$action_f.", ".$action_s;

    $validator = $connL->prepare(@"SELECT COUNT(*) FROM dbo.logs WHERE emp_code = :id AND date >= :date1 AND date <= :date2 AND action = :act");
    $validator->bindParam(":id", $userid, PDO::PARAM_STR);
    $validator->bindParam(":date1", $date1, PDO::PARAM_STR);
    $validator->bindParam(":date2", $date2, PDO::PARAM_STR);
    $validator->bindParam(":act", $action_f, PDO::PARAM_STR);
    $validator->execute();

    if ($validator->fetchColumn() >= 3)
    {

        $_SESSION['msg_blocked'] = 'YOUR ACCOUNT IS BLOCKED! PLEASE CONTACT YOUR ADMINISTRATOR!';

                $ins = $connL->prepare(@"UPDATE dbo.mf_user SET locked_acnt = 1 where userid = :id");
                $ins->bindParam(":id", $userid, PDO::PARAM_STR);
                $ins->execute();
    }
    else
    {
        if(strlen(trim($userid))>=1 && strlen(trim($password))>=1 )
        {
            $uid = $userClass->userLogin($userid,$password);

            if($uid)
            {
                $_SESSION['write'] = true;
                $_SESSION['userid'] = $userid;
                $_SESSION['expire'] = $_SESSION['start'] + (30 * 60);
                
                $url = 'pages/index.php';
                $url_2 = 'pages/index.php';

            }
            else
            {
                $ins = $connL->prepare(@"INSERT INTO dbo.logs VALUES(:id, :act, :date)");
                $ins->bindParam(":id", $userid, PDO::PARAM_STR);
                $ins->bindParam(":act", $action_f, PDO::PARAM_STR);
                $ins->bindParam(":date", $dateatt, PDO::PARAM_STR);
                $ins->execute();

                
               $_SESSION['msg_error'] = 'Wrong Username/Password';
                session_destroy();
            }
        }
    }
}

if (empty($_SESSION['userid'])) {
?>
<!DOCTYPE html>
<html>
<head>
    <title>PCHI | Timekeeping</title>
    <noscript><h3>Please enable Javascript in order to use this form.</h3><meta HTTP-EQUIV='refresh' content=0; url='JavascriptNotEnabled.php'></noscript>
    
    <meta charset='utf-8'>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="robots" content="noindex">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<link type='image/x-png' rel='icon' href='img/pchi-logo.png'>
<link rel="stylesheet" type="text/css" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel='stylesheet' href='css/login_caru.css'>
<link rel='stylesheet' href='css/login.css'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
      <!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script type='text/javascript' src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- <script type="text/javascript" src='js/script.js'></script> -->
<style type="text/css">    
.loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url(img/loader.gif) 50% 50% no-repeat rgb(249,249,249) ;
            opacity: .8;
            /*background-size:200px 120px;*/
        }
</style>
</head>
<body class="login-page" >
<div id = "myDiv" style="display:none;" class="loader"></div>
<!-- end of code -->
<div class="login-box">
      <!-- /.login-logo -->
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
            
            <a href="#" class="h5">
            <img class="mb-2 img-fluid mx-auto d-block newoblogo" src="img/pchi-logo.png" alt="">    
            <b>Human Resource Information System</b> </a>
        </div>
        <div class="card-body">
          <!-- <p class="login-box-msg">Sign in to start your session</p> -->

          <form action="" method="post" name="login" onsubmit="show()">
            <div class="input-group mb-3">
              <input type="text" name="userid" id="userid" class="form-control" placeholder="Email" autocomplete="on" required/>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fa fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off" required/>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fa fa-lock"></span>
                </div>
              </div>
            </div>
            <?php 
                if(isset($_SESSION['msg_error']) && $_SESSION['msg_error'] != ''){
                echo'<body onload="showWrongPass()"></body>';
                unset($_SESSION['msg_error']);
                }
            ?>

            <?php 
                if(isset($_SESSION['msg_blocked']) && $_SESSION['msg_blocked'] != ''){
                echo'<body onload="showBlocked()"></body>';
                unset($_SESSION['msg_blocked']);
                }
            ?>
          <div class="social-auth-links text-center mt-2 mb-3">
            <input type="submit" class="btn btn-login btn-block" name="loginSubmit" value="Login" >
            <div class="row">
                <!-- <h6>Forgot password?Kindly contact the administrator.</h6> -->
<!--                 <div class="col-sm-6">
                    <a href="newhireaccess/newemployee_entry.php" class="btn btn-block btn-login-emp mt-2" onclick="show()">
                        <i class="fas fa-users"></i> New Employee
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="applicantprofile/applicant_entry.php" class="btn btn-block btn-login-emp mt-2" onclick="show()">
                        <i class="fas fa-file "></i> Applicant
                    </a>
                </div> -->
            </div>
          </div>
          </form>
            
          <!-- /.social-auth-links -->

          <!-- <p class="mb-1">
            <a href="forgot-password.html">I forgot my password</a>
          </p>
          <p class="mb-0">
            <a href="register.html" class="text-center"
              >Register a new membership</a
            >
          </p> -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->
<script type="text/javascript">

    function showWrongPass() {
        swal({text:"Incorrect Username and Password!",icon:"error"});
      }

     function showBlocked() {
        swal({title:"Your account has been blocked. Kindly contact the administrator.",icon:"warning"});
      }

         function show() {
            document.getElementById("myDiv").style.display="block";
        }

</script>

<?php
    }
    else
    {

        include('controller/empInfo.php');
        $empInfo = new EmployeeInformation();

        $empInfo->SetEmployeeInformation($_SESSION['userid']);
        $empUserType = $empInfo->GetEmployeeUserType();


        $url = 'pages/admin.php';
        $url_2 = 'pages/employee.php';

        if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' ||$empUserType == 'President' )
        {
            header("Location: $url");
        }
        else
        {
            header("Location: $url_2");
        }
    }
?>