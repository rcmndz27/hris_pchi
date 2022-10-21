<?php

    include('../salary/salarylist.php');
    include('../config/db.php');

    $dd = new SalaryList();
    $empStatus = $_POST["empStatus"];

    $dd->GetAllSalaryList($empStatus);

?>