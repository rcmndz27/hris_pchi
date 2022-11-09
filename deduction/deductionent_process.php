<?php

date_default_timezone_set('Asia/Manila');


include('../deduction/deductionent.php');
include('../config/db.php');

$dedEnt = new DeductionEnt();

$dedent = json_decode($_POST["data"]);

if($dedent->{"Action"} == "InseryDeductionEnt")
{

    $emp_code = $dedent->{"emp_code"};
    $deduction_id = $dedent->{"deduction_id"};
    $period_cutoff = $dedent->{"period_cutoff"};
    $effectivity_date = $dedent->{"effectivity_date"};
    $end_date = $dedent->{"end_date"};
    $amount = $dedent->{"amount"};
    $eMplogName = $dedent->{"eMplogName"};


    $dedEnt->InsertDeductionEnt($eMplogName,$emp_code,$deduction_id,$period_cutoff,$effectivity_date,$end_date,$amount);

}else{

}
    

?>

