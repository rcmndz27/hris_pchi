<?php

Class MfpyrollcoList{

    public function GetAllMfpyrollcoList(){
        global $connL;

        echo '<table id="allMfpyrollcoList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Payroll From</th>
                <th>Payroll To</th>
                <th>Payroll Type</th>
                <th hidden>CO Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT (CASE WHEN co_type = 0 then 'Payroll 15th' else 'Payroll 30th' END) AS cotype,* from dbo.mf_pyrollco ORDER BY pyrollco_from DESC";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                echo '
                <tr>
                <td id="pcf'.$result['rowid'].'">'.date('Y-m-d',strtotime($result['pyrollco_from'])).'</td>
                <td id="pct'.$result['rowid'].'">'.date('Y-m-d',strtotime($result['pyrollco_to'])).'</td>
                <td id="cot'.$result['rowid'].'">'.$result['cotype'].'</td>
                <td id="cor'.$result['rowid'].'" hidden>'.$result['co_type'].'</td>
                <td id="st'.$result['rowid'].'">'.$result['status'].'</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfpyrollcoModal('.$rowd.')">
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

