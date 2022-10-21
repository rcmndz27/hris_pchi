<?php

    include('../allowances/allowanceslist.php');
    include('../config/db.php');

    $dd = new AllowancesList();
    $empStatus = $_POST["empStatus"];

    $dd->GetAllAllowancesList($empStatus);

?>