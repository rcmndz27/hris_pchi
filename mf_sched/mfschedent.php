<?php

Class MfschedEnt{

public function InsertMfschedEnt($empcode,$schedule_name,$time_in_from_sun,$time_in_to_sun,$time_out_from_sun,$time_out_to_sun,$working_hours_sun,$time_in_from_mon,$time_in_to_mon,$time_out_from_mon,$time_out_to_mon,$working_hours_mon,$time_in_from_tue,$time_in_to_tue,$time_out_from_tue,$time_out_to_tue,$working_hours_tue,$time_in_from_wed,$time_in_to_wed,$time_out_from_wed,$time_out_to_wed,$working_hours_wed,$time_in_from_thu,$time_in_to_thu,$time_out_from_thu,$time_out_to_thu,$working_hours_thu,$time_in_from_fri,$time_in_to_fri,$time_out_from_fri,$time_out_to_fri,$working_hours_fri,$time_in_from_sat,$time_in_to_sat,$time_out_from_sat,$time_out_to_sat,$working_hours_sat)
    {
global $connL;

$query = "INSERT INTO schedules (schedule_name,status,audituser,auditdate) 
VALUES (:schedule_name,:status,:audituser,:auditdate)";

    $stmt =$connL->prepare($query);

    $param = array(
        ":schedule_name"=> $schedule_name,
        ":status" => 'Active',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );


$result = $stmt->execute($param);
echo $result;


$resquery = "SELECT * FROM schedules where rowid = (select max(rowid) from schedules) and audituser = :empcode";
$resparam = array(':empcode' => $empcode);
$resstmt =$connL->prepare($resquery);
$resstmt->execute($resparam);
$rs = $resstmt->fetch();  
$rsid = $rs['rowid'];


// sunday

if($working_hours_sun){
    $qsun = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
    VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

        $stmt_sun =$connL->prepare($qsun);

        $param_sun = array(
            ":schedule_id"=> $rsid,
            ":time_in_from" => $time_in_from_sun,
            ":time_in_to" => $time_in_to_sun,
            ":time_out_from" => $time_out_from_sun,
            ":time_out_to" => $time_out_to_sun,
            ":working_hours" => $working_hours_sun,
            ":day_name" => 'Sunday',
            ":audituser" => $empcode,
            ":auditdate" => date('Y-m-d')                                      
        );


    $rsun = $stmt_sun->execute($param_sun);
    echo $rsun; 
}else{}


if($working_hours_mon){
// monday
$qmon = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_mon =$connL->prepare($qmon);

    $param_mon = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_mon,
        ":time_in_to" => $time_in_to_mon,
        ":time_out_from" => $time_out_from_mon,
        ":time_out_to" => $time_out_to_mon,
        ":working_hours" => $working_hours_mon,
        ":day_name" => 'Monday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rmon = $stmt_mon->execute($param_mon);
echo $rmon; 

}else{}


if($working_hours_tue){
// tuesday
$qtue = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_tue =$connL->prepare($qtue);

    $param_tue = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_tue,
        ":time_in_to" => $time_in_to_tue,
        ":time_out_from" => $time_out_from_tue,
        ":time_out_to" => $time_out_to_tue,
        ":working_hours" => $working_hours_tue,
        ":day_name" => 'Tuesday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rtue = $stmt_tue->execute($param_tue);
echo $rtue; 
}else{}


if($working_hours_wed){
// wednesday
$qwed = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_wed =$connL->prepare($qwed);

    $param_wed = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_wed,
        ":time_in_to" => $time_in_to_wed,
        ":time_out_from" => $time_out_from_wed,
        ":time_out_to" => $time_out_to_wed,
        ":working_hours" => $working_hours_wed,
        ":day_name" => 'Wednesday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rwed = $stmt_wed->execute($param_wed);
echo $rwed; 
}else{}



if($working_hours_thu){
// thursday
$qthu = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_thu =$connL->prepare($qthu);

    $param_thu = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_thu,
        ":time_in_to" => $time_in_to_thu,
        ":time_out_from" => $time_out_from_thu,
        ":time_out_to" => $time_out_to_thu,
        ":working_hours" => $working_hours_thu,
        ":day_name" => 'Thursday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rthu = $stmt_thu->execute($param_thu);
echo $rthu; 
}else{}


if($working_hours_fri){
// friday
$qfri = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_fri =$connL->prepare($qfri);

    $param_fri = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_fri,
        ":time_in_to" => $time_in_to_fri,
        ":time_out_from" => $time_out_from_fri,
        ":time_out_to" => $time_out_to_fri,
        ":working_hours" => $working_hours_fri,
        ":day_name" => 'Friday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rfri = $stmt_fri->execute($param_fri);
echo $rfri; 
}else{}


if($working_hours_sat){
// saturday
$qsat = "INSERT INTO schedule_datas (schedule_id,time_in_from,time_in_to,time_out_from,time_out_to,working_hours,day_name,audituser,auditdate) 
VALUES (:schedule_id,:time_in_from,:time_in_to,:time_out_from,:time_out_to,:working_hours,:day_name,:audituser,:auditdate)";

    $stmt_sat =$connL->prepare($qsat);

    $param_sat = array(
        ":schedule_id"=> $rsid,
        ":time_in_from" => $time_in_from_sat,
        ":time_in_to" => $time_in_to_sat,
        ":time_out_from" => $time_out_from_sat,
        ":time_out_to" => $time_out_to_sat,
        ":working_hours" => $working_hours_sat,
        ":day_name" => 'Saturday',
        ":audituser" => $empcode,
        ":auditdate" => date('Y-m-d')                                      
    );

$rsat = $stmt_sat->execute($param_sat);
echo $rsat; 
}else{}


 }

}

?>