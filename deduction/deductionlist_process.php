<?php

    include('../deduction/deductionlist.php');
    include('../config/db.php');

    $dd = new DeductionList();
    $empStatus = $_POST["empStatus"];

    $dd->GetAllDeductionList($empStatus);

?>