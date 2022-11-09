<?php

Class MfallowancesList{

    public function GetAllMfallowancesList(){
        global $connL;

        echo '<table id="allMfallowancesList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Allowances Code</th>
                <th>Allowances Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_benefits ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();


        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $benefitcode = "'".$result['benefit_code']."'";
                echo '
                <tr>
                <td id="ac'.$result['rowid'].'">'.$result['benefit_code'].'</td>
                <td id="an'.$result['rowid'].'">'.$result['benefit_name'].'</td>
                <td id="st'.$result['rowid'].'">' . $result['status']. '</td>';
                echo'<td><button type="button" class="btn btn-info btn-sm" 
                onclick="editMfallowancesModal('.$rowd.','.$benefitcode.')">
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

