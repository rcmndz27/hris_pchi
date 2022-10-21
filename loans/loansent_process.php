<?php


    include('../loans/loansent.php');
    include('../config/db.php');

$lnEnt = new LoansEnt();

$lnent = json_decode($_POST["data"]);

if($lnent->{"Action"} == "InsertLoansEnt")
{

    $emp_code = $lnent->{"emp_code"};
    $loandec_id = $lnent->{"loandec_id"};
    $loan_date = $lnent->{"loan_date"};
    $loan_amount = $lnent->{"loan_amount"};
    $loan_balance = $lnent->{"loan_balance"};
    $loan_totpymt = $lnent->{"loan_totpymt"};
    $loan_amort = $lnent->{"loan_amort"};
    $eMplogName = $lnent->{"eMplogName"};

    $lnEnt->InsertLoansEnt($eMplogName,$emp_code,$loandec_id,$loan_date,$loan_amount,$loan_balance,$loan_totpymt,$loan_amort);

}
    

?>

