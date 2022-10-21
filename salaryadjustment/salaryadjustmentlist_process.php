<?php

    include('../salaryadjustment/salaryadjustmentlist.php');
    include('../config/db.php');

    $dd = new SalaryAdjList();
    $empStatus = $_POST["empStatus"];

    $dd->GetAllSalaryAdjList($empStatus);

?>