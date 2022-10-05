
<?php

$now = new DateTime(null, new DateTimeZone('Asia/Taipei'));

if (!empty($_SESSION['userid']))
{
    echo '<script type="text/javascript">alert("Please login first!!");</script>';
    header( "refresh:1;url=../index.php" );
}
else
{
    include('../config/db.php');
    include('../config/dependencies.php');
    include('../controller/empInfo.php');
    include('../controller/indexProcess.php');

  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>PCHI | HRIS Portal</title>

  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link type='image/x-png' rel='icon' href='../img/pchi-logo.png'>
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
          <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Google fonts-->
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;1,600&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,500;0,600;0,700;1,300;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,400;1,400&amp;display=swap" rel="stylesheet" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Dosis:300,400,500,,600,700,700i|Lato:300,300i,400,400i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link type='text/css' rel='stylesheet' href="<?= constant('FONTAWESOME_CSS'); ?>">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/custom.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet" />
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script type='text/javascript' src="<?= constant('BOOTSTRAP_JS'); ?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- <script type="text/javascript" src='../js/script.js'></script> -->
    </head>
<body>
<div id = "myDiv" style="display:none;" class="loader"></div>
  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top">
    <div class="container d-flex align-items-center justify-content-between">
      <img src='../img/pchi-logo.png'alt="" class="ob_logo"/>
      <nav id="navbar" class="navbar">
          <ul>
                <li><a class="nav-link active" href="#"><i class="fas fa-users fa-fw"></i>  &nbsp;NEW EMPLOYEE</a></li>
<!--                 <li><a class="nav-link" href="../index.php" onclick="show()"><i class="fas fa-home fa-fw"></i>  &nbsp;LOGIN</a></li> -->

            </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->
    </div>
  </header><br><!-- End Header -->
</body>

<script type="text/javascript">
         function show() {
            document.getElementById("myDiv").style.display="block";
        }
</script>

