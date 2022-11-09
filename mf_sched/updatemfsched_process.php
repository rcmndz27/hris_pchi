<?php

    include('../mf_sched/updatemfsched.php');
    include('../config/db.php');

    $action = $_POST["action"];
    $rowid = $_POST["rowid"];
    $schedule_name = $_POST["schedule_name"];
    $status = $_POST["status"];    

    if ($action == 1)
    {
        UpdateMfSched($rowid,$schedule_name,$status);
    }
?>
