<?php

Class BankList{

    public function GetAllBankList(){
        global $connL;

        echo '<table id="allBankList" class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Bank Code</th>
                <th>Bank Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>';

        $query = "SELECT * from dbo.mf_banktypes ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result){
            do { 
                $rowd = "'".$result['rowid']."'";
                $dscsb = "'".$result['descsb']."'";;
                echo '
                <tr>
                <td id="bc'.$result['rowid'].'">'.$result['descsb'].'</td>
                <td id="bn'.$result['rowid'].'">'.$result['descsb_name'].'</td>
                <td id="st'.$result['rowid'].'">'.$result['status']. '</td>';
                echo'<td><button type="button"class="btn btn-info btn-sm" 
                onclick="editBankModal('.$rowd.','.$dscsb.')">
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

