<?php

Class MfschedList{

    public function GetAllMfschedList(){
        global $connL;

        echo '<table id="allMfschedList" class="table table-sm">
        <thead>
            <tr>
                <th>Schedule Name</th>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th hidden>Statusy</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "EXEC schedule_list";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $r = $stmt->fetch();


        if($r){
            do { 
                $schedid = "'".$r['sched_id']."'";
                $txsti = 'Start ';
                $txsto = 'End ';
                $txwh = 'Hours';
                $sunday = ($r['sun_work'] == 0) ? 'Rest Day': $txsti.': '.$r['sun_ti'].'<br>'.$txsto.':'.$r['sun_to'].'<br>'.$txwh.':'.$r['sun_work'];
                $monday = ($r['mon_work'] == 0) ? 'Rest Day': $txsti.': '.$r['mon_ti'].'<br>'.$txsto.':'.$r['mon_to'].'<br>'.$txwh.':'.$r['mon_work'];
                $tueday = ($r['tue_work'] == 0) ? 'Rest Day': $txsti.':'.$r['tue_ti'].'<br>'.$txsto.':'.$r['tue_to'].'<br>'.$txwh.':'.$r['tue_work']; 
                $wedday = ($r['wed_work'] == 0) ? 'Rest Day': $txsti.':'.$r['wed_ti'].'<br>'.$txsto.':'.$r['wed_to'].'<br>'.$txwh.':'.$r['wed_work'];
                $thuday = ($r['thu_work'] == 0) ? 'Rest Day': $txsti.':'.$r['thu_ti'].'<br>'.$txsto.':'.$r['thu_to'].'<br>'.$txwh.':'.$r['thu_work'];
                $friday = ($r['fri_work'] == 0) ? 'Rest Day': $txsti.':'.$r['fri_ti'].'<br>'.$txsto.':'.$r['fri_to'].'<br>'.$txwh.':'.$r['fri_work'];
                $satday = ($r['sat_work'] == 0) ? 'Rest Day': $txsti.':'.$r['sat_ti'].'<br>'.$txsto.':'.$r['sat_to'].'<br>'.$txwh.':'.$r['sat_work'];                                                                               
                echo '
                <tr class="csor-pointer" onclick="editMfschedModal('.$schedid.')">
                <td id="sn'.$r['sched_id'].'">'.$r['sched_name'].'</td>
                <td id="sun'.$r['sched_id'].'"><small>'.$sunday.'<small></td>
                <td id="mon'.$r['sched_id'].'"><small>'.$monday.'<small></td>
                <td id="tue'.$r['sched_id'].'"><small>'.$tueday.'<small></td>
                <td id="wed'.$r['sched_id'].'"><small>'.$wedday.'<small></td>
                <td id="thu'.$r['sched_id'].'"><small>'.$thuday.'<small></td>
                <td id="fri'.$r['sched_id'].'"><small>'.$friday.'<small></td>
                <td id="sat'.$r['sched_id'].'"><small>'.$satday.'<small></td>
                <td id="stts'.$r['sched_id'].'" hidden>'.$r['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" onclick="editMfschedModal('.$schedid.')" title="Edit/Update"><i class="fas fa-edit"></i>
                        </button>
                        </td>';
                
                
            } while ($r = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>
        ';
    }
}

?>

