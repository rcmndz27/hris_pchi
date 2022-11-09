<?php

Class MfdeductionList{

    public function GetAllMfdeductionList(){
        global $connL;

        echo '<table id="allMfdeductionList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Deduction Code</th>
                <th>Deduction Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_deductions ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $deductioncode = "'".$result['deduction_code']."'";
                echo '
                <tr>
                <td id="dc'.$result['rowid'].'">' . $result['deduction_code']. '</td>
                <td id="dn'.$result['rowid'].'">' . $result['deduction_name']. '</td>
                <td id="st'.$result['rowid'].'">' . $result['status']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfdeductionModal('.$rowd.','.$deductioncode.')">
                                <i class="fas fa-edit"></i> Update
                            </button></td>';
                
                
            } while ($result = $stmt->fetch());

            echo '</tr></tbody>';

        }else { 
            echo '<tfoot></tfoot>'; 
        }
        echo '</table>';
    }


}

?>

