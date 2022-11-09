<?php


    include('../mf_holiday/mfholidayent.php');
    include('../config/db.php');

$mfHol = new MfholidayEnt();

$mfhol = json_decode($_POST["data"]);

if($mfhol->{"Action"} == "InsertMfholidayEnt")
{

    $holidaydate = $mfhol->{"holidaydate"};
    $holidaytype = $mfhol->{"holidaytype"};
    $holidaydescs = $mfhol->{"holidaydescs"};
    $holidayterm = $mfhol->{"holidayterm"};
    $expired_date = $mfhol->{"expired_date"};
    $eMplogName = $mfhol->{"eMplogName"};
    $status = $mfhol->{"status"};


    $mfHol->InsertMfholidayEnt($eMplogName,$holidaydate,$holidaytype,$holidaydescs,$holidayterm,$expired_date,$status);

}
    

?>

