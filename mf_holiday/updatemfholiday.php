<?php 


function UpdateMfholiday($rowid,$holidaydate,$holidaytype,$holidaydescs,$holidayterm,$expired_date,$status)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.mf_holiday SET 
                holidaydate = :holidaydate,holidaytype = :holidaytype, 
                holidaydescs = :holidaydescs,status = :status,
                holidayterm = :holidayterm,expired_date = :expired_date
                where rowid = :rowid");
            $cmd->bindValue('rowid',$rowid);
            $cmd->bindValue('holidaydate',$holidaydate);
            $cmd->bindValue('holidaytype',$holidaytype);
            $cmd->bindValue('holidaydescs',$holidaydescs);
            $cmd->bindValue('holidayterm',$holidayterm);
            $cmd->bindValue('expired_date',$expired_date);
            $cmd->bindValue('status',$status);
            $cmd->execute();
    }


?>
