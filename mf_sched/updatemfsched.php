<?php 


function UpdateMfSched($rowid,$schedule_name,$status)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE schedules SET status = :status,schedule_name = :schedule_name where rowid = :rowid");
            $cmd->bindValue('rowid',$rowid);
            $cmd->bindValue('schedule_name',$schedule_name);
            $cmd->bindValue('status',$status);
            $cmd->execute();
    }


?>
