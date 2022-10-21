<?php 


function UpdateMfpyrollco($rowid,$pyrollco_from,$pyrollco_to,$co_type,$status)
    {
            global $connL;

            $cmd = $connL->prepare("UPDATE dbo.mf_pyrollco SET 
                pyrollco_from = :pyrollco_from,pyrollco_to = :pyrollco_to,
                co_type = :co_type,status = :status where rowid = :rowid");
            $cmd->bindValue('rowid',$rowid);
            $cmd->bindValue('pyrollco_from',$pyrollco_from);
            $cmd->bindValue('pyrollco_to',$pyrollco_to);
            $cmd->bindValue('co_type',$co_type);
            $cmd->bindValue('status',$status);
            $cmd->execute();
    }


?>
