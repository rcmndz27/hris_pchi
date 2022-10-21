<?php

    include('../mf_pyrollco/updatemfpyrollco.php');
    include('../config/db.php');

    $action = $_POST["action"];
    $rowid = $_POST["rowid"];
    $pyrollco_from = $_POST["pyrollco_from"];
    $pyrollco_to = $_POST["pyrollco_to"];
    $co_type = $_POST["co_type"];
    $status = $_POST["status"];    

    if ($action == 1)
    {
        UpdateMfpyrollco($rowid,$pyrollco_from,$pyrollco_to,$co_type,$status);
    }
?>
