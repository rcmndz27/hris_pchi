<?php 
session_start(); 
include('config/db.php');
include('class/userClass.php');
include('controller/indexProcess.php');

$userClass = new userClass();

$errorMsgReg='';
$errorMsgLogin='';

if (!empty($_POST['loginSubmit'])) 
{
    $userid = $_POST['userid'];
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

        header("Location: blocked.php");
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

                if ($userid == 'PMI12000001' || $userid == 'PMI18000072')
                {
                    $ins = $connL->prepare(@"INSERT INTO dbo.logs VALUES(:id, :act, :date)");
                    $ins->bindParam(":id", $userid, PDO::PARAM_STR);
                    $ins->bindParam(":act", $action_s, PDO::PARAM_STR);
                    $ins->bindParam(":date", $dateatt, PDO::PARAM_STR);
                    $ins->execute();

                    header("Location: $url");
                }
                else
                {
                    $ins = $connL->prepare(@"INSERT INTO dbo.logs VALUES(:id, :act, :date)");
                    $ins->bindParam(":id", $userid, PDO::PARAM_STR);
                    $ins->bindParam(":act", $action_s, PDO::PARAM_STR);
                    $ins->bindParam(":date", $dateatt, PDO::PARAM_STR);
                    $ins->execute();
                    
                    header("Location: $url");
                }
            }
            else
            {
                $ins = $connL->prepare(@"INSERT INTO dbo.logs VALUES(:id, :act, :date)");
                $ins->bindParam(":id", $userid, PDO::PARAM_STR);
                $ins->bindParam(":act", $action_f, PDO::PARAM_STR);
                $ins->bindParam(":date", $dateatt, PDO::PARAM_STR);
                $ins->execute();

                $errorMsgLogin = "Incorrect Username / Password.";
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
    <title>PCHI | HRIS Portal</title>
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
    <link rel='stylesheet' href='css/login.css'>

    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script type='text/javascript' src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Add new style -->

  <style type="text/css">

.cb-slideshow,
.cb-slideshow:after { 
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    z-index: 0; 
}
.cb-slideshow:after { 
    content: '';
    background: transparent url(../images/pattern.png) repeat top left; 
}

.cb-slideshow li span { 
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0px;
    left: 0px;
    color: transparent;
    background-size: cover;
    background-position: 50% 50%;
    background-repeat: none;
    opacity: 0;
    z-index: 0;
    animation: imageAnimation 36s linear infinite 0s; 
}

.cb-slideshow li div { 
    z-index: 1000;
    position: absolute;
    bottom: 30px;
    left: 0px;
    width: 100%;
    text-align: center;
    opacity: 0;
    color: #fff;
    animation: titleAnimation 36s linear infinite 0s; 
}
.cb-slideshow li div h3 { 
    font-family: 'BebasNeueRegular', 'Arial Narrow', Arial, sans-serif;
    font-size: 240px;
    padding: 0;
    line-height: 200px; 
}

.cb-slideshow li:nth-child(1) span { 
    background-image: url('img/PMITOWER_banner.jpg') 
}
.cb-slideshow li:nth-child(2) span { 
    background-image: url('img/MBI_banner.jpg');
    animation-delay: 6s; 
}
.cb-slideshow li:nth-child(3) span { 
    background-image: url('img/MAC_banner.jpg');
    animation-delay: 12s; 
}
.cb-slideshow li:nth-child(4) span { 
    background-image: url('img/CSC_banner.jpg');
    animation-delay: 18s; 
}
.cb-slideshow li:nth-child(5) span { 
    background-image: url('img/CSC_banner2.jpg');
    animation-delay: 24s; 
}
.cb-slideshow li:nth-child(6) span { 
    background-image: url('img/IMC_banner.jpg');
    animation-delay: 30s; 
}

.cb-slideshow li:nth-child(2) div { 
    animation-delay: 6s; 
}
.cb-slideshow li:nth-child(3) div { 
    animation-delay: 12s; 
}
.cb-slideshow li:nth-child(4) div { 
    animation-delay: 18s; 
}
.cb-slideshow li:nth-child(5) div { 
    animation-delay: 24s; 
}
.cb-slideshow li:nth-child(6) div { 
    animation-delay: 30s; 
}

@keyframes imageAnimation { 
    0% { opacity: 0; animation-timing-function: ease-in; }
    8% { opacity: 1; animation-timing-function: ease-out; }
    17% { opacity: 1 }
    25% { opacity: 0 }
    100% { opacity: 0 }
}

@keyframes titleAnimation { 
    0% { opacity: 0 }
    8% { opacity: 1 }
    17% { opacity: 1 }
    19% { opacity: 0 }
    100% { opacity: 0 }
}

@keyframes imageAnimation { 
  0% {
      opacity: 0;
      animation-timing-function: ease-in;
  }
  8% {
      opacity: 1;
      transform: scale(1.05);
      animation-timing-function: ease-out;
  }
  17% {
      opacity: 1;
      transform: scale(1.1) rotate(3deg);
  }
  25% {
      opacity: 0;
      transform: scale(1.1) rotate(3deg);
  }
  100% { opacity: 0 }
}

@keyframes titleAnimation { 
  0% {
      opacity: 0;
      transform: translateX(200px);
  }
  8% {
      opacity: 1;
      transform: translateX(0px);
  }
  17% {
      opacity: 1;
      transform: translateX(0px);
  }
  19% {
      opacity: 0;
      transform: translateX(-400px);
  }
  25% { opacity: 0 }
  100% { opacity: 0 }
}

ul{
     list-style:none;
}

.no-cssanimations .cb-slideshow li span{
  opacity: 1;
}

@media screen and (max-width: 1140px) { 
    .cb-slideshow li div h3 { font-size: 140px }
}
@media screen and (max-width: 600px) { 
    .cb-slideshow li div h3 { font-size: 80px }
}

.bgform{
    background-color: #ffffff;
    opacity: 1;
    color: black;
    position: relative;
    border-radius: 15px;
}
.wb{
    font-weight: bolder;
}

.loginbg{
    background-color: #ffaa00;
    border-color: #ffaa00;
}

.newemp{
    background-color: #fbec1e;
    border-color: #fbec1e;
}
.imgsze{
    height: 20px;
    width: 20px;
}

.blk{
    color: #000000;
}

  </style>



<!-- end of style -->
    
</head>
<body>

<!-- Bg code added -->

<!-- <div class="bgslide"> 
<ul class="cb-slideshow">
  <li>
    <span>Image 01</span>
    <div>
      <h3>Humility</h3>
    </div>
  </li>
   <li>
    <span>Image 02</span>
    <div>
      <h3>Innovation</h3>
    </div>
  </li>
  <li>
    <span>Image 03</span>
    <div>
      <h3>Integrity</h3>
    </div>
    <li>
    <span>Image 04</span>
    <div>
      <h3>Synergy</h3>
    </div>
  </li>
  <li>
    <span>Image 05</span>
    <div>
      <h3>Premium Services</h3>
    </div>
  </li>
  <li>
    <span>Image 06</span>
    <div>
      <h3>Commitment</h3>
    </div>
  </li>
  </li>
</ul>
</div> -->

<!-- end of code -->
    <div class="container form-signin bgform">
        <form class="container" method="post" action="" name="login">
            <div class="row">
                <div class="col">
                    <img class="mb-4 img-fluid mx-auto d-block" src="img/pchi-logo.png" alt="">
                </div>
            </div>
            <div class="row">
                <div class="col" id="app_button">
                    <button class="btn btn-info btn-block wb loginbg" />
                        <a href='emp_index.php' class="blk">
                            <img src="img/employee.png" class="imgsze">
                        EMPLOYEE-LOGIN
                        </a>
                    </button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col" id="app_button">
                    <button class="btn btn-info btn-block wb newemp" />
                        <a href='newhireaccess/newemployee_entry.php' class="blk">
                            <img src="img/newemp.png" class="imgsze">
                        NEW EMPLOYEE
                        </a>
                    </button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col" id="app_button">
                    <button class="btn btn-info btn-block wb loginbg" />
                        <a href='applicantprofile/applicant_entry.php' class="blk">
                            <img src="img/applicant.png" class="imgsze">
                        APPLICANT
                        </a>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">

        $('#app_button').show();
        $('#emp_button').show();
        $('#loginbutt').hide();
        $('#usr_field').hide();
        $('#pass_field').hide();
     
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

        if ($empUserType === 'Admin')
        {
            header("Location: $url");
        }
        else
        {
            header("Location: $url_2");
        }
    }
?>