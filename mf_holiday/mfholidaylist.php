<?php

Class MfholidayList{

    public function GetAllMfholidayList(){
        global $connL;

        echo '<table id="allMfholidayList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Holiday Date</th>
                <th>Holiday Type</th>
                <th>Holiday Name</th>
                <th>Holiday Term</th>
                <th hidden>date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_holiday ORDER BY holidaydate desc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $ddt = date('Y-m-d', strtotime($result['holidaydate']));
                $edt = "'".date('Y-m-d', strtotime($result['expired_date']))."'";
                echo '
                <tr>
                <td id="hd'.$result['rowid'].'">'.$ddt.'</td>
                <td id="ht'.$result['rowid'].'">'.$result['holidaytype'].'</td>
                <td id="hn'.$result['rowid'].'">'.$result['holidaydescs'].'</td>
                <td id="htr'.$result['rowid'].'">'.$result['holidayterm'].'</td>
                <td id="ed'.$result['rowid'].'" hidden>'.date('Y-m-d', strtotime($result['expired_date'])).'</td>
                <td id="st'.$result['rowid'].'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfholidayModal('.$rowd.','.$edt.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';
                
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    }
}

?>

