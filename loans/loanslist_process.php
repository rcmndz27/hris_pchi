<?php

    include('../loans/loanslist.php');
    include('../config/db.php');

    $dd = new LoansList();
    $empStatus = $_POST["empStatus"];

    $dd->GetAllLoansList($empStatus);

?>