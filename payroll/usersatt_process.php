<?php


    include('../payroll/usersatt.php');
    include('../config/db.php');

$usrEnt = new UsersAtt();

$usrent = json_decode($_POST["data"]);

if($usrent->{"Action"} == "InsertUsersAtt")
{

    $bdno = $usrent->{"bdno"};
    $name = $usrent->{"name"};
    $pfrom = $usrent->{"pfrom"};
    $pto = $usrent->{"pto"};
    $loct = $usrent->{"loct"};
    $logname = $usrent->{"logname"};

    $usrEnt->InsertUsersAtt($bdno,$name,$pfrom,$pto,$loct,$logname);

}else{

    $bdno = $usrent->{"bdno"};
    $name = $usrent->{"name"};
    $pfrom = $usrent->{"pfrom"};
    $pto = $usrent->{"pto"};
    $loct = $usrent->{"loct"};
    $logname = $usrent->{"logname"};

    $usrEnt->InsertUsersAttEmp($bdno,$name,$pfrom,$pto,$loct,$logname);
}
    

?>

