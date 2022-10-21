<?php

include('../mf_sched/mfschedent.php');
include('../config/db.php');

$mfSchd = new MfschedEnt();
$mfschd = json_decode($_POST["data"]);

if($mfschd->{"Action"} == "InsertMfschedEnt")
{

    $schedule_name = $mfschd->{"schedule_name"};
    $time_in_from_sun = $mfschd->{"time_in_from_sun"};
    $time_in_to_sun = $mfschd->{"time_in_to_sun"};
    $time_out_from_sun = $mfschd->{"time_out_from_sun"};
    $time_out_to_sun = $mfschd->{"time_out_to_sun"};
    $working_hours_sun = $mfschd->{"working_hours_sun"};
    $time_in_from_mon = $mfschd->{"time_in_from_mon"};
    $time_in_to_mon = $mfschd->{"time_in_to_mon"};
    $time_out_from_mon = $mfschd->{"time_out_from_mon"};
    $time_out_to_mon = $mfschd->{"time_out_to_mon"};
    $working_hours_mon = $mfschd->{"working_hours_mon"};  
    $time_in_from_tue = $mfschd->{"time_in_from_tue"};
    $time_in_to_tue = $mfschd->{"time_in_to_tue"};
    $time_out_from_tue = $mfschd->{"time_out_from_tue"};
    $time_out_to_tue = $mfschd->{"time_out_to_tue"};
    $working_hours_tue = $mfschd->{"working_hours_tue"};  
    $time_in_from_wed = $mfschd->{"time_in_from_wed"};
    $time_in_to_wed = $mfschd->{"time_in_to_wed"};
    $time_out_from_wed = $mfschd->{"time_out_from_wed"};
    $time_out_to_wed = $mfschd->{"time_out_to_wed"};
    $working_hours_wed = $mfschd->{"working_hours_wed"};  
    $time_in_from_thu = $mfschd->{"time_in_from_thu"};
    $time_in_to_thu = $mfschd->{"time_in_to_thu"};
    $time_out_from_thu = $mfschd->{"time_out_from_thu"};
    $time_out_to_thu = $mfschd->{"time_out_to_thu"};
    $working_hours_thu = $mfschd->{"working_hours_thu"};  
    $time_in_from_fri = $mfschd->{"time_in_from_fri"};
    $time_in_to_fri = $mfschd->{"time_in_to_fri"};
    $time_out_from_fri = $mfschd->{"time_out_from_fri"};
    $time_out_to_fri = $mfschd->{"time_out_to_fri"};
    $working_hours_fri = $mfschd->{"working_hours_fri"};  
    $time_in_from_sat = $mfschd->{"time_in_from_sat"};
    $time_in_to_sat = $mfschd->{"time_in_to_sat"};
    $time_out_from_sat = $mfschd->{"time_out_from_sat"};
    $time_out_to_sat = $mfschd->{"time_out_to_sat"};
    $working_hours_sat = $mfschd->{"working_hours_sat"};                            
    $empcode = $mfschd->{"empcode"};

    $mfSchd->InsertMfschedEnt($empcode,$schedule_name,$time_in_from_sun,$time_in_to_sun,$time_out_from_sun,$time_out_to_sun,$working_hours_sun,$time_in_from_mon,$time_in_to_mon,$time_out_from_mon,$time_out_to_mon,$working_hours_mon,$time_in_from_tue,$time_in_to_tue,$time_out_from_tue,$time_out_to_tue,$working_hours_tue,$time_in_from_wed,$time_in_to_wed,$time_out_from_wed,$time_out_to_wed,$working_hours_wed,$time_in_from_thu,$time_in_to_thu,$time_out_from_thu,$time_out_to_thu,$working_hours_thu,$time_in_from_fri,$time_in_to_fri,$time_out_from_fri,$time_out_to_fri,$working_hours_fri,$time_in_from_sat,$time_in_to_sat,$time_out_from_sat,$time_out_to_sat,$working_hours_sat);
}
    

?>

